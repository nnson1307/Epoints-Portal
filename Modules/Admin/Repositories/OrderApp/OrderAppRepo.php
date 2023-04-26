<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/25/2020
 * Time: 10:45 AM
 */

namespace Modules\Admin\Repositories\OrderApp;


use App\Exports\ExportFile;
use App\Jobs\CheckMailJob;
use App\Jobs\SaveLogZns;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Api\BookingApi;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Http\Api\SendNotificationApi;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Models\ConfigPrintServiceCardTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\ContractMapOrderTable;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\CustomerAppointmentLogTable;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Models\CustomerBranchMoneyTable;
use Modules\Admin\Models\CustomerDebtTable;
use Modules\Admin\Models\CustomerGroupTable;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DeliveryHistoryLogTable;
use Modules\Admin\Models\DeliveryHistoryTable;
use Modules\Admin\Models\DeliveryTable;
use Modules\Admin\Models\InventoryOutputDetailTable;
use Modules\Admin\Models\InventoryOutputTable;
use Modules\Admin\Models\OrderAppTable;
use Modules\Admin\Models\OrderCommissionTable;
use Modules\Admin\Models\OrderConfigTabTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\OrderImageTable;
use Modules\Admin\Models\OrderLogTable;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\ProductBranchPriceTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventoryTable;
use Modules\Admin\Models\PromotionDailyTimeTable;
use Modules\Admin\Models\PromotionDateTimeTable;
use Modules\Admin\Models\PromotionDetailTable;
use Modules\Admin\Models\PromotionLogTable;
use Modules\Admin\Models\PromotionMasterTable;
use Modules\Admin\Models\PromotionMonthlyTimeTable;
use Modules\Admin\Models\PromotionObjectApplyTable;
use Modules\Admin\Models\PromotionWeeklyTimeTable;
use Modules\Admin\Models\ProvinceTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptOnlineTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\RoomTable;
use Modules\Admin\Models\ServiceBranchPriceTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceCardList;
use Modules\Admin\Models\ServiceMaterialTable;
use Modules\Admin\Models\ServiceTable;
use Modules\Admin\Models\SmsConfigTable;
use Modules\Admin\Models\SpaInfoTable;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Models\StaffTable;
use Modules\Admin\Models\Voucher;
use Modules\Admin\Models\WarehouseTable;
use Modules\Admin\Models\WarrantyCardTable;
use Modules\Admin\Models\WarrantyPackageDetailTable;
use Modules\Admin\Models\WarrantyPackageTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutput\InventoryOutputRepositoryInterface;
use Modules\Admin\Repositories\InventoryOutputDetail\InventoryOutputDetailRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Admin\Models\CustomerContactTable;
use Modules\Admin\Repositories\Warehouse\WarehouseRepositoryInterface;
use Modules\Delivery\Models\DeliveryCostDetailTable;
use App;

class OrderAppRepo implements OrderAppRepoInterface
{
    protected $order;
    protected $orderRepo;

    const NEW = 'new';
    const PAYSUCCESS = 'paysuccess';
    const PAY_HALF = 'pay-half';
    const ORDER_CANCEL = 'ordercancle';
    const CONFIRMED = 'confirmed';

    public function __construct(
        OrderTable $order,
        OrderRepositoryInterface $orderRepo
    ) {
        $this->order = $order;
        $this->orderRepo = $orderRepo;
    }

    const LIVE = 1;

    /**
     * Danh sách đơn hàng từ app
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $mOrderApp = new OrderAppTable();
        $mReceipt = new ReceiptTable();
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);

        $list = $mOrderApp->getList($filters);
        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                $receipt = $mReceipt->getReceiptOrder($v['order_id']);
                $v['amount_paid'] = $receipt != null ? $receipt['amount_paid'] : 0;
                $v['note_receipt'] = $receipt != null ? $receipt['note'] : null;
            }
        }

        if (count($list->items()) > 0) {
            foreach ($list->items() as $item) {
                //Lấy lần thanh toán đơn hàng gần nhất
                $getReceiptLast = $mReceipt->getReceiptOrderLast($item['order_id']);

                $item['receipt_last'] = $getReceiptLast;
                $item['receipt_detail_last'] = [];

                if ($getReceiptLast != null) {
                    $item['receipt_detail_last'] = $mReceiptDetail->getItem($getReceiptLast['receipt_id']);
                }
            }
        }

        return [
            'list' => $list
        ];
    }

    /**
     * Data view thêm đơn hàng
     *
     * @return array|mixed
     */
    public function dateViewCreate($input)
    {
        $mCustomer = new CustomerTable();
        $mStaff = new StaffsTable();
        $mCustomerGroup = new CustomerGroupTable();
        $mProvince = new ProvinceTable();
        $mPaymentMethod = new PaymentMethodTable();
        $mConfig = new ConfigTable();
        $mServiceBranch = new ServiceBranchPriceTable();
        $mRoom = new RoomTable();

        $customer_default = $mCustomer->getCustomerOption();
        //Lấy nv phục vụ
        $staff_technician = $mStaff->getStaffTechnician();
        //Lấy nhóm khách hàng
        $customer_group = $mCustomerGroup->getOption();
        //Lấy option tỉnh thành
        $province = $mProvince->getOptionProvince();
        //Lấy hình thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];
        //Lấy option dịch vụ
        $service = $mServiceBranch->getOptionService(Auth()->user()->branch_id);
        $optionService = [];
        foreach ($service as $item) {
            $optionService[$item['service_id']] = $item['service_name'];
        }
        //Lấy option phòng
        $optionRoom = [];
        foreach ($mRoom->getRoomOption() as $item) {
            $optionRoom[$item['room_id']] = $item['name'];
        }
        $configToDate = $mConfig->getInfoByKey('booking_to_date')['value'];
        //Lấy option nhân viên
        $optionStaff = [];
        foreach ($staff_technician as $item) {
            $optionStaff[$item['staff_id']] = $item['full_name'];
        }
        // sale : 1 1 0 1
        // bs: 0 1 0 1
        // thu ngân: 0 0 1 0

        $is_edit_full = 0;
        $is_edit_staff = 0;
        $is_payment_order = 0;
        if (in_array('is_payment_order', session('routeList'))) {
            $is_payment_order = 1;
        }
        if (in_array('is_edit_full', session('routeList'))) {
            $is_edit_full = 1;
        }
        if (in_array('is_edit_staff', session('routeList'))) {
            $is_edit_staff = 1;
        }

        $is_update_order = 0;
        if ($is_edit_full == 1) {
            $is_update_order = 1;
            $is_edit_staff = 1;
        }
        if ($is_edit_staff == 1) {
            $is_update_order = 1;
        }
        $listMemberCard = [];

        if (isset($input->customer_id) && $input->customer_id != null) {
            $mCustomer = new CustomerTable();
            $mCustomerMoney = new CustomerBranchMoneyTable();
            //Lấy thông tin khách hàng
            $infoCustomer = $mCustomer->getItem($input->customer_id);

            if ($infoCustomer == null) {
                return redirect()->route('admin.order');
            }

            $infoCustomer['money'] = $mCustomerMoney->getPriceBranch($infoCustomer['customer_id'], Auth()->user()->branch_id);
            //Lấy tông tin thẻ liệu trình
            $mMemberCard = new CustomerServiceCardTable();
            $listMemberCard = $mMemberCard->getMemberCard($infoCustomer['customer_id'], Auth::user()->branch_id);

            $customerLoad = $infoCustomer;
            $listMemberCard = $listMemberCard;
        }

        $mConfigTab = app()->get(OrderConfigTabTable::class);

        //Lấy cấu hình tab
        $getTab = $mConfigTab->getConfigTab();

        return [
            'listPhone' => $customer_default,
            'staff_technician' => $staff_technician,
            'customer_refer' => $customer_default,
            'customer_group' => $customer_group,
            'province' => $province,
            'optionPaymentMethod' => $optionPaymentMethod,
            'customPrice' => $customPrice,
            'optionService' => $optionService,
            'optionStaff' => $optionStaff,
            'optionRoom' => $optionRoom,
            'configToDate' => $configToDate,
            'is_edit_full' => $is_edit_full,
            'is_edit_staff' => $is_edit_staff,
            'is_payment_order' => $is_payment_order,
            'is_update_order' => $is_update_order,
            'listMemberCard' => $listMemberCard,
            'getTab' => $getTab
        ];
    }

