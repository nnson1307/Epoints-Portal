<?php


namespace Modules\Report\Repository\PerformanceReport;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\EmailCampaignTable;
use Modules\Admin\Models\EmailLogTable;
use Modules\Admin\Models\NotificationLogTable;
use Modules\Admin\Models\SmsCampaignTable;
use Modules\Admin\Models\SmsLogTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\DealCareTable;
use Modules\CustomerLead\Models\StaffsTable;
use Modules\Notification\Models\NotificationTable;
use Modules\Notification\Models\NotificationTemplateTable;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\CustomerGroupTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\ReceiptDetailTable;
use Modules\Report\Models\ReceiptTable;

class PerformanceReportRepo implements PerformanceReportRepoInterface
{


    public function filterAction($data)
    {
        $mEmail = new EmailCampaignTable();
        $mSms = new SmsCampaignTable();
        $mNoti = new NotificationTemplateTable();
        $mEmailLog = new EmailLogTable();
        $mSmsLog = new SmsLogTable();
        $mNotifyLog = new NotificationTable();
        $mDeal = new CustomerDealTable();
        $mLead = new CustomerLeadTable();
        $mStaff = new StaffsTable();
        $mDealCare = new DealCareTable();
        $time = $data['time'];
        $startTime = $endTime = null;
        $dataSeries = [];
        $dataCategories = [];
        $check12Months = false;
        if($time == __("Tất cả 12 tháng")){
            $check12Months= true;
            $startTime = Carbon::now()->startOfYear()->format('d/m/Y');
            $endTime = Carbon::now()->format('d/m/Y');
            $data['time']  = $startTime . " - " . $endTime;
        }
        $time = $data['time'];
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $dataPerformance = $mDeal->getRevenuePerformanceByType($data);
        // cụm doanh thu
        $totalRevenue = (float)$dataPerformance['revenue'] ?? 0;
        // cụm KHTC
        // get customer convert deal
        $dataCustomer = $mDealCare->getCustomerApproachPerformance($data);
        // get lead care
        $lstLeadCode = $mDealCare->getListLeadCodeDealCare($data);
        $lead = $mLead->getCustomerApproachRejectListLead($data, $lstLeadCode);
        $totalLead = count($lead);
        // get customer convert to customer
        $leadConvert = $mLead->getCustomerApproach($data, $lstLeadCode);
        $totalLeadConvert = $this->getTotal($leadConvert,[],[],'sum_lead_convert');

        $mDataLog = [
            'sum_lead' => $this->getTotal($dataCustomer,[],[],'sum_lead') + $totalLead,
            'sum_customer' => $this->getTotal($dataCustomer,[],[],'sum_customer'),
            'sum_lead_convert' => $totalLeadConvert,
        ];

        // cụm chốt deal thành công
        $dataOnlineDealSuccess = $mDeal->getTypeCustomerDealSuccess($data, 'online');
        $dataOnlineDealSuccess = $dataOnlineDealSuccess['total_lead'] + $dataOnlineDealSuccess['total_customer'];
        $dataLeadDealSuccess = $mDeal->getTypeCustomerDealSuccess($data, 'lead');
        $dataLeadDealSuccess = $dataLeadDealSuccess['total_lead'] + $dataLeadDealSuccess['total_customer'];
        $totalDealSuccess = $dataOnlineDealSuccess + $dataLeadDealSuccess ;

        // danh sách nhân viên thuộc phòng ban, chi nhánh, tìm kiếm theo tên
        $lstStaff = $mStaff->getListStaffByFilter($data);
        $dataStaff = [];
        foreach ($lstStaff as $staff) {
            // get customer from care (lead, customer, convert)
            $dealCareStaff = $mDealCare->getCustomerApproachByStaff($data, $staff['staff_id']);
            // get customer convert to customer
            $leadConvert = $mLead->getCustomerApproachByStaff($data, $staff['staff_id']);
//            $sumLeadCare = isset($dealCareStaff['sum_lead']) ? $dealCareStaff['sum_lead'] : 0;
//            $sumLeadConvertCare = isset($dealCareStaff['sum_lead_convert']) ? $dealCareStaff['sum_lead_convert'] : 0;
//            $sumLead = isset($leadConvert['sum_lead_convert']) ? $leadConvert['sum_lead_convert'] : 0;
            $sumLeadConvert = isset($leadConvert['sum_lead_convert']) ? $leadConvert['sum_lead_convert'] : 0;
            // get count lead assign by staff
            $dataLead = $mLead->getListAssignByStaff($data, $staff['staff_id']);
            $sumLead = isset($dataLead['sum_lead_assign']) ? $dataLead['sum_lead_assign'] : 0;
            $staff['sum_lead'] = $sumLead;
            $staff['sum_lead_convert'] =  $sumLeadConvert;
            $dataStaff[] = $staff;
        }

        // data chart tổng doanh thu theo lead và customer
        $dataCustomer = $mDeal->getRevenueByLeadAndCustomer($data, 'customer');
        $dataLead = $mDeal->getRevenueByLeadAndCustomer($data, 'lead');
        // data chart tỉ lệ chuyển đổi
//        $dataRateLeadTemp = $mDealCare->getDataChartRateLead($data);
        $dataRateLeadTemp = $mLead->getDataChartRateLead($data);
        $dataRateConvert = $mLead->getDataChartRateConvert($data);
        $dataRateLead = [];
        foreach ($dataRateLeadTemp as $key => $value){
            $dataRateLead[$value['created_group']] = $value;
        }
        $dataTotalRevenue = $dataTotalRate = [
            'arrayCategories' => [],
            'dataSeries' => []
        ];
        if($check12Months){
            $arrayCategoriesGetData = [];
            $arrayCategories = [];
            for($i = 1; $i <= 12; $i++){
                $temp = $i < 10 ? '0'. $i : $i;
                $month = Carbon::now()->format("$temp/Y");
                $startMonth = Carbon::now()->format("$temp/d/Y");
                $start = Carbon::parse($startMonth)->startOfMonth();
                $endOfMonthStart = Carbon::parse($start)->endOfMonth();
                $sM = $start->format("d/m/Y");
                $eM = $endOfMonthStart->format("d/m/Y");
                $arrayCategoriesGetData[] = $sM . " - " . $eM;
                $arrayCategories[] = $month;
            }
            $dataCategoriesRevenue = $this->processDataMoreThan10Days($arrayCategoriesGetData, $dataCustomer, $dataLead, 'revenue');
            $dataTotalRevenue['dataSeries'] = $this->dataSeries($dataCategoriesRevenue);
            $dataTotalRevenue['arrayCategories'] = $arrayCategories;
            $dataCategoriesRate = $this->processDataMoreThan10Days($arrayCategoriesGetData, $dataRateConvert, $dataRateLead, 'total');
            $dataTotalRate['dataSeries'] = $this->dataSeriesRate($dataCategoriesRate);
            $dataTotalRate['arrayCategories'] = $arrayCategories;
        }
        else{
            $dataTotalRevenue = $this->processCategoriesChart($startTime, $endTime, $dataCustomer, $dataLead, 'revenue');
            $dataCategoriesRevenue = $dataTotalRevenue['dataCategories'];
            $dataTotalRevenue['dataSeries'] = $this->dataSeries($dataCategoriesRevenue);
            $dataTotalRate = $this->processCategoriesChart($startTime, $endTime, $dataRateConvert, $dataRateLead, 'total');
            $dataCategoriesRate = $dataTotalRate['dataCategories'];
            $dataTotalRate['dataSeries'] = $this->dataSeriesRate($dataCategoriesRate);
        }

        $dataReturn = [
            'filter' => $data,

            'totalRevenue' => $totalRevenue,

            'totalDealSuccess' => $totalDealSuccess,

            'dataLog' => $mDataLog,

            'dataStaff' => $dataStaff,

            'dataTotalRevenue' => $dataTotalRevenue,

            'dataTotalRate' => $dataTotalRate,

        ];
        return response()->json($dataReturn);
    }
    /**
     * Tổng chi phí/doanh thu
     *
     * @param $dataCostEmail
     * @param $dataCostSms
     * @param $dataCostNotify
     * @param $keyData
     * @return int
     */
    private function getTotal($dataCostEmail, $dataCostSms, $dataCostNotify, $keyData)
    {
        $totalCost = 0;
        foreach ($dataCostEmail as $item) {
            $totalCost += $item[$keyData];
        }
        foreach ($dataCostSms as $item) {
            $totalCost += $item[$keyData];
        }
        foreach ($dataCostNotify as $item){
            $totalCost += $item[$keyData];
        }
        return $totalCost;
    }
    /**
     * xử lý categories của chart column
     *
     * @param $startTime
     * @param $endTime
     * @param $dataCustomer
     * @param $dataLead
     * @param $keyData
     * @return array
     */
    private function processCategoriesChart($startTime, $endTime, $dataCustomer, $dataLead, $keyData){
        $arrayCategories = $arrayDate = [];
        $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;

        if($dateDiff < 10){
            for ($i = 0; $i < $dateDiff; $i++) {
                $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y');
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processData10Days($arrayDate, $dataCustomer, $dataLead, $keyData);
        }
        elseif($dateDiff >= 10 && $dateDiff <= 60){
            $countWeek = ceil((float)$dateDiff / 7);
            for ($i = 0; $i < $dateDiff; $i += 7) {
                if($dateDiff - $i < 7){
                    $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y') . ' - ' . Carbon::parse($startTime)->addDays($dateDiff - 1)->format('d/m/Y');
                }
                else{
                    $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y') . ' - ' . Carbon::parse($startTime)->addDays($i + 6)->format('d/m/Y');
                }
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataCustomer, $dataLead, $keyData);
        }
        else{
            //28/10/2019 - 02/08/2021
            $startMonth = Carbon::parse($startTime)->format('m');
            $endMonth = Carbon::parse($endTime)->format('m');
            $startYear = Carbon::parse($startTime)->format('Y');
            $endYear = Carbon::parse($endTime)->format('Y');

            $start = Carbon::parse($startTime)->startOfMonth();
            $end   = Carbon::parse($endTime)->startOfMonth();
            $key = 0;
            do
            {
                $endOfMonthStart = Carbon::parse($start)->endOfMonth();
                $months[$key] = $start->format('d/m/Y') .
                                                        ' - ' .
                                                        $endOfMonthStart->format('d/m/Y');
                if($start->format('m-Y') == Carbon::parse($startTime)->format('m-Y')){
                    $months[$key] = Carbon::parse($startTime)->format('d/m/Y').
                                                            ' - ' .
                                                            $endOfMonthStart->format('d/m/Y');
                }
                if($start->format('m-Y') == Carbon::parse($endTime)->format('m-Y')){
                    $months[$key] = $start->format('d/m/Y') .
                                                            ' - ' .
                                                            Carbon::parse($endTime)->format('d/m/Y');
                }
                $key++;
            } while ($start->addMonth() <= $end);
            $arrayCategories = $arrayDate = $months;
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataCustomer, $dataLead, $keyData);
        }
        return [
            'dataCategories' => $dataCategories,
            'arrayCategories' => $arrayCategories,
        ];
    }
    /**
     * Xử lý data biểu đồ cột khi filter < 10 ngày
     *
     * @param array $arrayDate
     * @param $dataCustomer
     * @param $dataLead
     * @param $keyData
     * @return array
     */
    private function processData10Days(array $arrayDate, $dataCustomer, $dataLead, $keyData)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {
            $data[$value] = [
                'dataCustomer' => 0,
                'dataLead' => 0,
            ];
        }
        foreach ($dataCustomer as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data[$timeTemp])) {
                $data[$timeTemp]['dataCustomer'] += $value[$keyData];
            }
        }
        foreach ($dataLead as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data[$timeTemp])) {
                $data[$timeTemp]['dataLead'] += $value[$keyData];
            }
        }
        return $data;
    }
    /**
     * Xử lý data biểu đồ cột khi filter > 10 ngày
     *
     * @param array $arrayDate
     * @param $dataCustomer
     * @param $dataLead
     * @param $keyData
     * @return array
     */
    private function processDataMoreThan10Days(array $arrayDate, $dataCustomer, $dataLead, $keyData)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {
            $data[$value] = [
                'dataCustomer' => 0,
                'dataLead' => 0,
            ];
        }
        foreach ($dataCustomer as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($data as $k => $v){
                $arr_filter = explode(" - ", $k);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data[$k]['dataCustomer'] += $value[$keyData];
                }
            }
        }
        foreach ($dataLead as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($data as $k => $v){
                $arr_filter = explode(" - ", $k);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data[$k]['dataLead'] += $value[$keyData];
                }
            }
        }
        return $data;
    }
    /**
     * Xử lý data series
     *
     * @param $dataByCategories
     * @return array
     */
    private function dataSeries($dataByCategories)
    {
        $arrDataCustomer = [];
        $arrDataLead = [];

        foreach ($dataByCategories as $k => $v) {
            $arrDataCustomer [] = $v['dataCustomer'];
            $arrDataLead [] = $v['dataLead'];
        }

        return [
            [
                'name' => __('LEAD'),
                'data' => $arrDataLead
            ],
            [
                'name' => __('KH'),
                'data' => $arrDataCustomer
            ],
        ];
    }
    private function dataSeriesRate($dataByCategories)
    {
        $arrDataCustomer = [];
        $arrDataLead = [];

        foreach ($dataByCategories as $k => $v) {
            $arrDataCustomer [] = $v['dataCustomer'];
            $arrDataLead [] = $v['dataLead'];
        }

        return [
            [
                'name' => __('LEAD'),
                'data' => $arrDataLead
            ],
            [
                'name' => __('KH mới'),
                'data' => $arrDataCustomer
            ],
        ];
    }

}