<?php


namespace Modules\FNB\Repositories\Order;


use App\Exports\ExportFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Api\LoyaltyApi;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Admin\Models\CustomerRemindUseTable;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Repositories\MapProductAttribute\MapProductAttributeRepositoryInterface;
use Modules\FNB\Models\ConfigTable;
use Modules\FNB\Models\ContractCategoriesTable;
use Modules\FNB\Models\ContractGoodsTable;
use Modules\FNB\Models\ContractLogGoodsTable;
use Modules\FNB\Models\ContractLogReceiptSpendTable;
use Modules\FNB\Models\ContractLogTable;
use Modules\FNB\Models\ContractMapOrderTable;
use Modules\FNB\Models\ContractPaymentTable;
use Modules\FNB\Models\ContractReceiptDetailTable;
use Modules\FNB\Models\ContractReceiptTable;
use Modules\FNB\Models\ContractTable;
use Modules\FNB\Models\CustomerBranchMoneyLogTable;
use Modules\FNB\Models\CustomerContactTable;
use Modules\FNB\Models\CustomerServiceCardTable;
use Modules\FNB\Models\CustomerTable;
use Modules\FNB\Models\DeliveryCostDetailTable;
use Modules\FNB\Models\DeliveryCostTable;
use Modules\FNB\Models\DeliveryHistoryTable;
use Modules\FNB\Models\DeliveryTable;
use Modules\FNB\Models\FNBAreasTable;
use Modules\FNB\Models\FNBCustomerRequestTable;
use Modules\FNB\Models\FNBTableTable;
use Modules\FNB\Models\OrderDetailSerialTable;
use Modules\FNB\Models\OrderDetailTable;
use Modules\FNB\Models\OrderLogTable;
use Modules\FNB\Models\OrderSessionSerialTable;
use Modules\FNB\Models\OrderTable;
use Modules\FNB\Models\ProductChildTable;
use Modules\FNB\Models\PromotionDailyTimeTable;
use Modules\FNB\Models\PromotionDateTimeTable;
use Modules\FNB\Models\PromotionDetailTable;
use Modules\FNB\Models\PromotionLogTable;
use Modules\FNB\Models\PromotionMonthlyTimeTable;
use Modules\FNB\Models\PromotionObjectApplyTable;
use Modules\FNB\Models\PromotionWeeklyTimeTable;
use Modules\FNB\Models\ReceiptDetailTable;
use Modules\FNB\Models\ReceiptTable;
use Modules\FNB\Models\ServiceTable;
use Modules\FNB\Models\StaffsTable;
use Modules\FNB\Models\WarrantyCardTable;
use Modules\FNB\Models\WarrantyPackageDetailTable;
use Modules\FNB\Models\WarrantyPackageTable;
use Modules\FNB\Repositories\Config\ConfigRepositoryInterface;
use Modules\FNB\Repositories\Customer\CustomerRepositoryInterface;
use Modules\FNB\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\FNB\Repositories\CustomerGroup\CustomerGroupRepositoryInterface;
use Modules\FNB\Repositories\CustomerServiceCard\CustomerServiceCardRepositoryInterface;
use Modules\FNB\Repositories\DiscountCause\DiscountCauseRepositoryInterface;
use Modules\FNB\Repositories\FNBAreas\FNBAreasRepositoryInterface;
use Modules\FNB\Repositories\FNBTable\FNBTableRepositoryInterface;
use Modules\FNB\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\FNB\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\FNB\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\FNB\Repositories\PaymentMethod\PaymentMethodRepositoryInterface;
use Modules\FNB\Repositories\ProductBranchPrice\ProductBranchPriceRepositoryInterface;
use Modules\FNB\Repositories\ProductCategory\ProductCategoryRepositoryInterface;
use Modules\FNB\Repositories\ProductChild\ProductChildRepositoryInterface;
use Modules\FNB\Repositories\ProductTopping\ProductToppingRepositoryInterface;
use Modules\FNB\Repositories\Province\ProvinceRepositoryInterface;
use Modules\FNB\Repositories\Room\RoomRepositoryInterface;
use Modules\FNB\Repositories\ServiceBranchPrice\ServiceBranchPriceRepositoryInterface;
use Modules\FNB\Repositories\Staff\StaffRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    private $order;

    const LIVE = 1;

    const NEW = 'new';
    const PAYSUCCESS = 'paysuccess';
    const PAY_HALF = 'pay-half';
    const ORDER_CANCEL = 'ordercancle';
    const CONFIRMED = 'confirmed';

    public function __contruct(OrderTable $order){
        $this->order = $order;
    }

    /**
     * Danh sách đơn hàng
     *
     * @param array $filters
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function list(array $filters = [])
    {
        $order = app()->get(OrderTable::class);
        $list = $order->getList($filters);

        //Data Receipt
        $mReceipt = new ReceiptTable();
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);

        $listReceipt = $mReceipt->getAllReceipt();

        $arrReceipt = [];
        foreach ($listReceipt as $item) {
            $arrReceipt[$item['order_id']] = [
                'order_id' => $item['order_id'],
                'amount_paid' => $item['amount_paid'],
                'note' => $item['note']
            ];
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
            'list' => $list,
            'receipt' => $arrReceipt,
        ];
    }

    public function addOrders($request)
    {
        $rCustomerGroup = app()->get(CustomerGroupRepositoryInterface::class);
        $rProvince = app()->get(ProvinceRepositoryInterface::class);
        $rPaymentMethod = app()->get(PaymentMethodRepositoryInterface::class);
        $rConfig = app()->get(ConfigRepositoryInterface::class);
        $rDiscountCause = app()->get(DiscountCauseRepositoryInterface::class);
        $rServiceBranchPrice = app()->get(ServiceBranchPriceRepositoryInterface::class);
        $staff = app()->get(StaffRepositoryInterface::class);
        $rCustomer = app()->get(CustomerRepositoryInterface::class);
        $customerLoad = null;
        $listMemberCard = [];

        if (isset($request->customer_id) && $request->customer_id != null) {

            $rCustomerMoney = app()->get(CustomerBranchMoneyRepositoryInterface::class);
            //Lấy thông tin khách hàng
            $infoCustomer = $rCustomer->getItem($request->customer_id);

            if ($infoCustomer == null) {
                return redirect()->route('admin.order');
            }

            $infoCustomer['money'] = $rCustomerMoney->getPriceBranch($infoCustomer['customer_id'], Auth()->user()->branch_id);
            //Lấy tông tin thẻ liệu trình
            $rMemberCard = app()->get(CustomerServiceCardRepositoryInterface::class);
            $listMemberCard = $rMemberCard->getMemberCard($infoCustomer['customer_id'], Auth::user()->branch_id);

            $customerLoad = $infoCustomer;
            $listMemberCard = $listMemberCard;
        }

        $session = Carbon::now()->format('YmdHisu');

        $customer_default = $rCustomer->getCustomerOption();
        //Lấy nv phục vụ
        $staff_technician = $staff->getStaffTechnician();
        //Lấy nhóm khách hàng
        $customer_group = $rCustomerGroup->getOption();
        //Lấy option tỉnh thành
        $province = $rProvince->getOptionProvince();
        //Lấy hình thức thanh toán
        $optionPaymentMethod = $rPaymentMethod->getOption();

//        Chỉ thanh toán bằng tiền mặt
        $optionPaymentMethod = collect($optionPaymentMethod)->where('payment_method_code','CASH');

        //Lấy cấu hình thay đổi giá
        $customPrice = $rConfig->getInfoByKey('customize_price')['value'];
        $decimalQuantity = $rConfig->getInfoByKey('decimal_quantity')['value'];
        //Lấy option lý do giảm giá
        $optionDiscountCause = $rDiscountCause->getOption();
        //Lấy option dịch vụ
        $optionService = $rServiceBranchPrice->getOptionService(Auth()->user()->branch_id);
        //Lấy option phòng
        $rRoom = app()->get(RoomRepositoryInterface::class);
        $optionRoom = [];
        foreach ($rRoom->getRoomOption() as $item) {
            $optionRoom[$item['room_id']] = $item['name'];
        }
        $configToDate = $rConfig->getInfoByKey('booking_to_date')['value'];
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

        $params = $request;



        return [
            'listPhone' => $customer_default,
            'staff_technician' => $staff_technician,
            'customer_refer' => $customer_default,
            'customer_group' => $customer_group,
            'province' => $province,
            'optionPaymentMethod' => $optionPaymentMethod,
            'customPrice' => $customPrice,
            'optionDiscountCause' => $optionDiscountCause,
            'customerLoad' => $customerLoad,
            'listMemberCard' => $listMemberCard,
            'optionStaff' => $staff_technician,
            'optionService' => $optionService,
            'optionRoom' => $optionRoom,
            'configToDate' => $configToDate,
            'is_edit_full' => $is_edit_full,
            'is_edit_staff' => $is_edit_staff,
            'is_payment_order' => $is_payment_order,
            'is_update_order' => $is_update_order,
            'session' => $session,
            'params' => $params,
            'decimalQuantity' => $decimalQuantity
        ];
    }

    /**
     * Chọn sản phẩm/ dịch vụ
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function chooseType($input)
    {
        $rProductCategory = app()->get(ProductCategoryRepositoryInterface::class);
        $area = app()->get(FNBAreasRepositoryInterface::class);

        $data = [];
        switch ($input['object_type']) {
            case 'product':
                //Lấy loại sản phẩm
                $getCategory = $rProductCategory->getAll();

                if (count($getCategory) > 0) {
                    foreach ($getCategory as $v) {
                        $data[] = [
                            'category_id' => $v['product_category_id'],
                            'category_name' => $v['category_name']
                        ];
                    }
                }

                break;
            case 'area':
                //Lấy loại dịch vụ
                $getCategory = $area->getAll();

                if (count($getCategory) > 0) {
                    foreach ($getCategory as $v) {
                        $data[] = [
                            'category_id' => $v['area_id'],
                            'category_name' => $v['name']
                        ];
                    }
                }
                break;
        }

        return [
            'data' => $data
        ];
    }

    /**
     * Tab sản phẩm, dịch vụ, thẻ dịch vụ bán hàng khi chọn tab
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function listAddAction($request)
    {

        $table = app()->get(FNBTableRepositoryInterface::class);
        $productBranchPrice = app()->get(ProductBranchPriceRepositoryInterface::class);
        $order = app()->get(OrderTable::class);
        $type = $request['object_type'];
        $arr = [];

        $pageAdd = false;

        if (session()->has('page_add')){
            $pageAdd = true;
        }

        if ($type == 'area') {
            $request['area_id'] = $request['category_id'] == 'all' ? -1 : $request['category_id'];
            $listTable = $table->getListPagination($request);
            $arr = [];
            if (count($listTable) != 0){
                $arr = collect($listTable)->toArray()['data'];
            }

        } else if ($type == 'product') {
            //Lấy giới hạn 16 sản phẩm
            $list = $productBranchPrice->getItemBranchLimitMaster(Auth()->user()->branch_id, $request['category_id'], $request['search'], $request['page']);
            foreach ($list as $item) {
                $getPrice = $this->getPromotionDetail('product', $item['product_code'], $request['customer_id'], 'live', 1);

                if ($getPrice != null && $getPrice > $item['new_price']) {
                    $getPrice = $item['new_price'];
                }

                $arr[$item['product_id']] = [
                    'name' => $item['product_child_name'],
                    'product_name' => $item['product_name'],
                    'id' => $item['product_child_id'],
                    'product_id' => $item['product_id'],
                    'price' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'price_hidden' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'avatar' => $item['avatar'],
                    'code' => $item['product_code'],
                    'type' => $type,
                    'is_sale' => $getPrice != null ? 1 : 0,
                    'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
                    'is_surcharge' => $item['is_surcharge'],
                    'inventory_management' => $item['inventory_management'],
                    'description' => $item[getValueByLang('description_')],
                ];
            }
        }

        $tableId = session()->get('table_selected');

        $view = view('fnb::orders.inc.list-product', [
            'list' => $arr,
            'type' => $type,
            'tableId' => $tableId,
            'pageAdd' => $pageAdd
        ])->render();

        return $view;
    }


    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $customerId
     * @param $orderSource
     * @param $promotionType
     * @param $quantity
     * @param $date
     * @return mixed|void
     */
    public function getPromotionDetail($objectType, $objectCode, $customerId, $orderSource, $promotionType, $quantity = null, $date = null)
    {
        $mPromotionDetail = app()->get(PromotionDetailTable::class);
        $mDaily = app()->get(PromotionDailyTimeTable::class);
        $mWeekly = app()->get(PromotionWeeklyTimeTable::class);
        $mMonthly = app()->get(PromotionMonthlyTimeTable::class);
        $mFromTo = app()->get(PromotionDateTimeTable::class);
        $mCustomer = app()->get(CustomerTable::class);
        $mPromotionApply = app()->get(PromotionObjectApplyTable::class);

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $currentTime = Carbon::now()->format('H:i');

        if ($date != null) {
            $currentDate = Carbon::createFromFormat('Y-m-d H:i', $date)->format('Y-m-d H:i:s');
            $currentTime = Carbon::createFromFormat('Y-m-d H:i', $date)->format('H:i');
        }

        $price = [];
        $arrGift = [];

        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, $promotionType, $currentDate);

        if (count($getDetail) > 0) {
            foreach ($getDetail as $v) {
                //Check thời gian diễn ra chương trình
                if ($currentDate < $v['start_date'] || $currentDate > $v['end_date']) {
                    //Kết thúc 1 lần for
                    continue;
                }
                //Check chi nhánh áp dụng
                if (
                    $v['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $v['branch_apply']))
                ) {
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
                                //Kết thúc 1 lần for
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
                                    //Kết thúc 1 lần for
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

                //Check KM theo type = discount or gift
                if ($v['promotion_type'] != $promotionType) {
                    //Kết thúc 1 lần for
                    continue;
                }

                //Check nguồn đơn hàng
                if ($v['order_source'] != 'all' && $v['order_source'] != $orderSource) {
                    //Kết thúc 1 lần for
                    continue;
                }
                //Check đối tượng áp dụng
                if ($v['promotion_apply_to'] != 1 && $v['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc 1 lần for
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
                        //Kết thúc 1 lần for
                        continue;
                    }
                }

                //Check quota (số tiền)
                if ($promotionType == 1) {
                    $price[] = $v['promotion_price'];
                } else {
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
                            $arrGift[] = [
                                'promotion_code' => $v['promotion_code'],
                                'gift_object_type' => $v['gift_object_type'],
                                'gift_object_id' => $v['gift_object_id'],
                                'gift_object_name' => $v['gift_object_name'],
                                'gift_object_code' => $v['gift_object_code'],
                                'multiplication' => $multiplication . '-' . $quantity . '-' . $v['quantity_buy'],
                                'quantity_gift' => $totalGift,
                                //mới update param thêm
                                'quantity_buy' => $v['quantity_buy'],
                                'quota' => !empty($v['quota']) ? $v['quota'] : 0,
                                'quota_use' => floatval($v['quota_use']),
                                'total_price_gift' => $priceGift * $totalGift
                            ];
                        }
                    }
                }
            }
        }

        //Trả về kết quả khuyến mãi
        //Khuyến mãi giảm giá
        if ($promotionType == 1) {
            if (count($price) > 0) {
                //Lấy giả giảm ưu đãi nhất
                return min($price);
            } else {
                return 0;
            }
        } else {
            //Khuyến mãi quà tặng
            if (count($arrGift) > 0) {
                //Lấy quà tặng ưu đãi nhất
                return $this->getGiftMostPreferential($arrGift);
            } else {
                return [];
            }
        }
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

        $mProduct = app()->get(ProductChildTable::class);
        //Lấy thông tin sp khuyến mãi
        $getProduct = $mProduct->getProductPromotion($objectCode);
        $price = $getProduct['new_price'];

        return floatval($price);
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
            return $arrGift[0];
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
            'promotion_code' => $arrGift[0]['promotion_code'],
            'multiplication' => $arrGift[0]['multiplication'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_name' => $arrGift[0]['gift_object_name'],
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
                    'promotion_code' => $v['promotion_code'],
                    'multiplication' => $v['multiplication'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_name' => $v['gift_object_name'],
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
            'promotion_code' => $arrGift[0]['promotion_code'],
            'multiplication' => $arrGift[0]['multiplication'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_name' => $arrGift[0]['gift_object_name'],
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
                    'promotion_code' => $v['promotion_code'],
                    'multiplication' => $v['multiplication'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_name' => $v['gift_object_name'],
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
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'multiplication' => $arrLimited[0]['multiplication'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_name' => $arrLimited[0]['gift_object_name'],
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
                        'promotion_code' => $v['promotion_code'],
                        'multiplication' => $v['multiplication'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_name' => $v['gift_object_name'],
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
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'multiplication' => $arrUnLimited[0]['multiplication'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_name' => $arrUnLimited[0]['gift_object_name'],
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
                        'promotion_code' => $v['promotion_code'],
                        'multiplication' => $v['multiplication'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_name' => $v['gift_object_name'],
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
     * Hiển thị popup chọn topping
     * @param $data
     * @return mixed|void
     */
    public function selectTopping($data)
    {
        try {
            $rProductChild = app()->get(ProductChildRepositoryInterface::class);
            $rMapProductAttribute = app()->get(MapProductAttributeRepositoryInterface::class);
            $rProductTopping = app()->get(ProductToppingRepositoryInterface::class);

            $productChildId = $data['product_child_id'];
//            Tạo mảng lưu giữ danh sách thuộc tính đính kèm được chọn
            $arrSelectAttribute = [];
            $arrTopping = [];
            $note = '';
//            Lấy product cha
            $product = $rProductChild->getParentProduct($productChildId);

            $master = $rProductChild->getParentProductMaster($product['product_id']);
                $arrSelectAttribute = json_decode($master['product_attribute_json']) == null ? [] : json_decode($master['product_attribute_json']);

//            Session lưu các cấu hình sản phẩm đã chọn món đã chọn thuộc tính đính kèm
            $sessionProduct = session()->get('topping_product');

//            Nếu session đã lưu lựa chọn của sản phẩm rồi thì sẽ lấy lại để dùng
            if(isset($sessionProduct[$product['product_id'].'_'.$data['row']])){
                if (isset($sessionProduct[$product['product_id'].'_'.$data['row']]['product_attribute_id'])){
                    $arrSelectAttribute = $sessionProduct[$product['product_id'].'_'.$data['row']]['product_attribute_id'];
                }
                $arrTopping = $sessionProduct[$product['product_id'].'_'.$data['row']]['topping'];
                $note = $sessionProduct[$product['product_id'].'_'.$data['row']]['note'];
            }

//            Lấy danh sách attribute
            $listAttribute = $rMapProductAttribute->getListMapProductAttribute($product['product_id']);

            if (count($listAttribute) != 0){
                $listAttribute = collect($listAttribute)->unique('product_attribute_id')->sortByDesc('is_master')->groupBy('product_attribute_group_id');
            }

//            Lấy danh sách topping
            $listTopping = $rProductTopping->getListTopping($product['product_id']);

            $view = view('fnb::orders.popup.select-topping',[
                'listAttribute' => $listAttribute,
                'listTopping' => $listTopping,
                'product' => $product,
                'row' => $data['row'],
                'arrSelectAttribute' => $arrSelectAttribute,
                'topping' => $arrTopping,
                'note' => $note,
                'removeProduct' => $data['removeProduct']
            ])->render();

            return [
                'error' => false,
                'view' => $view,
                'total_attr' => $arrSelectAttribute == null ? 0 : count($arrSelectAttribute)
            ];

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Lưu lựa chọn topping
     * @param $data
     * @return mixed|void
     */
    public function saveToppingSelect($data)
    {
        try {

            $productBranchPrice = app()->get(ProductBranchPriceRepositoryInterface::class);
            $rConfig = app()->get(ConfigRepositoryInterface::class);

            $session = [];
            if (session()->has('topping_product')){
                $session = session()->get('topping_product');
            };

            if (isset($session[$data['product_topping_select'].'_'.$data['numberRowChange']])){
                unset($session[$data['product_topping_select'].'_'.$data['numberRowChange']]);
            }
            if(isset($data['product_attribute_id'])){
                $session[$data['product_topping_select'].'_'.$data['numberRowChange']]['product_attribute_id'] = $data['product_attribute_id'];
            }
            $session[$data['product_topping_select'].'_'.$data['numberRowChange']]['topping'] = [];
            $session[$data['product_topping_select'].'_'.$data['numberRowChange']]['note'] = $data['note_topping'];
            if (isset($data['topping'])){
                $session[$data['product_topping_select'].'_'.$data['numberRowChange']]['topping'] = $data['topping'];
            }

            session()->put('topping_product',$session);

            $view = view('fnb::orders.inc.show-topping-select',$data)->render();

            $total = 0;
            $filter = [
                'product_id' => $data['product_topping_select']
            ];
            if(isset($data['product_attribute_id'])){
                $filter['arr_product_attribute_id'] = $data['product_attribute_id'];
            }

            $childDetail = $productBranchPrice->getItemBranchByAttribute($filter);

            $total = (double)$childDetail['new_price'];

            if (isset($data['product_attribute_id'])){
                foreach ($data['product_attribute_id'] as $item){
                    $total += (double)$data['price_attribute'][$item];
                }
            }

            if (isset($data['topping'])){
                foreach ($data['topping'] as $item){
                    $total += (double)$data['price_topping'][$item];
                }
            }

            $viewChild = '';
//            if (isset($data['topping'])){
//                $listProductChild = $productBranchPrice->getItemBranchByChildId(Auth()->user()->branch_id,$data['topping']);
//
//                $customPrice = $rConfig->getInfoByKey('customize_price')['value'];
//                $dataChild = [];
//
//                foreach ($listProductChild as $item){
//                    $getPrice = $this->getPromotionDetail('product', $item['product_code'], $data['customer_id'], 'live', 1);
//
//                    if ($getPrice != null && $getPrice > $item['new_price']) {
//                        $getPrice = $item['new_price'];
//                    }
//
//                    $dataChild[$item['product_id']] = [
//                        'name' => $item['product_child_name'],
//                        'product_name' => $item['product_name'],
//                        'id' => $item['product_child_id'],
//                        'product_id' => $item['product_id'],
//                        'price' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
//                        'price_hidden' => number_format($item['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
//                        'avatar' => $item['avatar'],
//                        'code' => $item['product_code'],
//                        'type' => 'product',
//                        'is_sale' => $getPrice != null ? 1 : 0,
//                        'promotion_price' => number_format($getPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0),
//                        'is_surcharge' => $item['is_surcharge'],
//                        'inventory_management' => $item['inventory_management']
//                    ];
//                }
//
//                $viewChild = view('fnb::orders.inc.add-product-child',[
//                    'dataChild' => $dataChild,
//                    'numberRow' => $data['numberRow'],
//                    'product_id' => $data['product_topping_select'],
//                    'stt_tr' => $data['stt_tr'],
//                    'customPrice' => $customPrice
//                ])->render();
//
//                $data['numberRow'] = $data['numberRow'] + count($dataChild);
//                $data['stt_tr'] = $data['stt_tr'] + count($dataChild);
//            }


            return [
                'error' => false,
                'message' => __('Lưu thông tin thành công'),
                'view' => $view,
                'product_child_id' => $data['product_child_topping_select'],
                'total' => $total,
                'numberRow' => $data['numberRow'],
                'stt_tr' => $data['stt_tr'],
                'product_id' => $data['product_topping_select'],
                'viewChild' => $viewChild,
                'product_child_id_change' => $childDetail['product_child_id'] // Id child thay đổi khi thay đổi các lựa chọn món
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lưu thông tin thất bại')
            ];
        }
    }

    public function changeToppingSelect($data)
    {
        try {

            $productBranchPrice = app()->get(ProductBranchPriceRepositoryInterface::class);
            $rConfig = app()->get(ConfigRepositoryInterface::class);

            $total = 0;

            $filter['product_id'] = $data['product_topping_select'];

            if (isset($data['product_attribute_id'])){
                $filter['arr_product_attribute_id'] = $data['product_attribute_id'];
            }

            $childDetail = $productBranchPrice->getItemBranchByAttribute($filter);

            $total = (double)$childDetail['new_price'];

            if (isset($data['product_attribute_id'])){
                foreach ($data['product_attribute_id'] as $item){
                    $total += (double)$data['price_attribute'][$item];
                }
            }

            if (isset($data['topping'])){
                foreach ($data['topping'] as $item){
                    $total += (double)$data['price_topping'][$item];
                }
            }

            return [
                'error' => false,
                'total' => number_format($total),
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lưu thông tin thất bại')
            ];
        }
    }

    public function submitOrUpdate($data)
    {
        $staff = app()->get(StaffsTable::class);
        $order = app()->get(OrderTable::class);
        $orderDetail = app()->get(OrderDetailTable::class);
        $mPromotionLog = new PromotionLogTable();
        $rOrderApp = app()->get(OrderAppRepoInterface::class);
        $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
        $mProductChild = app()->get(ProductChildTable::class);
        $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
        $mOrderLog = new OrderLogTable();
        DB::beginTransaction();
        try {
            $sessionProduct = [];
            if (session()->has('topping_product')){
                $sessionProduct = session()->get('topping_product');
            };

            if (!isset($data['table_id'])){
                $data['table_id'] = 1;
//                return response()->json([
//                    'error' => false,
//                    'message' => __('Vui lòng chọn bàn')
//                ]);
            }

            // nếu chưa có order_id thì tạo
            if (!$data['order_id']) {
                if ($data['table_add'] == null) {
                    return response()->json([
                        'table_error' => 1
                    ]);
                }

//                $session = $data['sessionSerial'];

                if ($data['receipt_info_check'] == 1 && !isset($data['delivery_type'])) {
                    return response()->json([
                        'error' => false,
                        'message' => __('Vui lòng chọn hình thức nhận hàng')
                    ]);
                }

                $staff_branch = $staff->getItem(Auth::id());
                $mCustomerContact = app()->get(CustomerContactTable::class);

                $detailAddress = $mCustomerContact->getDetailContact($data['customer_contact_id']);

                $data_order = [
                    'customer_id' => $data['customer_id'],
                    'total' => $data['total_bill'],
                    'discount' => $data['discount_bill'],
                    'amount' => $data['amount_bill'],
                    'voucher_code' => $data['voucher_bill'],
//                    'order_description' => $data['order_description'],
                    'branch_id' => $staff_branch['branch_id'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
//                    'refer_id' => $data['refer_id'],
                    'customer_contact_code' => $detailAddress != null ? $detailAddress['customer_contact_code'] : '',
                    'customer_contact_id' => $data['customer_contact_id'],
                    'receive_at_counter' => $data['receipt_info_check'] == 1 ? 0 : 1,
                    'type_time' => $data['type_time'],
                    'time_address' => $data['time_address'] != '' ? Carbon::createFromFormat('d/m/Y', $data['time_address'])->format('Y-m-d') : '',
                    'tranport_charge' => $data['tranport_charge'],
                    'type_shipping' => $data['delivery_type'],
                    'delivery_cost_id' => $data['delivery_cost_id'],
                    'discount_member' => $data['discount_member'],
                    'fnb_table_id' => $data['table_id'],
                    'fnb_customer_id' => $data['customer_id'],
                ];

                $id_order = $order->add($data_order);

                $day_code = date('dmY');
                if ($id_order < 10) {
                    $id_order = '0' . $id_order;
                }

                $orderCode = 'DH_' . $day_code . $id_order;
                $order->edit([
                    'order_code' => $orderCode
                ], $id_order);

                $arrPromotionLog = [];
                $arrQuota = [];
                $arrObjectBuy = [];

                $messageSerialError = '';

                if ($data['table_add'] != null) {
                    $aData = $data['table_add'];
                    foreach ($aData as $key => $value) {
                        $value['amount'] = str_replace(',', '', $value['amount']);
                        $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                        $isCheckPromotion = isset($value['is_check_promotion']) ? $value['is_check_promotion'] : 0;

                        $idProductChild = $value['product_child_id'];

                        if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['product_child_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $data['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }
                        $data_order_detail = [
                            'order_id' => $id_order,
                            'object_id' => $value['product_child_id'],
                            'object_name' => $value['product_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => str_replace(',', '', $value['amount']),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                            'staff_id' => isset($data['staff_id']) ? $data['staff_id'] : null,
                            'is_change_price' => $isChangePrice,
                            'is_check_promotion' => $isCheckPromotion,
                            'note' => isset($sessionProduct[$value['product_id'].'_'.$value['key_string']]) ? $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note'] : null
                        ];

                        $orderDetailId = $orderDetail->add($data_order_detail);

                        if(isset($sessionProduct[$value['product_id'].'_'.$value['key_string']])){
                            $arrProductChildTmp = $sessionProduct[$value['product_id'].'_'.$value['key_string']]['topping'];
                            if (count($arrProductChildTmp) != 0){
                                $listProductChild = $mProductChild->getProductChildInId($arrProductChildTmp);
                                foreach ($listProductChild as $itemChildProduct){
                                    $data_order_detail_child = [
                                        'order_id' => $id_order,
                                        'object_id' => $itemChildProduct['productId'],
                                        'object_name' => $itemChildProduct['productName'],
                                        'object_type' => $value['object_type'],
                                        'object_code' => $value['object_code'],
                                        'price' => 0,
                                        'quantity' => 1,
                                        'discount' => 0,
                                        'voucher_code' => '',
                                        'amount' => 0,
                                        'created_by' => Auth::id(),
                                        'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                                        'staff_id' => isset($data['staff_id']) ? $data['staff_id'] : null,
                                        'is_change_price' => $isChangePrice,
                                        'is_check_promotion' => $isCheckPromotion,
                                        'order_detail_id_parent' => $orderDetailId,
                                        'note' => $sessionProduct[$value['product_id'].'_'.$value['key_string']]['note']
                                    ];

                                    $orderDetail->add($data_order_detail_child);
                                }
                            }
                        }

//                        if ($value['object_type'] == 'product') {
//                            $tmpSerial = [];
//                            $listSerialLog = $mOrderSessionSerialLog->getListSerialNoLimit($session, $value[14], $value['object_code']);
//                            foreach ($listSerialLog as $item) {
//                                $tmpSerial[] = [
//                                    'order_id' => $id_order,
//                                    'order_detail_id' => $orderDetailId,
//                                    'product_code' => $value[3],
//                                    'serial' => $item['serial'],
//                                    'created_at' => Carbon::now(),
//                                    'created_by' => Auth::id(),
//                                    'updated_at' => Carbon::now(),
//                                    'updated_by' => Auth::id(),
//                                ];
//                            }
//
//                            if (count($tmpSerial) != 0) {
//                                $mOrderDetailSerial->insertSerial($tmpSerial);
//                            }
//                        }
                    }
                }

                if ($messageSerialError != '') {
                    return [
                        'error' => false,
                        'message' => $messageSerialError
                    ];
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
                    'note_en' => 'Order success',
                ]);

                //Trừ quota_user khi đơn hàng có promotion quà tặng
                $rOrderApp->subtractQuotaUsePromotion($data['order_id']);
                //Remove promotion log
                $mPromotionLog->removeByOrder($data['order_id']);
                if (!isset($data['custom_price']) && $data['custom_price'] == 0) {
                    //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                    $getPromotionLog = $rOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                    //Insert promotion log
                    $arrPromotionLog = $getPromotionLog['promotion_log'];
                    $mPromotionLog->insert($arrPromotionLog);
                    //Cộng quota_use promotion quà tặng
                    $arrQuota = $getPromotionLog['promotion_quota'];
                    $rOrderApp->plusQuotaUsePromotion($arrQuota);
                }

                //Cộng điểm khi mua hàng trực tiếp
//                $mPlusPoint = new LoyaltyApi();
//                $mPlusPoint->plusPointEvent([
//                    'customer_id' => $data['customer_id'],
//                    'rule_code' => 'order_direct',
//                    'object_id' => $id_order
//                ]);

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                $isCreateTicket = 0;

                if ($data['customer_id'] != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

//                try {
//                    // Insert email log
//                    App\Jobs\FunctionSendNotify::dispatch([
//                        'type' => SEND_EMAIL_CUSTOMER,
//                        'event' => 'is_event',
//                        'key' => 'order_success',
//                        'object_id' => $id_order,
//                        'tenant_id' => session()->get('idTenant')
//                    ]);
//                    // Insert sms log
//                    App\Jobs\FunctionSendNotify::dispatch([
//                        'type' => SEND_SMS_CUSTOMER,
//                        'key' => 'order_success',
//                        'object_id' => $id_order,
//                        'tenant_id' => session()->get('idTenant')
//                    ]);
//                    //Gửi thông báo khách hàng
//                    App\Jobs\FunctionSendNotify::dispatch([
//                        'type' => SEND_NOTIFY_CUSTOMER,
//                        'key' => 'order_status_W',
//                        'customer_id' => $data['customer_id'],
//                        'object_id' => $id_order,
//                        'tenant_id' => session()->get('idTenant')
//                    ]);
//                    //Gửi thông báo nhân viên
//                    App\Jobs\FunctionSendNotify::dispatch([
//                        'type' => SEND_NOTIFY_STAFF,
//                        'key' => 'order_status_W',
//                        'customer_id' => $data['customer_id'],
//                        'object_id' => $id_order,
//                        'branch_id' => Auth()->user()->branch_id,
//                        'tenant_id' => session()->get('idTenant')
//                    ]);
//                    // Lưu log ZNS
//                    App\Jobs\FunctionSendNotify::dispatch([
//                        'type' => SEND_ZNS_CUSTOMER,
//                        'key' => 'order_success',
//                        'customer_id' => $data['customer_id'],
//                        'object_id' => $id_order,
//                        'tenant_id' => session()->get('idTenant')
//                    ]);
//                } catch (\Throwable $th) {
//
//                }

                return response()->json([
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                    'is_create_ticket' => $isCreateTicket,
                    'route' => route('fnb.orders.receipt',['id' => $id_order,'type' => 'orders']),
                    'error' => true,
                    'message' => __('Thêm thành công')
                ]);
            } else {
                // nếu có thì update
                if ($data['table_add'] == null) {
                    return response()->json([
                        'table_error' => 1
                    ]);
                }
                $mPromotionLog = new PromotionLogTable();
                $rOrderApp = app()->get(OrderAppRepoInterface::class);
                $mOrderLog = new OrderLogTable();

                $staff_branch = $staff->getItem(Auth::id());
                $mCustomerContact = app()->get(CustomerContactTable::class);

                $detailAddress = $mCustomerContact->getDetailContact($data['customer_contact_id']);

                $id_order = $data['order_id'];
                $orderCode = $data['order_code'];
                $data_order = [
                    'order_code' => $orderCode,
                    'customer_id' => $data['customer_id'],
                    'total' => $data['total_bill'],
                    'discount' => $data['discount_bill'],
                    'amount' => $data['amount_bill'],
                    'voucher_code' => $data['voucher_bill'],
//                    'order_description' => $data['order_description'],
                    'branch_id' => $staff_branch['branch_id'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
//                    'refer_id' => $data['refer_id'],
                    'discount_member' => $data['discount_member'],
                    'customer_contact_id' => $data['customer_contact_id'],
                    'customer_contact_code' => $detailAddress['customer_contact_code']
                ];

                $order->edit($data_order, $id_order);

                $arrPromotionLog = [];
                $arrQuota = [];
                $arrObjectBuy = [];

                if ($data['table_add'] != null) {
                    $orderDetail->remove($id_order);
//                    $aData = array_chunk($data['table_add'], 15, false);
                    $aData = $data['table_add'];
                    foreach ($aData as $key => $value) {
                        $value['price'] = str_replace(',', '', $value['price']);
                        $isChangePrice = isset($value['is_change_price']) ? $value['is_change_price'] : 0;
                        $isCheckPromotion = isset($value['is_check_promotion']) ? $value['is_check_promotion'] : 0;

                        if (in_array($value['object_type'], ['product', 'service', 'service_card']) && $isCheckPromotion == 1) {
                            $arrObjectBuy[] = [
                                'object_type' => $value['object_type'],
                                'object_code' => $value['object_code'],
                                'object_id' => $value['product_child_id'],
                                'price' => $value['price'],
                                'quantity' => $value['quantity'],
                                'customer_id' => $data['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $id_order,
                                'order_code' => $orderCode
                            ];
                        }
                        $data_order_detail = [
                            'order_id' => $id_order,
                            'object_id' => $value['product_child_id'],
                            'object_name' => $value['product_name'],
                            'object_type' => $value['object_type'],
                            'object_code' => $value['object_code'],
                            'price' => $value['price'],
                            'quantity' => $value['quantity'],
                            'discount' => str_replace(',', '', $value['discount']),
                            'voucher_code' => $value['voucher_code'],
                            'amount' => str_replace(',', '', $value['amount']),
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
//                            'refer_id' => $data['refer_id'],
                            'staff_id' => isset($data['staff_id']) ? $data['staff_id'] : null,
                            'is_change_price' => $isChangePrice,
                            'is_check_promotion' => $isCheckPromotion
                        ];
                        $orderDetail->add($data_order_detail);
                    }
                }

                if (!isset($data['custom_price']) && $data['custom_price'] == 0) {
                    //Lấy thông tin CTKM dc áp dụng cho đơn hàng
                    $getPromotionLog = $rOrderApp->groupQuantityObjectBuy($arrObjectBuy);
                    //Insert promotion log
                    $arrPromotionLog = $getPromotionLog['promotion_log'];
                    $mPromotionLog->insert($arrPromotionLog);
                    //Cộng quota_use promotion quà tặng
                    $arrQuota = $getPromotionLog['promotion_quota'];
                    $rOrderApp->plusQuotaUsePromotion($arrQuota);
                }

                $mConfig = app()->get(ConfigTable::class);
                //Kiểm tra có tạo ticket không
                $configCreateTicket = $mConfig->getInfoByKey('save_order_create_ticket')['value'];

                $isCreateTicket = 0;

                if ($data['customer_id'] != 1 && $configCreateTicket == 1) {
                    $isCreateTicket = 1;
                }

                DB::commit();

                return response()->json([
                    'order_code' => $orderCode,
                    'order_id' => $id_order,
                    'is_create_ticket' => $isCreateTicket,
                    'error' => true,
                    'message' => __('Thêm thành công'),
                    'data' => [
                        'order_code' => $orderCode,
                        'order_id' => $id_order,
                    ], //data for chathub
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getLine() . $e->getFile()
            ]);
        }
    }

    /**
     * lấy chi tiết
     * @param $id
     * @return mixed
     */
    public function getItemDetail($id)
    {
        $order = app()->get(OrderTable::class);
        return $order->getItemDetail($id);
    }

    /**
     * Export danh sách đơn hàng
     * @param array $params
     * @return mixed
     */
    public function exportList($params = [])
    {
        $filters = [
            'orders$order_source_id' => 1,
        ];
        $data = $this->listAll($filters);
        $list = $data['list'];
        $receipt = $data['receipt'];
        //Data export
        $arr_data = [];
        foreach ($list as $key => $item) {
            $amount = '0';
            $temp = 0;
            if (isset(config()->get('config.decimal_number')->value)) {
                $temp = config()->get('config.decimal_number')->value;
            }
            $amount = number_format($item['amount'], $temp);
            $rec = '0';
            if (isset($receipt[$item['order_id']])) {
                $rec = number_format($receipt[$item['order_id']]['amount_paid'], $temp);
            }
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
                if (isset($receipt[$item['order_id']])) {
                    $note = $receipt[$item['order_id']]['note'];
                }
            } elseif ($item['process_status'] == self::ORDER_CANCEL) {
                $note = $item['order_description'];
            }
            $arr_data[] = [
                $key + 1,
                $item['order_code'],
                $item['full_name_cus'],
                $item['full_name'],
                $amount,
                $rec,
                $item['order_source_name'],
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
            __('NGUỒN'),
            __('CHI NHÁNH'),
            __('TRẠNG THÁI'),
            __('GHI CHÚ'),
            __('NGÀY TẠO'),
        ];
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile($heading, $arr_data), 'order.xlsx');
    }

    /**
     * Danh sách đơn hàng
     *
     * @param array $filters
     * @return mixed
     */
    public function listAll(array $filters = [])
    {
        $order = app()->get(OrderTable::class);
        $list = $order->getAll($filters);

        //Data Receipt
        $mReceipt = new ReceiptTable();
        $listReceipt = $mReceipt->getAllReceipt();

        $arrReceipt = [];
        foreach ($listReceipt as $item) {
            $arrReceipt[$item['order_id']] = [
                'order_id' => $item['order_id'],
                'amount_paid' => $item['amount_paid'],
                'note' => $item['note']
            ];
        }
        return [
            'list' => $list,
            'receipt' => $arrReceipt,
        ];
    }

    /**
     * Xóa session lưu sản phẩm cần xóa
     * @param $data
     * @return mixed|void
     */
    public function removeSessionProduct($data)
    {
        try {
            if (session()->has('topping_product')){
                $session = session()->get('topping_product');

                if (isset($session[$data['product_id'].'_'.$data['key_string']])){
                    unset($session[$data['product_id'].'_'.$data['key_string']]);
                }
            }

            session()->forget('topping_product');
            session()->put('topping_product',$session);

            return [
                'error' => false,
                'message' => __('Xóa sản phẩm thành công')
            ];
        } catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa sản phẩm thất bại')
            ];
        }
    }

    /**
     * Lưu session table
     * @param $data
     * @return mixed|void
     */
    public function saveSessionTable($data)
    {
        if (session()->has('table_selected')){
            session()->forget('table_selected');
        }

        session()->put('table_selected',$data['tableId']);

//        lấy danh sách order chưa thanh toán theo bàn
        $view = $this->viewListOrderTable($data['tableId']);

        return [
            'error' => false,
            'view' => $view
        ];
    }

    /**
     * Render view danh sách đơn hàng theo bàn
     * @param $tableId
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     */
    public function viewListOrderTable($tableId, $orderId = 0){
        $mOrder = app()->get(OrderTable::class);
        $listOrder = $mOrder->getListOrderByTable($tableId);

        if (count($listOrder) != 0){
            $listOrder = collect($listOrder)->keyBy('order_id')->toArray();
        }

        $view = view('fnb::orders.inc.list-order-table', [
            'list' => $listOrder,
            'orderId' => $orderId
        ])->render();

        return $view;
    }

    /**
     * Xóa đơn hàng
     * @param $data
     * @return mixed|void
     */
    public function removeOrder($data)
    {
        try {

            $mOrder = app()->get(OrderTable::class);

//            Xóa đơn hàng
            $mOrder->remove($data['orderId']);

            $tableId = 0;
            if(session()->has('table_selected')){
                $tableId = session()->get('table_selected');
            }

//        lấy danh sách order chưa thanh toán theo bàn
            $view = $this->viewListOrderTable($tableId);

            return [
                'error' => false,
                'message' => __('Xóa đơn hàng thành công'),
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Xóa đơn hàng thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    public function edit($data, $id)
    {
        $mOrder = app()->get(OrderTable::class);
        return $mOrder->edit($data, $id);
    }

    /**
     * Tính hoa hồng cho nhân viên phục vụ theo ds nhân viên phục vụ
     * Kèm theo các thông tin cần thiết như commission theo object type và các thông tin của order detail
     *
     * @param $refer_id
     * @param null $check_commission
     * @param $id_detail
     * @param $object_id
     * @param null $item4
     * @param $item10
     * @param $item11
     * @param int $refer_money
     * @param int $staff_money
     * @param string $type
     */
    public function calculatedCommission(
        $quantity,
        $refer_id,
        $check_commission = null,
        $id_detail,
        $object_id,
        $item4 = null,
        $item10,
        $staffId,
        $refer_money = 0,
        $staff_money = 0,
        $type = ""
    ) {
        $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
        $mStaff = new StaffsTable();
        $mCustomer = new CustomerTable();
        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();
        $mStaff = app()->get(StaffsTable::class);
        $rOrderCommission = app()->get(OrderCommissionRepositoryInterface::class);

        $arrServiceStaff = [];
        //Lấy thông tin nhân viên
        $staff = $mStaff->getItem(Auth::id());

        // region tính hoa hồng cho người giới thiệu
        if ($type == "member_card") {
            $mCustomerServiceCard = new CustomerServiceCardTable();
            //Lấy thông tin hoa hồng khi sử dụng thẻ liệu trình
            $getCard = $mCustomerServiceCard->getCommissionMemberCard($item4);
            $refer_money = $getCard['refer_commission'] != null ? floatval($getCard['refer_commission']) : 0;
        }
        if ($check_commission != null) {
            if ($check_commission['type_refer_commission'] == 'money') {
                if (isset($check_commission['refer_commission_value'])) {
                    $refer_money = ($check_commission['refer_commission_value']);
                }
            } else {
                if (isset($check_commission['refer_commission_value'])) {
                    $refer_money = ($item10 / 100) * ($check_commission['refer_commission_value']);
                }
            }
        }

        //Tính tiền hoa hồng cho người giới thiệu
        if ($refer_money > 0 && $refer_id != null) {
            $refer_money = $refer_money * (int)$quantity;
            //Insert order commission
            $rOrderCommission->add([
                'order_detail_id' => $id_detail,
                'refer_id' => $refer_id,
                'refer_money' => $refer_money,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ]);

            if ($refer_money > 0 && isset($refer_id)) {
                //Lấy thông tin khách hàng
                $infoCustomer = $mCustomer->getItem($refer_id);
                //Cập nhật tổng tiền của KH
                $mCustomer->edit([
                    'total_commission' => $infoCustomer['total_commission'] + $refer_money
                ], $infoCustomer['customer_id']);
                //Lưu log + tiền
                $mBranchMoneyLog->add([
                    "customer_id" => $infoCustomer['customer_id'],
                    "branch_id" => Auth()->user()->branch_id,
                    "source" => "commission",
                    "type" => 'plus',
                    "money" => $refer_money,
                    "screen" => 'order',
                    // merge code thieu $orderCode
                    //"screen_object_code" => $orderCode
                ]);
            }
        }
        // end region refer money

        // region tính hoa hồng cho nhân viên phục vụ
        if ($staffId != '') {
//        if ($item11 != null && $item11 != '') {
//            foreach ($item11 as $staffId) {
            //Lấy tỉ lệ hoa hồng nv
            $getStaff = $mStaff->getCommissionStaff($staffId);
            $staffCommission = floatval(isset($getStaff) ? $getStaff['commission_rate'] : 0);
            if ($type == "member_card") {
                $mCustomerServiceCard = new CustomerServiceCardTable();
                //Lấy thông tin hoa hồng khi sử dụng thẻ liệu trình
                $getCard = $mCustomerServiceCard->getCommissionMemberCard($item4);
                $staff_money = $getCard['staff_commission'] != null ? floatval($getCard['staff_commission'] * $staffCommission) : 0;
            }
            if ($check_commission != null) {
                if ($check_commission['type_staff_commission'] == 'money') {
                    if (isset($check_commission['staff_commission_value'])) {
                        $staff_money = round($check_commission['staff_commission_value'] * $staffCommission, $decimal, PHP_ROUND_HALF_DOWN);
                    }
                } else {
                    if (isset($check_commission['staff_commission_value'])) {
                        $staffMoney = ($item10 / 100) * ($check_commission['staff_commission_value']);
                        $staff_money = round($staffMoney * $staffCommission, $decimal, PHP_ROUND_HALF_DOWN);
                    }
                }
            }
            if ($staff_money > 0) {
                if ($staffId != null) {
                    $staff_money = $staff_money * (int)$quantity;
                    $data_commission = [
                        'order_detail_id' => $id_detail,
                        'staff_id' => $staffId,
                        'staff_money' => $staff_money,
                        'staff_commission_rate' => $staffCommission,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];
                    //Insert order commission
                    $rOrderCommission->add($data_commission);
                }
            }
//            }
//        }
        }
        // end region
    }

    /**
     * Xóa đơn hàng cần giao
     *
     * @param $orderId
     * @return mixed|void
     */
    public function removeDelivery($orderId)
    {
        $mDelivery = new DeliveryTable();
        $mDeliveryHistory = new DeliveryHistoryTable();

        //Lấy thông tin giao hàng
        $info = $mDelivery->getInfo($orderId);

        if ($info != null) {
            //Xóa đơn hàng cần giao
            $mDelivery->edit([
                'delivery_status' => 'cancel'
            ], $orderId);
            //Xóa lịch sử giao hàng
            $mDeliveryHistory->removeAll($info['delivery_id']);
        }
    }

    /**
     * lấy danh sách serial theo id đơn hàng
     * @param $orderId
     * @return mixed|void
     */
    public function getListSerialOrder($orderId, $session)
    {
        $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
        $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
        $data = $mOrderDetailSerial->getListSerialByOrderIdNotOrderBy($orderId);

        $listSerial = [];

        foreach ($data as $item) {
            $listSerial[] = [
                'session' => $session,
                'position' => $item['order_detail_id'],
                'product_code' => $item['product_code'],
                'serial' => $item['serial'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
        }

        if (count($listSerial) != 0) {
            $mOrderSessionSerialLog->addListSerialLog($listSerial);
        }

        if (count($data) != 0) {
            $data = collect($data)->groupBy('order_detail_id');
        }

        return $data;
    }

    const GOODS = "goods";
    const RECEIPT = "receipt";

    /**
     * Update thông tin hàng hoá của hợp đồng
     *
     * @param $orderId
     * @param int $isPayment
     * @return mixed|void
     */
    public function updateContractGoods($orderId, $isPayment = 0)
    {
        $mOrder = app()->get(OrderTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);

        //Lấy thông tin đơn hàng
        $infoOrder = $mOrder->getOrderById($orderId);
        //Lấy thông tin chi tiết đơn hàng
        $orderDetail = $mOrderDetail->getItem($orderId);

        //Lấy thông tin đơn hàng map với hợp đồng
        $getContractMap = $mContractMapOrder->getContractMapOrder($infoOrder['order_code']);

        if ($getContractMap != null) {
            $mLog = app()->get(ContractLogTable::class);
            $mLogGoods = app()->get(ContractLogGoodsTable::class);
            $mContractGoods = app()->get(ContractGoodsTable::class);
            $mContract = app()->get(ContractTable::class);
            $mContractCategory = app()->get(ContractCategoriesTable::class);
            $mContractPayment = app()->get(ContractPaymentTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($getContractMap['contract_id']);
            //Lấy thông tin loại HĐ
            $infoCategory = $mContractCategory->getItem($infoContract['contract_category_id']);

            //Xoá hàng hoá cũ
            $mContractGoods->removeGoodsByContract($getContractMap['contract_id']);

            $totalTax = 0;

            if (count($orderDetail) > 0) {
                //Lưu log hợp đồng khi thêm hàng hoá
                $logId = $mLog->add([
                    "contract_id" => $getContractMap['contract_id'],
                    "change_object_type" => self::GOODS,
                    "note" => __('Thay đổi hàng hoá'),
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);

                foreach ($orderDetail as $v) {
                    //Lưu thông tin hàng hoá
                    $goodsId = $mContractGoods->add([
                        "contract_id" => $infoContract['contract_id'],
                        "object_type" => $v['object_type'],
                        "object_name" => $v['object_name'],
                        "object_id" => $v['object_id'],
                        "object_code" => $v['object_code'],
                        "price" => $v['price'],
                        "quantity" => $v['quantity'],
                        "discount" => $v['discount'],
                        "tax" => $v['tax'],
                        "amount" => $v['amount'],
                        "order_code" => $infoOrder['order_code'],
                        "staff_id" => $v['staff_id'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ]);

                    //Log detail
                    $mLogGoods->add([
                        "contract_log_id" => $logId,
                        "contract_godds_id" => $goodsId,
                        "object_type" => $v['object_type'],
                        "object_name" => $v['object_name'],
                        "object_id" => $v['object_id'],
                        "object_code" => $v['object_code'],
                        "price" => $v['price'],
                        "quantity" => $v['quantity'],
                        "discount" => $v['discount'],
                        "tax" => $v['tax'],
                        "amount" => $v['amount'],
                    ]);

                    $totalTax += $v['tax'];
                }
            }

            //Update giá trị hợp đồng (nếu là hđ bán, or là hđ mua có check lấy giá trị)
            if ($infoCategory['type'] == 'sell' || $infoContract['is_value_goods'] == 1) {
                $mContractPayment->edit([
                    'total_amount' => $infoOrder['total'],
                    'tax' => $totalTax,
                    'discount' => $infoOrder['discount'],
                    'last_total_amount' => $infoOrder['amount']
                ], $infoContract['contract_id']);
            }

            if ($isPayment == 1) {
                $mReceipt = app()->get(ReceiptTable::class);
                $mReceiptDetail = app()->get(ReceiptDetailTable::class);
                $mContractReceipt = app()->get(ContractReceiptTable::class);
                $mContractReceiptDetail = app()->get(ContractReceiptDetailTable::class);
                $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);

                //Lấy thông tin phiếu thu
                $getReceipt = $mReceipt->getReceiptByOrder($infoOrder['order_id']);

                if (count($getReceipt) > 0) {
                    foreach ($getReceipt as $v) {
                        //Thêm đợt thu
                        $contractReceiptId = $mContractReceipt->add([
                            'contract_id' => $infoContract['contract_id'],
                            'receipt_code' => $v['receipt_code'],
                            'content' => __("Thanh toán đơn hàng") . ' ' . $infoOrder['order_code'],
                            'collection_date' => Carbon::now()->format('Y-m-d'),
                            'collection_by' => $v['staff_id'],
                            'total_amount_receipt' => $v['amount_paid'],
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id()
                        ]);

                        //Lấy thông tin chi tiết thanh toán
                        $getReceiptDetail = $mReceiptDetail->getReceiptDetail($v['receipt_id']);

                        $arrReceiptDetail = [];

                        if (count($getReceiptDetail) > 0) {
                            foreach ($getReceiptDetail as $v1) {
                                $arrReceiptDetail[] = [
                                    "contract_receipt_id" => $contractReceiptId,
                                    "amount_receipt" => $v1['amount'],
                                    "payment_method_id" => $v1['payment_method_id'],
                                    "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                                    "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                                ];
                            }
                        }
                        //Thêm chi tiết đợt thu
                        $mContractReceiptDetail->insert($arrReceiptDetail);

                        //Lưu log hợp đồng khi trigger thu - chi
                        $logId = $mLog->add([
                            "contract_id" => $infoContract['contract_id'],
                            "change_object_type" => self::RECEIPT,
                            "note" => __('Thêm đợt thu'),
                            "created_by" => Auth()->id(),
                            "updated_by" => Auth()->id()
                        ]);
                        //Log detail
                        $mLogReceipt->add([
                            "contract_log_id" => $logId,
                            "object_type" => self::RECEIPT,
                            "object_id" => $contractReceiptId
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Lưu log dự kiến nhắc sử dụng
     *
     * @param $orderId
     * @param $customerId
     * @param $arrObject
     * @return mixed|void
     */
    public function insertRemindUse($orderId, $customerId, $arrObject)
    {
        $arrData = [];

        if (count($arrObject) > 0 && $customerId != 1) {
            foreach ($arrObject as $v) {
                $info = null;

                switch ($v['object_type']) {
                    case 'product':
                        $mProdudctChild = new ProductChildTable();
                        //Lấy thông tin sản phẩm
                        $info = $mProdudctChild->getItem($v['object_id']);
                        break;
                    case 'service':
                        $mService = new ServiceTable();
                        //Lấy thông tin dịch vụ
                        $info = $mService->getItem($v['object_id']);
                        break;
                    case 'service_card':
                        $mServiceCard = new ServiceCard();
                        //Lấy thông tin thẻ dịch vụ
                        $info = $mServiceCard->getServiceCardInfo($v['object_id']);
                        break;
                }

                if ($info != null && $info['is_remind'] == 1) {
                    $arrData[] = [
                        'customer_id' => $customerId,
                        'order_id' => $orderId,
                        'object_type' => $v['object_type'],
                        'object_code' => $v['object_code'],
                        'object_id' => $v['object_id'],
                        'object_name' => $v['object_name'],
                        'sent_at' => Carbon::now()->addDays(intval($info['remind_value']))->format('Y-m-d H:i:s'),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
        }

        $mCustomerRemind = new CustomerRemindUseTable();
        //Lưu log dự kiến nhắc sử dụng
        $mCustomerRemind->insert($arrData);
    }

    /**
     * Hiển thị popup chọn bàn
     * @param $data
     * @return mixed|void
     */
    public function popupSelectTable($data)
    {
        try {

            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $rOrderDetail = app()->get(OrderDetailRepositoryInterface::class);
            $mArea = app()->get(FNBAreasTable::class);

            $orderInfo = $mOrder->getItemDetail($data['orderId']);
            $listProduct = $mOrderDetail->getListByOrderId($data['orderId']);
            $listProductAll = $rOrderDetail->getItem($data['orderId']);

//            Lấy danh sách khu vực
            $listArea = $mArea->getAllAreas();
            $productParent = [];
            $productChild = [];
            if($data['type'] == 'merge-table'){
                $linkView = 'fnb::orders.popup.merge-table';
            } else if ($data['type'] == 'merge-bill') { // merge-bill
                $linkView = 'fnb::orders.popup.merge-bill';
            } else if ($data['type'] == 'move-table') { // move-table
                $linkView = 'fnb::orders.popup.move-table';
            }  else { // move-table
                $linkView = 'fnb::orders.popup.split-table';
                foreach ($listProductAll as $item){
                    if (!isset($item['order_detail_id_parent'])){
                        $productParent[] = [
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
                            'name_attribute' => isset($item['name_attribute'])? $item['name_attribute'] : []
                        ];
                    } else {
                        $productChild[$item['order_detail_id_parent']][] = [
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
                        ];
                    }
                }
            }

            $view = view($linkView,[
                'item' => $orderInfo,
                'listProduct' => $listProduct,
                'productParent' => $productParent,
                'productChild' => $productChild,
                'listArea' => $listArea
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại')
            ];
        }
    }

    /**
     * Thay đổi khu vực
     * @param $data
     * @return mixed|void
     */
    public function changeArea($data)
    {
        try {
            $mTable = app()->get(FNBTableTable::class);

            if (!isset($data['area_id'])){
                $data['area_id'] = -1;
            }

            if(isset($data['table_id'])){
                $data['un_table_id'] = $data['table_id'];
            }

            if ($data['type'] == 'merge-table'){
                $linkView = 'fnb::orders.inc.option-table';
                $listTable = $mTable->getListTableByArea($data);
            } elseif ($data['type'] == 'move-table') { //move-table
                $linkView = 'fnb::orders.inc.list-table';
                $listTable = $mTable->getListTableByAreaPagination($data);
            } else {
                $linkView = 'fnb::orders.inc.option-table';
                $listTable = $mTable->getListTableByArea($data);
            }

            $view = view($linkView,[
                'listTable' => $listTable
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách thất bại')
            ];
        }
    }

    /**
     * Tìm kiếm bàn muốn thay đổi
     * @param $data
     * @return mixed|void
     */
    public function searchOrder($data)
    {
        try {

            $mOrder = app()->get(OrderTable::class);

            $listOrder = $mOrder->getListOrder($data);

            $view = view('fnb::orders.inc.list-order-search',[
                'listOrder' => $listOrder
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách thất bại')
            ];
        }
    }

    /**
     * Lưu thông tin gộp bàn
     * Gộp đơn hàng từ bàn cũ sang bàn mới
     * Note lại thông tin bàn cũ "Gộp bàn"
     * @param $data
     * @return mixed|void
     */
    public function submitMergeTable($data)
    {
        DB::beginTransaction();
        try {

            $mOrder = app()->get(OrderTable::class);

//            Lấy thông tin bàn hiện tại
            $orderOld = $mOrder->getInfoOrder($data['order_old']);
//            Lấy thông tin bàn mới
            $orderNew = $mOrder->getInfoOrder($data['order_new']);

            $idOrder = $this->handleOrder($orderOld,$orderNew,'merge-table');
            DB::commit();
            return [
                'error' => false,
                'message' => __('Gộp bàn thành công'),
                'route' => route('fnb.orders.receipt',['id' => $idOrder,'type' => 'order'])
            ];
        }catch (Exception $e){
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Gộp bàn thất bại')
            ];
        }
    }

    /**
     * Lưu thông tin gộp bill
     * Gộp bill từ bàn cũ sang bàn mới
     * Note lại thông tin bàn cũ "Gộp bill"
     * @param $data
     * @return mixed|void
     */
    public function submitMergeBill($data)
    {
        DB::beginTransaction();
        try {

            $mOrder = app()->get(OrderTable::class);

//            Lấy thông tin bàn hiện tại
            $orderOld = $mOrder->getInfoOrder($data['order_old']);
            $orderOld = collect($orderOld)->toArray();
//            Lấy thông tin bàn mới
            $orderNew = $mOrder->getInfoOrder($data['order_new']);
            $orderNew = collect($orderNew)->toArray();

            $idOrder = $this->handleOrder($orderOld,$orderNew,'merge-bill');
            DB::commit();
            return [
                'error' => false,
                'message' => __('Gộp bill thành công'),
                'route' => route('fnb.orders.receipt',['id' => $idOrder,'type' => 'order'])
            ];
        }catch (Exception $e){
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Gộp bill thất bại')
            ];
        }
    }

    /**
     * Lưu thông tin chuyển bàn
     * Chuyển đơn hàng từ bàn cũ sang bàn mới
     * Note lại thông tin bàn cũ "Chuyển bàn"
     * @param $data
     * @return mixed|void
     */
    public function submitMoveTable($data)
    {
        DB::beginTransaction();
        try {

            $mOrder = app()->get(OrderTable::class);

//            Lấy thông tin bàn hiện tại
            $orderOld = $mOrder->getInfoOrder($data['order_old']);
            $orderOld['fnb_table_id'] = $data['table_new'];

            $idOrder = $this->handleOrder($orderOld,[],'move-table');
            DB::commit();
            return [
                'error' => false,
                'message' => __('Di chuyển bàn thành công'),
                'route' => route('fnb.orders.receipt',['id' => $idOrder,'type' => 'order'])
            ];
        }catch (Exception $e){
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Di chuyển bàn thất bại')
            ];
        }
    }

    /**
     * Lưu thông tin tách bàn
     * Chuyển đơn hàng từ bàn cũ sang bàn mới
     * Note lại thông tin bàn cũ "Tách bàn"
     * @param $data
     * @return mixed|void
     */
    public function submitSplitTable($data)
    {
        DB::beginTransaction();
        try {

            $mOrder = app()->get(OrderTable::class);
            $orderNew = [];
//            Lấy thông tin bàn hiện tại
            $orderOld = $mOrder->getInfoOrder($data['order_old']);
            $orderOld = collect($orderOld)->toArray();
            $orderNew = $orderOld;
            $orderNew['fnb_table_id'] = $data['table_new'];

            $idOrder = $this->handleOrderSplit($orderOld,$orderNew,$data['list'],'split-table');
            DB::commit();
            return [
                'error' => false,
                'message' => __('Tách bàn thành công'),
                'route' => route('fnb.orders.receipt',['id' => $idOrder,'type' => 'order'])
            ];
        }catch (Exception $e){
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Tách bàn thất bại')
            ];
        }
    }

    /**
     * Xử lý bàn
     */
    public function handleOrder($orderOld , $orderNew , $type){

        try {
            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);

            $dataOrder = []; //Data đơn hàng sẽ chuyển ,tách , ....
            $dataChildOrder = []; // Danh sách sản phẩm chi tiết
            $note = '';
            $orderOldId = $orderOld['order_id'];

            $orderNewId = isset($orderNew['order_id']) ? $orderNew['order_id'] : '';

            switch ($type) {
                case 'merge-table':
                    $note = __('Gộp bàn');
                    $dataOrder = $orderOld;
                    unset($dataOrder['order_id']);
                    $dataOrder['fnb_table_id'] = $orderNew['fnb_table_id'];
                    break;

                case 'move-table':
                    $note = __('Di chuyển bàn');
                    $dataOrder = $orderOld;
                    unset($dataOrder['order_id']);
                    break;

                case 'merge-bill':
                    $note = __('Gộp bill');
//                Loại gộp bill sẽ tạo giá trị đơn hàng được merge đến + thêm số tiền từ đơn hàng cũ
                    $dataOrder = $orderNew;
                    $dataOrder['total'] = (double)$dataOrder['total'] + (double)$orderOld['total'];
                    $dataOrder['discount'] = (double)$dataOrder['discount'] + (double)$orderOld['discount'];
                    $dataOrder['amount'] = (double)$dataOrder['amount'] + (double)$orderOld['amount'];
                    $dataOrder['process_status'] = 'new';
                    $dataOrder['updated_at'] = Carbon::now();
                    $dataOrder['updated_by'] = Auth::id();

                    unset($dataOrder['order_id']);

                    $mOrder->edit([
                        'order_description' => $note,
                        'process_status' => 'ordercancle',
                        'is_deleted' => 1
                    ], $orderNew['order_id']);

                    break;
                default:
                    break;
            }

            $dataOrder['updated_at'] = Carbon::now();
            $dataOrder['updated_by'] = Auth::id();

            if ($type == 'merge-bill'){
                $dataChildOrder = $mOrderDetail->getListFullByOrderArrId([$orderOldId,$orderNewId]);
            } else {
                $dataChildOrder = $mOrderDetail->getListFullByOrderId($orderOldId);
            }

            //        Tạo đơn hàng mới
            $idOrder = $mOrder->add(collect($dataOrder)->toArray());

//        Cập nhật thông tin đơn hàng cũ
            $mOrder->edit([
                'order_description' => $note,
                'process_status' => 'ordercancle',
                'is_deleted' => 1
            ], $orderOldId);

            $day_code = date('dmY');

            $orderCode = 'DH_' . $day_code . $idOrder;

//        Cập nhật đơn hàng
            $mOrder->edit([
                'order_code' => $orderCode
            ], $idOrder);

            foreach ($dataChildOrder as $item){
                if (!isset($item['order_detail_id_parent'])){
                    $order_detail_id_old = $item['order_detail_id'];
                    unset($item['order_detail_id']);
                    $item['order_id'] = $idOrder;
                    $item['updated_at'] = Carbon::now();
                    $item['updated_by'] = Auth::id();

                    $orderDetailId = $mOrderDetail->add(collect($item)->toArray());

                    $tmpOrderChild = collect($dataChildOrder)->where('order_detail_id_parent',$order_detail_id_old);

                    $tmpChild = [];
                    foreach ($tmpOrderChild as $keyChild => $itemChild){
                        $tmpChild[$keyChild] = collect($itemChild)->toArray();
                        unset($tmpChild[$keyChild]['order_detail_id']);
                        $tmpChild[$keyChild]['order_id'] = $idOrder;
                        $tmpChild[$keyChild]['order_detail_id_parent'] = $orderDetailId;
                        $tmpChild[$keyChild]['updated_at'] = Carbon::now();
                        $tmpChild[$keyChild]['updated_by'] = Auth::id();
                    }

                    if (count($tmpChild) != 0){
                        $mOrderDetail->insertArr($tmpChild);
                    }

                }
            }

            return $idOrder;
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * Xử lý bàn tách bàn , gộp bill
     */
    public function handleOrderSplit($orderOld , $orderNew , $listChild , $type){

        $mOrder = app()->get(OrderTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);

        $note = '';
        $dataChildOrder = []; // Danh sách sản phẩm chi tiết
        $amountNew = 0; // Tổng tiền đơn hàng cần tách
        $amountOld = 0; // Tổng tiền đơn hàng cũ sau khi tách
        $discount = 0;

        $orderOldId = $orderOld['order_id'];
        unset($orderOld['order_id']);
        unset($orderNew['order_id']);

        $orderOld['updated_at'] = Carbon::now();
        $orderOld['updated_by'] = Auth::id();

        $orderNew['updated_at'] = Carbon::now();
        $orderNew['updated_by'] = Auth::id();

        $dataChildOrder = $mOrderDetail->getListFullByOrderId($orderOldId);

        if (count($dataChildOrder) != 0){
            $dataChildOrder = collect($dataChildOrder)->keyBy('order_detail_id')->toArray();
        }

        switch ($type) {
            case 'split-table' :
                $note = __('Tách bàn');
                break;
        }

//        Cập nhật đơn hàng cũ
        $mOrder->edit([
            'order_description' => $note,
            'process_status' => 'ordercancle',
            'is_deleted' => 1
        ], $orderOldId);

        $day_code = date('dmY');

        //        Tạo đơn hàng mới
        $idOrderNew = $mOrder->add(collect($orderNew)->toArray());

        $idOrderOld = $mOrder->add(collect($orderOld)->toArray());

        foreach ($listChild as $item){
            $dataTmpNew = [];
            $dataTmpOld = [];
//                Xử lý đơn hàng cần tách
            if ($item['quantity'] != 0){
                $dataTmpNew = $dataChildOrder[$item['order_detail_id']];
                unset($dataTmpNew['order_detail_id']);
                $dataTmpNew['order_id'] = $idOrderNew;
                $dataTmpNew['amount'] = (double)$item['quantity'] * (double)$dataTmpNew['price'];
                $dataTmpNew['quantity'] = $item['quantity'];
                $dataTmpNew['discount'] = 0;
                $dataTmpNew['voucher_code'] = '';
                $dataTmpNew['updated_at'] = Carbon::now();
                $dataTmpNew['updated_by'] = Auth::id();

                $amountNew += (double)$dataTmpNew['amount'];

                $orderDetailId = $mOrderDetail->add(collect($dataTmpNew)->toArray());

                $tmpOrderChild = collect($dataChildOrder)->where('order_detail_id_parent',$item['order_detail_id']);
                $tmpChildNew = [];
                foreach ($tmpOrderChild as $keyChild => $itemChild){
                    $tmpChildNew[$keyChild] = $itemChild;

                    unset($tmpChildNew[$keyChild]['order_detail_id']);
                    $tmpChildNew[$keyChild]['order_id'] = $idOrderNew;
                    $tmpChildNew[$keyChild]['order_detail_id_parent'] = $orderDetailId;
                    $tmpChildNew[$keyChild]['updated_at'] = Carbon::now();
                    $tmpChildNew[$keyChild]['updated_by'] = Auth::id();
                }

                if (count($tmpChildNew) != 0){
                    $mOrderDetail->insertArr(collect($tmpChildNew)->toArray());
                }
            }

            //            Xử lý đơn hàng cũ với số lượng còn lại
            if ($dataChildOrder[$item['order_detail_id']]['quantity'] - $item['quantity'] != 0) {
                $dataTmpOld = $dataChildOrder[$item['order_detail_id']];
                unset($dataTmpOld['order_detail_id']);
                $dataTmpOld['order_id'] = $idOrderOld;
                $dataTmpOld['quantity'] = (double)$dataChildOrder[$item['order_detail_id']]['quantity'] - (double)$item['quantity'];
                $dataTmpOld['amount'] = (double)$dataTmpOld['quantity'] * (double)$dataTmpOld['price'] - (double)$dataTmpOld['discount'];
                $dataTmpOld['updated_at'] = Carbon::now();
                $dataTmpOld['updated_by'] = Auth::id();

                $amountOld += (double)$dataTmpOld['amount'];

                $orderDetailIdOld = $mOrderDetail->add(collect($dataTmpOld)->toArray());

                $tmpOrderChildOld = collect($dataChildOrder)->where('order_detail_id_parent',$item['order_detail_id']);
                $tmpChildOld = [];
                foreach ($tmpOrderChildOld as $keyChild => $itemChild){
                    $tmpChildOld[$keyChild] = $itemChild;

                    unset($tmpChildOld[$keyChild]['order_detail_id']);
                    $tmpChildOld[$keyChild]['order_id'] = $idOrderOld;
                    $tmpChildOld[$keyChild]['order_detail_id_parent'] = $orderDetailIdOld;
                    $tmpChildOld[$keyChild]['updated_at'] = Carbon::now();
                    $tmpChildOld[$keyChild]['updated_by'] = Auth::id();
                }

                if (count($tmpChildOld) != 0){
                    $mOrderDetail->insertArr(collect($tmpChildOld)->toArray());
                }
            }

        }

//        Nếu tổng tiền != 0 thì update , khác 0 thì remove order và order_detail
        $mOrder->edit([
            'discount' => 0,
            'voucher_code' => '',
            'amount' => $amountNew,
            'total' => $amountNew,
            'process_status' => 'new',
            'order_code' => 'DH_' . $day_code . $idOrderNew
        ], $idOrderNew);

        if($amountOld != 0){
            $mOrder->edit([
                'amount' => $amountOld - (double)$orderOld['discount'],
                'total' => $amountOld,
                'process_status' => 'new',
                'order_code' => 'DH_' . $day_code . $idOrderOld
            ], $idOrderOld);
        } else {
            $mOrder->removeByOrderId($idOrderOld);
            $mOrderDetail->removeByOrderId($idOrderOld);
        }


        return $idOrderNew;
    }

    /**
     * Hiển thị popup đơn hàng cần in
     * @param $data
     * @return mixed|void
     */
    public function showPopupOrderTable($data)
    {
        try {

            $mOrder = app()->get(OrderTable::class);
            $listOrder = $mOrder->getListOrderByTable($data['tableId']);

            $view = view('fnb::orders.popup.popup-order-table', [
                'list' => $listOrder,
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Hiển thị popup danh sách yêu cầu của khách hàng
     * @param $data
     * @return mixed|void
     */
    public function showPopupCustomerRequest($data)
    {
        try {

            $mCustomerRequest = app()->get(FNBCustomerRequestTable::class);
            $listRequest = $mCustomerRequest->getList($data);

            $view = view('fnb::orders.popup.popup-customer-request', [
                'list' => $listRequest,
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Thay đổi thông tin địa chỉ giao hàng
     * @param $data
     * @return mixed|void
     */
    public function changeInfoAddress($data)
    {
        try {
            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);
            $detailAddress = null;
            if (isset($data['customer_contact_id'])) {
                $detailAddress = $mCustomerContact->getDetail($data['customer_contact_id']);
            }

            if (isset($data['customer_id'])) {
                $detailAddress = $mCustomerContact->getDetailCustomer($data['customer_id']);
            }

            $itemFee = null;
            if ($detailAddress != null) {
                $mDeliveryCostDetail = app()->get(DeliveryCostDetailTable::class);
                $mDeliveryCost = app()->get(DeliveryCostTable::class);
                $itemFee = $mDeliveryCostDetail->checkAddress($detailAddress['province_id'], $detailAddress['district_id']);
                if ($itemFee == null) {
                    $itemFee = $mDeliveryCost->checkAddressDefault();
                }
            }

            $view = view('fnb::orders.inc.block-address', [
                'detailAddress' => $detailAddress,
                'data' => $data,
                'itemFee' => $itemFee
            ])->render();


            return [
                'error' => false,
                'message' => __('Thay đổi địa chỉ giao hàng thành công'),
                'view' => $view,
                'customer_contact_id' => $detailAddress != null ? $detailAddress['customer_contact_id'] : ''
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi địa chỉ giao hàng thất bại'),
                '__message' => $e->getMessage()
            ];
        }
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
    public function addWarrantyCard($customerCode, $orderId, $orderCode, $dataTableAdd, $dataTableEdit = null)
    {
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();

        // get array object
        if ($dataTableAdd != null) {
            $arrObject = array_chunk($dataTableAdd, 15, false);
            if ($arrObject != null && count($arrObject) > 0) {
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

}