<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/23/2020
 * Time: 4:46 PM
 */

namespace Modules\Delivery\Repositories\DeliveryHistory;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Models\WardTable;
use Modules\Delivery\Http\Api\DeliveryApi;
use Modules\Delivery\Models\DeliveryDetailTable;
use Modules\Delivery\Models\DeliveryHistoryTable;
use Modules\Delivery\Models\DeliveryPartnerTable;
use Modules\Delivery\Models\OrderDetailTable;
use Modules\Delivery\Models\PickupAddressTable;
use Modules\Delivery\Models\ProvinceTable;
use Modules\Delivery\Models\ReceiptTable;
use Modules\Delivery\Models\TransportTable;
use Modules\Delivery\Models\UserCarrierTable;
use Modules\Delivery\Models\WarehouseTable;

class DeliveryHistoryRepo implements DeliveryHistoryRepoInterface
{
    protected $deliveryHistory;

    public function __construct(
        DeliveryHistoryTable $deliveryHistory
    ) {
        $this->deliveryHistory = $deliveryHistory;
    }

    /**
     * Danh sách phiếu giao hàng
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->deliveryHistory->getList($filters);

        return [
            'list' => $list
        ];
    }

    /**
     * Data chi tiết phiếu giao hàng
     *
     * @param $deliveryHistoryId
     * @return array|mixed
     */
    public function dataDetail($deliveryHistoryId)
    {
        $mDeliveryDetail = new DeliveryDetailTable();
        $mUserCarrier = new UserCarrierTable();
        $mTransport = new TransportTable();
        //Chi tiết phiếu giao hàng
        $info = $this->deliveryHistory->getItem($deliveryHistoryId);
        //Thông tin sản phẩm
        $product = $mDeliveryDetail->getInfo($deliveryHistoryId);
        //Option
        $optionCarrier = $mUserCarrier->getOption();
        $optionTransport = $mTransport->getOption();
        //Lấy data địa chỉ lấy hàng
        $mWarehouse = new WarehouseTable();
        $optionPickupAddress = $mWarehouse->getWarehouse();

        $mProvince = app()->get(ProvinceTable::class);

        $province = $mProvince->getOptionProvince();

        $deliveryCustomerAddress = null;
        $district = [];
        $ward = [];
        $mDistrict = app()->get(\Modules\Admin\Models\DistrictTable::class);
        $mWard = app()->get(WardTable::class);

        $district = $mDistrict->getOptionDistrict($info['province_id']);
        $ward = $mWard->getOptionWard($info['district_id']);

        $from_address = 0;
        $to_address = $info['district_id'];
        $shop_id = 0;

        foreach($optionPickupAddress as $v){
            if ($info['warehouse_id_pick_up'] == $v['warehouse_id']){
                $from_address = (int)$v['district_id'];
                $shop_id = $v['ghn_shop_id'];
            }
        }

        $apiDelivery = app()->get(DeliveryApi::class);
        $listServiceTmp = [];
        $listServiceMain = [];
        if ($from_address != 0 && $to_address != 0 && $shop_id != 0){
            $listService = $apiDelivery->getListServiceGHN([
                'method' => 'ghn',
                'shop_id' => $shop_id,
                'from_district_id' => $from_address,
                'to_district_id' => $to_address
            ]);

            if ((isset($listService['ErrorCode']) && $listService['ErrorCode'] != 0) || $listService == null){
                $listServiceTmp = [];
            } else {
                $listServiceTmp = collect($listService['Data']['list'])->keyBy('service_type_id');
            }
        }

        foreach ($listServiceTmp as $key => $itemService){
            if (in_array($key,[1,2,3])){
                $tmp = $apiDelivery->getFee([
                    'method' => 'ghn',
                    'shop_id' => $shop_id,
                    'from_district_id' => $from_address,
                    'to_district_id' => $to_address,
                    'to_ward_id' => $info['ward_id'],
                    'service_id' => $itemService['service_id'],
                    'service_type_id' => $itemService['service_type_id'],
                    'insurance_value' => 0,
                    'coupon' => '',
                    'weight' => 1,
                    'length' => 1,
                    'width' => 1,
                    'height' => 1,
                ]);
                if (isset($tmp['Data']['service_fee'])){
                    $listServiceMain[$key][] = [
                        'service_name' => $itemService['short_name'],
                        'service_fee' => $tmp['Data']['service_fee'],
                        'service_id' => $itemService['service_id'],
                        'service_type_id' => $itemService['service_type_id']
                    ];
                }

            }
        }
        $service_id = $info['service_id'];
        $service_type_id = $info['service_type_id'];

        $totalMoney = 0 ;

        foreach ($product as $itemProduct){
            $totalMoney += $itemProduct['price'] * $itemProduct['quantity'];
        }

        return [
            'item' => $info,
            'dataProduct' => $product,
            'product' => $product,
            'optionCarrier' => $optionCarrier,
            'optionTransport' => $optionTransport,
            'optionPickupAddress' => $optionPickupAddress,
            'province' => $province,
            'deliveryCustomerAddress' => $deliveryCustomerAddress,
            'district' => $district,
            'ward' => $ward,
            'listServiceMain' => $listServiceMain,
            'service_id' => $service_id,
            'service_type_id' => $service_type_id,
            'totalMoney' => $totalMoney
        ];
    }

