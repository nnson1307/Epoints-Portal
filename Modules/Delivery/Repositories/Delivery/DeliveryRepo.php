<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-26
 * Time: 11:58 AM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Repositories\Delivery;


use App\Jobs\SaveLogZns;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\BookingApi;
use Modules\Admin\Models\CustomerContactTable;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Models\WardTable;
use Modules\Delivery\Http\Api\DeliveryApi;
use Modules\Delivery\Http\Api\SendNotificationApi;
use Modules\Delivery\Models\BranchTable;
use Modules\Delivery\Models\ConfigTable;
use Modules\Delivery\Models\CustomerDebtTable;
use Modules\Delivery\Models\CustomerTable;
use Modules\Delivery\Models\DeliveryDetailTable;
use Modules\Delivery\Models\DeliveryHistoryLogTable;
use Modules\Delivery\Models\DeliveryHistoryPaymentDetailTable;
use Modules\Delivery\Models\DeliveryHistoryPaymentTable;
use Modules\Delivery\Models\DeliveryHistoryTable;
use Modules\Delivery\Models\DeliveryTable;
use Modules\Delivery\Models\DistrictTable;
use Modules\Delivery\Models\InventoryOutputDetailTable;
use Modules\Delivery\Models\InventoryOutputTable;
use Modules\Delivery\Models\OrderDetailTable;
use Modules\Delivery\Models\OrderLogTable;
use Modules\Delivery\Models\OrderTable;
use Modules\Delivery\Models\PaymentMethodTable;
use Modules\Delivery\Models\PickupAddressTable;
use Modules\Delivery\Models\ProductInventoryTable;
use Modules\Delivery\Models\ProvinceTable;
use Modules\Delivery\Models\ReceiptDetailTable;
use Modules\Delivery\Models\ReceiptOnlineTable;
use Modules\Delivery\Models\ReceiptTable;
use Modules\Delivery\Models\SmsConfigTable;
use Modules\Delivery\Models\SmsLogTable;
use Modules\Delivery\Models\SmsProviderTable;
use Modules\Delivery\Models\StaffTable;
use Modules\Delivery\Models\TransportTable;
use Modules\Delivery\Models\UserCarrierTable;
use Modules\Delivery\Models\VoucherTable;
use Modules\Delivery\Models\WarehouseTable;

class DeliveryRepo implements DeliveryRepoInterface
{
    protected $delivery;

    public function __construct(
        DeliveryTable $delivery
    )
    {
        $this->delivery = $delivery;
    }

    const STATUS_OUTPUT = 'success';
    const TYPE_OUTPUT = 'retail';
    const STATUS_SUCCESS_RECEIPT_ONLINE = "success";

    /**
     * Danh sách đơn hàng cần giao
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $mHistory = new DeliveryHistoryTable();

        $list = $this->delivery->getList($filters);

        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                $isCreate = true;
                $historySuccess = $mHistory->countHistorySuccess($v['delivery_id']);

                $v['total_success'] = $historySuccess != null ? $historySuccess['total'] : 0;
            }
        }

        return [
            'list' => $list,
        ];
    }

    /**
     * Lấy các option view
     *
     * @return array|mixed
     */
    public function getOption()
    {
        $mBranch = new BranchTable();

        $optionBranch = $mBranch->getBranch(Auth::user()->branch_id);

        return [
            'optionBranch' => $optionBranch
        ];
    }

    /**
     * Lấy thông tin chỉnh sửa giao hàng
     *
     * @param $deliveryId
     * @return array|mixed
     */
    public function dataEdit($deliveryId)
    {
        $info = $this->delivery->getInfo($deliveryId);

        return [
            'item' => $info
        ];
    }

    /**
     * Chỉnh sửa đơn hàng cần giao
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            $this->delivery->edit($input, $input['delivery_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ];
        }
    }

    /**
     * Lấy thông tin view tạo phiếu giao hàng
     *
     * @param $deliveryId
     * @return array|mixed
     */
    public function dataCreateHistory($deliveryId)
    {
        $mTransport = new TransportTable();
        $mStaff = new StaffTable();
        $mOrderDetail = new OrderDetailTable();
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mReceipt = new ReceiptTable();
        $mUserCarrier = new UserCarrierTable();
        $apiDelivery = app()->get(DeliveryApi::class);


        $info = $this->delivery->getInfo($deliveryId);
        //Nối chuỗi địa chỉ khách hàng nếu nhận hàng tại quầy
        $info['district_name'] = $info['district_name'] != null ? ', ' . $info['district_name'] : '';
        $info['province_name'] = $info['province_name'] != null ? ', ' . $info['province_name'] : '';
        $info['customer_address'] = $info['customer_address'] . $info['district_name'] . $info['province_name'];

        $amount = 0;
        //Nếu đơn hàng đã thanh toán thì load lại giá cần thu
        if (in_array($info['process_status'], ['paysuccess', 'pay-half'])) {
            $getReceipt = $mReceipt->getInfoByOrder($info['order_id']);

            if (count($getReceipt) > 0) {
                foreach ($getReceipt as $item) {
                    $amount += $item['amount_paid'];
                }
            }
        }

        //Lấy thông tin phiếu giao hàng để trừ tiền giảm dần
        $getHistory = $mDeliveryHistory->getInfo($info['delivery_id']);

        if (count($getHistory) > 0) {
            foreach ($getHistory as $item) {
                if (!in_array($item['status'], ['fail', 'cancel'])) {
                    $amount += $item['amount'];
                }
            }
        }
        $info['amount'] = $info['amount'] - $amount;

        //Lấy option load view
        $optionStaff = $mStaff->getOption();
        $optionTransport = $mTransport->getOption();
        $optionCarrier = $mUserCarrier->getOption();
        //Lấy số sản phẩm đã được giao
        $getHistory = $mDeliveryHistory->getQuantityProductHistory($deliveryId);
        $arrHistory = [];
        if (count($getHistory) > 0) {
            foreach ($getHistory as $item) {
                if (isset($arrHistory[$item['object_type']][$item['object_id']])){
                    $arrHistory[$item['object_type']][$item['object_id']] += $item['quantity'];
                } else {
                    $arrHistory[$item['object_type']][$item['object_id']] = $item['quantity'];
                }

            }
        }

        //Lấy data sản phẩm giao hàng
        $orderDetail = $mOrderDetail->getDetail($info['order_id']);

        $dataProduct = [];
        if (count($orderDetail) > 0) {
            foreach ($orderDetail as $k => $item) {
                $checkQuantity = isset($arrHistory[$item['object_type']][$item['object_id']]) ? $arrHistory[$item['object_type']][$item['object_id']] : 0;

                if ($item['quantity'] - $checkQuantity > 0) {
                    $dataProduct[] = [
                        'object_type' => $item['object_type'],
                        'object_id' => $item['object_id'],
                        'object_name' => $item['object_name'],
                        'object_code' => $item['object_code'],
                        'quantity' => $item['quantity'] - $checkQuantity,
                        'price' => $item['price']
                    ];
                }
            }
        }
        //Lấy data địa chỉ lấy hàng
        $mWarehouse = new WarehouseTable();
        $optionPickupAddress = $mWarehouse->getWarehouse();

        $mProvince = app()->get(ProvinceTable::class);

        $province = $mProvince->getOptionProvince();

        $deliveryCustomerAddress = null;
        $district = [];
        $ward = [];
        if ($info['customer_contact_id'] != null) {
            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);
            $deliveryCustomerAddress = $mCustomerContact->getDetail($info['customer_contact_id']);
            $mDistrict = app()->get(\Modules\Admin\Models\DistrictTable::class);
            $mWard = app()->get(WardTable::class);
            if ($deliveryCustomerAddress != null) {
                $district = $mDistrict->getOptionDistrict($deliveryCustomerAddress['province_id']);
                $ward = $mWard->getOptionWard($deliveryCustomerAddress['district_id']);
            }
        }

        $from_address = 0;
        $to_address = 0;
        $shop_id = 0;
        foreach ($optionPickupAddress as $v) {
            if ($info['branch_id'] == $v['branch_id']) {
                $from_address = (int)$v['district_id'];
                $shop_id = $v['ghn_shop_id'];
            }
        }