    /**
     * Thêm đơn hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mStaff = new StaffsTable();
            $mOrderDetail = new OrderDetailTable();
            $mCustomer = new CustomerTable();
            $mPromotionLog = new PromotionLogTable();
            $mOrderLog = new OrderLogTable();

            if ($input['customer_id'] == 1) {
                return response()->json([
                    'error' => false,
                    'message' => __("Khách hàng không hợp lệ")
                ]);
            }

            $staff_branch = $mStaff->getItem(Auth()->id());

            $data_order = [
                'customer_id' => $input['customer_id'],
                'total' => $input['total_bill'],
                'discount' => $input['discount_bill'],
                'amount' => $input['amount_bill'],
                'voucher_code' => $input['voucher_bill'],
                'order_description' => $input['order_description'],
                'branch_id' => $staff_branch['branch_id'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'refer_id' => $input['refer_id'],
                'order_source_id' => 2,
                'process_status' => $input['delivery_active'] == 1 ? 'confirmed' : 'new',
                'tranport_charge' => str_replace(',', '', $input['tranport_charge']),
                'shipping_address' => $input['shipping_address'],
                'customer_contact_code' => $input['customer_contact_code']
            ];
            //Thêm đơn hàng
            $id_order = $this->order->add($data_order);

            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $id_order);

            //Cập nhật order code
            $this->order->edit([
                'order_code' => $orderCode
            ], $id_order);

            $arrObjectBuy = [];

            if (isset($input['table_add']) && count($input['table_add']) > 0) {
                $aData = array_chunk($input['table_add'], 14, false);
                foreach ($aData as $key => $value) {
                    $value[9] = str_replace(',', '', $value[9]);
                    $value[4] = str_replace(',', '', $value[4]);
                    $isChangePrice = isset($value[12]) ? $value[12] : 0;
                    $isCheckPromotion = isset($value[13]) ? $value[13] : 0;

                    if (in_array($value[2], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                        $arrObjectBuy[] = [
                            'object_type' => $value[2],
                            'object_code' => $value[3],
                            'object_id' => $value[0],
                            'price' => $value[4],
                            'quantity' => $value[5],
                            'customer_id' => $input['customer_id'],
                            'order_source' => self::LIVE,
                            'order_id' => $id_order,
                            'order_code' => $orderCode
                        ];
                    }

                    $data_order_detail = [
                        'order_id' => $id_order,
                        'object_id' => $value[0],
                        'object_name' => $value[1],
                        'object_type' => $value[2],
                        'object_code' => $value[3],
                        'price' => $value[4],
                        'quantity' => $value[5],
                        'discount' => str_replace(',', '', $value[7]),
                        'voucher_code' => $value[8],
                        'amount' => $value[9],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'refer_id' => $input['refer_id'],
                        'staff_id' =>  $value[10] != null ? implode(',', $value[10]) : null,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion
                    ];
                    //Thêm chi tiết đơn hàng
                    $mOrderDetail->add($data_order_detail);
                }
            } else {
                return response()->json([
                    'table_error' => 1
                ]);
            }
            //Insert order log đơn hàng mới
            $mOrderLog->insert([
                'order_id' => $id_order,
                'created_type' => 'backend',
                'status' => 'new',
                //                'note' => __('Đặt hàng thành công'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success'
            ]);

            if ($input['delivery_active'] == 1) {
                //Insert order log đơn hàng đã xác nhận, đang xử lý
                $mOrderLog->insert([
                    [
                        'order_id' => $id_order,
                        'created_type' => 'backend',
                        'status' => 'confirmed',
                        //                        'note' => __('Đã xác nhận đơn hàng'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đã xác nhận đơn hàng',
                        'note_en' => 'Order confirm'
                    ],
                    [
                        'order_id' => $id_order,
                        'created_type' => 'backend',
                        'status' => 'packing',
                        //                        'note' => __('Đang xử lý'),
                        'created_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'note_vi' => 'Đang xử lý',
                        'note_en' => 'Processing'
                    ]
                ]);
            }

            if (!isset($input['custom_price']) || $input['custom_price'] == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $this->plusQuotaUsePromotion($arrQuota);
            }

            //Lấy thông tin khách hàng
            $infoCustomer = $mCustomer->getItem($input['customer_id']);
            $mDelivery = new DeliveryTable();

            $dataDelivery = [
                'order_id' => $id_order,
                'customer_id' => $input['customer_id'],
                'contact_name' => $input['contact_name'] != null ? $input['contact_name'] : $infoCustomer['full_name'],
                'contact_phone' => $input['contact_phone'] != null ? $input['contact_phone'] : $infoCustomer['phone1'],
                'contact_address' => $input['shipping_address'],
                'is_actived' => $input['delivery_active'],
                'time_order' => Carbon::now()->format('Y-m-d H:i')
            ];
            //Insert thông tin giao hàng
            $mDelivery->add($dataDelivery);

            //Insert sms log
            $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
            $mSmsLog->getList('order_success', $id_order);
            //Insert email log
            CheckMailJob::dispatch('is_event', 'order_success', $id_order);

            //Cộng điểm khi mua hàng trực tiếp
            $mPlusPoint = new LoyaltyApi();
            $mPlusPoint->plusPointEvent([
                'customer_id' => $input['customer_id'],
                'rule_code' => 'order_app',
                'object_id' => $id_order
            ]);

            DB::commit();

            $mNoti = new SendNotificationApi();
            //Send notification
            if ($input['customer_id'] != 1) {
                $mNoti->sendNotification([
                    'key' => 'order_status_W',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $id_order
                ]);
            }
            //Gửi thông báo NV khi có đơn hàng mới
            $mNoti->sendStaffNotification([
                'key' => 'order_status_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $id_order,
                'branch_id' => Auth()->user()->branch_id
            ]);
            //Lưu log ZNS
            SaveLogZns::dispatch('order_success', $input['customer_id'], $id_order);

            return response()->json([
                'error' => true,
                'message' => __('Thêm thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => __("Thêm thất bại"),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }
    public function storeOrUpdateOrderApp($input)
    {
        DB::beginTransaction();
        try {
            $mStaff = new StaffsTable();
            $mOrderDetail = new OrderDetailTable();
            $mCustomer = new CustomerTable();
            $mPromotionLog = new PromotionLogTable();
            $mOrderLog = new OrderLogTable();

            if (!isset($input['table_add']) || count($input['table_add']) == 0) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn dịch vụ/thẻ dịch vụ/sản phẩm')
                ]);
            }

            if ($input['customer_id'] == 1) {
                return response()->json([
                    'error' => false,
                    'message' => __("Khách hàng không hợp lệ")
                ]);
            }

            if ($input['receipt_info_check'] == 1 && !isset($input['delivery_type'])) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

            if (!(isset($input['order_id']) && $input['order_id'] != null)) {
                $data_order = [
                    'customer_id' => $input['customer_id'],
                    'total' => $input['total_bill'],
                    'discount' => $input['discount_bill'],
                    'amount' => $input['amount_bill'],
                    'voucher_code' => $input['voucher_bill'],
                    'order_description' => $input['order_description'],
                    'branch_id' => Auth()->user()->branch_id,
                    'refer_id' => $input['refer_id'],
                    'order_source_id' => 2,
                    'process_status' => $input['delivery_active'] == 1 ? 'confirmed' : 'new',
                    'shipping_address' => $input['shipping_address'],
                    'customer_contact_code' => $input['customer_contact_code'],
                    'customer_contact_id' => $input['customer_contact_id'],
                    'receive_at_counter' => $input['receipt_info_check'] == 0 ? 1 : 0,
                    'type_time' => $input['type_time'],
                    'time_address' => $input['time_address'] != '' ? Carbon::createFromFormat('d/m/Y', $input['time_address'])->format('Y-m-d') : '',
                    'tranport_charge' => $input['tranport_charge'],
                    'type_shipping' => $input['delivery_type'],
                    'delivery_cost_id' => $input['delivery_cost_id'],
                    'discount_member' => $input['discount_member'],
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id()
                ];
                //Thêm đơn hàng
                $id_order = $this->order->add($data_order);

                $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $id_order);

                //Cập nhật order code
                $this->order->edit([
                    'order_code' => $orderCode
                ], $id_order);

                $arrObjectBuy = [];

                if (isset($input['table_add']) && count($input['table_add']) > 0) {
                    foreach ($input['table_add'] as $key => $value) {
                        if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $value['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['object_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $input['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }

                        //Thêm chi tiết đơn hàng
                        $orderDetailId = $mOrderDetail->add([
                            'order_id' => $id_order,
                            'object_id' => $value['object_id'],
                            'object_name' => $value['object_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => $value['amount'],
                            'refer_id' => $input['refer_id'],
                            'staff_id' =>  isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null,
                            'is_change_price' => $value['is_change_price'],
                            'is_check_promotion' => $value['is_check_promotion'],
                            'note' => $value['note'] ?? null,
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id(),
                        ]);

                        //Lưu dịch vụ kèm theo
                        if (isset($value['array_attach']) && count($value['array_attach']) > 0) {
                            foreach ($value['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $mOrderDetail->add([
                                    'order_id' => $id_order,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }
                    }
                }

                //Insert order log đơn hàng mới
                $mOrderLog->insert([
                    'order_id' => $id_order,
                    'created_type' => 'backend',
                    'status' => 'new',
                    //                'note' => __('Đặt hàng thành công'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đặt hàng thành công',
                    'note_en' => 'Order success'
                ]);

                if ($input['delivery_active'] == 1) {
                    //Insert order log đơn hàng đã xác nhận, đang xử lý
                    $mOrderLog->insert([
                        [
                            'order_id' => $id_order,
                            'created_type' => 'backend',
                            'status' => 'confirmed',
                            //                        'note' => __('Đã xác nhận đơn hàng'),
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đã xác nhận đơn hàng',
                            'note_en' => 'Order confirm'
                        ],
                        [
                            'order_id' => $id_order,
                            'created_type' => 'backend',
                            'status' => 'packing',
                            //                        'note' => __('Đang xử lý'),
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đang xử lý',
                            'note_en' => 'Processing'
                        ]
                    ]);
                }

                if (!isset($input['custom_price']) || $input['custom_price'] == 0) {
                    //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                    $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
                    //Insert promotion log
                    $arrPromotionLog = $getPromotionLog['promotion_log'];
                    $mPromotionLog->insert($arrPromotionLog);
                    //Cộng quota_use promotion quà tặng
                    $arrQuota = $getPromotionLog['promotion_quota'];
                    $this->plusQuotaUsePromotion($arrQuota);
                }

                //Lấy thông tin khách hàng
                $infoCustomer = $mCustomer->getItem($input['customer_id']);
                $mDelivery = new DeliveryTable();

                if ($input['receipt_info_check'] == 1) {
                    $mCustomerContact = app()->get(CustomerContactTable::class);
                    //Lấy thông tin khách hàng
                    $infoCustomer = $mCustomerContact->getDetail($input['customer_contact_id']);
                    $dataDelivery = [
                        'order_id' => $id_order,
                        'customer_id' => $input['customer_id'],
                        'contact_name' => $infoCustomer != null ? $infoCustomer['customer_name'] : '',
                        'contact_phone' => $infoCustomer != null ? $infoCustomer['customer_phone'] : '',
                        'contact_address' => $infoCustomer != null ? $infoCustomer['address'] . ' , ' . $infoCustomer['ward_name'] . ' , ' . $infoCustomer['district_name'] . ' , ' . $infoCustomer['province_name'] : '',
                        'is_actived' => $input['delivery_active'],
                        'time_order' => Carbon::now()->format('Y-m-d H:i')
                    ];
                    //Insert thông tin giao hàng
                    $mDelivery->add($dataDelivery);
                } else {
                    $dataDelivery = [
                        'order_id' => $id_order,
                        'customer_id' => $input['customer_id'],
                        'contact_name' => $input['contact_name'] != null ? $input['contact_name'] : $infoCustomer['full_name'],
                        'contact_phone' => $input['contact_phone'] != null ? $input['contact_phone'] : $infoCustomer['phone1'],
                        'contact_address' => $input['shipping_address'],
                        'is_actived' => $input['delivery_active'],
                        'time_order' => Carbon::now()->format('Y-m-d H:i')
                    ];
                    //Insert thông tin giao hàng
                    $mDelivery->add($dataDelivery);
                }

                //Cộng điểm khi mua hàng trực tiếp
                $mPlusPoint = new LoyaltyApi();
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $input['customer_id'],
                    'rule_code' => 'order_app',
                    'object_id' => $id_order
                ]);

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                $isCreateTicket = 0;

                if ($input['customer_id'] != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'order_success',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'order_success',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);

                //Send notification
                if ($input['customer_id'] != 1) {
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'order_status_W',
                        'customer_id' => $input['customer_id'],
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
                //Gửi thông báo NV khi có đơn hàng mới
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_STAFF,
                    'key' => 'order_status_W',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $id_order,
                    'branch_id' => Auth()->user()->branch_id,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_success',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);

                return response()->json([
                    'error' => true,
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                    'is_create_ticket' => $isCreateTicket,
                    'message' => __('Thêm thành công')
                ]);
            } else {
                $id_order = $input['order_id'];
                $orderCode = $input['order_code'];

                $data_order = [
                    'customer_id' => $input['customer_id'],
                    'total' => $input['total_bill'],
                    'discount' => $input['discount_bill'],
                    'amount' => $input['amount_bill'],
                    'voucher_code' => $input['voucher_bill'],
                    'order_description' => $input['order_description'],
                    'branch_id' => Auth()->user()->branch_id,
                    'refer_id' => $input['refer_id'],
                    'order_source_id' => 2,
                    'process_status' => $input['delivery_active'] == 1 ? 'confirmed' : 'new',
                    'tranport_charge' => str_replace(',', '', $input['tranport_charge']),
                    'shipping_address' => $input['shipping_address'],
                    'customer_contact_code' => $input['customer_contact_code'],
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id(),
                ];
                //Chỉnh sửa đơn hàng
                $this->order->edit($data_order, $id_order);

                $arrObjectBuy = [];

                if (isset($input['table_add']) && count($input['table_add']) > 0) {
                    //Xoá chi tiết đơn hàng
                    $mOrderDetail->remove($id_order);

                    foreach ($input['table_add'] as $key => $value) {
                        if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $value['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['object_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $input['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }

                        //Thêm chi tiết đơn hàng
                        $orderDetailId = $mOrderDetail->add([
                            'order_id' => $id_order,
                            'object_id' => $value['object_id'],
                            'object_name' => $value['object_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => $value['amount'],
                            'refer_id' => $input['refer_id'],
                            'staff_id' =>  isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null,
                            'is_change_price' => $value['is_change_price'],
                            'is_check_promotion' => $value['is_check_promotion'],
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id(),
                            'created_at_day' => Carbon::now()->format('d'),
                            'created_at_month' => Carbon::now()->format('m'),
                            'created_at_year' => Carbon::now()->format('Y'),
                            'note' => $value['note'] ?? null
                        ]);

                        //Lưu dịch vụ kèm theo
                        if (isset($value['array_attach']) && count($value['array_attach']) > 0) {
                            foreach ($value['array_attach'] as $v1) {
                                //Lưu chi tiết đơn hàng
                                $mOrderDetail->add([
                                    'order_id' => $id_order,
                                    'object_id' => $v1['object_id'],
                                    'object_name' => $v1['object_name'],
                                    'object_type' => $v1['object_type'],
                                    'object_code' => $v1['object_code'],
                                    'price' => $v1['price'],
                                    'quantity' => $v1['quantity'],
                                    'amount' => $v1['price'] * $v1['quantity'],
                                    'created_by' => Auth::id(),
                                    'updated_by' => Auth::id(),
                                    'order_detail_id_parent' => $orderDetailId,
                                    'created_at_day' => Carbon::now()->format('d'),
                                    'created_at_month' => Carbon::now()->format('m'),
                                    'created_at_year' => Carbon::now()->format('Y'),
                                ]);
                            }
                        }
                    }
                }

                if ($input['delivery_active'] == 1) {
                    //Insert order log đơn hàng đã xác nhận, đang xử lý
                    $mOrderLog->insert([
                        [
                            'order_id' => $id_order,
                            'created_type' => 'backend',
                            'status' => 'confirmed',
                            //                        'note' => __('Đã xác nhận đơn hàng'),
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đã xác nhận đơn hàng',
                            'note_en' => 'Order confirm'
                        ],
                        [
                            'order_id' => $id_order,
                            'created_type' => 'backend',
                            'status' => 'packing',
                            //                        'note' => __('Đang xử lý'),
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đang xử lý',
                            'note_en' => 'Processing'
                        ]
                    ]);
                }

                if (!isset($input['custom_price']) || $input['custom_price'] == 0) {
                    //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                    $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
                    //Insert promotion log
                    $arrPromotionLog = $getPromotionLog['promotion_log'];
                    $mPromotionLog->insert($arrPromotionLog);
                    //Cộng quota_use promotion quà tặng
                    $arrQuota = $getPromotionLog['promotion_quota'];
                    $this->plusQuotaUsePromotion($arrQuota);
                }

                //Lấy thông tin khách hàng
                $infoCustomer = $mCustomer->getItem($input['customer_id']);
                $mDelivery = new DeliveryTable();

                $dataDelivery = [
                    'order_id' => $id_order,
                    'customer_id' => $input['customer_id'],
                    'contact_name' => $input['contact_name'] != null ? $input['contact_name'] : $infoCustomer['full_name'],
                    'contact_phone' => $input['contact_phone'] != null ? $input['contact_phone'] : $infoCustomer['phone1'],
                    'contact_address' => $input['shipping_address'],
                    'is_actived' => $input['delivery_active'],
                    'time_order' => Carbon::now()->format('Y-m-d H:i')
                ];
                //Insert thông tin giao hàng
                $mDelivery->edit($dataDelivery, $id_order);

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                $isCreateTicket = 0;

                if ($input['customer_id'] != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

                return response()->json([
                    'error' => true,
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                    'is_create_ticket' => $isCreateTicket,
                    'message' => __('Thêm thành công')
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => __("Thêm thất bại"),
                '_message' => $e->getMessage() . $e->getFile() . $e->getLine()
            ]);
        }
    }

    public function checkQuantityOrderDetail($id_order)
    {
        $mOrderDetail = app()->get(OrderDetailTable::class);
        //            Lấy danh sách sản phẩm tồn kho so sánh

        $listProduct = $mOrderDetail->getListProductCheck($id_order);

        $errorMessage = '';
        $arrProductCode = [];

        foreach ($listProduct as $itemProductCheck) {
            if ($itemProductCheck['quantity'] > $itemProductCheck['product_quantity']) {
                if (!isset($arrProductCode[$itemProductCheck['product_child_code']])) {
                    $arrProductCode[$itemProductCheck['product_child_code']] = $itemProductCheck['product_child_code'];
                    $errorMessage = $errorMessage . __('Sản phẩm :product_child_name quản lý theo serial không cho phép bán âm', ['product_child_name' => $itemProductCheck['product_child_name']]) . '</br>';
                }
            }
        }



        if ($errorMessage != '') {
            return [
                'error' => false,
                'message' => $errorMessage
            ];
        } else {
            return [
                'error' => true,
                'message' => ''
            ];
        }
    }

    /**
     * Thêm đơn hàng và thanh toán
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function storeReceipt($input)
    {
        DB::beginTransaction();
        try {
            $mStaff = new StaffsTable();
            $mCode = app()->get(CodeGeneratorRepositoryInterface::class);
            $mVoucher = new Voucher();
            $mOrderDetail = new OrderDetailTable();
            $mService = new ServiceTable();
            $mOrderCommission = new OrderCommissionTable();
            $mCustomerBranch = new CustomerBranchMoneyTable();
            $mProductChild = new ProductChildTable();
            $mServiceCard = new ServiceCard();
            $mCustomer = new CustomerTable();
            $mCustomerServiceCard = new CustomerServiceCardTable();
            $mServiceCardList = new ServiceCardList();
            $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
            $mSpaInfo = new SpaInfoTable();
            $mCustomerDebt = new CustomerDebtTable();
            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();
            $mSmsConfig = new SmsConfigTable();
            $mPromotionLog = new PromotionLogTable();
            $mOrderLog = new OrderLogTable();
            $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

            if ($input['customer_id'] == 1) {
                return response()->json([
                    'error' => false,
                    'message' => "Khách hàng không hợp lệ"
                ]);
            }

            $checkQuantityOrderDetail = $this->checkQuantityOrderDetail($input['order_id']);

            if ($checkQuantityOrderDetail['error'] == false) {
                return response()->json([
                    'error' => $checkQuantityOrderDetail['error'],
                    'message' => $checkQuantityOrderDetail['message']
                ]);
            }

            if ($input['receipt_info_check'] == 1 && !isset($input['delivery_type'])) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
            $staff_branch = $mStaff->getItem(Auth()->id());

            $day_code = date('dmY');
            $id_order = $input['order_id'];
            //Lấy chi tiết đơn hàng
            $detailOrder = $this->order->getDetailOrder($id_order);

            $orderCode = $detailOrder['order_code'];
            //Cập nhật trạng thái đơn hàng
            $this->order->edit([
                'process_status' => 'paysuccess'
            ], $id_order);

            if ($input['voucher_bill'] != null) {
                $get = $mVoucher->getCodeItem($input['voucher_bill']);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];

                $mVoucher->editVoucherOrder($data, $input['voucher_bill']);
            }

            $list_card_print = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];

            if (isset($input['table_add']) && $input['table_add'] != null) {
                //Xoá chi tiết đơn hàng
                $mOrderDetail->remove($id_order);

                foreach ($input['table_add'] as $key => $value) {
                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        if ($value['is_check_promotion'] == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['object_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $input['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }
                        //Lấy array nhắc sử dụng lại
                        $arrRemindUse[] = [
                            'object_type' => $value['object_type'],
                            'object_id' => $value['object_id'],
                            'object_code' => $value['object_code'],
                            'object_name' => $value['object_name'],
                        ];
                    }

                    //Thêm chi tiết đơn hàng
                    $id_detail = $mOrderDetail->add([
                        'order_id' => $id_order,
                        'object_id' => $value['object_id'],
                        'object_name' => $value['object_name'],
                        'object_type' => $value['object_type'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => str_replace(',', '', $value['discount']),
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'staff_id' => isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null,
                        'refer_id' => $input['refer_id'],
                        'is_change_price' => $value['is_change_price'],
                        'is_check_promotion' => $value['is_check_promotion'],
                        'note' => $value['note'] ?? null,
                        'created_at_day' => Carbon::now()->format('d'),
                        'created_at_month' => Carbon::now()->format('m'),
                        'created_at_year' => Carbon::now()->format('Y'),
                    ]);

                    //Lưu dịch vụ kèm theo
                    if (isset($value['array_attach']) && count($value['array_attach']) > 0) {
                        foreach ($value['array_attach'] as $v1) {
                            //Lưu chi tiết đơn hàng
                            $mOrderDetail->add([
                                'order_id' => $id_order,
                                'object_id' => $v1['object_id'],
                                'object_name' => $v1['object_name'],
                                'object_type' => $v1['object_type'],
                                'object_code' => $v1['object_code'],
                                'price' => $v1['price'],
                                'quantity' => $v1['quantity'],
                                'amount' => $v1['price'] * $v1['quantity'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'order_detail_id_parent' => $id_detail,
                                'created_at_day' => Carbon::now()->format('d'),
                                'created_at_month' => Carbon::now()->format('m'),
                                'created_at_year' => Carbon::now()->format('Y'),
                            ]);
                        }
                    }

                    switch ($value['object_type']) {
                        case 'product':
                            $check_commission = $mProductChild->getItem($value['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;


                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], $check_commission, $id_detail, $value['object_name'], null, $value['amount'], $value['staff_id'] ?? null);
                            break;
                        case 'service_card':
                            $sv_card = $mServiceCard->getServiceCardOrder($value['object_code']);
                            $staff = $mStaff->getItem(Auth()->id());
                            //Lấy hoa hồng thẻ dịch vụ
                            $check_commission = $mServiceCard->getServiceCardInfo($value['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            $refer_money = 0;
                            $staffCardCommission = 0;
                            if ($check_commission['type_refer_commission'] == 'money') {
                                if (isset($check_commission['refer_commission_value'])) {
                                    $refer_money = ($check_commission['refer_commission_value']);
                                }
                            } else {
                                if (isset($check_commission['refer_commission_value'])) {
                                    $refer_money = ($value['amount'] / 100) * ($check_commission['refer_commission_value']);
                                }
                            }
                            if ($check_commission['type_staff_commission'] == 'money') {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffCardCommission = round($check_commission['staff_commission_value'], $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            } else {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffMoney = ($value['amount'] / 100) * ($check_commission['staff_commission_value']);
                                    $staffCardCommission = round($staffMoney, $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            }

                            $arr_result = [];
                            for ($i = 0; $i < $value['quantity']; $i++) {
                                $code = $mCode->generateCardListCode();
                                while (array_search($code, $arr_result)) {
                                    $code = $mCode->generateCardListCode();
                                }

                                $data_card_list = [
                                    'service_card_id' => $value['object_id'],
                                    'order_code' => 'DH_' . $day_code . $id_order,
                                    'branch_id' => Auth()->user()->branch_id,
                                    'created_by' => Auth()->id(),
                                    'price' => $value['price'],
                                    'code' => $code,
                                    'refer_commission' => $refer_money,
                                    'staff_commission' => $staffCardCommission,
                                ];

                                if ($input['customer_id'] != 1 && $input['check_active'] == 1) {
                                    $data_card_list['is_actived'] = $input['check_active'];
                                    $data_card_list['actived_at'] = date("Y-m-d H:i");
                                    $data_cus_card = [
                                        'customer_id' => $input['customer_id'],
                                        'card_code' => $code,
                                        'service_card_id' => $value[0],
                                        'number_using' => $sv_card['number_using'],
                                        'count_using' => $sv_card['service_card_type'] == 'money' ? 1 : 0,
                                        'money' => $sv_card['money'],
                                        'actived_date' => date("Y-m-d"),
                                        'is_actived' => 1,
                                        'created_by' => Auth()->id(),
                                        'updated_by' => Auth()->id(),
                                        'branch_id' => Auth()->user()->branch_id
                                    ];

                                    if ($sv_card['date_using'] != 0) {
                                        $data_cus_card['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $sv_card['date_using'] . 'days'))));
                                    }
                                    if ($sv_card['service_card_type'] == 'money') {
                                        //Lấy thông tin KH
                                        $customer = $mCustomer->getItem($input['customer_id']);
                                        //Cập nhật tiền KH
                                        $mCustomer->edit([
                                            'account_money' => $customer['account_money'] + $sv_card['money']
                                        ], $input['customer_id']);

                                        //Lưu log + tiền
                                        $mBranchMoneyLog->add([
                                            "customer_id" => $input['customer_id'],
                                            "branch_id" => Auth()->user()->branch_id,
                                            "source" => "member_money",
                                            "type" => 'plus',
                                            "money" => $sv_card['money'],
                                            "screen" => 'active_card',
                                            "screen_object_code" => $code
                                        ]);
                                    }

                                    //Thêm vào customer service card thẻ đã active
                                    $id_cus_card = $mCustomerServiceCard->add($data_cus_card);
                                    //Thêm vào service card list thẻ đã active
                                    $id_card_list = $mServiceCardList->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                } else {
                                    $data_card_list['is_actived'] = 0;
                                    $id_card_list = $mServiceCardList->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                }
                            }

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], $check_commission, $id_detail, $value['object_name'], null,  $value['amount'], $value['staff_id'] ?? null);
                            break;
                        case 'service':
                            $check_commission = $mService->getItem($value['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], $check_commission, $id_detail, $value['object_name'], null, $value['amount'], $value['staff_id'] ?? null);
                            break;
                        case 'member_card':
                            //Trừ số lần sử dụng thẻ liệu trình
                            $list_cus_card = $mCustomerServiceCard->getItemCard($value['object_id']);
                            $data_edit_card = [
                                'count_using' => $list_cus_card['count_using'] + $value['quantity']
                            ];
                            $mCustomerServiceCard->editByCode($data_edit_card, $value['object_code']);

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], null, $id_detail, $value['object_name'], $value['object_code'], $value['amount'], $value['staff_id'] ?? null, 0, 0, "member_card");


                            DB::commit();

                            //Insert email log
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_EMAIL_CUSTOMER,
                                'event' => 'is_event',
                                'key' => 'service_card_over_number_used',
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Insert sms log
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_SMS_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Lưu log ZNS
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_ZNS_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'customer_id' => $input['customer_id'],
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Gửi thông báo khách hàng
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_NOTIFY_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'customer_id' => $input['customer_id'],
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            break;
                    }

                    if ($value['voucher_code'] != null) {
                        //Lấy thông tin voucher
                        $get = $mVoucher->getCodeItem($value['voucher_code']);
                        $data = [
                            'total_use' => ($get['total_use'] + 1)
                        ];
                        $mVoucher->editVoucherOrder($data, $value['voucher_code']);
                    }

                    if (in_array($value['object_type'], ['service_gift', 'product_gift', 'service_card_gift'])) {
                        $data_order_detail = [
                            'order_id' => $id_order,
                            'object_id' => $value['object_id'],
                            'object_name' => $value['object_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => $value['amount'],
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id(),
                            'staff_id' =>  isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null,
                            'refer_id' => $input['refer_id'],
                            'is_change_price' => $value['is_change_price'],
                            'is_check_promotion' => $value['is_check_promotion']
                        ];
                        $mOrderDetail->add($data_order_detail);
                    }
                }
            } else {
                return response()->json([
                    'table_error' => 1
                ]);
            }

            //Insert order log đơn hàng mới
            $mOrderLog->insert([
                'order_id' => $id_order,
                'created_type' => 'backend',
                'status' => 'new',
                //                'note' => __('Đặt hàng thành công'),
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success'
            ]);

            if (!isset($input['custom_price']) || $input['custom_price'] == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $this->plusQuotaUsePromotion($arrQuota);
            }

            if ($input['customer_id'] != 1) {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'order_success',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'order_success',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);

                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'paysuccess',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'paysuccess',
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
            }

            //Lấy phương thức thanh toán
            $arrMethodWithMoney = $input['array_method'];

            $amount_bill = str_replace(',', '', $input['amount_bill']);

            if ($input['amount_all'] != '') {
                $amount_receipt_all = 0;

                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($money > 0) {
                        $amount_receipt_all += $money;
                    }
                }
            } else {
                $amount_receipt_all = 0;
            }
            $receipt_type = $input['receipt_type'];
            $status = '';
            if ($amount_receipt_all >= $amount_bill) {
                $status = 'paid';
            } else {
                //Cập nhật trạng thái đơn hàng thanh toán còn thiếu
                $this->order->edit(['process_status' => 'pay-half'], $id_order);
            }

            if ($amount_bill != 0) {
                if ($amount_receipt_all < $amount_bill) {
                    //Check KH là hội viên
                    if ($input['customer_id'] != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $check_info = $mSpaInfo->getInfoSpa();
                        if ($check_info['is_part_paid'] == 1) {
                            $status = 'paid';
                            //Cho thanh toán thiếu nhưng nếu tạo từ app thì ko insert vào công nợ
                            if ($detailOrder['order_source_id'] != 2) {
                                //insert customer debt
                                $data_debt = [
                                    'customer_id' => $input['customer_id'],
                                    'debt_code' => 'debt',
                                    'staff_id' => Auth()->id(),
                                    'note' => $input['note'],
                                    'debt_type' => 'order',
                                    'order_id' => $id_order,
                                    'status' => 'unpaid',
                                    'amount' => $amount_bill - $amount_receipt_all,
                                    'created_by' => Auth()->id(),
                                    'updated_by' => Auth()->id()
                                ];
                                $debt_id = $mCustomerDebt->add($data_debt);
                                //update debt code
                                $day_code = date('dmY');
                                if ($debt_id < 10) {
                                    $debt_id = '0' . $debt_id;
                                }
                                $debt_code = [
                                    'debt_code' => 'CN_' . $day_code . $debt_id
                                ];
                                $mCustomerDebt->edit($debt_code, $debt_id);
                            }
                        } else {
                            return response()->json([
                                'amount_detail_small' => 1,
                                'message' => __('Số tiền không hợp lệ')
                            ]);
                        }
                    } else {
                        return response()->json([
                            'amount_detail_small' => 1,
                            'message' => __('Số tiền không hợp lệ')
                        ]);
                    }
                }
            }

            // get receipt by order id => remove receipt and receipt detail
            $dataReceipt = $mReceipt->getItem($id_order);
            // no receipt by order_id but $dataReceipt still have data (data with all null field) ?????
            if ($dataReceipt != null && isset($dataReceipt['receipt_id']) && $dataReceipt['receipt_id'] != null) {
                $mReceipt->removeReceipt($id_order);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }
            $data_receipt = [
                'customer_id' => $input['customer_id'],
                'staff_id' => Auth()->id(),
                'object_id' => $id_order,
                'object_type' => 'order',
                'order_id' => $id_order,
                'total_money' => $amount_receipt_all,
                'voucher_code' => $input['voucher_bill'],
                'status' => $status,
                'is_discount' => 1,
                //                'amount' => $amount_bill,
                'amount' => $amount_bill,
                'amount_paid' => $amount_receipt_all > $amount_bill ? $amount_bill : $amount_receipt_all,
                'amount_return' => $amount_receipt_all > $amount_bill ? $amount_receipt_all - $amount_bill : 0,
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => $orderCode, // order code
                'object_accounting_id' => $id_order, // order id
            ];

            if ($input['voucher_bill'] != null) {
                $data_receipt['discount'] = $input['discount_bill'];
            } else {
                $data_receipt['custom_discount'] = $input['discount_bill'];
            }

            $receipt_id = $mReceipt->add($data_receipt);

            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $receipt_id
            ];
            $mReceipt->edit($data_code, $receipt_id);

            if (isset($input['table_add']) && count($input['table_add']) > 0) {
                foreach ($input['table_add'] as $key => $value) {
                    if ($value['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $receipt_id,
                            'cashier_id' => Auth()->id(),
                            //                            'receipt_type' => 'member_card',
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $value['object_code'],
                            'amount' => $value['amount'],
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id()
                        ];
                        $mReceiptDetail->add($data_receipt_detail);
                    }
                }
            }
            // Chi tiết thanh toán
            $mReceiptOnline = new ReceiptOnlineTable();
            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();

            $isNotifyMinAccount = 0;

            foreach ($arrMethodWithMoney as $methodCode => $money) {
                $itemMethod = $mPaymentMethod->getPaymentMethodByCode($methodCode);
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $receipt_id,
                        'cashier_id' => Auth::id(),
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amount_bill) { // trừ tiên thành viên
                            if ($money < $input['member_money']) {
                                //Lưu 1 dòng chi tiết thanh toán
                                $mReceiptDetail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $mCustomer->getItem($input['customer_id']);
                                //Cập nhật tiền của KH
                                $mCustomer->edit([
                                    'account_money' => $customerMoney['account_money'] - $money
                                ], $input['customer_id']);

                                $mConfig = app()->get(ConfigTable::class);
                                //Lấy cấu hình số tiền tối thiểu
                                $configMinAccount = $mConfig->getInfoByKey('money_account_min')['value'];

                                if (($customerMoney['account_money'] - $money) <= $configMinAccount) {
                                    $isNotifyMinAccount = 1;
                                }
                                //Lưu log - tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $input['customer_id'],
                                    "branch_id" => Auth()->id(),
                                    "source" => "member_money",
                                    "type" => 'subtract',
                                    "money" => $money,
                                    "screen" => 'order',
                                    "screen_object_code" => $orderCode
                                ]);
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $input['member_money']
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } elseif ($methodCode == 'VNPAY') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $receipt_id,
                            'status' => 'success'
                        ], 'order', $id_order, $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // get receipt_online of method/order
                        $dataReceiptOnline = $mReceiptOnline->getReceiptOnlineByTypeAndOrderId('order', $id_order, $methodCode);
                        if ($dataReceiptOnline != null) {
                            // update status, receipt_id of receipt_online
                            $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                                'amount_paid' => $money,
                                'receipt_id' => $receipt_id,
                                'status' => 'success'
                            ], 'order', $id_order, $methodCode);
                        } else {
                            // create status, receipt_id of receipt_online
                            $dataReceiptOnline = [
                                'receipt_id' => $receipt_id,
                                'object_type' => 'order',
                                'object_id' => $id_order,
                                'object_code' => $orderCode,
                                'payment_method_code' => $methodCode,
                                'amount_paid' => $money,
                                'payment_time' => Carbon::now(),
                                'status' => 'success',
                                'performer_name' => $staff_branch['name'],
                                'performer_phone' => $staff_branch['phone1'],
                                'type' => $itemMethod['payment_method_type'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);
                        }
                    } else {
                        $mReceiptDetail->add($dataReceiptDetail);
                    }
                }
            }
            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $mServiceCardList->searchCard($v);
                    $get_sv_card = $mServiceCard->getServiceCardInfo($get_cus_card['service_card_id']);
                    $data_print[] = [
                        'customer_id' => $input['customer_id'],
                        'type' => $get_sv_card['service_card_type'],
                        'card_name' => $get_cus_card['card_name'],
                        'card_code' => $get_cus_card['code'],
                        'number_using' => $get_sv_card['number_using'],
                        'date_using' => $get_sv_card['date_using'],
                        'money' => $get_sv_card['money'],
                        'service_card_id' => $get_sv_card['service_card_id'],
                    ];
                }
            }

            $checkSendSms = $mSmsConfig->getItemByType('paysuccess');
            //Lấy thông tin khách hàng
            $infoCustomer = $mCustomer->getItem($input['customer_id']);
            $mDelivery = new DeliveryTable();

            $dataDelivery = [
                'order_id' => $id_order,
                'customer_id' => $input['customer_id'],
                'contact_name' => $input['contact_name'] != null ? $input['contact_name'] : $infoCustomer['full_name'],
                'contact_phone' => $input['contact_phone'] != null ? $input['contact_phone'] : $infoCustomer['phone1'],
                'contact_address' => $input['shipping_address'],
                'is_actived' => 1,
                'time_order' => Carbon::now()->format('Y-m-d H:i')
            ];

            //Insert order log đơn hàng đã xác nhận, đang xử lý
            $mOrderLog->insert([
                [
                    'order_id' => $id_order,
                    'created_type' => 'backend',
                    'status' => 'confirmed',
                    //                    'note' => __('Đã xác nhận đơn hàng'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đã xác nhận đơn hàng',
                    'note_en' => 'Order confirm'
                ],
                [
                    'order_id' => $id_order,
                    'created_type' => 'backend',
                    'status' => 'packing',
                    //                    'note' => __('Đang xử lý'),
                    'created_by' => Auth()->id(),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'note_vi' => 'Đang xử lý',
                    'note_en' => 'Processing'
                ]
            ]);

            //Insert thông tin giao hàng
            $mDelivery->add($dataDelivery);

            // UPDATE: 29/04/2021: Thêm lịch hẹn nhanh ở popup thanh toán
            if (isset($input['arrAppointment'])) {
                $arrAppointment = $input['arrAppointment'];
                if ($arrAppointment['checked'] == 1) {
                    // Thêm lịch hẹn
                    $result = $this->_addQuickAppointment($arrAppointment, $input['customer_id']);
                    if ($result['error'] == false) {
                        return response()->json($result);
                    }
                }
            }
            // END UPDATE

            //Insert phiếu bảo hành điện tử
            $dataTableAdd = $input['table_add'];
            $this->addWarrantyCard($infoCustomer['customer_code'], $id_order, $orderCode, $dataTableAdd);

            $mOrder = app()->get(OrderRepositoryInterface::class);
            //Lưu log dự kiến nhắc sử dụng lại
            $mOrder->insertRemindUse($id_order, $input['customer_id'], $arrRemindUse);

            $rWarehouse = app()->get(WarehouseRepositoryInterface::class);
            $rInventoryOutput = app()->get(InventoryOutputRepositoryInterface::class);
            $rOrderDetail = app()->get(OrderDetailRepositoryInterface::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $rInventoryOutputDetail = app()->get(InventoryOutputDetailRepositoryInterface::class);

            $checkWarehouse = $rWarehouse->getWarehouseByBranch(Auth::user()->branch_id);

            $warehouseId = 0;

            foreach ($checkWarehouse as $item) {
                if ($item['is_retail'] == 1) {
                    $warehouseId = $item['warehouse_id'];
                }
            }

            //            Lấy danh sách sản phẩm để kiểm nếu có sản phẩm serial trạng thái là new
            $getListProductCheckStatus = $mOrderDetail->getListProductCheck($id_order);

            $dataInventoryOutput = [
                'warehouse_id' => $warehouseId,
                'po_code' => 'XK',
                'created_by' => Auth::user()->staff_id,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => count($getListProductCheckStatus) != 0 ? 'new' : 'success',
                'note' => '',
                'type' => 'retail',
                'object_id' => $id_order
            ];

            $idInventoryOutput = $rInventoryOutput->add($dataInventoryOutput);

            $idCode = $idInventoryOutput;
            if ($idInventoryOutput < 10) {
                $idCode = '0' . $idCode;
            }

            $rInventoryOutput->edit(['po_code' => $this->codeDMY('XK', $idCode)], $idInventoryOutput);

            //            Lấy danh sách sản phẩm
            $listOrderProduct = $rOrderDetail->getValueByOrderIdAndObjectType($id_order, 'product');

            foreach ($listOrderProduct as $item) {
                //                kiểm tra mã sản phẩm đã được tạo trong phiếu xuất kho hay chưa
                $checkProductInventotyOutput = $mInventoryOutputDetail->checkProductInventotyOutput($idInventoryOutput, $item['object_code']);

                $getDetailOutputDetail = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput, $item['object_code']);

                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $getDetailOutputDetail != null ? (int)$getDetailOutputDetail['quantity'] + (int)$item['quantity'] : $item['quantity'],
                    'current_price' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['current_price'] + (float)$item['price'] : $item['price'],
                    'total' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['total'] + (float)$item['amount'] : $item['amount'],
                ];

                if ($getDetailOutputDetail != null) {
                    $idIOD = $getDetailOutputDetail['inventory_output_detail_id'];
                    $mInventoryOutputDetail->editDetail($idIOD, $dataInventoryOutputDetail);
                } else {
                    $idIOD = $rInventoryOutputDetail->add($dataInventoryOutputDetail);
                }
            }

            DB::commit();

            //Send notification
            if ($input['customer_id'] != 1) {
                //Gửi thông báo khách hàng
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_S',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Cộng điểm khi mua hàng từ app
                $mPlusPoint = new LoyaltyApi();
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $input['customer_id'],
                    'rule_code' => 'order_app',
                    'object_id' => $id_order
                ]);
                //Lưu log ZNS (tạo đơn hàng mới)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_success',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS (thanh toán thành công)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_thanks',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $id_order,
                    'tenant_id' => session()->get('idTenant')
                ]);
                if ($isNotifyMinAccount == 1) {
                    //Gửi thông báo tiền trong tài khoản sắp hết
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'money_account_min',
                        'customer_id' => $input['customer_id'],
                        'object_id' => $id_order,
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
            }
            //Thông báo NV khi có đơn hàng mới
            App\Jobs\FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'order_status_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $id_order,
                'branch_id' => Auth()->user()->branch_id,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();
            if ($amount_receipt_all >= $amount_bill) {
                $mBookingApi->plusPointReceiptFull(['receipt_id' => $receipt_id]);
            } else {
                $mBookingApi->plusPointReceipt(['receipt_id' => $receipt_id]);
            }

            return response()->json([
                'error' => true,
                'message' => 'Thanh toán thành công',
                'print_card' => $data_print,
                'orderId' => $id_order,
                'isSMS' => $checkSendSms['is_active']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getLine() . $e->getFile()
            ]);
        }
    }

    //Code theo chữ cái đầu và stt tự tăng.
    public function codeDMY($string, $stt)
    {
        $time = date("dmY");
        return $string . '_' . $time . $stt;
    }

    const PLUS = "plus";
    const SUBTRACT = "subtract";

    /**
     * Lấy dữ liệu view thanh toán
     *
     * @param $orderId
     * @return mixed|void
     */
    public function dataViewReceipt($orderId, $paymentType)
    {
        $mStaff = new StaffsTable();
        $mCustomer = new CustomerTable();
        $mOrderDetail = new OrderDetailTable();
        $mCustomerBranchMoney = new CustomerBranchMoneyTable();
        $mCustomerServiceCard = new CustomerServiceCardTable();
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mPaymentMethod = new PaymentMethodTable();
        $mConfig = new ConfigTable();
        $mBranchMoneyLog = app()->get(CustomerBranchMoneyLogTable::class);

        //Lấy hình thức thanh toán
        $optionPaymentMethod = $mPaymentMethod->getOption();
        //Lấy nv phục vụ
        $staff_technician = $mStaff->getStaffTechnician();
        //Lấy nhóm khách hàng
        $customer_default = $mCustomer->getCustomerOption();
        //Lấy thông tin đơn hàng
        $data_receipt = $this->order->getItemDetail($orderId);

        $mCustomerContact = app()->get(CustomerContactTable::class);
        if ($data_receipt['customer_contact_code'] != null) {
            $detailAddressCode = $mCustomerContact->getDetailByCode($data_receipt['customer_contact_code']);
            if ($detailAddressCode != null) {
                $this->order->edit(['customer_contact_id' => $detailAddressCode['customer_contact_id']], $orderId);
                $data_receipt['customer_contact_id'] = $detailAddressCode['customer_contact_id'];
            }
        }

        //Lấy chi tiết đơn hàng
        $order_detail = $mOrderDetail->getItem($orderId);

        $branchId = null;
        //Lấy cấu hình 1 chi nhánh or liên chi nhánh
        $configBranch = $mConfig->getInfoByKey('is_total_branch')['value'];

        if ($configBranch == 0) {
            //Lấy chi nhánh của nv đăng nhập
            $branchId = Auth()->user()->branch_id;
        }

        //Lấy thông tin nhân viên
        $staff = $mStaff->getItem(Auth()->id());
        //Lấy tổng tiền thành viên cộng vào
        $totalPlusMoney = $mBranchMoneyLog->getTotalMoney($data_receipt['customer_id'], $branchId, self::PLUS);
        //Lấy tổng tiền thành viên trừ ra
        $totalSubtractMoney = $mBranchMoneyLog->getTotalMoney($data_receipt['customer_id'], $branchId, self::SUBTRACT);

        $accountMoney = floatval($totalPlusMoney['total']) - floatval($totalSubtractMoney['total']);
        //Lấy tiền thành viên
        $money_customer = $accountMoney > 0 ? $accountMoney : 0;

        //Lấy list thẻ liệu trình
        $list_card_active = $mCustomerServiceCard->loadCardMember($data_receipt['customer_id'], $branchId);

        $data = [];
        $data_detail = [];

        foreach ($list_card_active as $key => $item) {
            if ($item['expired_date'] == null) {
                if ($item['number_using'] == 0) {
                    $data[] = [
                        'customer_service_card_id' => $item['customer_service_card_id'],
                        'card_code' => $item['card_code'],
                        'card_name' => $item['name_code'],
                        'image' => $item['image'],
                        'number_using' => $item['number_using'],
                        'count_using' => 'Không giới hạn',
                    ];
                } else {
                    if ($item['number_using'] > $item['count_using']) {
                        $data[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => $item['number_using'] - $item['count_using'],
                        ];
                    }
                }
            } else {
                if (date('Y-m-d', strtotime($item['expired_date'])) >= date('Y-m-d')) {
                    if ($item['number_using'] == 0) {
                        $data[] = [
                            'customer_service_card_id' => $item['customer_service_card_id'],
                            'card_code' => $item['card_code'],
                            'card_name' => $item['name_code'],
                            'image' => $item['image'],
                            'number_using' => $item['number_using'],
                            'count_using' => 'Không giới hạn',
                            'expired_date' => date('d/m/Y', strtotime($item['expired_date']))
                        ];
                    } else {
                        if ($item['number_using'] > $item['count_using']) {
                            $data[] = [
                                'customer_service_card_id' => $item['customer_service_card_id'],
                                'card_code' => $item['card_code'],
                                'card_name' => $item['name_code'],
                                'image' => $item['image'],
                                'number_using' => $item['number_using'],
                                'count_using' => $item['number_using'] - $item['count_using'],
                                'expired_date' => date('d/m/Y', strtotime($item['expired_date']))
                            ];
                        }
                    }
                }
            }
        }

        $collectionDetail = collect($order_detail->toArray());

        foreach ($order_detail as $item) {
            if ($item['order_detail_id_parent'] == null) {
                $data_detail[] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'max_quantity_card' => $mCustomerServiceCard->searchCard($item['object_code']),
                    'staff_id' => explode(',', $item['staff_id']),
                    'refer_id' => $item['refer_id'],
                    'is_change_price' => $item['is_change_price'],
                    'is_check_promotion' => $item['is_check_promotion'],
                    'inventory_management' => $item['inventory_management'],
                    'note' => $item['note'],
                    'attach' => $collectionDetail->where('order_detail_id_parent', $item['order_detail_id'])->all()
                ];
            }
        }

