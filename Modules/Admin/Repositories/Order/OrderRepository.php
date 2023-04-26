<?php

/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/20/2018
 * Time: 10:20 PM
 */

namespace Modules\Admin\Repositories\Order;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Http\Api\PaymentOnline;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\ContractCategoriesTable;
use Modules\Admin\Models\ContractGoodsTable;
use Modules\Admin\Models\ContractLogGoodsTable;
use Modules\Admin\Models\ContractLogReceiptSpendTable;
use Modules\Admin\Models\ContractLogTable;
use Modules\Admin\Models\ContractPaymentTable;
use Modules\Admin\Models\ContractReceiptDetailTable;
use Modules\Admin\Models\ContractReceiptTable;
use Modules\Admin\Models\ContractTable;
use Modules\Admin\Models\CustomerBranchMoneyLogTable;
use Modules\Admin\Models\CustomerContactTable;
use Modules\Admin\Models\CustomerRemindUseTable;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\DeliveryCustomerAddressTable;
use Modules\Admin\Models\DeliveryDistrictTable;
use Modules\Admin\Models\DeliveryHistoryTable;
use Modules\Admin\Models\DeliveryProvinceTable;
use Modules\Admin\Models\DeliveryTable;
use Modules\Admin\Models\DeliveryWardTable;
use Modules\Admin\Models\DistrictTable;
use Modules\Admin\Models\OrderDetailSerialTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\OrderImageTable;
use Modules\Admin\Models\OrderSessionSerialTable;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\ProductCategoryTable;
use Modules\Admin\Models\ProductChildTable;
use Modules\Admin\Models\ProductInventorySerialTable;
use Modules\Admin\Models\PromotionDetailTable;
use Modules\Admin\Models\PromotionObjectApplyTable;
use Modules\Admin\Models\ProvinceTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptOnlineTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\PromotionDailyTimeTable;
use Modules\Admin\Models\PromotionDateTimeTable;
use Modules\Admin\Models\PromotionMonthlyTimeTable;
use Modules\Admin\Models\PromotionWeeklyTimeTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceCategoryTable;
use Modules\Admin\Models\ServiceMaterialTable;
use Modules\Admin\Models\ServiceTable;
use Modules\Admin\Models\StaffsTable;
use Modules\Admin\Models\Voucher;
use Modules\Admin\Models\WardTable;
use Modules\Admin\Models\WarrantyCardTable;
use Modules\Admin\Models\WarrantyPackageDetailTable;
use Modules\Admin\Models\WarrantyPackageTable;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\Admin\Repositories\OrderCommission\OrderCommissionRepositoryInterface;
use Modules\Admin\Repositories\Service\ServiceRepositoryInterface;
use Modules\Admin\Repositories\Staffs\StaffRepositoryInterface;
use Modules\Admin\Models\ContractMapOrderTable;
use Modules\Delivery\Models\DeliveryCostDetailTable;
use Modules\Delivery\Models\DeliveryCostMapMethodTable;
use Modules\Delivery\Models\DeliveryCostTable;
use Modules\Warranty\Models\ServiceCardGroupTable;

class OrderRepository implements OrderRepositoryInterface
{
    private $order;
    protected $order_commission;
    protected $customer_branch_money;
    protected $staff;
    protected $service;

    const NEW = 'new';
    const PAYSUCCESS = 'paysuccess';
    const PAY_HALF = 'pay-half';
    const ORDER_CANCEL = 'ordercancle';
    const CONFIRMED = 'confirmed';