        if ($deliveryCustomerAddress != null) {
            $to_address = $deliveryCustomerAddress['district_id'];
        }

        $listServiceTmp = [];
        $listServiceMain = [];

        return [
            'item' => $info,
            'optionStaff' => $optionStaff,
            'optionTransport' => $optionTransport,
            'dataProduct' => $dataProduct,
            'optionCarrier' => $optionCarrier,
            'optionPickupAddress' => $optionPickupAddress,
            'province' => $province,
            'deliveryCustomerAddress' => $deliveryCustomerAddress,
            'district' => $district,
            'ward' => $ward,
            'listServiceMain' => $listServiceMain
        ];
    }

    /**
     * Chọn sản phẩm
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function chooseProduct($input)
    {
        $mOrderDetail = new OrderDetailTable();

        $detail = $mOrderDetail->getDetailByObject($input['object_id'], $input['order_id']);

        return response()->json($detail);
    }

    /**
     * Tạo phiếu giao hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeHistory($input)
    {
        DB::beginTransaction();
        try {

            if ($input['shipping_unit'] == 'delivery_unit') {
                if (!isset($input['service_id'])) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Hãy chọn đối tác vận chuyển')
                    ]);
                }
                $time_ship = Carbon::parse($input['time_ship'])->format('Y-m-d 00:00:00');
            } else {
                if ($input['time_ship_staff'] == null || $input['time_ship_staff'] == '') {
                    return response()->json([
                        'error' => true,
                        'message' => __('Hãy chọn thời gian giao hàng dự kiến')
                    ]);
                }
                $time_ship = Carbon::createFromFormat('d/m/Y', $input['time_ship_staff'])->format('Y-m-d 00:00:00');
            }

            $now = Carbon::now()->format('Y-m-d 00:00:00');

            if ($time_ship < $now) {
                return response()->json([
                    'error' => true,
                    'message' => __('Thời gian giao hàng dự kiến phải lớn hơn thời gian hiện tại')
                ]);
            }

            $input['time_ship'] = $time_ship;

            $messsageError = '';

            if ($input['shipping_unit'] == 'delivery_unit'){
                if ($input['is_post_office'] == 1) {
                    if ($input['length'] > 100) {
                        $messsageError = $messsageError . 'Chiều dài vượt quá 100 cm';
                    }
                    if ($input['width'] > 100) {
                        $messsageError = $messsageError . 'Chiều rộng vượt quá 100 cm';
                    }
                    if ($input['height'] > 100) {
                        $messsageError = $messsageError . 'Chiều cao vượt quá 100 cm';
                    }
                } else {
                    if ($input['length'] > 50) {
                        $messsageError = $messsageError . 'Chiều dài vượt quá 50 cm';
                    }
                    if ($input['width'] > 30) {
                        $messsageError = $messsageError . 'Chiều rộng vượt quá 30 cm';
                    }
                    if ($input['height'] > 50) {
                        $messsageError = $messsageError . 'Chiều cao vượt quá 50 cm';
                    }
                }

                if ($messsageError != '') {
                    return response()->json([
                        'error' => true,
                        'message' => $messsageError
                    ]);
                }

                if ($input['type_weight'] == 'kg' && str_replace(',', '', $input['weight']) > 50) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Trọng lượng vượt quá 50kg')
                    ]);
                }
            }

            $mDeliveryHistory = new DeliveryHistoryTable();
            $mHistoryDetail = new DeliveryDetailTable();
            $mDeliveryHistoryLog = new DeliveryHistoryLogTable();
            $mOrderDetail = new OrderDetailTable();
            $mReceipt = new ReceiptTable();
            $mNoti = new SendNotificationApi();
            $mOrderLog = new OrderLogTable();

            $info = $this->delivery->getInfo($input['delivery_id']);

            $amount = 0;
            //Nếu đơn hàng đã thanh toán thì load lại giá cần thu
            if (in_array($info['process_status'], ['paysuccess', 'pay-half'])) {
                $getReceipt = $mReceipt->getInfoByOrder($info['order_id']);
                if (count($getReceipt) > 0) {
                    foreach ($getReceipt as $item) {
                        $amount += $item['amount_paid'];
                    }
                }
            }

            //Lấy thông tin phiếu giao hàng để trừ tiền giảm dần
            $getHistory = $mDeliveryHistory->getInfo($info['delivery_id']);
            if (count($getHistory) > 0) {
                foreach ($getHistory as $item) {
                    if (!in_array($item['status'], ['fail', 'cancel'])) {
                        $amount += $item['amount'];
                    }
                }
            }

            //Tổng tiền của đơn hàng đã trừ thanh toán
            $totalDelivery = floatval($info['amount']) - floatval($amount);
            //Số tiền cần thu khi tạo phiếu giao hàng
            $totalBill = floatval($input['amount']);

//            if ($totalDelivery > $totalBill) {
//                return response()->json([
//                    'error' => true,
//                    'message' => __('Số tiền cần thu không hợp lệ, tiền cần thu còn lại') . ' ' . (number_format(($info['amount'] - $amount), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0))
//                ]);
//            }

            //Check tồn kho
            $checkInventory = $this->checkInventory($input['arrProduct'], $input['warehouse_id']);
            if ($checkInventory['error'] == 1) {
                return response()->json([
                    'error' => true,
                    'message' => $checkInventory['message']
                ]);
            }

            $totalQuantityBill = 0;
            $totalQuantity = 0;
            $totalQuantityDelivery = 0;
            //Lấy tổng số sản phẩm đã giao
            $getHistory = $mDeliveryHistory->getQuantityProductHistory($input['delivery_id']);
            if (count($getHistory) > 0) {
                foreach ($getHistory as $item) {
                    $totalQuantityDelivery += $item['quantity'];
                }
            }

            //Tạo phiếu giao hàng
            $dataHistory = [
                'delivery_id' => $input['delivery_id'],
//                'transport_id' => $input['transport_id'],
//                'transport_code' => $input['transport_code'],
                'delivery_staff' => $input['delivery_staff'],
                'contact_phone' => $input['contact_phone'],
                'contact_address' => $input['contact_address'],
                'contact_name' => $input['contact_name'],
//                'amount' => str_replace(',', '', $input['amount']),
                'amount' => str_replace(',', '', $input['amount_cod']),
                'note' => $input['note'],
                'status' => 'new',
                'time_ship' => $input['time_ship'],
                'verified_payment' => 0,
                'pick_up' => $input['pick_up'],
                'warehouse_id_pick_up' => $input['warehouse_id'],
                'province_id' => $input['province_id'],
                'district_id' => $input['district_id'],
                'ward_id' => $input['ward_id'],
                'weight' => str_replace(',', '', $input['weight']),
                'type_weight' => $input['type_weight'],
                'length' => $input['length'],
                'width' => $input['width'],
                'height' => $input['height'],
                'shipping_unit' => $input['shipping_unit'],
                'is_insurance' => isset($input['is_insurance']) ? $input['is_insurance'] : 0,
                'is_post_office' => isset($input['is_post_office']) ? $input['is_post_office'] : 0,
                'required_note' => isset($input['required_note']) ? $input['required_note'] : '',
                'service_id' => isset($input['service_id']) ? $input['service_id'] : 0,
                'service_type_id' => isset($input['service_type_id']) ? $input['service_type_id'] : 0,
                'fee' => isset($input['fee']) ? $input['fee'] : 0,
                'name_service' => isset($input['name_service']) ? $input['name_service'] : '',
                'transport_code' => isset($input['is_partner']) ? $input['is_partner'] : '',
                'partner' => isset($input['is_partner']) ? $input['is_partner'] : '',
                'total_fee' => isset($input['total_fee']) ? $input['total_fee'] : 0,
                'insurance_fee' => isset($input['insurance_fee']) ? $input['insurance_fee'] : 0,
                'is_cod_amount' => isset($input['is_cod_amount']) ? $input['is_cod_amount'] : 0,
                'cod_amount' => str_replace(',', '', $input['amount_cod']),
            ];
            $idHistory = $mDeliveryHistory->add($dataHistory);
            //Chỉnh sửa mã phiếu giao hàng
            $mDeliveryHistory->edit([
                "delivery_history_code" => 'PGH_' . date('dmY') . sprintf("%02d", $idHistory)
            ], $idHistory);

            $arrProduct = [];

            //Thêm chi tiết phiếu giao hàng
            if (isset($input['arrProduct']) && count($input['arrProduct']) > 0) {
                $tmp = '';
//                foreach ($input['arrProduct'] as $item) {
//                    if ($item['sku'] != null && $item['sku'] != '') {
//                        $check = $mHistoryDetail->checkSKU($item['sku']);
//                        if ($check != 0) {
//                            $tmp = $tmp . 'SKU' . $item['sku'] . __('đã được sử dụng') . ' </br>';
//                        }
//                    }
//                }
//                if ($tmp != '') {
//                    DB::rollback();
//                    return response()->json([
//                        'error' => true,
//                        'message' => $tmp,
//                    ]);
//                }
                foreach ($input['arrProduct'] as $item) {
                    $totalQuantity += floatval($item['quantity']);
                    if ($item['quantity'] > 0) {
                        $dataDetail = [
                            'delivery_history_id' => $idHistory,
                            'object_type' => $item['object_type'],
                            'object_id' => $item['object_id'],
                            'quantity' => $item['quantity'],
                            'note' => $item['note'],
                            'price' => $item['price'],
//                            'sku' => $item['sku']
                        ];
                        $arrProduct[] = [
                            'name' => $item['object_name'],
                            'code' => $item['object_code'],
                            'quantity' => (float)$item['quantity'],
                            'price' => (float)$item['price'],
                        ];
                        $mHistoryDetail->add($dataDetail);
                    }
                }
            }
            //Lưu log trạng thái phiếu giao hàng
            $mDeliveryHistoryLog->add([
                'delivery_history_id' => $idHistory,
                'status' => 'new',
                'created_type' => 'backend',
                'created_by' => Auth()->id()
            ]);
            //Lấy tổng sản phẩm của đơn hàng
            $orderDetail = $mOrderDetail->getDetail($input['order_id']);
            if (count($orderDetail) > 0) {
                foreach ($orderDetail as $item) {
                    $totalQuantityBill += floatval($item['quantity']);
                }
            }

            if ($info['delivery_status'] == "packing") {
                //Cập nhật trạng thái đơn hàng cần giao thành đang giao
                $this->delivery->edit([
                    'delivery_status' => 'delivering'
                ], $input['delivery_id']);


                //Kiểm tra order log
                $checkOrderLog = $mOrderLog->checkStatusLog($input['order_id'], 'delivering');

                if ($checkOrderLog == null) {
                    //Insert order log đơn hàng đang vận chuyển
                    $mOrderLog->insert([
                        'order_id' => $input['order_id'],
                        'created_type' => 'backend',
                        'status' => 'delivering',
//                        'note' => __('Đang vận chuyển'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đang vận chuyển',
                        'note_en' => 'Delivering',
                    ]);
                }
            }
            // Insert sms log
            $mSmsLog = new SmsLogTable();
            $mCustomer = new CustomerTable();
            $mSmsConfig = new SmsConfigTable();
            $mOrder = new OrderTable();
            $dataCus = $mCustomer->getItem($info['customer_id']);
            $dataOrder = $mOrder->getItem($info['order_id']);
            $parameter = [
                'phone' => $dataCus['phone1'],
                'full_name' => $dataCus['full_name'],
                'name' => substr($dataCus['full_name'], strrpos($dataCus['full_name'], ' ') + 1),
                'gender' => $dataCus['gender'],
                'object_id' => $info['order_id'],
                'object_type' => 'order'
            ];
            $smsConfig = $mSmsConfig->getItemByType('delivery_note');
            if ($smsConfig->is_active == 1) {
                $mSmsProvider = new SmsProviderTable();
                $brandName = $mSmsProvider->getItem(1)->value;
                $content = $smsConfig->content;
                //Build nội dung.
                $gender = __('Anh');
                if ($parameter['gender'] == 'female') {
                    $gender = __('Chị');
                } elseif ($parameter['gender'] == 'other') {
                    $gender = __('Anh/Chị');
                }
                $message = str_replace(['{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{ORDER_CODE}', '{DATETIME}'],
                    [$parameter['full_name'] . ' ', $gender . ' ', $dataOrder['order_code'], $input['time_ship']], $content);
                // insert
                $dataSmsLog = [
                    'brandname' => $brandName,
                    'phone' => $parameter['phone'],
                    'customer_name' => $parameter['full_name'],
                    'message' => $message,
                    'sms_type' => 'delivery_note',
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'sms_status' => 'new',
                    'object_id' => $parameter['object_id'],
                    'object_type' => 'order',
                ];
                $idSmsLog = $mSmsLog->add($dataSmsLog);
            }

            //Tạo phiếu xuất kho
            $this->insertInventoryOutput($input['arrProduct'], $input['warehouse_id']);

            DB::commit();

            if ($input['shipping_unit'] == 'delivery_unit') {
                $apiDelivery = app()->get(DeliveryApi::class);
                $mWareHouse = app()->get(WarehouseTable::class);

                $detailWareHouse = $mWareHouse->getWarehouseDetail($input['warehouse_id']);

                if ($detailWareHouse != null && $detailWareHouse['ghn_shop_id'] != null) {
                    $createOrderGHN = $apiDelivery->createOrder([
                        'method' => 'ghn',
                        'mode' => 'sandbox',
                        'shop_id' => $detailWareHouse['ghn_shop_id'],
                        'to_name' => $input['contact_name'],
                        'to_phone' => $input['contact_phone'],
                        'to_address' => $input['contact_address'],
                        'to_ward_id' => $input['ward_id'],
                        'service_id' => isset($input['service_id']) ? (int)$input['service_id'] : 0,
                        'service_type_id' => isset($input['service_type_id']) ? (int)$input['service_type_id'] : 0,
                        'insurance_value' => isset($input['insurance_fee']) && isset($input['is_insurance']) && $input['is_insurance'] == 1 ? (int)$input['insurance_fee'] : 0,
                        'from_district_id' => (int)$detailWareHouse['district_id'],
                        'coupon' => '',
                        'weight' => (int)str_replace(',', '', $input['weight']),
                        'length' => (int)$input['length'],
                        'width' => (int)$input['width'],
                        'height' => (int)$input['height'],
                        'name' => 'Đặt hàng',
                        'quantity' => (int)count($arrProduct),
                        'required_note' => isset($input['required_note']) ? $input['required_note'] : '',
                        'payment_type_id' => 2,
                        'items' => $arrProduct,
                    ]);
                    if (isset($createOrderGHN['code']) && $createOrderGHN['code'] == 200 && isset($createOrderGHN['data'])) {
                        $mDeliveryHistory->edit([
                            "ghn_order_code" => $createOrderGHN['data']['order_code']
                        ], $idHistory);
                    }
                }
            }

//            if ($info['delivery_status'] == "packing") {
//                //Send Notification đang giao hàng
//                $mNoti->sendNotification([
//                    'key' => 'order_status_D',
//                    'customer_id' => $info['customer_id'],
//                    'object_id' => $info['order_id']
//                ]);
//                //Lưu log ZNS (đơn hàng đang giao)
//                SaveLogZns::dispatch('order_waiting', $info['customer_id'], $input['delivery_id']);
//            }
//
//            //Lưu log ZNS (tạo phiếu giao hàng)
//            SaveLogZns::dispatch('delivery_note', $info['customer_id'], $idHistory);
//
//            //Send Notification đang giao hàng
//            $mNoti->sendNotification([
//                'key' => 'delivery_W',
//                'customer_id' => $info['customer_id'],
//                'object_id' => $idHistory
//            ]);

            return response()->json([
                'error' => false,
                'message' => __('Tạo phiếu giao hàng thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Tạo phiếu giao hàng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Preview code
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function previewOrderAction($input)
    {
        DB::beginTransaction();
        try {

            $messsageError = '';
            if ($input['is_post_office'] == 1) {
                if ($input['length'] > 100) {
                    $messsageError = $messsageError . 'Chiều dài vượt quá 100 cm';
                }
                if ($input['width'] > 100) {
                    $messsageError = $messsageError . 'Chiều rộng vượt quá 100 cm';
                }
                if ($input['height'] > 100) {
                    $messsageError = $messsageError . 'Chiều cao vượt quá 100 cm';
                }
            } else {
                if ($input['length'] > 50) {
                    $messsageError = $messsageError . 'Chiều dài vượt quá 50 cm';
                }
                if ($input['width'] > 30) {
                    $messsageError = $messsageError . 'Chiều rộng vượt quá 30 cm';
                }
                if ($input['height'] > 50) {
                    $messsageError = $messsageError . 'Chiều cao vượt quá 50 cm';
                }
            }

            if ($messsageError != '') {
                return response()->json([
                    'error' => true,
                    'message' => $messsageError
                ]);
            }

            //Tạo phiếu giao hàng
            $dataHistory = [
                'delivery_staff' => $input['delivery_staff'],
                'contact_phone' => $input['contact_phone'],
                'contact_address' => $input['contact_address'],
                'contact_name' => $input['contact_name'],
                'amount' => str_replace(',', '', $input['amount']),
                'note' => $input['note'],
                'status' => 'new',
                'verified_payment' => 0,
                'pick_up' => $input['pick_up'],
                'warehouse_id_pick_up' => $input['warehouse_id'],
                'province_id' => $input['province_id'],
                'district_id' => $input['district_id'],
                'ward_id' => $input['ward_id'],
                'weight' => $input['weight'],
                'type_weight' => $input['type_weight'],
                'length' => $input['length'],
                'width' => $input['width'],
                'height' => $input['height'],
                'shipping_unit' => $input['shipping_unit'],
                'is_insurance' => $input['is_insurance'],
                'is_post_office' => $input['is_post_office'],
                'is_cod_amount' => $input['is_cod_amount'],
                'required_note' => $input['required_note'],
            ];
            $listProduct = [];
            if (isset($input['arrProduct']) && count($input['arrProduct']) > 0) {

                foreach ($input['arrProduct'] as $item) {
                    if ($item['quantity'] > 0) {
                        $listProduct[] = [
                            'name' => $item['object_name'],
                            'quantity' => (int)$item['quantity'],
                            'price' => (int)$item['price'],
                        ];
                    }
                }
            }

            $mWarehouse = app()->get(WarehouseTable::class);

            $detailWarehouse = $mWarehouse->getWarehouseDetail($input['warehouse_id']);

            $apiDelivery = app()->get(DeliveryApi::class);

            if ($detailWarehouse != null && $detailWarehouse['ghn_shop_id'] != null) {

                $listServiceTmp = [];
                $listServiceMain = [];
                if ($detailWarehouse['district_id'] != 0 && $input['district_id'] != 0 && $detailWarehouse['ghn_shop_id'] != 0) {
                    $listService = $apiDelivery->getListServiceGHN([
                        'method' => 'ghn',
                        'shop_id' => $detailWarehouse['ghn_shop_id'],
                        'from_district_id' => $detailWarehouse['district_id'],
//                        'from_district_id' => 14,
                        'to_district_id' => $input['district_id']
//                        'to_district_id' => 14
                    ]);

                    if ((isset($listService['ErrorCode']) && $listService['ErrorCode'] != 0) || $listService == null) {
                        $listServiceTmp = [];
                    } else {
                        $listServiceTmp = collect($listService['Data']['list'])->keyBy('service_type_id');
                    }
                }

                foreach ($listServiceTmp as $key => $itemService) {
                    if (in_array($key, [1, 2, 3])) {
                        $tmp = $apiDelivery->getFee([
                            'method' => 'ghn',
                            'shop_id' => $detailWarehouse['ghn_shop_id'],
                            'from_district_id' => $detailWarehouse['district_id'],
                            'to_district_id' => $input['district_id'],
                            'to_ward_id' => $input['ward_id'],
                            'service_id' => $itemService['service_id'],
                            'service_type_id' => $itemService['service_type_id'],
                            'insurance_value' => 0,
                            'coupon' => '',
                            'weight' => 1,
                            'length' => 1,
                            'width' => 1,
                            'height' => 1,
                        ]);
                        if (isset($tmp['ErrorCode']) && $tmp['ErrorCode'] == 0 && isset($tmp['Data']['service_fee'])) {
                            $listServiceMain[$key][] = [
                                'service_name' => $itemService['short_name'],
                                'service_fee' => $tmp['Data']['service_fee'],
                                'service_id' => $itemService['service_id'],
                                'service_type_id' => $itemService['service_type_id']
                            ];
                        }

                    }
                }

                $view = view('delivery::delivery-history.append.block-receipt-product', [
                    'listServiceMain' => $listServiceMain,
                    'service_id' => isset($input['service_id']) ? (int)$input['service_id'] : 0,
                    'service_type_id' => isset($input['service_type_id']) ? (int)$input['service_type_id'] : 0,
                ])->render();

                $callPreviewOrder = $apiDelivery->previewOrder([
                    'method' => 'ghn',
                    'mode' => 'sandbox',
                    'shop_id' => $detailWarehouse['ghn_shop_id'],
                    'to_name' => $input['contact_name'],
                    'to_phone' => $input['contact_phone'],
                    'to_address' => $input['contact_address'],
                    'to_ward_id' => $input['ward_id'],
                    'service_id' => isset($input['service_id']) ? (int)$input['service_id'] : 0,
                    'cod_amount' => 0,
                    'service_type_id' => isset($input['service_type_id']) ? (int)$input['service_type_id'] : 0,
                    'insurance_value' => (int)$input['amount'],
                    'from_district_id' => $detailWarehouse['district_id'],
                    'coupon' => '',
                    'weight' => (int)$input['weight'],
                    'length' => (int)$input['length'],
                    'width' => (int)$input['width'],
                    'height' => (int)$input['height'],
                    'quantity' => count($input['arrProduct']),
                    'required_note' => $input['required_note'],
                    'payment_type_id' => 2,
                    'items' => $listProduct,
                    'name' => 'Đặt hàng'
                ]);

                if (isset($callPreviewOrder['code']) && $callPreviewOrder['code'] == 200) {
//                    && isset($callPreviewOrder['code']) && $callPreviewOrder['code'] == 200
                    $fee = $callPreviewOrder['data']['fee']['main_service'];
                    $total_fee = $callPreviewOrder['data']['total_fee'];
                    $insurance = $callPreviewOrder['data']['fee']['insurance'];
                    $expected_delivery_time = $callPreviewOrder['data']['expected_delivery_time'];

                    return [
                        'error' => false,
                        'view' => $view,
                        'fee' => $fee,
                        'total_fee' => $total_fee,
                        'insurance' => $insurance,
                        'expected_delivery_time' => Carbon::parse($expected_delivery_time)->format('Y-m-d H:i:s'),
                        'expected_delivery_time_input' => Carbon::parse($expected_delivery_time)->format('d/m/Y'),
                    ];
                }

                return [
                    'error' => true,
                    'view' => $view
                ];

            } else {
                return [
                    'error' => true
                ];
            }


        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Tạo phiếu giao hàng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check tồn kho của sản phẩm
     *
     * @param $listProduct
     * @param $idWarehouse
     * @return array
     */
    private function checkInventory($listProduct, $idWarehouse)
    {
        //Lấy cấu hình cho bán âm ko, nếu cho bán âm thì ko cần check tồn kho
        $mConfig = app()->get(ConfigTable::class);
        $orderMinus = $mConfig->getConfig('sell_minus'); //value = 0 là ko cho bán âm, 1 ngược lại

        $message = "";

        if ($orderMinus['value'] == 0) {
            $mProductInventory = app()->get(ProductInventoryTable::class);

            foreach ($listProduct as $v) {
                if ($v['object_type'] == 'product') {
                    $getInventory = $mProductInventory->getInventory($v['object_code'], $idWarehouse);
                    //Lấy số lượng sp tồn kho
                    $inventory = $getInventory['quantity'] != null ? intval($getInventory['quantity']) : 0;

                    if ($inventory < $v['quantity']) {
                        $message .= $v['object_name'] . ' ' . __('đã vượt số lượng tồn kho') . '<br>';
                    }
                }
            }
        }

        return [
            'error' => $message != '' ? 1 : 0,
            'message' => $message
        ];
    }

    /**
     * Tạo phiếu xuất kho
     *
     * @param $listProduct
     * @param $idWarehouse
     */
    private function insertInventoryOutput($listProduct, $idWarehouse)
    {
        if (count($listProduct) > 0) {
            $mOutput = app()->get(InventoryOutputTable::class);
            //Tạo phiếu xuất kho
            $idOutput = $mOutput->add([
                'warehouse_id' => $idWarehouse,
                'status' => self::STATUS_OUTPUT,
                'type' => self::TYPE_OUTPUT,
                'note' => __('Xuất kho khi tạo phiếu giao hàng'),
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);
            //Update po_code
            $poCode = 'XK_' . date('dmY') . sprintf("%02d", $idOutput);
            $mOutput->edit([
                'po_code' => $poCode
            ], $idOutput);

            $arrOutputDetail = [];

            foreach ($listProduct as $v) {
                $mProductInventory = app()->get(ProductInventoryTable::class);
                //Lấy thông tin tồn kho
                $getInventory = $mProductInventory->getInventory($v['object_code'], $idWarehouse);

                if ($getInventory != null) {
                    //Có thông tin tồn kho thì update lại
                    $mProductInventory->edit([
                        'export' => intval($getInventory['export']) + intval($v['quantity']),
                        'quantity' => intval($getInventory['quantity']) - intval($v['quantity'])
                    ], $getInventory['product_inventory_id']);
                } else {
                    //Không có thông tin tồn kho thì insert
                    $mProductInventory->create([
                        'product_id' => $v['object_id'],
                        'product_code' => $v['object_code'],
                        'warehouse_id' => $idWarehouse,
                        'import' => 0,
                        'export' => $v['quantity'],
                        'quantity' => 0 - intval($v['quantity']),
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ]);
                }

                $arrOutputDetail [] = [
                    'inventory_output_id' => $idOutput,
                    'product_code' => $v['object_code'],
                    'quantity' => $v['quantity'],
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ];
            }
            $mOutputDetail = app()->get(InventoryOutputDetailTable::class);
            //Tạo chi tiết phiếu xuất kho
            $mOutputDetail->insert($arrOutputDetail);
        }
    }

    /**
     * Data view chi tiết đơn hàng cần giao
     *
     * @param $deliveryId
     * @return array|mixed
     */
    public function dataDetail($deliveryId)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mDeliveryDetail = new DeliveryDetailTable();
        $mReceipt = new ReceiptTable();
        $mOrderDetail = new OrderDetailTable();

        //Thông tin đơn hàng cần giao
        $info = $this->delivery->getInfo($deliveryId);
        //Thông tin lịch sử giao hàng
        $getHistory = $mDeliveryHistory->getInfo($deliveryId);
        $dataHistory = [];
        if (count($getHistory) > 0) {
            foreach ($getHistory as $item) {
                $item['detail'] = $mDeliveryDetail->getInfo($item['delivery_history_id']);
                $dataHistory[] = $item;
            }
        }
        //Tiền đã thành toán
        $amountPaid = 0;
        if (in_array($info['process_status'], ['paysuccess', 'pay-half'])) {
            $getReceipt = $mReceipt->getInfoByOrder($info['order_id']);
            if (count($getReceipt) > 0) {
                $collection = collect($getReceipt);
                $amountPaid = $collection->sum('amount_paid');
            }
        }
        //Lấy sản phẩm của đơn hàng
        $arrOrderDetail = [];
        $orderDetail = $mOrderDetail->getDetail($info['order_id']);
        //Lấy sản phẩm đã giao xong
        $productDelivered = $mDeliveryDetail->getProductDelivered($deliveryId);
        $arrProductDelivered = [];
        if (count($productDelivered)) {
            foreach ($productDelivered as $v) {
                if (isset($arrProductDelivered[$v['object_type']][$v['object_id']])){
                    $arrProductDelivered[$v['object_type']][$v['object_id']] += $v['quantity'];
                }else {
                    $arrProductDelivered[$v['object_type']][$v['object_id']] = $v['quantity'];
                }

            }
        }

        if (count($orderDetail) > 0) {
            foreach ($orderDetail as $v) {
                $finish = 0;

                if (isset($arrProductDelivered[$v['object_type']][$v['object_id']])) {
                    $finish = $arrProductDelivered[$v['object_type']][$v['object_id']];
                }
                $arrOrderDetail[] = [
                    'object_name' => $v['object_name'],
                    'object_code' => $v['object_code'],
                    'total_quantity' => $v['quantity'],
                    'finish_quantity' => $finish,
                    'un_finish_quantity' => $v['quantity'] - $finish,
                    'amount' => $v['amount']
                ];
            }
        }

        return [
            'item' => $info,
            'history' => $dataHistory,
            'amountPaid' => $amountPaid,
            'orderDetail' => $arrOrderDetail
        ];
    }

    /**
     * Cập nhật trạng thái đơn hàng cần giao
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function saveDetail($input)
    {
        DB::beginTransaction();
        try {
            $mHistory = new DeliveryHistoryTable();
            $mOrderDetail = new OrderDetailTable();
            $mOrderLog = new OrderLogTable();

            $totalQuantityBill = 0;
            $totalQuantityDelivery = 0;

            $arrHistoryCancelFail = [];

            //Lấy thông tin đơn hàng cần giao
            $infoDelivery = $this->delivery->getInfo($input['delivery_id']);
            //Lấy tổng sản phẩm của đơn hàng
            $orderDetail = $mOrderDetail->getDetail($infoDelivery['order_id']);
            if (count($orderDetail) > 0) {
                foreach ($orderDetail as $item) {
                    $totalQuantityBill += floatval($item['quantity']);
                }
            }

            if (isset($input['arrHistory']) && count($input['arrHistory']) > 0) {
                foreach ($input['arrHistory'] as $item) {
                    //Lưu log giao hàng
                    $this->insertLog($item['delivery_history_id'], $item['status']);
                    //Cập nhật trạng thái lịch sử giao hàng
                    $dataEditHistory = [
                        'status' => $item['status'],
                        'updated_by' => Auth()->id(),
                    ];
                    if ($item['status'] == 'inprogress') {
                        $dataEditHistory['time_pick_up'] = Carbon::now()->format('Y-m-d H:i:s');
                    }
                    if ($item['status'] == 'success') {
                        $dataEditHistory['time_drop'] = Carbon::now()->format('Y-m-d H:i:s');
                    }
                    $mHistory->edit($dataEditHistory, $item['delivery_history_id']);

                    if (in_array($item['status'], ['fail', 'cancel'])) {
                        $arrHistoryCancelFail [] = [
                            'delivery_history_id' => $item['delivery_history_id']
                        ];
                    }
                }
            }

            //Nếu trạng thái hoàn thành check cập nhật trạng thái
            $getHistory = $mHistory->getQuantityProductHistory($input['delivery_id']);

            if (count($getHistory) > 0) {
                foreach ($getHistory as $item) {
                    if (in_array($item['status'], ['success', 'confirm'])) {
                        $totalQuantityDelivery += $item['quantity'];
                    }
                }
            }

            //Cập nhật trạng thái đơn hàng cần giao nếu hoàn thành đơn hàng
            if ($totalQuantityBill - $totalQuantityDelivery <= 0) {
                $this->delivery->edit([
                    'delivery_status' => 'delivered'
                ], $input['delivery_id']);

                //Kiểm tra order log
                $checkOrderLog = $mOrderLog->checkStatusLog($infoDelivery['order_id'], 'ordercomplete');

                if ($checkOrderLog == null) {
                    //Insert order log đơn hàng đang vận chuyển
                    $mOrderLog->insert([
                        'order_id' => $infoDelivery['order_id'],
                        'created_type' => 'backend',
                        'status' => 'ordercomplete',
//                        'note' => __('Hoàn tất'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Hoàn tất',
                        'note_en' => 'Order completed',
                    ]);
                }
            }

            DB::commit();

            if ($totalQuantityBill - $totalQuantityDelivery <= 0) {
                //Send notification
                $mNoti = new SendNotificationApi();
                $mNoti->sendNotification([
                    'key' => 'order_status_I',
                    'customer_id' => $infoDelivery['customer_id'],
                    'object_id' => $infoDelivery['order_id']
                ]);
            }

            if (count($arrHistoryCancelFail) > 0) {
                $mDeliveryApi = new DeliveryApi();
                //Cộng kho khi hủy phiếu giao hàng
                $mDeliveryApi->backInventory($arrHistoryCancelFail);
            }

            return response()->json([
                'error' => false,
                'message' => __('Lưu trạng thái thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Lưu trạng thái thất bại'),
                '_message' => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Xác nhận thanh toán
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function confirmReceipt($input)
    {
        DB::beginTransaction();
        try {
            $input['total'] = str_replace(',', '', $input['total']);

            $mHistory = new DeliveryHistoryTable();
            $mReceipt = new ReceiptTable();
            $mOrderDetail = new OrderDetailTable();
            $mReceiptDetail = new ReceiptDetailTable();
            $mOrder = new OrderTable();
            $mCustomerDebt = new CustomerDebtTable();
            $mHistoryPayment = new DeliveryHistoryPaymentTable();
            $mHistoryPaymentDetail = new DeliveryHistoryPaymentDetailTable();
            $mOrderLog = new OrderLogTable();
            $mVoucher = new VoucherTable();

            //Lấy thông tin lịch sử giao hàng
            $itemHistory = $mHistory->getItem($input['delivery_history_id']);

            $amountPaid = 0;
            //Lấy số tiền thanh toán
            if (count($input['arrayMethod']) > 0) {
                foreach ($input['arrayMethod'] as $v) {
                    $amountPaid += $v['money'];
                }
            }

            if ($itemHistory['amount'] != $amountPaid) {
                return response()->json([
                    'error' => true,
                    'message' => __('Tiền thanh toán không hợp lệ')
                ]);
            }

            //Cập nhật đã confirm payment phiếu giao hàng
            $mHistory->edit([
                'verified_payment' => 1,
                'verified_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
            ], $itemHistory['delivery_history_id']);

            //Lấy thông tin thanh toán phiếu giao hàng
            $getPayment = $mHistoryPayment->getPaymentByHistory($input['delivery_history_id']);
            if ($getPayment != null) {
                //Cập nhật đã xác nhận thanh toán phiếu giao hàng
                $mHistoryPayment->edit([
                    'is_verify' => 1,
                    'total' => $input['total']
                ], $getPayment['delivery_payment_id']);
            }
            //Thêm phiếu thanh toán
            $dataReceipt = [
                'customer_id' => $itemHistory['customer_id'],
                'object_type' => 'order',
                'object_id' => $itemHistory['order_id'],
                'order_id' => $itemHistory['order_id'],
                'total_money' => $input['total'],
                'status' => 'paid',
                'amount' => $input['total'],
                'amount_paid' => $input['total'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'receipt_source' => 'delivery',
                'note' => $input['note'],
                'object_accounting_id' => $itemHistory['order_id']
            ];
            $idReceipt = $mReceipt->add($dataReceipt);
            //Cập nhật mã thanh toán
            $mReceipt->edit([
                'receipt_code' => 'TT_' . date('dmY') . sprintf("%02d", $idReceipt)
            ], $idReceipt);

            if (count($input['arrayMethod']) > 0) {
                $mReceiptOnline = app()->get(ReceiptOnlineTable::class);
                $mPaymentMethod = app()->get(PaymentMethodTable::class);

                foreach ($input['arrayMethod'] as $v) {
                    //Thêm chi tiết thanh toán
                    $mReceiptDetail->add([
                        'receipt_id' => $idReceipt,
                        'cashier_id' => Auth::id(),
                        'payment_method_code' => $v['payment_method_code'],
                        'amount' => $v['money'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);

                    if ($v['delivery_history_payment_detail_id'] != null) {
                        //Cập nhật chi tiết lưu tạm thanh toán
                        $mHistoryPaymentDetail->edit([
                            'amount' => $v['money']
                        ], $v['delivery_history_payment_detail_id']);
                    }

                    if ($v['payment_transaction_code'] != null) {
                        //Cập nhật trạng thái thanh toán online
                        $mReceiptOnline->editByCode([
                            'receipt_id' => $idReceipt,
                            'status' => self::STATUS_SUCCESS_RECEIPT_ONLINE
                        ], $v['payment_transaction_code']);
                    }

                    if ($v['payment_method_code'] == "TRANSFER") {
                        //Lấy thông tin phương thức thanh toán
                        $infoMethod = $mPaymentMethod->getInfoByCode($v['payment_method_code']);

                        //Lưu log thanh toán online
                        $mReceiptOnline->add([
                            "object_type" => 'delivery_history',
                            "object_id" => $itemHistory['delivery_history_id'],
                            "object_code" => $itemHistory['delivery_history_code'],
                            "payment_method_code" => $v['payment_method_code'],
                            "amount_paid" => $v['money'],
                            "payment_time" => Carbon::now()->format('Y-m-d H:i:s'),
                            "type" => $infoMethod['payment_method_type'],
                            "platform" => "web",
                            "performer_name" => $itemHistory['contact_name'],
                            "performer_phone" => $itemHistory['contact_phone'],
                            "status" => self::STATUS_SUCCESS_RECEIPT_ONLINE
                        ]);
                    }
                }
            } else {
                //Thêm chi tiết thanh toán
                $mReceiptDetail->add([
                    'receipt_id' => $idReceipt,
                    'cashier_id' => Auth::id(),
                    'payment_method_code' => 'CASH',
                    'amount' => $input['total'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ]);
            }
            //Cập nhật trạng thái đơn hàng
            $mOrder->edit([
                'process_status' => 'pay-half'
            ], $itemHistory['order_id']);

            if ($itemHistory['status'] != 'confirm') {
                //Lưu log giao hàng
                $this->insertLog($itemHistory['delivery_history_id'], 'success');
                //Cập nhật trạng thái lịch sử giao hàng
                $mHistory->edit([
                    'status' => 'success',
                    'updated_by' => Auth()->id(),
                ], $input['delivery_history_id']);
            }
            //Lấy tổng sản phẩm của đơn hàng
            $totalQuantityBill = 0;
            $totalQuantityDelivery = 0;
            $orderDetail = $mOrderDetail->getDetail($itemHistory['order_id']);
            if (count($orderDetail) > 0) {
                foreach ($orderDetail as $item) {
                    $totalQuantityBill += floatval($item['quantity']);
                }
            }
            //Nếu trạng thái hoàn thành check cập nhật trạng thái
            $getHistory = $mHistory->getQuantityProductHistory($itemHistory['delivery_id']);
            if (count($getHistory) > 0) {
                foreach ($getHistory as $item) {
                    if (in_array($item['status'], ['success', 'confirm'])) {
                        $totalQuantityDelivery += $item['quantity'];
                    }
                }
            }
            //Cập nhật trạng thái đơn hàng cần giao nếu hoàn thành đơn hàng
            if ($totalQuantityBill - $totalQuantityDelivery <= 0) {
                $this->delivery->edit([
                    'delivery_status' => 'delivered'
                ], $itemHistory['delivery_id']);

                //Kiểm tra order log
                $checkOrderLog = $mOrderLog->checkStatusLog($itemHistory['order_id'], 'ordercomplete');

                if ($checkOrderLog == null) {
                    //Insert order log đơn hàng đang vận chuyển
                    $mOrderLog->insert([
                        'order_id' => $itemHistory['order_id'],
                        'created_type' => 'backend',
                        'status' => 'ordercomplete',
//                        'note' => __('Hoàn tất'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Hoàn tất',
                        'note_en' => 'Order completed',
                    ]);
                }
            }
            //Thông tin đơn hàng cần giao
            $info = $this->delivery->getInfo($itemHistory['delivery_id']);
            $amount = 0;
            //Nếu đơn hàng đã thanh toán thì load lại giá cần thu
            if (in_array($info['process_status'], ['paysuccess', 'pay-half'])) {
                $getReceipt = $mReceipt->getInfoByOrder($info['order_id']);
                if (count($getReceipt) > 0) {
                    foreach ($getReceipt as $item) {
                        $amount += $item['amount_paid'];
                    }
                }
            }
            //Kiểm tra tổng sản phẩm giao hàng có bằng tổng sản phẩm đơn hàng
            if ($totalQuantityBill == $totalQuantityDelivery) {
                //Nếu tổng tiền đơn hàng lớn hơn tiền đã thanh toán thì vào công nợ
                if ($info['amount'] > $amount) {
                    //Lấy thông tin công nợ
                    $infoDebt = $mCustomerDebt->getInfo($info['order_id']);
                    if ($infoDebt == null) {
                        //Thêm công nợ mới
                        $idDebt = $mCustomerDebt->add([
                            'debt_code' => 'abc',
                            'customer_id' => $info['customer_id'],
                            'staff_id' => Auth()->id(),
                            'debt_type' => 'order',
                            'order_id' => $info['order_id'],
                            'status' => 'unpaid',
                            'amount' => $info['amount'] - $amount,
                            'amount_paid' => 0,
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id()
                        ]);
                        //Cập nhật debt_code
                        $mCustomerDebt->edit([
                            'debt_code' => 'CN_' . date('dmY') . sprintf("%02d", $idDebt)
                        ], $idDebt);
                    }
                } else {
                    //Cập nhật trạng thái đơn hàng
                    $mOrder->edit([
                        'process_status' => 'paysuccess'
                    ], $itemHistory['order_id']);
                    $this->saveSmsLog($itemHistory['delivery_id'], 'paysuccess');
                }
            } else {
                //Nếu tổng tiền đơn hàng bằng tiền đã thanh toán thì cập nhật trạng thái đơn hàng
                if ($info['amount'] == $amount) {
                    //Cập nhật trạng thái đơn hàng
                    $mOrder->edit([
                        'process_status' => 'paysuccess'
                    ], $itemHistory['order_id']);
                }
                $this->saveSmsLog($itemHistory['delivery_id'], 'paysuccess');
            }

            // Insert sms log giao hàng hoàn tất (thanh toán xong + giao hàng xong)
            $this->saveSmsLog($itemHistory['delivery_id'], 'confirm_deliveried');
            // Kiểm tra nếu có sử dụng voucher thì tăng số lần sử dụng
            $orderInfo = $mOrder->getItem($itemHistory['order_id']);
            $getItemVoucher = $mVoucher->getItemByCode($orderInfo['voucher_code']);
            if ($getItemVoucher != null) {
                $dataVoucher = [
                    'total_use' => ($getItemVoucher['total_use'] + 1)
                ];
                $mVoucher->editVoucherOrder($dataVoucher, $orderInfo['voucher_code']);
            }

            DB::commit();

            if ($totalQuantityBill - $totalQuantityDelivery <= 0) {
                //Send notification
                $mNoti = new SendNotificationApi();
                $mNoti->sendNotification([
                    'key' => 'order_status_I',
                    'customer_id' => $itemHistory['customer_id'],
                    'object_id' => $itemHistory['order_id']
                ]);
            }

            //Lưu log ZNS
            SaveLogZns::dispatch('confirm_deliveried', $itemHistory['customer_id'], $itemHistory['delivery_history_id']);

            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();
            if ($info['amount'] >= $itemHistory['amount']) {
                $mBookingApi->plusPointReceiptFull(['receipt_id' => $idReceipt]);
            } else {
                $mBookingApi->plusPointReceipt(['receipt_id' => $idReceipt]);
            }

            return response()->json([
                'error' => false,
                'message' => __('Xác nhận thanh toán thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Xác nhận thanh toán thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Load lại số tiền cần thu khi thay đổi số lượng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function loadAmount($input)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mReceipt = new ReceiptTable();
        $mOrderDetail = new OrderDetailTable();

        $info = $this->delivery->getInfo($input['delivery_id']);

        $amount = 0;
        $amountLoad = 0;
        //Nếu đơn hàng đã thanh toán thì load lại giá cần thu
        if (in_array($info['process_status'], ['paysuccess', 'pay-half'])) {
            $getReceipt = $mReceipt->getInfoByOrder($info['order_id']);
            if (count($getReceipt) > 0) {
                $collection = collect($getReceipt);
                $amount = $collection->sum('amount_paid');
            }
        }
        //Lấy thông tin phiếu giao hàng để trừ tiền giảm dần
        $getHistory = $mDeliveryHistory->getInfo($info['delivery_id']);
        if (count($getHistory) > 0) {
            foreach ($getHistory as $item) {
                if (!in_array($item['status'], ['fail', 'cancel'])) {
                    $amount += $item['amount'];
                }
            }
        }
        //Lấy tổng sản phẩm của đơn hàng
        $totalQuantityBill = 0;
        $orderDetail = $mOrderDetail->getDetail($info['order_id']);
        if (count($orderDetail) > 0) {
            $collection = collect($orderDetail);
            $totalQuantityBill = $collection->sum('quantity');
        }

        $info['amount'] = $info['amount'] - $amount;
        //Đơn giao hàng đầu tiên
//        if (count($getHistory) == 0) {
        if ($totalQuantityBill == $input['quantityAll']) {
            //Giao một lần
            $amountLoad = $info['amount'];
        } else {
            //Giao nhiều lần
            $collection = collect($orderDetail);
            if (count($input['arrProduct']) > 0) {
                foreach ($input['arrProduct'] as $v) {
                    //Lấy giá sản phẩm lúc mua hàng
                    $search = $collection->firstWhere('object_id', $v['object_id']);
                    $amountProduct = $search == null ? 0 : $search['price'];
                    $amountLoad += $amountProduct * $v['quantity'];
                }
            }
            //Nếu giá tất cả sp > tổng tiền thì load lại giá = tổng tiền
            if ($amountLoad > $info['amount']) {
                $amountLoad = $info['amount'];
            }
        }
//        } else {
//            //Đơn giao hàng còn lại
//            $amountLoad = $info['amount'];
//        }
        return response()->json([
            'amount' => $amountLoad
        ]);
    }

    /**
     * Lưu log thay đổi trạng thái giao hàng
     *
     * @param $deliveryHistoryId
     * @param $status
     */
    public function insertLog($deliveryHistoryId, $status)
    {
        $mHistory = new DeliveryHistoryTable();
        $mHistoryLog = new DeliveryHistoryLogTable();
        //Check status có thay đổi thì mới insert log
        $check = $mHistory->getItem($deliveryHistoryId);

        if ($check['status'] != $status) {
            switch ($status) {
                //Đang giao
                case "inprogress":
                    $mHistoryLog->add([
                        'delivery_history_id' => $deliveryHistoryId,
                        'status' => $status,
                        'created_type' => 'backend',
                        'created_by' => Auth()->id()
                    ]);
                    break;
                //Hoàn thành
                case "success":
                    //Check log đang giao chưa có thì insert log đang giao
                    $checkInprogress = $mHistoryLog->getLogByStatus($deliveryHistoryId, 'inprogress');
                    if ($checkInprogress == null) {
                        $mHistoryLog->add([
                            'delivery_history_id' => $deliveryHistoryId,
                            'status' => 'inprogress',
                            'created_type' => 'backend',
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log hoàn thành
                    $mHistoryLog->add([
                        'delivery_history_id' => $deliveryHistoryId,
                        'status' => $status,
                        'created_type' => 'backend',
                        'created_by' => Auth()->id()
                    ]);
                    break;
                //Đã nhận hàng
                case "confirm":
                    //Check log đang giao chưa có thì insert log đang giao
                    $checkInprogress = $mHistoryLog->getLogByStatus($deliveryHistoryId, 'inprogress');
                    if ($checkInprogress == null) {
                        $mHistoryLog->add([
                            'delivery_history_id' => $deliveryHistoryId,
                            'status' => 'inprogress',
                            'created_type' => 'backend',
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Check log hoàn thành chưa có thì insert log hoàn thành
                    $checkSuccess = $mHistoryLog->getLogByStatus($deliveryHistoryId, 'success');
                    if ($checkSuccess == null) {
                        $mHistoryLog->add([
                            'delivery_history_id' => $deliveryHistoryId,
                            'status' => 'success',
                            'created_type' => 'backend',
                            'created_by' => Auth()->id()
                        ]);
                    }
                    //Insert log đã nhận hàng
                    $mHistoryLog->add([
                        'delivery_history_id' => $deliveryHistoryId,
                        'status' => $status,
                        'created_type' => 'backend',
                        'created_by' => Auth()->id()
                    ]);
                    break;
                //Đã hủy
                case "cancel":
                    //Insert log hủy
                    $mHistoryLog->add([
                        'delivery_history_id' => $deliveryHistoryId,
                        'status' => $status,
                        'created_type' => 'backend',
                        'created_by' => Auth()->id()
                    ]);
                    break;
                //Giao thất bại
                case "fail":
                    //Insert log giao thất bại
                    $mHistoryLog->add([
                        'delivery_history_id' => $deliveryHistoryId,
                        'status' => $status,
                        'created_type' => 'backend',
                        'created_by' => Auth()->id()
                    ]);
                    break;
            }
        }
    }

    /**
     * Chi tiết phiếu giao hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function detailHistory($input)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();

        $getDetail = $mDeliveryHistory->getItem($input['deliver_history_id']);

        $view = \View::make('delivery::delivery.popup.detail-history', [
            'item' => $getDetail
        ])->render();

        return response()->json([
            'url' => $view
        ]);
    }

    public function editHistory($input)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mUserCarrier = new UserCarrierTable();

        //Lấy thông tin phiếu giao hàng
        $getDetail = $mDeliveryHistory->getItem($input['deliver_history_id']);
        //Option nhân viên giao hàng
        $optionCarrier = $mUserCarrier->getOption();

        $view = \View::make('delivery::delivery.popup.edit-history', [
            'item' => $getDetail,
            'optionCarrier' => $optionCarrier
        ])->render();

        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function updateHistory($input)
    {
        try {
            $mDeliveryHistory = new DeliveryHistoryTable();

            $input['time_ship'] = Carbon::createFromFormat('d/m/Y H:i', $input['time_ship'])->format('Y-m-d H:i');

            $mDeliveryHistory->edit($input, $input['delivery_history_id']);

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa phiếu giao hàng thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa phiếu giao hàng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show modal thanh toán phiếu giao hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function modalConfirmReceipt($input)
    {
        $mDeliveryHistory = app()->get(DeliveryHistoryTable::class);
        $mHistoryPayment = new DeliveryHistoryPaymentTable();
        $mHistoryPaymentDetail = new DeliveryHistoryPaymentDetailTable();

        //Lấy thông tin phiếu giao hàng
        $infoHistory = $mDeliveryHistory->getItem($input['delivery_history_id']);
        //Lấy yêu cầu thanh toán phiếu giao hàng
        $getPayment = $mHistoryPayment->getPaymentByHistory($input['delivery_history_id']);
        //Chi tiết yêu cầu thanh toán
        $getPaymentDetail = [];

        if ($getPayment != null) {
            $getPaymentDetail = $mHistoryPaymentDetail->getPaymentDetail($getPayment != null ? $getPayment['delivery_payment_id'] : null);
        }

        $view = \View::make('delivery::delivery.popup.confirm-payment', [
            'payment' => $getPayment,
            'paymentDetail' => $getPaymentDetail,
            'delivery_history_id' => $input['delivery_history_id'],
            'infoHistory' => $infoHistory
        ])->render();

        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Thêm đơn hàng cần giao
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeDelivery($input)
    {
        try {
            $mDelivery = new DeliveryTable();
            $mOrders = new OrderTable();

//            Lấy chi tiết đơn hàng
            $detailOrder = $mOrders->getItem($input['order_id']);
            if ($detailOrder['receive_at_counter'] == 0){
//                Lấy địa chỉ giao hàng
                $mCustomerContact = app()->get(CustomerContactTable::class);
                $detailAddress = $mCustomerContact->getDetail($detailOrder['customer_contact_id']);
                $data = [
                    'order_id' => $input['order_id'],
                    'customer_id' => $detailAddress == null ? '' : $detailAddress['customer_id'],
                    'contact_name' => $detailAddress == null ? '' : $detailAddress['customer_name'],
                    'contact_phone' => $detailAddress == null ? '' : $detailAddress['customer_phone'],
                    'contact_address' => $detailAddress == null ? '' : $detailAddress['address'].' , '.$detailAddress['ward_name'].' , '.$detailAddress['district_name'].' , '.$detailAddress['province_name'],
                    'is_actived' => 1,
                    'time_order' => Carbon::now()->format('Y-m-d H:i')
                ];
            } else {
                $data = [
                    'order_id' => $input['order_id'],
                    'customer_id' => $input['customer_id'],
                    'contact_name' => $input['contact_name'],
                    'contact_phone' => $input['contact_phone'],
                    'contact_address' => isset($input['contact_address']) ? $input['contact_address'] : '',
                    'is_actived' => 1,
                    'time_order' => Carbon::now()->format('Y-m-d H:i')
                ];
            }

            //Insert thông tin giao hàng
            $mDelivery->add($data);

            // Chuyển trạng thái đơn hàng từ new sang confirm
            $mOrders->edit(['process_status' => 'confirmed'], $input['order_id']);

            return response()->json([
                'error' => false,
                'message' => __('Thêm đơn hàng cần giao thành công'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm đơn hàng cần giao thất bại'),
                '_message' => $e->getMessage()
            ]);
        }

    }

    /**
     * Cập nhật trạng thái hoạt động delivery
     *
     * @param $input
     * @return mixed|void
     */
    public function updateIsActiveDelivery($input)
    {
        try {
            $mOrders = new OrderTable();
            $mOrderLog = new OrderLogTable();

            $this->delivery->edit($input, $input['delivery_id']);

            // Chuyển trạng thái đơn hàng từ new sang confirm
            $mOrders->edit(['process_status' => 'confirmed'], $input['order_id']);

            // Insert log order
            //Kiểm tra order log
            $checkOrderLog = $mOrderLog->checkStatusLog($input['order_id'], 'packing');

            if ($checkOrderLog == null) {
                //Insert order log đơn hàng đang vận chuyển
                $mOrderLog->insert([
                    'order_id' => $input['order_id'],
                    'created_type' => 'backend',
                    'status' => 'packing',
//                    'note' => __('Đang xử lý'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đang xử lý',
                    'note_en' => 'Processing',
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    public function saveSmsLog($deliveryId, $smsType)
    {
        $mSmsLog = new SmsLogTable();
        $mCustomer = new CustomerTable();
        $mSmsConfig = new SmsConfigTable();
        $mOrder = new OrderTable();
        $detailDelivery = $this->delivery->getInfo($deliveryId);
        $dataCus = $mCustomer->getItem($detailDelivery['customer_id']);
        $dataOrder = $mOrder->getItem($detailDelivery['order_id']);
        $parameter = [
            'phone' => $dataCus['phone1'],
            'full_name' => $dataCus['full_name'],
            'name' => substr($dataCus['full_name'], strrpos($dataCus['full_name'], ' ') + 1),
            'gender' => $dataCus['gender'],
            'object_id' => $detailDelivery['order_id'],
            'object_type' => 'order'
        ];
        $smsConfig = $mSmsConfig->getItemByType($smsType);
        if ($smsConfig->is_active == 1) {
            $mSmsProvider = new SmsProviderTable();
            $brandName = $mSmsProvider->getItem(1)->value;
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $timeFinish = Carbon::now()->format('Y-m-d H:i');
            $message = str_replace(['{CUSTOMER_FULL_NAME}', '{CUSTOMER_NAME}', '{CUSTOMER_GENDER}', '{ORDER_CODE}', '{DATETIME}'],
                [$parameter['full_name'] . ' ', $parameter['name'], $gender . ' ', $dataOrder['order_code'], $timeFinish], $content);
            // insert
            $dataSmsLog = [
                'brandname' => $brandName,
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => $smsType,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'order',
            ];
            $idSmsLog = $mSmsLog->add($dataSmsLog);
        }
    }
}