        //Lấy thông tin phiếu giao hàng từ đơn hàng
        $deliveryHistory = $mDeliveryHistory->getHistoryByOrder($orderId);
        $numberHistorySuccess = 0;
        if (count($deliveryHistory) > 0) {
            foreach ($deliveryHistory as $item) {
                if (in_array($item['status'], ['inprogress', 'success', 'confirm'])) {
                    $numberHistorySuccess++;
                }
            }
        }
        //Lấy cấu hình thay đổi giá
        $customPrice = $mConfig->getInfoByKey('customize_price')['value'];
        // sale : 1 1 0 1
        // bs: 0 1 0 1
        // thu ngân: 0 0 1 0

        $is_edit_full = 0;
        $is_edit_staff = 0;
        $is_payment_order = 0;
        if (in_array('is_payment_order', session('routeList'))) {
            $is_payment_order = 1;
        }
        if (in_array('is_edit_full', session('routeList'))) {
            $is_edit_full = 1;
        }
        if (in_array('is_edit_staff', session('routeList'))) {
            $is_edit_staff = 1;
        }

        $is_update_order = 0;
        if ($is_edit_full == 1) {
            $is_update_order = 1;
            $is_edit_staff = 1;
        }
        if ($is_edit_staff == 1) {
            $is_update_order = 1;
        }