    /**
     * OrderRepository constructor.
     * @param OrderTable $orders
     * @param StaffRepositoryInterface $staffs
     * @param ServiceRepositoryInterface $services
     * @param OrderCommissionRepositoryInterface $order_commission
     * @param CustomerBranchMoneyRepositoryInterface $customer_branch_moneys
     */
    public function __construct(
        OrderTable $orders,
        StaffRepositoryInterface $staffs,
        ServiceRepositoryInterface $services,
        OrderCommissionRepositoryInterface $order_commission,
        CustomerBranchMoneyRepositoryInterface $customer_branch_moneys
    ) {
        $this->order = $orders;
        $this->staff = $staffs;
        $this->order_commission = $order_commission;
        $this->service = $services;
        $this->customer_branch_money = $customer_branch_moneys;
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
        $item11,
        $refer_money = 0,
        $staff_money = 0,
        $type = ""
    ) {
        $decimal = isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0;
        $mStaff = new StaffsTable();
        $mCustomer = new CustomerTable();
        $mBranchMoneyLog = new CustomerBranchMoneyLogTable();

        $arrServiceStaff = [];
        //Lấy thông tin nhân viên
        $staff = $this->staff->getItem(Auth::id());

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
            $this->order_commission->add([
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
        if ($item11 != null && $item11 != '') {
            foreach ($item11 as $staffId) {
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
                        $this->order_commission->add($data_commission);
                    }
                }
            }
        }
        // end region
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
        $list = $this->order->getList($filters);

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

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->order->add($data);
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function getItemDetail($id)
    {
        return $this->order->getItemDetail($id);
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function getItemDetailPrint($id)
    {
        return $this->order->getItemDetailPrint($id);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->order->edit($data, $id);
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function remove($id)
    {
        $this->order->remove($id);
    }

    public function detailDayCustomer($id)
    {
        // TODO: Implement detailCustomer() method.
        return $this->order->detailDayCustomer($id);
    }

    public function detailCustomer($id)
    {
        return $this->order->detailCustomer($id);
    }

    public function getIndexReportRevenue()
    {
        $select = $this->order->getIndexReportRevenue();
        return $select;
    }

    public function getValueByYear($year, $startTime = null, $endTime = null)
    {
        return $this->order->getValueByYear($year, $startTime, $endTime);
    }

    public function getValueByDate($date, $field = null, $valueField = null, $field2 = null, $valueField2 = null)
    {
        $result = 0;
        $select = $this->order->getValueByDate($date, $field, $valueField, $field2, $valueField2);
        if ($select[0]['total'] != null) {
            $result = $select[0]['total'];
        }
        return $result;
    }

    //Lấy dữ liệu với tham số truyền vào(thời gian, cột)
    public function getValueByParameter($date, $filer, $valueFilter)
    {
        $result = 0;
        $select = $this->order->getValueByParameter($date, $filer, $valueFilter);
        if ($select[0]['total'] != null) {
            $result = $select[0]['total'];
        }
        return $result;
    }

    //Lấy giá trị từ ngày - đến ngày.
    public function getValueByDay($startTime, $endTime)
    {
        $result = 0;
        $select = $this->order->getValueByDay($startTime, $endTime);
        if ($select[0]['total'] != null) {
            $result = $select[0]['total'];
        }
        return $result;
    }

    //Lấy dữ liệu với tham số truyền vào(thời gian, cột) 2
    public function getValueByParameter2($startTime, $endTime, $filer, $valueFilter)
    {
        return $this->order->getValueByParameter2($startTime, $endTime, $filer, $valueFilter);
    }

    //Lấy giá trị theo năm, cột và giá trị cột truyền vào
    public function fetchValueByParameter($year, $startTime, $endTime, $field, $fieldValue)
    {
        return $this->order->fetchValueByParameter($year, $startTime, $endTime, $field, $fieldValue);
    }

    //Lấy giá trị theo năm, cột và giá trị 2 cột truyền vào
    public function fetchValueByParameter2($year, $startTime, $endTime, $field, $fieldValue, $field2, $fieldValue2)
    {
        return $this->order->fetchValueByParameter2($year, $startTime, $endTime, $field, $fieldValue, $field2, $fieldValue2);
    }


    public function getValueByDate2($date, $branch, $customer)
    {
        $result = 0;
        $select = $this->order->getValueByDate2($date, $branch, $customer);
        if ($select != null) {
            foreach ($select as $key => $value) {
                $result += $value['amount'];
            }
        }
        return $result;
    }

    //Lấy các giá trị theo created_at, branch_id và created_by
    public function getValueByDate3($date, $branch, $staff)
    {
        $result = 0;
        $select = $this->order->getValueByDate3($date, $branch, $staff);
        if ($select != null) {
            foreach ($select as $key => $value) {
                $result += $value['amount'];
            }
        }
        return $result;
    }

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng.
    public function getDataReportGrowthByCustomer($year, $month, $operator, $customerOdd, $field = null, $valueField = null)
    {
        return $this->order->getDataReportGrowthByCustomer($year, $month, $operator, $customerOdd, $field, $valueField);
    }

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng theo năm.
    public function getDataReportGrowthCustomerByYear($year, $operator, $customerOdd, $branch)
    {
        return $this->order->getDataReportGrowthCustomerByYear($year, $operator, $customerOdd, $branch);
    }

    //Thống kê tăng trưởng khách hàng(theo nhóm khách hàng).
    public function getValueReportGrowthByCustomerCustomerGroup($year, $branch = null)
    {
        return $this->order->getValueReportGrowthByCustomerCustomerGroup($year, $branch);
    }

    //Thống kê tăng trưởng khách hàng(theo nguồn khách hàng).
    public function getValueReportGrowthByCustomerCustomerSource($year, $branch = null)
    {
        return $this->order->getValueReportGrowthByCustomerCustomerSource($year, $branch);
    }

    //Thống kê tăng trưởng khách hàng(theo giới tính).
    public function getValueReportGrowthByCustomerCustomerGender($year, $branch = null)
    {
        return $this->order->getValueReportGrowthByCustomerCustomerGender($year, $branch);
    }

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng(từ ngày đến ngày và/hoặc chi nhánh).
    public function getDataReportGrowthByCustomerDataBranch($startTime, $endTime, $operator, $customerOdd, $branch)
    {
        return $this->order->getDataReportGrowthByCustomerDataBranch($startTime, $endTime, $operator, $customerOdd, $branch);
    }

    //Thống kê tăng trưởng khách hàng(theo nhóm khách hàng) theo từ ngày đến ngày và/hoặc chi nhánh.
    public function getValueReportGrowthByCustomerCustomerGroupTimeBranch($startTime, $endTime, $branch = null)
    {
        return $this->order->getValueReportGrowthByCustomerCustomerGroupTimeBranch($startTime, $endTime, $branch);
    }

    //Thống kê tăng trưởng khách hàng(theo giới tính) theo từ ngày đến ngày và/hoặc chi nhánh.
    public function getValueReportGrowthByCustomerCustomerGenderTimeBranch($startTime, $endTime, $branch)
    {
        return $this->order->getValueReportGrowthByCustomerCustomerGenderTimeBranch($startTime, $endTime, $branch);
    }

    //Thống kê tăng trưởng khách hàng(theo nguồn khách hàng) theo từ ngày tới ngày và/hoặc chi nhánh.
    public function getValueReportGrowthByCustomerCustomerSourceTimeBranch($startTime, $endTime, $branch)
    {
        return $this->order->getValueReportGrowthByCustomerCustomerSourceTimeBranch($startTime, $endTime, $branch);
    }

    //Lấy dữ liệu theo năm/từ ngày đến ngày và chi nhánh
    public function getValueByYearAndBranch($year, $branch, $startTime = null, $endTime = null)
    {
        return $this->order->getValueByYearAndBranch($year, $branch, $startTime, $endTime);
    }

    //Lấy danh sách khách hàng cho tăng trưởng khách hàng theo năm cho từng chi nhánh.
    public function getDataReportGrowthCustomerByTime($startTime, $endTime, $operator, $customerOdd, $branch)
    {
        return $this->order->getDataReportGrowthCustomerByTime($startTime, $endTime, $operator, $customerOdd, $branch);
    }

    public function searchDashboard($keyword)
    {
        return $this->order->searchDashboard($keyword);
    }

    public function getAllByCondition($startTime, $endTime, $branch)
    {
        return $this->order->getAllByCondition($startTime, $endTime, $branch);
    }

    public function getCustomerDetail($id)
    {
        return $this->order->getCustomerDetail($id);
    }

    public function getValueByParameter3($startTime, $endTime, $filer, $valueFilter)
    {
        return $this->order->getValueByParameter3($startTime, $endTime, $filer, $valueFilter);
    }

    public function getValueByParameter4($startTime, $endTime, $filer, $valueFilter, $customerGroup = null)
    {
        return $this->order->getValueByParameter4($startTime, $endTime, $filer, $valueFilter, $customerGroup);
    }

    public function getValueByYear2($year, $startTime = null, $endTime = null)
    {
        return $this->order->getValueByYear2($year, $startTime, $endTime);
    }

    //Lấy giá trị theo năm, cột và giá trị cột truyền vào. Lấy tiền thanh toán trong receipt.
    public function fetchValueByParameter3(
        $year,
        $startTime,
        $endTime,
        $field,
        $fieldValue
    ) {
        return $this->order->fetchValueByParameter3(
            $year,
            $startTime,
            $endTime,
            $field,
            $fieldValue
        );
    }

    /**
     * Chuyển tiếp chi nhánh
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function applyBranch($input)
    {
        try {
            $this->order->edit([
                'branch_id' => $input['branch_id'],
                'is_apply' => 1
            ], $input['order_id']);

            return response()->json([
                'error' => false,
                'message' => __('Chuyển tiếp thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chuyển tiếp thất bại')
            ]);
        }
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
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

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
            //    var_dump($dataTableAdd, $dataTableEdit); die;
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();

        try {
            if ($dataTableAdd != null) {
                // $arrObject = array_chunk($dataTableAdd, 15, false);
                $arrObject =$dataTableAdd;
                if ($arrObject != null && count($arrObject) > 0) {
                    foreach ($arrObject as $item) {
                        // value item
                        // var_dump($item['object_id']);
                        // $objectId = isset($item[0]) ? $item[0] : 0;
                        // $objectType = isset($item[2]) ? $item[2] : null;
                        // $objectCode = isset($item[3]) ? $item[3] : null;
                        // $objectPrice = isset($item[4]) ? $item[4] : 0;
                        // $objectQuantity = isset($item[5]) ? $item[5] : 1;
                        $objectId = isset($item['object_id']) ? $item['object_id'] : 0;
                        $objectType = isset($item['object_type']) ? $item['object_type'] : null;
                        $objectCode = isset($item['object_code']) ? $item['object_code'] : null;
                        $objectPrice = isset($item['price']) ? $item['price'] : 0;
                        $objectQuantity = isset($item['quantity']) ? $item['quantity'] : 1;
                       
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
                // $arrObject = array_chunk($dataTableEdit, 16, false);
                $arrObject = $dataTableEdit;
                if ($arrObject != null && count($arrObject) > 0) {
                    foreach ($arrObject as $item) {
                        // value item
                        // $objectId = isset($item[1]) ? $item[1] : 0;
                        // $objectType = isset($item[3]) ? $item[3] : null;
                        // $objectCode = isset($item[4]) ? $item[4] : null;
                        // $objectPrice = isset($item[5]) ? $item[5] : 0;
                        // $objectQuantity = isset($item[6]) ? $item[6] : 1;
                        $objectId = isset($item['object_id']) ? $item['object_id'] : 0;
                        $objectType = isset($item['object_type']) ? $item['object_type'] : null;
                        $objectCode = isset($item['object_code']) ? $item['object_code'] : null;
                        $objectPrice = isset($item['price']) ? $item['price'] : 0;
                        $objectQuantity = isset($item['quantity']) ? $item['quantity'] : 1;
    
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
        } catch (Exception $ex) {
            // return response()->json([
            //     'error' => true,
            //     'message' => __('Lưu ảnh thất bại'),
            //     '_message' => $ex->getMessage()
            // ]);
        }
        // get array object
       
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
        $list = $this->order->getAll($filters);

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
     * Lưu ảnh trước/sau khi sử dụng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function saveImage($input)
    {
        try {
            $mOrderImage = app()->get(OrderImageTable::class);

            //Xoá ảnh theo type
            $mOrderImage->removeOrderImage($input['order_code'], $input['type']);
            //Lưu ảnh
            $arrInsert = [];

            if (isset($input['arrImage']) && count($input['arrImage']) > 0) {
                foreach ($input['arrImage'] as $v) {
                    $arrInsert[] = [
                        'order_code' => $input['order_code'],
                        'type' => $input['type'],
                        'link' => $v,
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }
            $mOrderImage->insert($arrInsert);

            return response()->json([
                'error' => false,
                'message' => __('Lưu ảnh thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Lưu ảnh thất bại'),
                '_message' => $e->getMessage()
            ]);
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
     * Lấy danh sách địa chỉ theo khách hàng
     * @param $data
     */
    public function showPopupAddress($data)
    {
        try {
            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            $idAddress = isset($data['customerAddressId']) ? $data['customerAddressId'] : '';
            $dataTmp = $data;
            unset($data['customerAddressId']);
            unset($data['type_time']);
            unset($data['time_address']);
            $listAddress = $mCustomerContact->getList($data);

            $view = view('admin::orders.pop.pop-address', [
                'listAddress' => $listAddress,
                'idAddress' => $idAddress,
                'data' => $dataTmp
            ])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa địa chỉ giao hàng thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Hiển thị popup thêm địa chỉ
     * @param $data
     * @return mixed|void
     */
    public function showPopupAddAddress($data)
    {
        try {

            $mProvince = app()->get(ProvinceTable::class);
            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);
            $mDistrict = app()->get(DistrictTable::class);
            $mWard = app()->get(WardTable::class);

            $listProvince = $mProvince->getOptionProvince();
            $listDistrict = [];
            $listWard = [];
            $detailAddress = null;
            if (isset($data['customer_contact_id'])) {
                $detailAddress = $mCustomerContact->getDetail($data['customer_contact_id']);
                $listDistrict = $mDistrict->getOptionDistrict($detailAddress['province_id']);
                $listWard = $mWard->getOptionWard($detailAddress['district_id']);
            }

            $view = view('admin::orders.pop.pop-add-address', [
                'listProvince' => $listProvince,
                'customer_id' => $data['customer_id'],
                'listDistrict' => $listDistrict,
                'listWard' => $listWard,
                'detailAddress' => $detailAddress
            ])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Hiển thị popup thêm địa chỉ thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Thay đổi tinh/thành phố
     * @param $data
     * @return mixed|void
     */
    public function changeProvince($data)
    {
        try {
            $mDistrict = app()->get(DistrictTable::class);

            $listDistrict = $mDistrict->getOptionDistrict($data['province_id']);

            $view = view('admin::orders.inc.option-district', [
                'listDistrict' => $listDistrict
            ])->render();

            $view1 = view('admin::orders.inc.option-ward', [
                'listWard' => []
            ])->render();
            return [
                'error' => false,
                'view' => $view,
                'view1' => $view1
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lấy danh sách Quận/Huyện thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Thay đổi quận huyện
     * @param $data
     * @return mixed|void
     */
    public function changeDistrict($data)
    {
        try {
            $mWard = app()->get(WardTable::class);

            $listWard = $mWard->getOptionWard($data['district_id']);

            $view = view('admin::orders.inc.option-ward', [
                'listWard' => $listWard
            ])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lấy danh sách Phường/Xã thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Tạo địa chỉ nhận hàng
     * @param $data
     * @return mixed|void
     */
    public function submitAddress($data)
    {
        try {


            $checkPhone = $this->checkPhone($data['customer_phone']);
            if ($checkPhone != 1) {
                return [
                    'error' => true,
                    'message' => __('Số điện thoại người nhận không đúng định dạng, bắt đầu bằng 0')
                ];
            }

            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);
            $mCustomerContact = app()->get(CustomerContactTable::class);

            //            Cập nhật nếu có đánh dấu là mặc định
            if (isset($data['is_default'])) {
                $mCustomerContact->updateAddressCustomer(['address_default' => 0], $data['customer_id']);
            }

            //            Kiểm tra địa chỉ đã tồn tại hay chưa

            $countAddressCustomer = $mCustomerContact->countAddressCustomer($data['customer_id']);

            $dataValue = [
                'customer_id' => $data['customer_id'],
                'contact_name' => $data['customer_name'],
                'contact_phone' => $data['customer_phone'],
                'province_id' => $data['province_id'],
                'district_id' => $data['district_id'],
                'ward_id' => $data['ward_id'],
                'full_address' => $data['address'],
                'type_address' => $data['type_address'],
                'address_default' => isset($data['is_default']) || $countAddressCustomer == 0 ? 1 : 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            if ($dataValue['address_default'] == 0) {
                $checkAddressDefault = $mCustomerContact->checkAddressDefault($data['customer_id']);
                if ($checkAddressDefault == null) {
                    return [
                        'error' => true,
                        'message' => __('Vui lòng chọn địa chỉ mặc định'),
                    ];
                }
            }

            if (isset($data['customer_contact_id'])) {
                $idAddress = $data['customer_contact_id'];

                unset($dataValue['customer_id']);
                unset($dataValue['created_at']);
                unset($dataValue['created_by']);

                $mCustomerContact->updateAddress($dataValue, $idAddress);
            } else {
                $idAddress = $mCustomerContact->addAddress($dataValue);
                $mCustomerContact->updateAddress([
                    'customer_contact_code' => 'CC_' . date('dmY') . sprintf("%02d", $idAddress)
                ], $idAddress);
            }


            return [
                'error' => false,
                'message' => __('Lưu thông tin địa chỉ nhận hàng thành công'),
                'idAddress' => $idAddress
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Lưu thông tin địa chỉ nhận hàng thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    public function checkPhone($mobile)
    {
        if ($mobile[0] == 0) {
            return true;
        }
        return false;
    }

    /**
     * Xoá địa chỉ khách hàng
     * @param $data
     * @return mixed|void
     */
    public function removeAddressCustomer($data)
    {
        try {

            $mDeliveryCustomerAddress = app()->get(DeliveryCustomerAddressTable::class);

            //            Xoá địa chỉ nhận hàng
            $mDeliveryCustomerAddress->deleteAddressCustomer($data['deliveryCustomerAddressId']);

            //            Lấy danh địa chỉ nhận hàng
            unset($data['deliveryCustomerAddressId']);
            $listAddress = $mDeliveryCustomerAddress->getList($data);
            $view = view('admin::orders.inc.list-address-customer', [
                'listAddress' => $listAddress
            ])->render();
            return [
                'error' => false,
                'message' => __('Xoá địa chỉ nhận hàng thành công'),
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá địa chỉ nhận hàng thất bại'),
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

            $view = view('admin::orders.inc.block-address', [
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

    public function saveOrderWithoutReceipt($request)
    {
        try {
            DB::beginTransaction();

            DB::commit();
            return response()->json([
                'error' => true,
                'message' => 'Thanh toán thành công',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => false,
                'message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    public function createQrCodeVnPay($input)
    {
        try {
            DB::beginTransaction();
            $orderId = $input['order_id'];
            $orderCode = $input['order_code'];
            $money = $input['money'];
            $mReceiptOnline = new ReceiptOnlineTable();
            $mReceipt = new ReceiptTable();
            $mReceiptDetail = new ReceiptDetailTable();

            // setup data
            $data_receipt = [
                'customer_id' => $input['customer_id'],
                'staff_id' => Auth::id(),
                'object_id' => $orderId,
                'object_type' => $input['object_type'],
                'order_id' => $orderId,
                'total_money' => $money,
                'voucher_code' => '',
                'status' => 'unpaid',
                'is_discount' => 1,
                'amount' => $money,
                'amount_paid' => $money,
                'amount_return' => 0,
                'note' => $input['note'] ?? '',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => $orderCode, // order code
                'object_accounting_id' => $orderId, // order id
            ];
            if (isset($input['voucher_bill']) && $input['voucher_bill'] != null) {
                $data_receipt['discount'] = $input['discount_bill'] ?? '';
            } else {
                $data_receipt['custom_discount'] = $input['discount_bill'] ?? '';
            }
            $receipt_id = '';
            // check receipt exists => edit => else create
            $dataReceipt = $mReceipt->getItem($orderId);
            // no receipt by order_id but $dataReceipt still have data (data with all null field) ?????
            if ($dataReceipt != null && isset($dataReceipt['receipt_id']) && $dataReceipt['receipt_id'] != null) {
                $receipt_id = $dataReceipt['receipt_id'];
                // remove receipt detail + receipt online of vnpay
                $mReceiptDetail->removeReceiptDetailMethod($receipt_id, 'VNPAY');
                $mReceiptOnline->removeReceiptOnlineMethod($receipt_id, 'VNPAY');

                // update receipt
                $mReceipt->edit($data_receipt, $receipt_id);
            } else {
                // create receipt with payment vnpay
                $receipt_id = $mReceipt->add($data_receipt);
                $day_code = date('dmY');
                $data_code = [
                    'receipt_code' => 'TT_' . $day_code . $receipt_id
                ];
                $mReceipt->edit($data_code, $receipt_id);
            }
            // create receipt detail, type vnpay
            $dataReceiptDetail = [
                'receipt_id' => $receipt_id,
                'cashier_id' => Auth::id(),
                'amount' => $money,
                'payment_method_code' => 'VNPAY',
                'created_by' => Auth::id(),
                'updated_by' => Auth::id()
            ];
            $mReceiptDetail->add($dataReceiptDetail);
            // create qr code
            $staff_branch = $this->staff->getItem(Auth::id());
            $arrVnPay = null;
            $callVnPay = $this->_paymentVnPay($orderCode, $money, Auth()->id(), $staff_branch['branch_id'], 'web', "");
            if ($callVnPay['ErrorCode'] == 0) {
                $arrVnPay = $callVnPay['Data'];
                $arrVnPay['order_code'] = $arrVnPay['order_id'];
                unset($arrVnPay['order_id']);
            }

            $mPaymentMethod = new \Modules\Payment\Models\PaymentMethodTable();
            $itemMethod = $mPaymentMethod->getPaymentMethodByCode('VNPAY');
            // create receipt online
            $dataReceiptOnline = [
                'receipt_id' => $receipt_id,
                'object_type' => 'order',
                'object_id' => $orderId,
                'object_code' => $orderCode,
                'payment_method_code' => 'VNPAY',
                'amount_paid' => $money,
                'payment_transaction_code' => $arrVnPay['payment_transaction_code'],
                'payment_transaction_uuid' => $arrVnPay['payment_transaction_uuid'],
                'payment_time' => Carbon::now(),
                'performer_name' => $staff_branch['name'],
                'performer_phone' => $staff_branch['phone1'],
                'type' => $itemMethod['payment_method_type'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            $mReceiptOnline->createReceiptOnline($dataReceiptOnline);

            DB::commit();
            return response()->json([
                'error' => false,
                'data' => $arrVnPay
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => $e->getMessage() . $e->getLine()
            ]);
        }
    }

    /**
     * Kiểm tra số serial được enter
     * @param $data
     * @return mixed|void
     */
    public function checkSerialEnter($data)
    {
        try {

            $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);

            $data['serial'] = strip_tags($data['serial']);
            $checkSerialNumber = $mProductInventorySerial->checkSerialOrder($data['product_code'], $data['serial']);

            if ($checkSerialNumber != null) {
                $checkLogSerial = $mOrderSessionSerialLog->checkSerial($data['session'], $data['product_code'], $data['serial']);
                if (count($checkLogSerial) == 0) {
                    $tmpInsert = [
                        'position' => $data['numberRow'],
                        'session' => $data['session'],
                        'product_code' => $data['product_code'],
                        'serial' => $data['serial'],
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];
                    $mOrderSessionSerialLog->addSerialLog($tmpInsert);
                    //            lấy tổng số serial theo từng sản phẩm
                    $totalSerial = $mOrderSessionSerialLog->totalSerial($data['session'], $data['numberRow'], $data['product_code']);

                    $listSerial = $mOrderSessionSerialLog->getListSerialLimit($data['session'], $data['numberRow'], $data['product_code']);
                    $view = view('admin::orders.inc.list-serial', [
                        'listSerial' => $listSerial,
                        'session' => $data['session'],
                        'product_code' => $data['product_code'],
                        'numberRow' => $data['numberRow'],
                        'id' => $data['id']
                    ])->render();
                    return [
                        'error' => false,
                        'view' => $view,
                        'total_serial' => $totalSerial
                    ];
                } else {
                    return [
                        'error' => true,
                        'message' => __('Số serial đã được sử dụng')
                    ];
                }
            }

            return [
                'error' => true,
                'message' => __('Không có số serial trong kho')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm số serial thất bại')
            ];
        }
    }

    /**
     * Xoá số serial
     * @param $data
     * @return mixed|void
     */
    public function removeSerial($data)
    {
        try {

            $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);

            $mOrderSessionSerialLog->removeSerial($data['session'], $data['numberRow'], $data['product_code'], $data['serial']);
            //            lấy tổng số serial theo từng sản phẩm
            $totalSerial = $mOrderSessionSerialLog->totalSerial($data['session'], $data['numberRow'], $data['product_code']);
            //            lấy danh sách serial có giới hạn theo từng sản phẩm
            $listSerial = $mOrderSessionSerialLog->getListSerialLimit($data['session'], $data['numberRow'], $data['product_code']);
            $view = view('admin::orders.inc.list-serial', [
                'listSerial' => $listSerial,
                'session' => $data['session'],
                'product_code' => $data['product_code'],
                'numberRow' => $data['numberRow'],
                'id' => $data['id']
            ])->render();

            return [
                'error' => false,
                'view' => $view,
                'total_serial' => $totalSerial
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Xoá serial thất bại')
            ];
        }
    }

    /**
     * Hiển thị popup serial
     * @param $data
     * @return mixed|void
     */
    public function showPopupSerial($data)
    {
        try {
            $type_view = isset($data['type_view']) ? $data['type_view'] : 'edit';
            $view = $this->viewPopupSerial($data, 'admin::orders.pop.popup-list-serial', $type_view);

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Xem thêm thất bại')
            ];
        }
    }

    /**
     * @param $data
     * @return mixed|void
     */
    public function searchSerial($data)
    {
        try {
            $type_view = isset($data['type_view']) ? $data['type_view'] : 'edit';
            $view = $this->viewPopupSerial($data, 'admin::orders.inc.list-serial-popup', $type_view);

            return [
                'error' => false,
                'view' => $view
            ];
        } catch (\Exception $e) {
            return [
                'error' => false,
            ];
        }
    }

    public function viewPopupSerial($data, $view, $type_view = 'edit')
    {
        $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);
        $mOrderDetailSerial = app()->get(OrderDetailSerialTable::class);
        $mProductChild = app()->get(ProductChildTable::class);

        $detailProduct = $mProductChild->getChildByCode($data['product_code']);

        if ($type_view == 'edit') {
            $listSerial = $mOrderSessionSerialLog->getListSerialPage($data);
        } else {
            $listSerial = $mOrderDetailSerial->getListSerialPage($data);
        }

        return view($view, [
            'detailProduct' => $detailProduct,
            'listSerial' => $listSerial,
            'session' => isset($data['session']) ? $data['session'] : '',
            'product_code' => isset($data['product_code']) ? $data['product_code'] : '',
            'numberRow' => isset($data['numberRow']) ? $data['numberRow'] : '',
            'id' => isset($data['id']) ? $data['id'] : '',
            'type_view' => $type_view,
            'order_detail_id' => isset($data['order_detail_id']) ? $data['order_detail_id'] : '',
        ])->render();
    }

    /**
     * lấy danh sách sản phẩm theo sản phẩm
     * @return mixed|void
     */
    public function getListSerial($data)
    {
        $mProductInventorySerial = app()->get(ProductInventorySerialTable::class);
        $mOrderSessionSerialLog = app()->get(OrderSessionSerialTable::class);

        //        Lấy dánh sách serial đã được thêm theo sản phẩm
        $listProductOrder = $mOrderSessionSerialLog->getListProductOrder($data);

        if (count($listProductOrder) != 0) {
            $listProductOrder = collect($listProductOrder)->pluck('serial')->toArray();
        }

        return $mProductInventorySerial->getListSerialForOrder($data, $listProductOrder);
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


    private function _paymentVnPay($orderCode, $amount, $userId, $branchId, $platform, $paramsExtra)
    {
        $paymentOnline = new PaymentOnline();
        //Call api thanh toán vn pay
        return $paymentOnline->paymentVnPay([
            'method' => 'vnpay',
            'order_id' => $orderCode,
            'amount' => $amount,
            'user_id' => $userId,
            'branch_id' => $branchId,
            'platform' => $platform,
            'params_extra' => $paramsExtra
        ]);
    }
    private function paymentVnPay(array $data = [])
    {
        $oClient = new Client();

        $mConfig = app()->get(ConfigTable::class);
        //Lấy thông tin cấu hình key + secret
        $key = $mConfig->getInfoByKey('oncall_key')['value'];
        $secret = $mConfig->getInfoByKey('oncall_secret')['value'];

        //Gọi api thực hiện cuộc gọi
        $response = $oClient->request('POST', env('BASE_URL_SHARE_SERVICE') . '/payment/pay', [
            'headers' => [
                'tenant' => session()->get('brand_code'),
                'key' => $key,
                'secret' => $secret
            ],
            'json' => $data
        ]);

        return json_decode($response->getBody());
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
        $mServiceCategory = app()->get(ServiceCategoryTable::class);
        $mProductCategory = app()->get(ProductCategoryTable::class);
        $mServiceCardGroup = app()->get(ServiceCardGroupTable::class);

        $data = [];

        switch ($input['object_type']) {
            case 'product':
                //Lấy loại sản phẩm
                $getCategory = $mProductCategory->getAll();

                if (count($getCategory) > 0) {
                    foreach ($getCategory as $v) {
                        $data[] = [
                            'category_id' => $v['product_category_id'],
                            'category_name' => $v['category_name']
                        ];
                    }
                }

                break;
            case 'service':
                //Lấy loại dịch vụ
                $getCategory = $mServiceCategory->getOptionServiceCategory();

                if (count($getCategory) > 0) {
                    foreach ($getCategory as $v) {
                        $data[] = [
                            'category_id' => $v['service_category_id'],
                            'category_name' => $v['name']
                        ];
                    }
                }

                break;

            case 'service_card':
                //Lấy loại thẻ dịch vụ
                $getCategory = $mServiceCardGroup->getAllName();

                if (count($getCategory) > 0) {
                    foreach ($getCategory as $v) {
                        $data[] = [
                            'category_id' => $v['service_card_group_id'],
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
     * Lấy sản phẩm/dịch vụ kèm theo
     *
     * @param $input
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getDataAttach($input)
    {
        $data = [];

        switch ($input['object_type']) {
            case 'service':
                $mServiceMaterial = app()->get(ServiceMaterialTable::class);

                //Lấy dịch vụ kèm theo
                $getAttach = $mServiceMaterial->getServiceMaterial($input['object_id'], Auth()->user()->branch_id);

                if (count($getAttach) > 0) {
                    foreach ($getAttach as $v) {
                        //Lấy giá khuyến mãi của dịch vụ
                        $getPrice = $this->getPromotionDetail('service', $v['service_code'], $input['customer_id'], 'live', 1);

                        if ($getPrice != null && $getPrice > $v['new_price']) {
                            $v['new_price'] = $getPrice;
                        }

                        $data[] = [
                            'object_type' => 'service',
                            'object_id' => $v['material_id'],
                            'object_code' => $v['service_code'],
                            'object_name' => $v['service_name'],
                            'price' => $v['new_price']
                        ];
                    }
                }

                break;
        }

        return $data;
    }
}