    /**
     * Chỉnh sửa phiếu giao hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            DB::beginTransaction();
                if ($input['shipping_unit'] == 'delivery_unit'){
                    if (!isset($input['service_id'])){
                        return response()->json([
                            'error' => true,
                            'message' => __('Hãy chọn đối tác vận chuyển')
                        ]);
                    }
                    $time_ship = Carbon::parse($input['time_ship'])->format('Y-m-d 00:00:00');
                } else {
                    if ($input['time_ship_staff'] == null || $input['time_ship_staff'] == ''){
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
                            $messsageError = $messsageError.'Chiều dài vượt quá 100 cm';
                        }
                        if ($input['width'] > 100) {
                            $messsageError = $messsageError.'Chiều rộng vượt quá 100 cm';
                        }
                        if ($input['height'] > 100) {
                            $messsageError = $messsageError.'Chiều cao vượt quá 100 cm';
                        }
                    } else {
                        if ($input['length'] > 50) {
                            $messsageError = $messsageError.'Chiều dài vượt quá 50 cm';
                        }
                        if ($input['width'] > 30) {
                            $messsageError = $messsageError.'Chiều rộng vượt quá 30 cm';
                        }
                        if ($input['height'] > 50) {
                            $messsageError = $messsageError.'Chiều cao vượt quá 50 cm';
                        }
                    }

                    if ($messsageError != ''){
                        return response()->json([
                            'error' => true,
                            'message' => $messsageError
                        ]);
                    }

                    if ($input['type_weight'] == 'kg' && str_replace(',', '', $input['weight']) > 50){
                        return response()->json([
                            'error' => true,
                            'message' => __('Trọng lượng vượt quá 50kg')
                        ]);
                    }
                }

                $dataHistory = [
                    'delivery_staff' => $input['delivery_staff'],
                    'contact_phone' => $input['contact_phone'],
                    'contact_address' => $input['contact_address'],
                    'contact_name' => $input['contact_name'],
//                    'amount' => str_replace(',', '', $input['amount']),
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
                    'transport_code' => isset($input['is_partner']) ? $input['is_partner'] : '',
                    'partner' => isset($input['is_partner']) ? $input['is_partner'] : '',
                    'fee' => isset($input['fee']) ? $input['fee'] : 0,
                    'name_service' => isset($input['name_service']) ? $input['name_service'] : '',
                    'total_fee' => isset($input['total_fee']) ? $input['total_fee'] : 0,
                    'insurance_fee' => isset($input['insurance_fee']) ? $input['insurance_fee'] : 0,
                    'is_cod_amount' => isset($input['is_cod_amount']) ? $input['is_cod_amount'] : 0,
                    'cod_amount' => str_replace(',', '', $input['amount_cod']),
                ];

                $this->deliveryHistory->edit($dataHistory, $input['delivery_history_id']);
                $totalQuantity = 0;

                $mHistoryDetail = new DeliveryDetailTable();
                $arrProduct = [];
                if (isset($input['arrProduct']) && count($input['arrProduct']) > 0) {

                    foreach ($input['arrProduct'] as $item) {
                        $totalQuantity += intval($item['quantity']);
                        $dataDetail = [
                            'object_type' => $item['object_type'],
                            'object_id' => $item['object_id'],
                            'quantity' => $item['quantity'],
                            'note' => $item['note'],
                            'price' => $item['price'],
                        ];
                        $arrProduct[] = [
                            'name' => $item['object_name'],
                            'code' => $item['object_code'],
                            'quantity' => (int)$item['quantity'],
                            'price' => (int)$item['price'],
                        ];
                        $mHistoryDetail->updateHistory($dataDetail,$item['delivery_detail_id']);
                    }

                    if ($totalQuantity <= 0){
                        return response()->json([
                            'error' => true,
                            'message' => __('Vui lòng chọn ít nhất 1 sản phẩm')
                        ]);
                    }
                }

                if ($input['shipping_unit'] == 'delivery_unit'){
                    $apiDelivery = app()->get(DeliveryApi::class);
                    $mWareHouse = app()->get(WarehouseTable::class);

                    $detailWareHouse = $mWareHouse->getWarehouseDetail($input['warehouse_id']);

                    $detailHistory = $this->deliveryHistory->getItem($input['delivery_history_id']);

                    if ($detailWareHouse != null && $detailWareHouse['ghn_shop_id'] != null){

                        $dataTmp = [
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
                        ];

                        if ($detailHistory['ghn_order_code'] != null){
                            $dataTmp['order_code'] = $detailHistory['ghn_order_code'];
                            $orderGHN = $apiDelivery->updateOrder($dataTmp);
                        } else {
                            $orderGHN = $apiDelivery->createOrder($dataTmp);
                            if (isset($orderGHN['ErrorCode']) && $orderGHN['ErrorCode'] == 0){
                                $this->deliveryHistory->edit([
                                    "ghn_order_code" => $orderGHN['Data']['order_code']
                                ], $input['delivery_history_id']);
                            }
                        }

                    }
                }
            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa phiếu giao hàng thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa phiếu giao hàng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * lấy danh sách đối tác vận chuyển
     * @return mixed|void
     */
    public function getListDeliveryPartner()
    {
        $mDeliveryPartner = app()->get(DeliveryPartnerTable::class);
        return $mDeliveryPartner->getListPartner();
    }

    public function getListTransport(){
        $mTransport = app()->get(TransportTable::class);
        return $mTransport->getOption();
    }

    /**
     * Phiếu in
     * @param $input
     */
    public function print($input)
    {
        try {
            $apiDelivery = app()->get(DeliveryApi::class);
            $input['mode'] = 'sanbox';
            $input['shop_id'] = (int)$input['shop_id'];
            $input['order_code'] = strtoupper($input['ghn_order_code']);
            $link = $apiDelivery->printView($input);

            if (isset($link['ErrorCode']) && $link['ErrorCode'] == 0 && isset($link['Data']['url'])){
                return [
                    'error' => false,
                    'url' => $link['url']
                ];
            } else {
                return [
                    'error' => true,
                    'message' => __('Hiển thị phiếu in Giao hàng nhanh thất bại'),
                ];
            }


        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị phiếu in Giao hàng nhanh thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Hiển thị kích thước in
     * @param $input
     * @return mixed|void
     */
    public function showPopupPrint($input){
        try {

            $view = view('delivery::delivery-history.popup.print-size',$input)->render();

            return [
                'error' => false,
                'message' => __('Hiển thị kích thước in thành công'),
                'view' => $view
            ];
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị kích thước in thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }
}