        $detailAddress = $mCustomerContact->getDetail($data_receipt['customer_contact_id']);

        $itemFee = null;
        if ($detailAddress != null) {
            $mDeliveryCostDetail = app()->get(DeliveryCostDetailTable::class);
            $itemFee = $mDeliveryCostDetail->checkAddress($detailAddress['province_id'], $detailAddress['district_id']);
        }


        $mCustomerDebt = app()->get(CustomerDebtTable::class);
        //Lấy công nợ của KH
        $amountDebt = $mCustomerDebt->getItemDebt($data_receipt['customer_id']);

        $debt = 0;
        if (count($amountDebt) > 0) {
            foreach ($amountDebt as $item) {
                $debt += $item['amount'] - $item['amount_paid'];
            }
        }

        $mConfigTab = app()->get(OrderConfigTabTable::class);

        //Lấy cấu hình tab
        $getTab = $mConfigTab->getConfigTab();

        return [
            'item' => $data_receipt,
            'order_detail' => $data_detail,
            'data' => $data,
            'money' => $money_customer,
            'orderIdsss' => $orderId,
            'staff_technician' => $staff_technician,
            'customer_refer' => $customer_default,
            'numberHistorySuccess' => $numberHistorySuccess,
            'optionPaymentMethod' => $optionPaymentMethod,
            'customPrice' => $customPrice,
            'optionStaff' => $staff_technician,
            'is_edit_full' => $is_edit_full,
            'is_edit_staff' => $is_edit_staff,
            'is_payment_order' => $is_payment_order,
            'is_update_order' => $is_update_order,
            'paymentType' => $paymentType,
            'detailAddress' => $detailAddress,
            'itemFee' => $itemFee,
            'debt' => $debt,
            'getTab' => $getTab
        ];
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function edit($input)
    {
        DB::beginTransaction();
        try {
            $mNoti = new SendNotificationApi();
            $mOrderDetail = new OrderDetailTable();

            $data_order = [
                'total' => $input['total_bill'],
                'discount' => $input['discount_bill'],
                'voucher_code' => $input['voucher_bill'],
                'amount' => str_replace(',', '', $input['amount_bill']),
                'updated_by' => Auth()->id(),
                'refer_id' => $input['refer_id'],
                'tranport_charge' => str_replace(',', '', $input['tranport_charge'])
            ];
            $this->order->edit($data_order, $input['order_id']);

            //Xóa chi tiết đơn hàng cũ
            $mOrderDetail->remove($input['order_id']);
            if (isset($input['table_edit']) && count($input['table_edit']) > 0) {
                $aData = array_chunk($input['table_edit'], 12, false);
                foreach ($aData as $key => $value) {
                    $data_order_detail = [
                        'order_id' => $input['order_id'],
                        'object_id' => $value[1],
                        'object_name' => $value[2],
                        'object_type' => $value[3],
                        'object_code' => $value[4],
                        'price' => $value[5],
                        'quantity' => $value[6],
                        'discount' => str_replace(',', '', $value[8]),
                        'voucher_code' => $value[9],
                        'amount' => $value[10],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'refer_id' => $input['refer_id'],
                        'staff_id' =>  $value[11] != null ? implode(',', $value[11]) : null,
                    ];
                    $mOrderDetail->add($data_order_detail);
                }
            }
            if (isset($input['table_add']) && count($input['table_add']) > 0) {
                $aData = array_chunk($input['table_add'], 11, false);
                foreach ($aData as $key => $value) {
                    $data_order_detail = [
                        'order_id' => $input['order_id'],
                        'object_id' => $value[0],
                        'object_name' => $value[1],
                        'object_type' => $value[2],
                        'object_code' => $value[3],
                        'price' => $value[4],
                        'quantity' => $value[5],
                        'discount' => str_replace(',', '', $value[7]),
                        'voucher_code' => $value[8],
                        'amount' => $value[9],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'refer_id' => $input['order_id'],
                        'staff_id' =>  $value[10] != null ? implode(',', $value[10]) : null,
                    ];
                    $mOrderDetail->add($data_order_detail);
                }
            }

            if (isset($input['delivery_active']) && $input['delivery_active'] == 1) {
                //Cập nhật trạng thái đơn hàng
                $this->order->edit([
                    'process_status' => 'confirmed'
                ], $input['order_id']);
                //Cập nhật trạng thái đơn hàng cần giao
                $mDelivery = new DeliveryTable();
                $mDelivery->edit([
                    'is_actived' => 1
                ], $input['order_id']);
            }
            //Xóa tất cả phiếu giao hàng  của đơn hàng
            $this->removeDeliveryHistory($input['order_id']);

            DB::commit();

            if (isset($input['delivery_active']) && $input['delivery_active'] == 1 && $input['customer_id'] != 1) {
                //Send notification xác nhận đơn hàng
                $mNoti->sendNotification([
                    'key' => 'order_status_A',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $input['order_id']
                ]);
            }

            return response()->json([
                'error' => false,
                'message' => 'Lưu đơn hàng thành công',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Thanh toán đơn hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function receipt($input)
    {
        DB::beginTransaction();
        try {
            $mNoti = new SendNotificationApi();
            $mStaff = new StaffsTable();
            $mOrderDetail = new OrderDetailTable();
            $mService = new ServiceTable();
            $mOrderCommission = new OrderCommissionTable();
            $mCustomerBranch = new CustomerBranchMoneyTable();
            $mProductChild = new ProductChildTable();
            $mServiceCard = new ServiceCard();
            $mCode = app()->get(CodeGeneratorRepositoryInterface::class);
            $mCustomer = new CustomerTable();
            $mCustomerServiceCard = new CustomerServiceCardTable();
            $mServiceCardList = new ServiceCardList();
            $mSmsLog = app()->get(SmsLogRepositoryInterface::class);
            $mVoucher = new Voucher();
            $mSpaInfo = new SpaInfoTable();
            $mCustomerDebt = new CustomerDebtTable();
            $mReceipt = new ReceiptTable();
            $mWareHouse = new WarehouseTable();
            $mInventoryOutput = new InventoryOutputTable();
            $mInventoryOutputDetail = new InventoryOutputDetailTable();
            $mProductInventory = new ProductInventoryTable();
            $mSmsConfig = new SmsConfigTable();
            $mReceiptDetail = new ReceiptDetailTable();
            $mServiceMaterial = new ServiceMaterialTable();
            $mProductBranchPrice = new ProductBranchPriceTable();
            $mPromotionLog = new PromotionLogTable();
            $mOrderLog = new OrderLogTable();
            $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

            $checkQuantityOrderDetail = $this->checkQuantityOrderDetail($input['order_id']);

            if ($checkQuantityOrderDetail['error'] == false) {
                return response()->json([
                    'error' => $checkQuantityOrderDetail['error'],
                    'message' => $checkQuantityOrderDetail['message']
                ]);
            }

            if ($input['receipt_info_check'] == 1 && !isset($input['delivery_type'])) {
                return response()->json([
                    'error' => false,
                    'message' => __('Vui lòng chọn hình thức nhận hàng')
                ]);
            }

            //Lấy thông tin nhân viên
            $staff = $mStaff->getItem(Auth()->id());

            //Lấy số lẻ decimal
            $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
            //Lấy thông tin đơn hàng
            $infoOrder = $this->order->getItemDetail($input['order_id']);

            $data_order = [
                'total' => $input['total_bill'],
                'discount' => $input['discount_bill'],
                'voucher_code' => $input['voucher_bill'],
                'amount' => str_replace(',', '', $input['amount_bill']),
                'process_status' => 'paysuccess',
                'discount_member' => $input['discount_member'],
                'tranport_charge' => isset($input['tranport_charge']) ? str_replace(',', '', $input['tranport_charge']) : '',
                'cashier_by' => Auth()->id(),
                'cashier_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_by' => Auth()->id(),
            ];
            //Chỉnh sửa đơn hàng
            $this->order->edit($data_order, $input['order_id']);

            $list_card_print = [];
            $arrObjectBuy = [];
            $arrRemindUse = [];

            // remove all detail => add again
            $mOrderDetail->remove($input['order_id']);

            //Xử lý table
            if (count($input['table_edit']) > 0) {
                foreach ($input['table_edit'] as $value) {
                    $isChangePrice = $value['is_change_price'] ?? 0;
                    $isCheckPromotion = $value['is_check_promotion'] ?? 0;
                    $position = $value['number_row'] ?? 0;
                    $staffId = isset($value['staff_id']) && $value['staff_id'] != null ? implode(',', $value['staff_id']) : null;

                    if (in_array($value['object_type'], ['product', 'service', 'service_card'])) {
                        if ($isCheckPromotion == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['object_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $input['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $input['order_id'],
                                'order_code' => $input['order_code']
                            ];
                        }
                        //Lấy array nhắc sử dụng lại
                        $arrRemindUse[] = [
                            'object_type' => $value['object_type'],
                            'object_id' => $value['object_id'],
                            'object_code' => $value['object_code'],
                            'object_name' => $value['object_name'],
                        ];
                    }

                    $data_order_detail = [
                        'order_id' => $input['order_id'],
                        'object_id' => $value['object_id'],
                        'object_name' => $value['object_name'],
                        'object_type' => $value['object_type'],
                        'object_code' => $value['object_code'],
                        'price' => $value['price'],
                        'quantity' => $value['quantity'],
                        'discount' => $value['discount'],
                        'voucher_code' => $value['voucher_code'],
                        'amount' => $value['amount'],
                        'refer_id' => $input['refer_id'],
                        'staff_id' => $staffId,
                        'is_change_price' => $isChangePrice,
                        'is_check_promotion' => $isCheckPromotion,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'note' => $value['note']
                    ];

                    if (isset($value['order_detail_id']) && $value['order_detail_id'] != null) {
                        $data_order_detail['order_detail_id'] = $value['order_detail_id'];
                    }

                    $id_detail = $mOrderDetail->add($data_order_detail);

                    switch ($value['object_type']) {
                        case 'service':
                            //Lấy thông tin dịch vụ
                            $check_commission = $mService->getItem($value['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], $check_commission, $id_detail, $value['object_id'], null, $value['amount'], $value['staff_id'] ?? null);
                            break;
                        case 'product':
                            //Lấy thông tin sản phẩm
                            $check_commission = $mProductChild->getItem($value['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], $check_commission, $id_detail, $value['object_id'], null, $value['amount'], $value['staff_id'] ?? null);
                            break;
                        case 'service_card':
                            //Lấy thông tin thẻ dịch vụ
                            $sv_card = $mServiceCard->getServiceCardOrder($value['object_code']);
                            //Lấy hoa hồng thẻ dịch vụ
                            $check_commission = $mServiceCard->getServiceCardInfo($value['object_id']);
                            $check_commission['staff_commission_value'] = $check_commission['staff_commission_value'] != null ? $check_commission['staff_commission_value'] : 0;

                            $refer_money = 0;
                            $staff_money = 0;
                            $staffCardCommission = 0;
                            if ($check_commission['type_refer_commission'] == 'money') {
                                if (isset($check_commission['refer_commission_value'])) {
                                    $refer_money = ($check_commission['refer_commission_value']);
                                }
                            } else {
                                if (isset($check_commission['refer_commission_value'])) {
                                    $refer_money = ($value['amount'] / 100) * ($check_commission['refer_commission_value']);
                                }
                            }
                            if ($check_commission['type_staff_commission'] == 'money') {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffCardCommission = round($check_commission['staff_commission_value'], $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            } else {
                                if (isset($check_commission['staff_commission_value'])) {
                                    $staffMoney = ($value['amount'] / 100) * ($check_commission['staff_commission_value']);
                                    $staffCardCommission = round($staffMoney, $decimal, PHP_ROUND_HALF_DOWN);
                                }
                            }

                            $arr_result = [];
                            for ($i = 0; $i < $value['quantity']; $i++) {
                                $code = $mCode->generateCardListCode();
                                while (array_search($code, $arr_result)) {
                                    $code = $mCode->generateCardListCode();
                                }
                                $data_card_list = [
                                    'service_card_id' => $value['object_id'],
                                    'is_actived' => 0,
                                    'created_by' => Auth::id(),
                                    'branch_id' => Auth()->user()->branch_id,
                                    'order_code' => $input['order_code'],
                                    'code' => $code,
                                    'price' => $value['price'],
                                    'refer_commission' => $refer_money,
                                    'staff_commission' => $staffCardCommission
                                ];
                                if ($input['customer_id'] != 1 && $input['check_active'] == 1) {
                                    $data_card_list['is_actived'] = $input['check_active'];
                                    $data_card_list['actived_at'] = date("Y-m-d H:i");

                                    $data_cus_card = [
                                        'customer_id' => $input['customer_id'],
                                        'service_card_id' => $value['object_id'],
                                        'number_using' => $sv_card['number_using'],
                                        'count_using' => $sv_card['service_card_type'] == 'money' ? 1 : 0,
                                        'money' => $sv_card['money'],
                                        'actived_date' => date("Y-m-d"),
                                        'is_actived' => 1,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
                                        'branch_id' => Auth()->user()->branch_id,
                                        'card_code' => $code
                                    ];
                                    if ($sv_card['date_using'] != 0) {
                                        $data_cus_card['expired_date'] = strftime("%Y-%m-%d", strtotime(date("Y-m-d", strtotime(date("Y-m-d") . '+ ' . $sv_card['date_using'] . 'days'))));
                                    }
                                    if ($sv_card['service_card_type'] == 'money') {
                                        //Lấy thông tin khách hàng
                                        $customer = $mCustomer->getItem($input['customer_id']);
                                        //Cập nhật lại tiền KH
                                        $mCustomer->edit([
                                            'account_money' => $customer['account_money'] + $sv_card['money']
                                        ], $input['customer_id']);
                                        //Lưu log + tiền
                                        $mBranchMoneyLog->add([
                                            "customer_id" => $input['customer_id'],
                                            "branch_id" => Auth()->user()->branch_id,
                                            "source" => "member_money",
                                            "type" => 'plus',
                                            "money" => $sv_card['money'],
                                            "screen" => 'active_card',
                                            "screen_object_code" => $code
                                        ]);
                                    }
                                    //Thêm vào customer service card thẻ đã active
                                    $id_cus_card = $mCustomerServiceCard->add($data_cus_card);
                                    //Thêm vào service card list thẻ đã active
                                    $id_card_list = $mCustomerServiceCard->add($data_card_list);

                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                } else {
                                    $id_card_list = $mCustomerServiceCard->add($data_card_list);
                                    array_push($list_card_print, $code);
                                    $arr_result[] = $code;
                                }
                            }

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], $check_commission, $id_detail, $value['object_id'], null, $value['amount'], $value['staff_id'] ?? null);

                            break;
                        case 'member_card':
                            //Lấy thông tin thẻ liệu trình của KH
                            $list_cus_card = $mCustomerServiceCard->getItemCard($value['object_id']);

                            $data_edit_card = [
                                'count_using' => $list_cus_card['count_using'] + $value['quantity']
                            ];
                            //Cập nhật lại số lần sử dụng thẻ
                            $mCustomerServiceCard->editByCode($data_edit_card, $value['object_code']);

                            // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                            $this->orderRepo->calculatedCommission($value['quantity'], $input['refer_id'], null, $id_detail, $value['object_id'], $value['object_code'], $value['amount'], $value['staff_id'] ?? null, 0, 0, "member_card");

                            DB::commit();

                            //Insert email log
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_EMAIL_CUSTOMER,
                                'event' => 'is_event',
                                'key' => 'service_card_over_number_used',
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Insert sms log
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_SMS_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Lưu log ZNS
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_ZNS_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'customer_id' => $input['customer_id'],
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            //Send notification
                            App\Jobs\FunctionSendNotify::dispatch([
                                'type' => SEND_NOTIFY_CUSTOMER,
                                'key' => 'service_card_over_number_used',
                                'customer_id' => $input['customer_id'],
                                'object_id' => $value['object_id'],
                                'tenant_id' => session()->get('idTenant')
                            ]);
                            break;
                    }

                    if ($value['voucher_code'] != null) {
                        //Lấy thông tin voucher
                        $get = $mVoucher->getCodeItem($value['voucher_code']);
                        //Cập nhật số lần sử dụng voucher
                        $mVoucher->editVoucherOrder([
                            'total_use' => ($get['total_use'] + 1)
                        ], $value['voucher_code']);
                    }
                }
            }

            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $this->subtractQuotaUsePromotion($input['order_id']);
            //Remove promotion log
            $mPromotionLog->removeByOrder($input['order_id']);

            if (!isset($input['custom_price']) || $input['custom_price'] == 0) {
                //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
                //Insert promotion log
                $arrPromotionLog = $getPromotionLog['promotion_log'];
                $mPromotionLog->insert($arrPromotionLog);
                //Cộng quota_use promotion quà tặng
                $arrQuota = $getPromotionLog['promotion_quota'];
                $this->plusQuotaUsePromotion($arrQuota);
            }

            if ($input['voucher_bill'] != null) {
                $get = $mVoucher->getCodeItem($input['voucher_bill']);
                $data = [
                    'total_use' => ($get['total_use'] + 1)
                ];
                $mVoucher->editVoucherOrder($data, $input['voucher_bill']);
            }

            //Lấy phương thức thanh toán
            $arrMethodWithMoney = $input['array_method'];

            $amount_bill = str_replace(',', '', $input['amount_bill']);
            $amount_receipt = str_replace(',', '', $input['amount_receipt']);

            $amount_return = str_replace(',', '', $input['amount_return']);
            if ($input['amount_all'] != '') {
                $amount_receipt_all = 0;

                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($money > 0) {
                        $amount_receipt_all += $money;
                    }
                }
            } else {
                $amount_receipt_all = 0;
            }
            $receipt_type = $input['receipt_type'];
            $status = '';
            if ($amount_receipt_all >= $amount_receipt) {
                $status = 'paid';
            } else {
                //Cập nhật trạng thái đơn hàng thanh toán còn thiếu
                $this->order->edit(['process_status' => 'pay-half'], $input['order_id']);
            }

            if ($amount_receipt != 0) {
                if ($amount_receipt_all < $amount_receipt) {
                    //Check KH là hội viên
                    if ($input['customer_id'] != 1) {
                        //Check cấu hình thanh toán nhiều lần
                        $check_info = $mSpaInfo->getInfoSpa();
                        if ($check_info['is_part_paid'] == 1) {
                            $status = 'paid';
                            //Cho thanh toán thiếu nhưng nếu tạo từ app thì ko insert vào công nợ
                            if (isset($input['order_source_id']) && $input['order_source_id'] != 2) {
                                //insert customer debt
                                $data_debt = [
                                    'customer_id' => $input['customer_id'],
                                    'debt_code' => 'debt',
                                    'staff_id' => Auth()->id(),
                                    'note' => $input['note'],
                                    'debt_type' => 'order',
                                    'order_id' => $input['order_id'],
                                    'status' => 'unpaid',
                                    'amount' => $amount_receipt - $amount_receipt_all,
                                    'created_by' => Auth()->id(),
                                    'updated_by' => Auth()->id()
                                ];
                                $debt_id = $mCustomerDebt->add($data_debt);
                                //update debt code
                                $day_code = date('dmY');
                                if ($debt_id < 10) {
                                    $debt_id = '0' . $debt_id;
                                }
                                $debt_code = [
                                    'debt_code' => 'CN_' . $day_code . $debt_id
                                ];
                                $mCustomerDebt->edit($debt_code, $debt_id);
                            }
                        } else {
                            return response()->json([
                                'amount_detail_small' => 1,
                                'message' => __('Số tiền không hợp lệ')
                            ]);
                        }
                    } else {
                        return response()->json([
                            'amount_detail_small' => 1,
                            'message' => __('Số tiền không hợp lệ')
                        ]);
                    }
                }
            }

            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();
            // get receipt by order id => remove receipt and receipt detail
            $dataReceipt = $mReceipt->getItem($input['order_id']);
            // no receipt by order_id but $dataReceipt still have data (data with all null field) ?????
            if ($dataReceipt != null && isset($dataReceipt['receipt_id']) && $dataReceipt['receipt_id'] != null) {
                $mReceipt->removeReceipt($input['order_id']);
                $mReceiptDetail->removeReceiptDetail($dataReceipt['receipt_id']);
            }
            $data_receipt = [
                'customer_id' => $input['customer_id'],
                'staff_id' => Auth()->id(),
                'object_id' => $input['order_id'],
                'object_type' => 'order',
                'order_id' => $input['order_id'],
                'total_money' => $amount_receipt_all,
                'voucher_code' => $input['voucher_bill'],
                'status' => $status,
                'is_discount' => 1,
                'amount' => $amount_bill,
                'amount_paid' => $amount_receipt_all > $amount_bill ? $amount_bill : $amount_receipt_all,
                'amount_return' => $amount_receipt_all > $amount_bill ? $amount_receipt_all - $amount_bill : 0,
                'note' => $input['note'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => '', // order code
                'object_accounting_id' =>  $input['order_id'], // order id
            ];
            if ($input['voucher_bill'] != null) {
                $data_receipt['discount'] = $input['discount_bill'];
            } else {
                $data_receipt['custom_discount'] = $input['discount_bill'];
            }
            $id_receipt = $mReceipt->add($data_receipt);
            $day_code = date('dmY');
            $data_code = [
                'receipt_code' => 'TT_' . $day_code . $id_receipt
            ];
            $mReceipt->edit($data_code, $id_receipt);

            if (count($input['table_edit']) > 0) {
                foreach ($input['table_edit'] as $key => $value) {
                    if ($value['object_type'] == 'member_card') {
                        $data_receipt_detail = [
                            'receipt_id' => $id_receipt,
                            'cashier_id' => Auth::id(),
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $value['price'],
                            'amount' => 0,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id()
                        ];
                        $mReceiptDetail->add($data_receipt_detail);
                    }
                }
            }

            // Chi tiết thanh toán
            $mReceiptOnline = new ReceiptOnlineTable();
            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();

            $isNotifyMinAccount = 0;

            foreach ($arrMethodWithMoney as $methodCode => $money) {
                $itemMethod = $mPaymentMethod->getPaymentMethodByCode($methodCode);
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $id_receipt,
                        'cashier_id' => Auth::id(),
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amount_bill) { // trừ tiên thành viên
                            if ($money < $input['member_money']) {
                                //Lưu từng dòng thanh toán
                                $mReceiptDetail->add($dataReceiptDetail);
                                //Lấy thông tin KH
                                $customerMoney = $mCustomer->getItem($input['customer_id']);
                                //Cập nhật tiền KH
                                $mCustomer->edit([
                                    'account_money' => $customerMoney['account_money'] - $money
                                ], $input['customer_id']);

                                $mConfig = app()->get(ConfigTable::class);
                                //Lấy cấu hình số tiền tối thiểu
                                $configMinAccount = $mConfig->getInfoByKey('money_account_min')['value'];

                                if (($customerMoney['account_money'] - $money) <= $configMinAccount) {
                                    $isNotifyMinAccount = 1;
                                }
                                //Lưu log - tiền
                                $mBranchMoneyLog->add([
                                    "customer_id" => $input['customer_id'],
                                    "branch_id" => Auth()->id(),
                                    "source" => "member_money",
                                    "type" => 'subtract',
                                    "money" => $money,
                                    "screen" => 'order',
                                    "screen_object_code" => $infoOrder['order_code']
                                ]);
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $input['member_money']
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } elseif ($methodCode == 'VNPAY') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // update receipt_id of receipt online
                        $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                            'receipt_id' => $id_receipt,
                            'status' => 'success'
                        ], 'order', $input['order_id'], $methodCode);
                    } elseif ($methodCode == 'TRANSFER') {
                        $mReceiptDetail->add($dataReceiptDetail);
                        // get receipt_online of method/order
                        $dataReceiptOnline = $mReceiptOnline->getReceiptOnlineByTypeAndOrderId('order', $input['order_id'], $methodCode);
                        if ($dataReceiptOnline != null) {
                            // update status, receipt_id of receipt_online
                            $mReceiptOnline->updateReceiptOnlineByTypeAndOrderId([
                                'amount_paid' => $money,
                                'receipt_id' => $id_receipt,
                                'status' => 'success'
                            ], 'order', $input['order_id'], $methodCode);
                        } else {
                            // create status, receipt_id of receipt_online
                            $dataReceiptOnline = [
                                'receipt_id' => $id_receipt,
                                'object_type' => 'order',
                                'object_id' => $input['order_id'],
                                'object_code' => $infoOrder['order_code'],
                                'payment_method_code' => $methodCode,
                                'amount_paid' => $money,
                                'payment_time' => Carbon::now(),
                                'status' => 'success',
                                'performer_name' => $staff['name'],
                                'performer_phone' => $staff['phone1'],
                                'type' => $itemMethod['payment_method_type'],
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);
                        }
                    } else {
                        $mReceiptDetail->add($dataReceiptDetail);
                    }
                }
            }

            $data_print = [];
            if (count($list_card_print) > 0) {
                foreach ($list_card_print as $k => $v) {
                    $get_cus_card = $mServiceCardList->searchCard($v);
                    $get_sv_card = $mServiceCard->getServiceCardInfo($get_cus_card['service_card_id']);

                    $data_print[] = [
                        'customer_id' => $input['customer_id'],
                        'type' => $get_sv_card['service_card_type'],
                        'card_name' => $get_cus_card['card_name'],
                        'card_code' => $get_cus_card['code'],
                        'number_using' => $get_sv_card['number_using'],
                        'date_using' => $get_sv_card['date_using'],
                        'money' => $get_sv_card['money'],
                        'service_card_id' => $get_sv_card['service_card_id'],
                    ];
                }
            }

            $checkSendSms = $mSmsConfig->getItemByType('paysuccess');

            if (isset($input['order_source_id']) && $input['order_source_id'] == 2) {
                //Cập nhật trạng thái đơn hàng cần giao (nếu là đơn hàng tại quầy thì không active)
                $mOrder = new OrderTable();
                $getOrder = $mOrder->getOrderById($input['order_id']);
                if ($getOrder['receive_at_counter'] == 0) {
                    $mDelivery = new DeliveryTable();
                    $mDelivery->edit([
                        'is_actived' => 1
                    ], $input['order_id']);
                }
                //Insert order log đơn hàng đã xác nhận
                $checkConfirm = $mOrderLog->checkStatusLog($input['order_id'], 'confirmed');
                if ($checkConfirm == null) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $input['order_id'],
                            'created_type' => 'backend',
                            'status' => 'confirmed',
                            //                            'note' => __('Đã xác nhận đơn hàng'),
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đã xác nhận đơn hàng',
                            'note_en' => 'Order confirm'
                        ]
                    ]);
                }
                //Insert order log đơn hàng đang xử lý
                $checkPacking = $mOrderLog->checkStatusLog($input['order_id'], 'packing');

                if ($checkPacking == null) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $input['order_id'],
                            'created_type' => 'backend',
                            'status' => 'packing',
                            //                            'note' => __('Đang xử lý'),
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đang xử lý',
                            'note_en' => 'Processing'
                        ]
                    ]);
                }
            }
            //Xóa tất cả phiếu giao hàng  của đơn hàng
            $this->removeDeliveryHistory($input['order_id']);

            // Thêm phiếu bảo hành điện tử
            $customer = $mCustomer->getItem($input['customer_id']);
            if (isset($input['table_add']) && $input['table_add'] > 0 || isset($input['table_edit']) && $input['table_edit'] > 0) {
                $dataTableAdd = isset($input['table_add']) ? $input['table_add'] : null;
                $dataTableEdit = isset($input['table_edit']) ? $input['table_edit'] : null;

                $this->addWarrantyCard($customer['customer_code'], $input['order_id'], $input['order_code'], $dataTableAdd, $dataTableEdit);
            }

            $mOrder = app()->get(OrderRepositoryInterface::class);
            //Lưu log dự kiến nhắc sử dụng lại
            $mOrder->insertRemindUse($input['order_id'], $input['customer_id'], $arrRemindUse);

            $id_order = $input['order_id'];

            $rWarehouse = app()->get(WarehouseRepositoryInterface::class);
            $rInventoryOutput = app()->get(InventoryOutputRepositoryInterface::class);
            $rOrderDetail = app()->get(OrderDetailRepositoryInterface::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mInventoryOutputDetail = app()->get(InventoryOutputDetailTable::class);
            $rInventoryOutputDetail = app()->get(InventoryOutputDetailRepositoryInterface::class);

            $checkWarehouse = $rWarehouse->getWarehouseByBranch(Auth::user()->branch_id);

            $warehouseId = 0;

            foreach ($checkWarehouse as $item) {
                if ($item['is_retail'] == 1) {
                    $warehouseId = $item['warehouse_id'];
                }
            }

            //            Lấy danh sách sản phẩm để kiểm nếu có sản phẩm serial trạng thái là new
            $getListProductCheckStatus = $mOrderDetail->getListProductCheck($id_order);

            $dataInventoryOutput = [
                'warehouse_id' => $warehouseId,
                'po_code' => 'XK',
                'created_by' => Auth::user()->staff_id,
                'created_at' => date('Y-m-d H:i:s'),
                'status' => count($getListProductCheckStatus) != 0 ? 'new' : 'success',
                'note' => '',
                'type' => 'retail',
                'object_id' => $id_order
            ];

            $idInventoryOutput = $rInventoryOutput->add($dataInventoryOutput);

            $idCode = $idInventoryOutput;
            if ($idInventoryOutput < 10) {
                $idCode = '0' . $idCode;
            }

            $rInventoryOutput->edit(['po_code' => $this->codeDMY('XK', $idCode)], $idInventoryOutput);

            //            Lấy danh sách sản phẩm
            $listOrderProduct = $rOrderDetail->getValueByOrderIdAndObjectType($id_order, 'product');

            foreach ($listOrderProduct as $item) {
                //                kiểm tra mã sản phẩm đã được tạo trong phiếu xuất kho hay chưa
                $checkProductInventotyOutput = $mInventoryOutputDetail->checkProductInventotyOutput($idInventoryOutput, $item['object_code']);

                $getDetailOutputDetail = $mInventoryOutputDetail->checkInventoryOutput($idInventoryOutput, $item['object_code']);

                $dataInventoryOutputDetail = [
                    'inventory_output_id' => $idInventoryOutput,
                    'product_code' => $item['object_code'],
                    'quantity' => $getDetailOutputDetail != null ? (int)$getDetailOutputDetail['quantity'] + (int)$item['quantity'] : $item['quantity'],
                    'current_price' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['current_price'] + (float)$item['price'] : $item['price'],
                    'total' => $getDetailOutputDetail != null ? (float)$getDetailOutputDetail['total'] + (float)$item['amount'] : $item['amount'],
                ];

                if ($getDetailOutputDetail != null) {
                    $idIOD = $getDetailOutputDetail['inventory_output_detail_id'];
                    $mInventoryOutputDetail->editDetail($idIOD, $dataInventoryOutputDetail);
                } else {
                    $idIOD = $rInventoryOutputDetail->add($dataInventoryOutputDetail);
                }
            }

            DB::commit();

            if ($input['customer_id'] != 1) {
                //Insert email log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_EMAIL_CUSTOMER,
                    'event' => 'is_event',
                    'key' => 'paysuccess',
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Insert sms log
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_SMS_CUSTOMER,
                    'key' => 'paysuccess',
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Gửi thông báo khách hàng
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_NOTIFY_CUSTOMER,
                    'key' => 'order_status_S',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu log ZNS (thanh toán thành công)
                App\Jobs\FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'order_thanks',
                    'customer_id' => $input['customer_id'],
                    'object_id' => $input['order_id'],
                    'tenant_id' => session()->get('idTenant')
                ]);

                if ($isNotifyMinAccount == 1) {
                    //Gửi thông báo tiền trong tài khoản sắp hết
                    App\Jobs\FunctionSendNotify::dispatch([
                        'type' => SEND_NOTIFY_CUSTOMER,
                        'key' => 'money_account_min',
                        'customer_id' => $input['customer_id'],
                        'object_id' => $input['order_id'],
                        'tenant_id' => session()->get('idTenant')
                    ]);
                }
            }
            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();
            if ($amount_receipt_all >= $amount_bill) {
                $mBookingApi->plusPointReceiptFull(['receipt_id' => $id_receipt]);
            } else {
                $mBookingApi->plusPointReceipt(['receipt_id' => $id_receipt]);
            }

            return response()->json([
                'error' => true,
                'message' => __('Thanh toán thành công'),
                'print_card' => $data_print,
                'orderId' => $input['order_id'],
                'isSMS' => $checkSendSms['is_active']
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * Render thẻ dịch vụ
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     * @throws \Throwable
     */
    public function renderCard($input)
    {
        $mStaff = new StaffsTable();
        $mBranch = new BranchTable();
        $mSpaInfo = new SpaInfoTable();
        $mConfigPrintCard = new ConfigPrintServiceCardTable();
        $mServiceCardList = new ServiceCardList();

        $staff = $mStaff->getItem(Auth()->id());
        $branch = $mBranch->getItem($staff['branch_id']);
        $spa_info = $mSpaInfo->getItem();
        $config_service_card = $mConfigPrintCard->getItem(1);
        $config_money_card = $mConfigPrintCard->getItem(2);

        $list_card = $input['list_card'];
        $data = [];
        foreach ($list_card as $item) {
            $check_card = $mServiceCardList->searchCard($item['card_code']);
            $data[] = [
                'card_code' => $check_card['code'],
                'card_name' => $check_card['card_name'],
                'card_type' => $check_card['service_card_type'],
                'number_using' => $check_card['number_using'],
                'date_using' => $check_card['date_using'],
                'is_actived' => $check_card['is_actived'],
                'actived_at' => $check_card['actived_at'],
                'money' => $check_card['money']
            ];
        }

        $view = view('admin::orders.modal-print-card', [
            'data_card' => $data,
            'branch' => $branch,
            'spa_info' => $spa_info[0],
            'config_service_card' => $config_service_card,
            'config_money_card' => $config_money_card
        ])->render();
        return response()->json($view);
    }

    /**
     * Cancel tất cả phiếu giao hàng của đơn hàng
     *
     * @param $orderId
     */
    public function removeDeliveryHistory($orderId)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mDeliveryHistoryLog = new DeliveryHistoryLogTable();

        //Kiểm tra trạng thái đơn hàng
        $infoOrder = $this->order->getItemDetail($orderId);

        if ($infoOrder['process_status']) {
            //Kiểm tra đơn hàng đó có phiếu giao hàng chưa
            $getDeliveryHistory = $mDeliveryHistory->getHistoryByOrder($orderId);
            if (count($getDeliveryHistory) > 0) {
                foreach ($getDeliveryHistory as $item) {
                    //Xóa phiếu giao hàng
                    $mDeliveryHistory->edit([
                        'status' => 'cancel'
                    ], $item['delivery_history_id']);
                    //Lưu log xóa phiếu giao hàng
                    $mDeliveryHistoryLog->add([
                        "delivery_history_id" => $item['delivery_history_id'],
                        "status" => "cancel",
                        "created_by" => Auth()->id(),
                        "created_type" => "backend"
                    ]);
                }
            }
        }
    }

    /**
     * Data view chi tiết đơn hàng
     *
     * @param $orderId
     * @return array|mixed
     */
    public function dataViewDetail($orderId)
    {
        $mOrderDetail = new OrderDetailTable();
        $mReceipt = new ReceiptTable();
        $mReceiptDetail = new ReceiptDetailTable();
        $mCustomerContact = new CustomerContactTable();
        $mStaffs = new StaffsTable();
        //Lấy thông tin đơn hàng
        $order = $this->order->getItemDetail($orderId);
        //Lấy thông tin chi tiết đơn hàng
        $list_table = $mOrderDetail->getItem($order['order_id']);
        $arr = [];

        $collectionDetail = collect($list_table->toArray());

        foreach ($list_table as $key => $item) {
            if ($item['order_detail_id_parent'] == null) {
                $staffName = "";
                if ($item['staff_id'] != null && $item['staff_id'] != "") {
                    $arrStaff = explode(",", $item['staff_id']);
                    if (count($arrStaff) > 0) {
                        foreach ($arrStaff as $value) {
                            $staffInfo = $this->staff->getItem($value);
                            if ($staffName == "") {
                                $staffName = $staffInfo['full_name'];
                            } else {
                                $staffName = $staffName . ', ' . $staffInfo['full_name'];
                            }
                        }
                    }
                }

                $arr[] = [
                    'order_detail_id' => $item['order_detail_id'],
                    'object_id' => $item['object_id'],
                    'object_name' => $item['object_name'],
                    'object_type' => $item['object_type'],
                    'object_code' => $item['object_code'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'discount' => $item['discount'],
                    'amount' => $item['amount'],
                    'voucher_code' => $item['voucher_code'],
                    'object_image' => $item['object_image'],
                    'full_name' => $staffName,
                    'note' => $item['note'],
                    'attach' => $collectionDetail->where('order_detail_id_parent', $item['order_detail_id'])->all(),
                ];
            }
        }

        $receipt = $mReceipt->getItem($orderId);
        // 1 order có nhiều receipt id
        $list_receipt_detail = $mReceiptDetail->getListDetailByOrderId($orderId);

        //Tiền đã thanh toán
        $amountPaid = 0;

        foreach ($list_receipt_detail as $v) {
            $amountPaid += $v['amount'];
        }

        $mOrderImage = app()->get(OrderImageTable::class);
        //Lấy hình ảnh trước/sau khi sử dụng
        $orderImage = $mOrderImage->getOrderImage($order['order_code']);
        //Lấy thông tin người nhận
        $infoContact = $mCustomerContact->getInfoContract($order['customer_contact_code']);

        $mContractMapOrder = app()->get(ContractMapOrderTable::class);
        $mConfig = app()->get(ConfigTable::class);

        //Cờ cho tạo hợp đồng không
        $getContractMap = $mContractMapOrder->getContractMapOrder($order['order_code']);
        //Lấy cấu hình có module hợp đồng chưa
        $configContract = $mConfig->getInfoByKey('contract')['value'];

        $isCreateContract = 0;

        if ($getContractMap == null && $configContract == 1) {
            $isCreateContract = 1;
        }

        $mCustomerContact = app()->get(CustomerContactTable::class);

        $detailAddress = $mCustomerContact->getDetailByCode($order['customer_contact_code']);

        //Lấy lịch sử thanh toán của đơn hàng
        $receiptOrder = $mReceipt->getReceiptByOrder($order['order_id']);

        return [
            'order' => $order,
            'oder_detail' => $arr,
            'receipt' => $receipt,
            'receipt_detail' => $list_receipt_detail,
            //              'amountReceipt' => $amountReceipt
            'amountReceipt' => $receipt['amount_paid'],
            'orderImage' => $orderImage,
            'infoContact' => $infoContact,
            'isCreateContract' => $isCreateContract,
            'detailAddress' => $detailAddress,
            'receiptOrder' => $receiptOrder,
            'amountPaid' => $amountPaid
        ];
    }

    public function getListContactByIdCus($idCustomer)
    {
        $mCustomerContact = new CustomerContactTable();
        $customerContact = $mCustomerContact->getList([
            'customer_id' => $idCustomer
        ]);

        // if count($data) = 0 -> insert address from table customer to customer_contact
        if (count($customerContact) <= 0) {
            $mCustomer = new CustomerTable();
            $getCustomerDetail = $mCustomer->getItem($idCustomer);
            // insert to customer_contact
            $data = [
                'customer_id' => $idCustomer,
                'province_id' => $getCustomerDetail['province_id'],
                'district_id' => $getCustomerDetail['district_id'],
                'postcode' => $getCustomerDetail['postcode'],
                'address_default' => 1,
                'contact_name' => $getCustomerDetail['full_name'],
                'full_address' => $getCustomerDetail['address'],
                'contact_phone' => $getCustomerDetail['phone1'],
                'contact_email' => $getCustomerDetail['email'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $insert = $mCustomerContact->add($data);
            // generate customer contact code
            $date_code = date('dmY');
            $contact_code = [
                'customer_contact_code' => 'CC_' . $date_code . $insert
            ];
            // update customer contact code
            $mCustomerContact->edit($contact_code, $insert);
            // get contact from table customer_contact
            $customerContact = $mCustomerContact->getList([
                'customer_id' => $idCustomer
            ]);
        }
        return $customerContact;
    }

    public function getDetailContact($idCusContact)
    {
        $mCustomerContact = new CustomerContactTable();
        return $detail = $mCustomerContact->getDetailContact($idCusContact);
    }

    public function addContact($data)
    {
        try {
            $mCustomerContact = new CustomerContactTable();
            //insert table customer contact
            $insert = [
                'customer_id' => $data['customer_id'],
                'province_id' => $data['province_id'],
                'district_id' => $data['district_id'],
                'postcode' => $data['postcode'],
                'full_address' => $data['full_address'],
                'address_default' => 0,
                'contact_name' => isset($data['contact_name']) ? $data['contact_name'] : '',
                'contact_phone' => isset($data['contact_phone']) ? $data['contact_phone'] : '',
                'contact_email' => isset($data['contact_email']) ? $data['contact_email'] : '',
            ];
            $insertContact = $mCustomerContact->add($insert);
            // generate customer contact code
            $date_code = date('dmY');
            $contact_code = [
                'customer_contact_code' => 'CC_' . $date_code . $insertContact
            ];
            // update customer contact code
            $mCustomerContact->edit($contact_code, $insertContact);

            return response()->json([
                'error' => false,
                'success' => 1
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
            ]);
        }
    }

    public function editContact($data)
    {
        try {
            $mCustomerContact = new CustomerContactTable();
            //            $idCus = $data['customer_id'];
            $idCusContact = $data['customer_contact_id'];

            //update table customer contact
            $update = [
                'province_id' => $data['province_id'],
                'district_id' => $data['district_id'],
                'postcode' => $data['postcode'],
                'full_address' => $data['full_address'],
                //                'address_default' => 0,
                'contact_name' => isset($data['contact_name']) ? $data['contact_name'] : '',
                'contact_phone' => isset($data['contact_phone']) ? $data['contact_phone'] : '',
                'contact_email' => isset($data['contact_email']) ? $data['contact_email'] : '',
            ];
            $updateContact = $mCustomerContact->edit($update, $idCusContact);

            return response()->json([
                'error' => true,
                'success' => 1
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => false,
            ]);
        }
    }

    public function removeContact($idContact)
    {
        DB::beginTransaction();
        try {
            $mCustomerContact = new CustomerContactTable();
            $removeContact = $mCustomerContact->remove($idContact);
            DB::commit();

            return response()->json([
                'error' => true,
                'success' => 1
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
            ]);
        }
    }

    public function setDefaultContact($idContact, $idCustomer)
    {
        DB::beginTransaction();
        try {
            $mCustomerContact = new CustomerContactTable();
            $setDefault = $mCustomerContact->setDefault($idCustomer, $idContact);
            DB::commit();

            return response()->json([
                'error' => true,
                'success' => 1
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
            ]);
        }
    }

    public function listCustomerContact($filter = [])
    {
        $customerId = $filter['customer_id'];
        $mCustomerContact = new CustomerContactTable();
        $customerContact = $mCustomerContact->getList($filter);

        return view('admin::orders.list-contact', [
            'listContact' => $customerContact,
            'customer_id' => $customerId
        ]);
    }

    public function getContactDefault($idCus)
    {
        $mCustomerContact = new CustomerContactTable();
        return $detail = $mCustomerContact->getContactDefault($idCus);
    }

    public function syncOrder($input)
    {
        try {
            $date = Carbon::now()->subHours($input['number_time'])->format('Y-m-d H:i:s');

            //Call api đồng bộ
            $client = new \GuzzleHttp\Client();

            $response = $client->request('POST', 'http://165.22.101.254/api/sale/trigger-scheduler', ['query' => [
                'start_date' => $date,
            ]]);

            $jsonResponse = json_decode($response->getBody(), true);

            return [
                'error' => false,
                'message' => __('Đồng bộ thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Đồng bộ thất bại')
            ];
        }
    }

    /**
     * Cộng quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $arrPromotionSubtract
     */
    public function plusQuotaUsePromotion($arrPromotionSubtract)
    {
        $mPromotionMaster = app()->get(PromotionMasterTable::class);

        if (count($arrPromotionSubtract) > 0) {
            foreach ($arrPromotionSubtract as $v) {

                $infoMaster = $mPromotionMaster->getInfo($v['promotion_code']);

                //Cập nhật quota_use của promotion
                $mPromotionMaster->edit([
                    'quota_use' => $infoMaster['quota_use'] + $v['quantity_gift']
                ], $v['promotion_code']);
            }
        }
    }

    /**
     * Trừ quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $orderId
     * @return mixed|void
     */
    public function subtractQuotaUsePromotion($orderId)
    {
        $mPromotionLog = app()->get(PromotionLogTable::class);
        $mPromotionMaster = app()->get(PromotionMasterTable::class);

        $getQuotaPromotion = $mPromotionLog->getQuotaPromotion($orderId);

        if (count($getQuotaPromotion) > 0) {
            foreach ($getQuotaPromotion as $v) {
                $infoMaster = $mPromotionMaster->getInfo($v['promotion_code']);

                //Cập nhật quota_use của promotion
                $mPromotionMaster->edit([
                    'quota_use' => $infoMaster['quota_use'] - $v['quantity_gift']
                ], $v['promotion_code']);
            }
        }
    }

    /**
     * Group số lượng mua của các object, lấy ra CTKM áp dụng cho đơn hàng
     *
     * @param $arrObjectBuy
     * @return mixed|void
     */
    public function groupQuantityObjectBuy($arrObjectBuy)
    {
        $promotionLog = [];
        $arrQuota = [];

        $arrBuy = [];

        //Group số lượng mua của những sp trùng nhau
        if (count($arrObjectBuy) > 0) {
            foreach ($arrObjectBuy as $v) {
                $objectCode = $v['object_code'];
                if (!array_key_exists($objectCode, $arrBuy)) {
                    $arrBuy[$objectCode] = $v;
                } else {
                    $arrBuy[$objectCode]['quantity'] = $arrBuy[$objectCode]['quantity'] + $v['quantity'];
                }
            }
        }


        if (count($arrBuy) > 0) {
            foreach ($arrBuy as $v) {
                //Lấy thông tin CTKM áp dụng cho đơn hàng
                $getLog = $this->getPromotionLog(
                    $v['object_type'],
                    $v['object_code'],
                    $v['price'],
                    $v['quantity'],
                    $v['customer_id'],
                    $v['order_source'],
                    $v['object_id'],
                    $v['order_id'],
                    $v['order_code']
                );

                foreach ($getLog['promotion_log'] as $vLog) {
                    $promotionLog[] = $vLog;
                }

                if (count($getLog['promotion_quota']) > 0) {
                    $arrQuota[] = $getLog['promotion_quota'];
                }
            }
        }

        return [
            'promotion_log' => $promotionLog,
            'promotion_quota' => $arrQuota
        ];
    }

    /**
     * Lấy thông tin CTKM khi mua hàng
     *
     * @param $objectType
     * @param $objectCode
     * @param $price
     * @param $quantity
     * @param $customerId
     * @param $orderSource
     * @param $objectId
     * @param $orderId
     * @param $orderCode
     * @return array|null
     */
    public function getPromotionLog($objectType, $objectCode, $price, $quantity, $customerId, $orderSource, $objectId, $orderId, $orderCode)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $currentTime = Carbon::now()->format('H:i');

        //Lấy chi tiết CTKM
        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, null, $currentDate);

        $promotionLog = [];
        $promotionQuota = [];
        $promotionPrice = [];
        $result = [];
        $resultPlusQuota = [];

        if (count($getDetail) > 0) {
            foreach ($getDetail as $v) {
                //Check thời gian diễn ra chương trình
                if ($currentDate < $v['start_date'] || $currentDate > $v['end_date']) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check chi nhánh áp dụng
                if (
                    $v['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $v['branch_apply']))
                ) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check KM theo time đặc biệt
                if ($v['is_time_campaign'] == 1) {
                    switch ($v['time_type']) {
                        case 'D':
                            $daily = $mDaily->getDailyByPromotion($v['promotion_code']);

                            if ($daily != null) {
                                $startTime = Carbon::createFromFormat('H:i:s', $daily['start_time'])->format('H:i');
                                $endTime = Carbon::createFromFormat('H:i:s', $daily['end_time'])->format('H:i');
                                //Kiểm tra giờ bắt đầu, giờ kết thúc
                                if ($currentTime < $startTime || $currentTime > $endTime) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                        case 'W':
                            $weekly = $mWeekly->getWeeklyByPromotion($v['promotion_code']);
                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['default_start_time'])->format('H:i');
                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['default_end_time'])->format('H:i');

                            switch (Carbon::createFromFormat('Y-m-d H:i:s', $currentDate)->format('l')) {
                                case 'Monday':
                                    if ($weekly['is_monday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Tuesday':
                                    if ($weekly['is_tuesday'] == 1) {
                                        if ($weekly['is_other_tuesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Wednesday':
                                    if ($weekly['is_wednesday'] == 1) {
                                        if ($weekly['is_other_wednesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Thursday':
                                    if ($weekly['is_thursday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Friday':
                                    if ($weekly['is_friday'] == 1) {
                                        if ($weekly['is_other_friday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Saturday':
                                    if ($weekly['is_saturday'] == 1) {
                                        if ($weekly['is_other_saturday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Sunday':
                                    if ($weekly['is_sunday'] == 1) {
                                        if ($weekly['is_other_sunday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                            }
                            //Kiểm tra giờ bắt đầu, giờ kết thúc
                            if ($currentTime < $startTime || $currentTime > $endTime) {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'M':
                            $monthly = $mMonthly->getMonthlyByPromotion($v['promotion_code']);

                            if (count($monthly) > 0) {
                                $next = false;

                                foreach ($monthly as $v1) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['start_time'])->format('Y-m-d H:i');
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['end_time'])->format('Y-m-d H:i');

                                    if ($currentDate > $startDate && $currentDate < $endDate) {
                                        $next = true;
                                    }
                                }

                                if ($next == false) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            } else {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'R':
                            $fromTo = $mFromTo->getDateTimeByPromotion($v['promotion_code']);

                            if ($fromTo != null) {
                                $startFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['form_date'] . ' ' . $fromTo['start_time'])->format('Y-m-d H:i');
                                $endFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['to_date'] . ' ' . $fromTo['end_time'])->format('Y-m-d H:i');

                                if ($currentDate < $startFrom || $currentDate > $endFrom) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                    }
                }

                //Check nguồn đơn hàng
                if ($v['order_source'] != 'all' && $v['order_source'] != $orderSource) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check đối tượng áp dụng
                if ($v['promotion_apply_to'] != 1 && $v['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc vòng for
                        continue;
                    }

                    if ($getCustomer['member_level_id'] == null) {
                        $getCustomer['member_level_id'] = 1;
                    }

                    $objectId = '';
                    if ($v['promotion_apply_to'] == 2) {
                        $objectId = $getCustomer['member_level_id'];
                    } else if ($v['promotion_apply_to'] == 3) {
                        $objectId = $getCustomer['customer_group_id'];
                    } else if ($v['promotion_apply_to'] == 4) {
                        $objectId = $v['customer_id'];
                    }

                    $getApply = $mPromotionApply->getApplyByObjectId($v['promotion_code'], $objectId);

                    if ($getApply == null) {
                        //Kết thúc vòng for
                        continue;
                    }
                }

                if ($v['promotion_type'] == 1) {
                    //Khuyến mãi giảm giá
                    $promotionPrice[] = $v;
                } else if ($v['promotion_type'] == 2) {
                    if ($quantity >= $v['quantity_buy']) {
                        $multiplication = intval($quantity / $v['quantity_buy']);
                        //Số quà được tặng
                        $totalGift = intval($v['quantity_gift'] * $multiplication);
                        //Lấy quota_use nếu tính áp dụng promotion này
                        $quotaUse = floatval($v['quota_use']) + $totalGift;
                        //Check số lượng cần mua để dc quà + quota_use
                        if ($v['quota'] == 0 || $v['quota'] == '' || $quotaUse <= floatval($v['quota'])) {
                            //Lấy giá trị quà tặng
                            $priceGift = $this->getPriceObject($v['gift_object_type'], $v['gift_object_code']);

                            $v['quantity_gift'] = $totalGift;
                            $v['quota'] = !empty($v['quota']) ? $v['quota'] : 0;
                            $v['quota_use'] = floatval($v['quota_use']);
                            $v['total_price_gift'] = $priceGift * $totalGift;

                            $promotionQuota[] = $v;
                        }
                    }
                }
            }
        }

        if (count($promotionPrice) > 0) {
            //Lấy CTKM có giá ưu đãi nhất
            $getPriceMostPreferential = $this->choosePriceMostPreferential($promotionPrice);

            $promotionLog[] = $getPriceMostPreferential;
        }

        if (count($promotionQuota) > 0) {
            //Lấy CTKM có quà tặng ưu đãi nhất
            $getGiftMostPreferential = $this->getGiftMostPreferential($promotionQuota);
            $promotionLog[] = $getGiftMostPreferential;
        }

        foreach ($promotionLog as $v) {
            $result[] = [
                'promotion_id' => $v['promotion_id'],
                'promotion_code' => $v['promotion_code'],
                'start_date' => $v['start_date'],
                'end_date' => $v['end_date'],
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'object_type' => $objectType,
                'object_id' => $objectId,
                'object_code' => $objectCode,
                'quantity' => $quantity,
                'base_price' => $v['base_price'],
                'promotion_price' => $v['promotion_price'],
                'gift_object_type' => $v['gift_object_type'],
                'gift_object_id' => $v['gift_object_id'],
                'gift_object_code' => $v['gift_object_code'],
                'quantity_gift' => $v['quantity_gift'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            if ($v['promotion_type'] == 2) {
                $resultPlusQuota = [
                    'promotion_code' => $v['promotion_code'],
                    'quantity_gift' => $v['quantity_gift']
                ];
            }
        }

        return [
            'promotion_log' => $result,
            'promotion_quota' => $resultPlusQuota
        ];
    }

    /**
     * Lấy giá trị khuyến mãi sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return int
     */
    private function getPriceObject($objectType, $objectCode)
    {
        $price = 0;

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(ProductChildTable::class);
                //Lấy thông tin sp khuyến mãi
                $getProduct = $mProduct->getProductPromotion($objectCode);
                $price = $getProduct['new_price'];

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dv khuyến mãi
                $getService = $mService->getServicePromotion($objectCode);
                $price = $getService['new_price'];

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCard::class);
                //Lấy thông tin thẻ dv khuyến mãi
                $getServiceCard = $mServiceCard->getServiceCardPromotion($objectCode);
                $price = $getServiceCard['new_price'];

                break;
        }

        return floatval($price);
    }

    /**
     * Chọn CTKM giảm giá ưu đãi nhất
     *
     * @param $arrPrice
     * @return array
     */
    private function choosePriceMostPreferential($arrPrice)
    {
        //Lấy giá trị quà tặng có giá trị cao nhất
        $minPrice = array_column($arrPrice, 'promotion_price');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($minPrice, SORT_ASC, $arrPrice);
        //Lấy CTKM có giá ưu đãi nhất
        return [
            'promotion_id' => $arrPrice[0]['promotion_id'],
            'promotion_code' => $arrPrice[0]['promotion_code'],
            'promotion_type' => $arrPrice[0]['promotion_type'],
            'start_date' => $arrPrice[0]['start_date'],
            'end_date' => $arrPrice[0]['end_date'],
            'base_price' => $arrPrice[0]['base_price'],
            'promotion_price' => $arrPrice[0]['promotion_price'],
            'gift_object_type' => $arrPrice[0]['gift_object_type'],
            'gift_object_id' => $arrPrice[0]['gift_object_id'],
            'gift_object_code' => $arrPrice[0]['gift_object_code'],
            'quantity_gift' => $arrPrice[0]['quantity_gift'],
        ];
    }

    /**
     * Lấy quà tặng ưu đãi nhất
     *
     * @param $arrGift
     * @return array
     */
    private function getGiftMostPreferential($arrGift)
    {
        $result = [];

        if (count($arrGift) == 1) {
            //Có 1 CTKM quà tặng thì lấy chính nó
            $result[] = [
                'promotion_type' => $arrGift[0]['promotion_type'],
                'promotion_id' => $arrGift[0]['promotion_id'],
                'promotion_code' => $arrGift[0]['promotion_code'],
                'start_date' => $arrGift[0]['start_date'],
                'end_date' => $arrGift[0]['end_date'],
                'base_price' => $arrGift[0]['base_price'],
                'promotion_price' => $arrGift[0]['promotion_price'],
                'gift_object_type' => $arrGift[0]['gift_object_type'],
                'gift_object_id' => $arrGift[0]['gift_object_id'],
                'gift_object_code' => $arrGift[0]['gift_object_code'],
                'quantity_gift' => $arrGift[0]['quantity_gift'],
            ];
        } else if (count($arrGift) > 1) {
            //Có nhiều CTKM quà tặng
            //Lấy quà tặng có giá trị cao nhất
            $giftPreferential = $this->chooseGiftPreferential($arrGift);

            $result = $giftPreferential;

            if (count($result) > 1) {
                //Lấy quà tặng có số lượng mua thấp nhất
                $giftMinBuy = $this->chooseGiftMinBuy($result);

                $result = $giftMinBuy;
            }

            if (count($result) > 1) {
                //Lấy quà tặng có quota - quota_use còn nhiều nhất (ưu tiên quota != 0 ko giới hạn)
                $giftQuota = $this->chooseGiftQuota($result);

                $result = $giftQuota;
            }
        }

        return $result[0];
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftPreferential($arrGift)
    {
        $result = [];
        //Lấy giá trị quà tặng có giá trị cao nhất
        $giftPrice = array_column($arrGift, 'total_price_gift');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($giftPrice, SORT_DESC, $arrGift);

        $result[] = [
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['total_price_gift'] >= $result[0]['total_price_gift']) {
                $result[] = [
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftMinBuy($arrGift)
    {
        //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
        $result = [];
        //Lấy quà tặng có số lượng mua thấp nhất
        $quantityBuy = array_column($arrGift, 'quantity_buy');
        //Sắp xếp lại array có số lượng cần mua thấp nhất
        array_multisort($quantityBuy, SORT_ASC, $arrGift);

        $result[] = [
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['quantity_buy'] == $result[0]['quantity_buy']) {
                $result[] = [
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited[] = $v;
            } else {
                $arrUnLimited[] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result[] = [
                'promotion_id' => $arrLimited[0]['promotion_id'],
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'promotion_type' => $arrLimited[0]['promotion_type'],
                'start_date' => $arrLimited[0]['start_date'],
                'end_date' => $arrLimited[0]['end_date'],
                'base_price' => $arrLimited[0]['base_price'],
                'promotion_price' => $arrLimited[0]['promotion_price'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_code' => $arrLimited[0]['gift_object_code'],
                'quantity_gift' => $arrLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrLimited[0]['quantity_buy'],
                'quota' => $arrLimited[0]['quota'],
                'quota_use' => $arrLimited[0]['quota_use'],
                'total_price_gift' => $arrLimited[0]['total_price_gift']
            ];

            unset($arrLimited[0]);

            foreach ($arrLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_balance'] == ($result[0]['quota'] - $result[0]['quota_use'])) {
                    $result[] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        if (count($result) == 0 && count($arrUnLimited) > 0) {
            //Lấy quà tặng có quota_use thấp nhất
            $quantityQuotaUse = array_column($arrUnLimited, 'quota_use');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuotaUse, SORT_ASC, $arrUnLimited);

            $result[] = [
                'promotion_id' => $arrUnLimited[0]['promotion_id'],
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'promotion_type' => $arrUnLimited[0]['promotion_type'],
                'start_date' => $arrUnLimited[0]['start_date'],
                'end_date' => $arrUnLimited[0]['end_date'],
                'base_price' => $arrUnLimited[0]['base_price'],
                'promotion_price' => $arrUnLimited[0]['promotion_price'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_code' => $arrUnLimited[0]['gift_object_code'],
                'quantity_gift' => $arrUnLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrUnLimited[0]['quantity_buy'],
                'quota' => $arrUnLimited[0]['quota'],
                'quota_use' => $arrUnLimited[0]['quota_use'],
                'total_price_gift' => $arrUnLimited[0]['total_price_gift']
            ];

            unset($arrUnLimited[0]);

            foreach ($arrUnLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_use'] <= $result[0]['quota_use']) {
                    $result[] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        //        if (count($result) > 1) {
        //            $result = $result[0];
        //        }

        return $result;
    }

    /**
     * Thêm phiếu bảo hành điện tử
     *
     * @param $customerCode
     * @param $orderId
     * @param $orderCode
     * @param $dataTableAdd
     * @param $dataTableEdit
     */
    protected function addWarrantyCard($customerCode, $orderId, $orderCode, $dataTableAdd, $dataTableEdit = null)
    {
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();

        // get array object
        if ($dataTableAdd != null) {
            $arrObject = array_chunk($dataTableAdd, 15, false);
            if (count($arrObject) > 0) {
                foreach ($arrObject as $item) {
                    // value item
                    $objectId = isset($item[0]) ? $item[0] : 0;
                    $objectType = isset($item[2]) ? $item[2] : null;
                    $objectCode = isset($item[3]) ? $item[3] : null;
                    $objectPrice = isset($item[4]) ? $item[4] : 0;
                    $objectQuantity = isset($item[5]) ? $item[5] : 1;
                    if ($objectType == 'product' || $objectType == 'service' || $objectType == 'service_card') {
                        // get object code -> get packed_code -> get info warranty package
                        $warrantyDetail = $mWarrantyDetail->getDetailByObjectCode($objectCode, $objectType);
                        if ($warrantyDetail != null) {
                            $warranty = $mWarranty->getInfoByCode($warrantyDetail['warranty_packed_code']);
                            $dataInsert = [
                                'customer_code' => $customerCode,
                                'warranty_packed_code' => $warrantyDetail['warranty_packed_code'],
                                'quota' => $warranty['quota'],
                                'warranty_percent' => $warranty['percent'],
                                'warranty_value' => $warranty['required_price'],
                                'status' => 'new',
                                'object_type' => $objectType,
                                'object_type_id' => $objectId,
                                'object_code' => $objectCode,
                                'object_price' => $objectPrice,
                                'created_by' => Auth::id(),
                                'order_code' => $orderCode,
                                'description' => $warranty['detail_description']
                            ];
                            if ($objectQuantity > 1) {
                                for ($i = 0; $i < $objectQuantity; $i++) {
                                    $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                    // card code
                                    $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                    $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                                }
                            } else {
                                $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                // card code
                                $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                            }
                        }
                    }
                }
            }
        }

        if ($dataTableEdit != null) {
            $arrObject = array_chunk($dataTableEdit, 16, false);
            if ($arrObject != null && count($arrObject) > 0) {
                foreach ($arrObject as $item) {
                    // value item
                    $objectId = isset($item[1]) ? $item[1] : 0;
                    $objectType = isset($item[3]) ? $item[3] : null;
                    $objectCode = isset($item[4]) ? $item[4] : null;
                    $objectPrice = isset($item[5]) ? $item[5] : 0;
                    $objectQuantity = isset($item[6]) ? $item[6] : 1;

                    if ($objectType == 'product' || $objectType == 'service' || $objectType == 'service_card') {
                        // get object code -> get packed_code -> get info warranty package
                        $warrantyDetail = $mWarrantyDetail->getDetailByObjectCode($objectCode, $objectType);
                        if ($warrantyDetail != null) {
                            $warranty = $mWarranty->getInfoByCode($warrantyDetail['warranty_packed_code']);
                            $dataInsert = [
                                'customer_code' => $customerCode,
                                'warranty_packed_code' => $warrantyDetail['warranty_packed_code'],
                                'quota' => $warranty['quota'],
                                'warranty_percent' => $warranty['percent'],
                                'warranty_value' => $warranty['required_price'],
                                'status' => 'new',
                                'object_type' => $objectType,
                                'object_type_id' => $objectId,
                                'object_code' => $objectCode,
                                'object_price' => $objectPrice,
                                'created_by' => Auth::id(),
                                'order_code' => $orderCode,
                                'description' => $warranty['detail_description']
                            ];
                            if ($objectQuantity > 1) {
                                for ($i = 0; $i < $objectQuantity; $i++) {
                                    $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                    // card code
                                    $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                    $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                                }
                            } else {
                                $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                // card code
                                $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Export danh sách đơn hàng
     * @param array $params
     * @return mixed
     */
    public function exportList($params = [])
    {
        $filters = [
            'orders$order_source_id' => 2,
        ];
        $list = $this->listAll($filters)['list'];
        //Data export
        $arr_data = [];
        foreach ($list as $key => $item) {
            $temp = 0;
            if (isset(config()->get('config.decimal_number')->value)) {
                $temp = config()->get('config.decimal_number')->value;
            }
            $amount = number_format($item['amount'], $temp);
            $amountPaid = number_format($item['amount_paid'], $temp);

            $status = __('Đã thanh toán');
            switch ($item['process_status']) {
                case self::PAY_HALF:
                    $status = __('Thanh toán còn thiếu');
                    break;
                case self::NEW:
                    $status = __('Mới');
                    break;
                case self::ORDER_CANCEL:
                    $status = __('Đã hủy');
                    break;
                case self::CONFIRMED:
                    $status = __('Đã xác nhận');
                    break;
                default:
                    $status = __('Đã thanh toán');
            }
            $note = $item['order_description'];
            if ($item['process_status'] == self::NEW) {
                $note = $item['order_description'];
            } elseif ($item['process_status'] == self::PAYSUCCESS) {
                $note = $item['note_receipt'];
            } elseif ($item['process_status'] == self::ORDER_CANCEL) {
                $note = $item['order_description'];
            }
            $arr_data[] = [
                $key + 1,
                $item['order_code'],
                $item['full_name_cus'] . (isset($item['group_name_cus']) ? ' - ' . $item['group_name_cus'] : ''),
                $item['full_name'],
                $amount,
                $amountPaid,
                $item['branch_name'],
                $status,
                $note,
                date("d/m/Y", strtotime($item['created_at']))
            ];
        }
        $heading = [
            __('STT'),
            __('MÃ ĐƠN HÀNG'),
            __('KHÁCH HÀNG'),
            __('NGƯỜI TẠO'),
            __('TỔNG TIỀN'),
            __('ĐÃ THANH TOÁN'),
            __('CHI NHÁNH'),
            __('TRẠNG THÁI'),
            __('GHI CHÚ'),
            __('NGÀY TẠO'),
        ];
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile($heading, $arr_data), 'order-app.xlsx');
    }

    /**
     * Danh sách đơn hàng từ app
     *
     * @param array $filters
     * @return array|mixed
     */
    public function listAll(array $filters = [])
    {
        $mOrderApp = new OrderAppTable();
        $mReceipt = new ReceiptTable();
        $list = $mOrderApp->getAll($filters);
        if (count($list->items()) > 0) {
            foreach ($list->items() as $v) {
                $receipt = $mReceipt->getReceiptOrder($v['order_id']);
                $v['amount_paid'] = $receipt != null ? $receipt['amount_paid'] : 0;
                $v['note_receipt'] = $receipt != null ? $receipt['note'] : null;
            }
        }
        return [
            'list' => $list
        ];
    }

    /**
     * thêm lịch hẹn nhanh
     *
     * @param $data
     * @param $customerId
     * @return array|false[]
     * @throws \MyCore\Api\ApiException
     */
    public function _addQuickAppointment($data, $customerId)
    {
        $mStaff = new StaffsTable();
        $repoSmsLog = app()->get(SmsLogRepositoryInterface::class);
        $repoOrder = app()->get(OrderRepositoryInterface::class);
        $mServiceBranchPrice = new ServiceBranchPriceTable();
        $now = Carbon::now();
        $date = Carbon::createFromFormat('d/m/Y', $data['date'])->format('Y-m-d');
        $time = $data['time'];

        $timeAppointment = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
        // kiểm tra lịch hẹn phải > hiện tại
        if ($timeAppointment->lessThanOrEqualTo($now)) {
            return [
                'error' => false,
                'message' => __('Ngày hẹn, giờ hẹn không hợp lệ')
            ];
        }
        $staff = $mStaff->getItem(Auth::id()); // Thông tin nhân viên
        $dataInsert = [
            'customer_id' => $customerId,
            'time' => $time,
            'date' => $date,
            'description' => 'Lich hen nhanh tu don hang',
            'customer_appointment_type' => 'appointment',
            'appointment_source_id' => 1, // Truc tiep
            'customer_quantity' => 1,
            'branch_id' => $staff['branch_id'],
            'status' => 'new',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'time_type' => 'R',
            'number_start' => 0,
        ];

        //Kiểm tra ngày giờ hẹn của user đã có chưa
        $mBooking = new CustomerAppointmentTable();
        $checkBooking = $mBooking->checkDateTimeCustomer($customerId, $date, $time, Auth::user()->branch_id, '');
        if ($checkBooking != null) {
            return [
                'error' => false,
                'message' => __('Bạn đã có lịch hẹn vào khung giờ này'),
            ];
        }
        // default: customer type: appointment (đặt lịch trước)
        $appointmentId = $mBooking->add($dataInsert);
        $appointmentCode = 'LH_' . date('dmY') . $appointmentId;
        $mBooking->edit([
            'customer_appointment_code' => $appointmentCode
        ], $appointmentId);
        //Insert email log
        App\Jobs\FunctionSendNotify::dispatch([
            'type' => SEND_EMAIL_CUSTOMER,
            'event' => 'is_event',
            'key' => 'new_appointment',
            'object_id' => $appointmentId,
            'tenant_id' => session()->get('idTenant')
        ]);
        //Insert sms log
        App\Jobs\FunctionSendNotify::dispatch([
            'type' => SEND_SMS_CUSTOMER,
            'key' => 'new_appointment',
            'object_id' => $appointmentId,
            'tenant_id' => session()->get('idTenant')
        ]);
        //Lưu log ZNS
        App\Jobs\FunctionSendNotify::dispatch([
            'type' => SEND_ZNS_CUSTOMER,
            'key' => 'new_appointment',
            'customer_id' => $customerId,
            'object_id' => $appointmentId,
            'tenant_id' => session()->get('idTenant')
        ]);
        //Insert notification log
        App\Jobs\FunctionSendNotify::dispatch([
            'type' => SEND_NOTIFY_CUSTOMER,
            'key' => 'appointment_W',
            'customer_id' => $customerId,
            'object_id' => $appointmentId,
            'tenant_id' => session()->get('idTenant')
        ]);
        //Gửi thông báo NV có LH mới
        App\Jobs\FunctionSendNotify::dispatch([
            'type' => SEND_NOTIFY_STAFF,
            'key' => 'appointment_W',
            'customer_id' => $customerId,
            'object_id' => $appointmentId,
            'branch_id' => Auth()->user()->branch_id,
            'tenant_id' => session()->get('idTenant')
        ]);

        $dataDetail = [];
        if (isset($data['table_quantity']) && $data['table_quantity'] != null) {
            $branchId = Auth()->user()->branch_id;
            // Nếu có data dịch vụ or thẻ liệu trình mới lưu
            $total = 0;
            foreach ($data['table_quantity'] as $key => $value) {
                if ($value['sv'] != null) {
                    //Nếu có data dịch vụ or thẻ liệu trình mới lưu
                    foreach ($value['sv'] as $k => $v) {
                        $price = 0;
                        if ($value['object_type'] == 'service') {
                            //Lấy giá chi nhánh sản phẩm
                            $priceBranch = $mServiceBranchPrice->getItemByBranchIdAndServiceId($branchId, $v);
                            // time_type = R, numberDay = 0: Config ko đặt lịch từ ngày đến ngày

                            //Lấy giá KM của dv
                            $getPrice = $repoOrder->getPromotionDetail(
                                'service',
                                $priceBranch['service_code'],
                                $customerId,
                                'live',
                                1
                            );
                            // Nếu có nhưng promotion > giá base thì lấy giá base
                            if ($getPrice != null && $getPrice > $priceBranch['new_price']) {
                                $getPrice = $priceBranch['new_price'];
                            }
                            // Nếu không có promotion
                            if ($getPrice == null) {
                                $getPrice = $priceBranch['new_price'];
                            }
                            $dataDetail[] = [
                                'customer_appointment_id' => $appointmentId,
                                'service_id' => $v,
                                'staff_id' => $value['staff'],
                                'room_id' => $value['room'],
                                'customer_order' => $value['stt'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'price' => (float)$getPrice,
                                'object_type' => $value['object_type'],
                                'object_id' => $v,
                                'object_code' => $priceBranch['service_code'],
                                'object_name' => $priceBranch['service_name'],
                                'is_check_promotion' => 1,
                                'created_at' => date('Y-m-d H:i')
                            ];
                            $total += $getPrice;
                        } else {
                            $dataDetail[] = [
                                'customer_appointment_id' => $appointmentId,
                                'service_id' => $v,
                                'staff_id' => $value['staff'],
                                'room_id' => $value['room'],
                                'customer_order' => $value['stt'],
                                'created_by' => Auth::id(),
                                'updated_by' => Auth::id(),
                                'price' => (float)$price,
                                'object_type' => $value['object_type'],
                                'object_id' => $v,
                                'object_code' => '',
                                'object_name' => null,
                                'is_check_promotion' => 0,
                                'created_at' => date('Y-m-d H:i')
                            ];
                        }
                    }
                }
            }
            // Cập nhật total, amount, discount cho customer-appointment
            $dataUpdate = [
                'total' => $total,
                'discount' => 0,
                'amount' => $total
            ];
            $mBooking->edit($dataUpdate, $appointmentId);
        }
        //Lưu chi tiết lịch hẹn
        $mAppointmentDetail = new CustomerAppointmentDetailTable();
        $mAppointmentDetail->insert($dataDetail);
        //Insert log lịch hẹn
        $this->_insertLogAdd($appointmentId, 'new');
        //Cộng điểm
        $this->_plusPoint($appointmentId, 1, $customerId);
        return [
            'error' => true
        ];
    }


    /**
     * Lưu log khi tạo lịch hẹn
     *
     * @param $appointmentId
     * @param $status
     */
    protected function _insertLogAdd($appointmentId, $status)
    {
        $mAppointmentLog = new CustomerAppointmentLogTable();

        switch ($status) {
            case 'new':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                break;
            case 'confirm':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log confirm
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'confirm',
                    'note' => __('Xác nhận lịch hẹn'),
                    'created_by' => Auth()->id()
                ]);
                break;
            case 'wait':
                //Insert log new
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'new',
                    'note' => __('Tạo lịch hẹn mới'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log confirm
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'confirm',
                    'note' => __('Xác nhận lịch hẹn'),
                    'created_by' => Auth()->id()
                ]);
                //Insert log wait
                $mAppointmentLog->add([
                    'customer_appointment_id' => $appointmentId,
                    'created_type' => 'backend',
                    'status' => 'wait',
                    'note' => __('Lịch hẹn được cập nhật đang phục vụ từ backend'),
                    'created_by' => Auth()->id()
                ]);
                break;
        }
    }

    /**
     * Cộng điểm khi thêm lịch hẹn
     *
     * @param $appointmentId
     * @param $appointmentSrcId
     * @param $customerId
     * @throws \MyCore\Api\ApiException
     */
    protected function _plusPoint($appointmentId, $appointmentSrcId, $customerId)
    {
        $mPlusPoint = new LoyaltyApi();
        switch ($appointmentSrcId) {
            case 1:
                //Cộng điểm khi đặt lịch trực tiếp
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_direct',
                    'object_id' => $appointmentId
                ]);
                break;
            case 2:
                //Cộng điểm khi đặt lịch từ facebook
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_fb',
                    'object_id' => $appointmentId
                ]);
                break;
            case 3:
                //Cộng điểm khi đặt lịch từ zalo
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_zalo',
                    'object_id' => $appointmentId
                ]);
                break;
            case 4:
                //Cộng điểm khi đặt lịch bằng gọi điện
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_call',
                    'object_id' => $appointmentId
                ]);
                break;
            case 5:
                //Cộng điểm khi đặt lịch online
                $mPlusPoint->plusPointEvent([
                    'customer_id' => $customerId,
                    'rule_code' => 'appointment_online',
                    'object_id' => $appointmentId
                ]);
                break;
        }
    }
}
