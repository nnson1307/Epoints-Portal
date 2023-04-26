<?php


namespace Modules\Report\Repository\CampaignOverviewReport;


use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\EmailCampaignTable;
use Modules\Admin\Models\EmailLogTable;
use Modules\Admin\Models\NotificationLogTable;
use Modules\Admin\Models\SmsCampaignTable;
use Modules\Admin\Models\SmsLogTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\Notification\Models\NotificationTable;
use Modules\Notification\Models\NotificationTemplateTable;
use Modules\Report\Models\BranchTable;
use Modules\Report\Models\CustomerGroupTable;
use Modules\Report\Models\OrderTable;
use Modules\Report\Models\ReceiptDetailTable;
use Modules\Report\Models\ReceiptTable;

class CampaignOverviewReportRepo implements CampaignOverviewReportRepoInterface
{
    /**
     * filter time, branch, customer group
     *
     * @param $data
     * @return mixed
     */
    public function filterAction($data)
    {
        $mEmail = new EmailCampaignTable();
        $mSms = new SmsCampaignTable();
        $mNoti = new NotificationTemplateTable();
        $mEmailLog = new EmailLogTable();
        $mSmsLog = new SmsLogTable();
        $mNotifyLog = new NotificationTable();
        $mDeal = new CustomerDealTable();
        // Declare input: khai báo
        $time = $data['time'];
        $startTime = $endTime = null;
        $dataSeries = [];
        $dataCategories = [];
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        // Chart cost
        $dataCostEmail  = $mEmail->getCostReport($data);
        $dataCostSms  = $mSms->getCostReport($data);
        $dataCostNotify  = $mNoti->getCostReport($data);
        $totalCost = $this->getTotal($dataCostEmail, $dataCostSms, $dataCostNotify, 'cost');
        $dataCostChart = $this->processCategoriesChart($startTime, $endTime, $dataCostEmail, $dataCostSms, $dataCostNotify, 'cost');
        $arrayCategories = $dataCostChart['arrayCategories'];
        $dataCategories = $dataCostChart['dataCategories'];
        $dataSeries = $this->dataSeries($dataCategories);

        // Chart revenue
        $dataRevenueEmail  = $mDeal->getRevenueCampaignOverview($data, 'email');
        $dataRevenueSms  = $mDeal->getRevenueCampaignOverview($data, 'sms');
        $dataRevenueNotify  = $mDeal->getRevenueCampaignOverview($data, 'notification');
        $totalRevenue = $this->getTotal($dataRevenueEmail, $dataRevenueSms, $dataRevenueNotify, 'revenue');
        $dataRevenueChart = $this->processCategoriesChart($startTime, $endTime, $dataRevenueEmail, $dataRevenueSms, $dataRevenueNotify, 'revenue');
        $arrayCategoriesRevenue = $dataRevenueChart['arrayCategories'];
        $dataCategoriesRevenue = $dataRevenueChart['dataCategories'];
        $dataSeriesRevenue = $this->dataSeries($dataCategoriesRevenue);

        // Chart customer approach
        $mDataEmailLog = $mEmailLog->getCustomerApproach($data);
        $mDataSmsLog = $mSmsLog->getCustomerApproach($data);
        $mDataNotifyLog = $mNotifyLog->getCustomerApproach($data);
        $dataChartCustomerApproach = $this->processDataChartCustomerApproach($mDataEmailLog, $mDataSmsLog, $mDataNotifyLog);

        // Chart deal success
        $dataDeaLSuccess = $mDeal->getTypeCustomerDealSuccess($data);
        $dataChartDealSuccess = $this->processDataChartDealSuccess($dataDeaLSuccess);

        // Chart roi rate
        $dataRoiSms = $mDeal->getRevenueAndCostByType($data, 'sms');
        $dataRoiEmail = $mDeal->getRevenueAndCostByType($data, 'email');
        $dataRoiNotify = $mDeal->getRevenueAndCostByType($data, 'notification');
        $dataRoiRate = $this->processDataRoiRate($dataRoiSms, $dataRoiEmail, $dataRoiNotify);
        $dataReturn = [
            'arrayCategories' => $arrayCategories,
            'dataSeries' => $dataSeries,
            'totalCost' => $totalCost,
            'arrayCategoriesRevenue' => $arrayCategoriesRevenue,
            'dataSeriesRevenue' => $dataSeriesRevenue,
            'totalRevenue' => $totalRevenue,
            'dataChartCustomerApproach' => $dataChartCustomerApproach['dataReturn'],
            'totalChartCustomerApproach' => $dataChartCustomerApproach['total'],
            'dataChartDealSuccess' => $dataChartDealSuccess['dataReturn'],
            'totalChartDealSuccess' => $dataChartDealSuccess['total'],
            'dataRoiRate' => $dataRoiRate['dataReturn'],
            'totalRoiRate' => $dataRoiRate['total'],
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
     * @param $dataEmail
     * @param $dataSms
     * @param $dataNotify
     * @param $keyData
     * @return array
     */
    private function processCategoriesChart($startTime, $endTime, $dataEmail, $dataSms, $dataNotify, $keyData){
        $arrayCategories = $arrayDate = [];
        $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;

        if($dateDiff < 10){
            for ($i = 0; $i < $dateDiff; $i++) {
                $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y');
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processData10Days($arrayDate, $dataEmail, $dataSms, $dataNotify, $keyData);
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
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataEmail, $dataSms, $dataNotify, $keyData);
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
            $dataCategories = $this->processDataMoreThan10Days($arrayDate, $dataEmail, $dataSms, $dataNotify, $keyData);
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
     * @param $dataEmail
     * @param $dataSms
     * @param $dataNotify
     * @param $keyData
     * @return array
     */
    private function processData10Days(array $arrayDate, $dataEmail, $dataSms, $dataNotify, $keyData)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {
            $data[$value] = [
                'dataSms' => 0,
                'dataEmail' => 0,
                'dataNotify' => 0,
            ];
        }
        foreach ($dataEmail as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data[$timeTemp])) {
                $data[$timeTemp]['dataEmail'] += $value[$keyData];
            }
        }
        foreach ($dataSms as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data[$timeTemp])) {
                $data[$timeTemp]['dataSms'] += $value[$keyData];
            }
        }
        foreach ($dataNotify as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($data[$timeTemp])) {
                $data[$timeTemp]['dataNotify'] += $value[$keyData];
            }
        }
        return $data;
    }
    /**
     * Xử lý data biểu đồ cột khi filter > 10 ngày
     *
     * @param array $arrayDate
     * @param $dataEmail
     * @param $dataSms
     * @param $dataNotify
     * @param $keyData
     * @return array
     */
    private function processDataMoreThan10Days(array $arrayDate, $dataEmail, $dataSms, $dataNotify, $keyData)
    {
        $data = [];
        foreach ($arrayDate as $key => $value) {
            $data[$value] = [
                'dataSms' => 0,
                'dataEmail' => 0,
                'dataNotify' => 0,
            ];
        }
        foreach ($dataEmail as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($data as $k => $v){
                $arr_filter = explode(" - ", $k);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data[$k]['dataEmail'] += $value[$keyData];
                }
            }
        }
        foreach ($dataSms as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($data as $k => $v){
                $arr_filter = explode(" - ", $k);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data[$k]['dataSms'] += $value[$keyData];
                }
            }
        }
        foreach ($dataNotify as $key => $value) {
            $timeTemp = $value['created_group'];
            foreach ($data as $k => $v){
                $arr_filter = explode(" - ", $k);
                $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                if($t->gte($a0) && $t->lte($a1)){
                    $data[$k]['dataNotify'] += $value[$keyData];
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
        $arrDataEmail = [];
        $arrDataSms = [];
        $arrDataNotify = [];

        foreach ($dataByCategories as $k => $v) {
            $arrDataEmail [] = $v['dataEmail'];
            $arrDataSms [] = $v['dataSms'];
            $arrDataNotify [] = $v['dataNotify'];
        }

        return [
            [
                'name' => __('Sms'),
                'data' => $arrDataSms
            ],
            [
                'name' => __('Email'),
                'data' => $arrDataEmail
            ],
            [
                'name' => __('Notification'),
                'data' => $arrDataNotify
            ],
        ];
    }
    /**
     * format data google chart customer approach
     *
     * @param $mDataEmailLog
     * @param $mDataSmsLog
     * @param $mDataNotifyLog
     * @return array
     */
    private function processDataChartCustomerApproach($mDataEmailLog, $mDataSmsLog, $mDataNotifyLog)
    {
        $dataReturn = [['Type', 'Amount']];
        $sumLeadConvert = 0;
        $sumLead = 0;
        $sumCustomer = 0;
        foreach ($mDataEmailLog as $k => $v) {
            $sumLeadConvert += $v['sum_lead_convert'];
            $sumLead += $v['sum_lead'];
            $sumCustomer += $v['sum_customer'];
        }
        foreach ($mDataSmsLog as $k => $v) {
            $sumLeadConvert += $v['sum_lead_convert'];
            $sumLead += $v['sum_lead'];
            $sumCustomer += $v['sum_customer'];
        }
        foreach ($mDataNotifyLog as $k => $v) {
            $sumCustomer += $v['sum_customer'];
        }
        $dataReturn [] = [
            __('Chuyển đổi lead'), (int)$sumLeadConvert
        ];
        $dataReturn [] = [
            __('lead'), (int)$sumLead
        ];
        $dataReturn [] = [
            __('Khách hàng'), (int)$sumCustomer
        ];
        $total = $sumLeadConvert + $sumLead + $sumCustomer;
        return [
            'dataReturn' => $dataReturn,
            'total' => $total,
        ];
    }
    /**
     * format data google chart data deal success
     *
     * @param $mDataDeal
     * @return array
     */
    private function processDataChartDealSuccess($mDataDeal)
    {
        $dataReturn = [['Type Customer', 'Amount']];
        $sumLead = $mDataDeal['total_lead'];
        $sumCustomer = $mDataDeal['total_customer'];
        $dataReturn [] = [
            __('Lead'), (int)$sumLead
        ];
        $dataReturn [] = [
            __('Khách hàng'), (int)$sumCustomer
        ];
        $total = $sumLead + $sumCustomer;
        return [
            'dataReturn' => $dataReturn,
            'total' => $total,
        ];
    }
    /**
     * format data google chart data roi rate
     *
     * @param $dataRoiSms
     * @param $dataRoiEmail
     * @param $dataRoiNotify
     * @return array
     */
    private function processDataRoiRate($dataRoiSms, $dataRoiEmail, $dataRoiNotify)
    {
        $dataReturn = [['Deal Type', 'Amount']];
        $roiSms = isset($dataRoiSms['cost']) == '' ? 0 : (float)($dataRoiSms['revenue'] - $dataRoiSms['cost'])/$dataRoiSms['cost'];
        $roiEmail = isset($dataRoiEmail['cost']) == '' ? 0 : (float)($dataRoiEmail['revenue'] - $dataRoiEmail['cost'])/$dataRoiEmail['cost'];
        $roiNotify = isset($dataRoiNotify['cost']) == '' ? 0 : (float)($dataRoiNotify['revenue'] - $dataRoiNotify['cost'])/$dataRoiNotify['cost'];
        $roiSms = round($roiSms,2) > 0 ? round($roiSms,2) : 0;
        $roiEmail = round($roiEmail,2) > 0 ? round($roiEmail,2) : 0;
        $roiNotify = round($roiNotify,2) > 0 ? round($roiNotify,2) : 0;
        $dataReturn [] = [
            __('Sms'), $roiSms
        ];
        $dataReturn [] = [
            __('Email'),  $roiEmail
        ];
        $dataReturn [] = [
            __('Notification'),  $roiNotify
        ];
        $total = $roiSms + $roiEmail + $roiNotify;
        return [
            'dataReturn' => $dataReturn,
            'total' => $total,
        ];
    }


    public function filterIIAction($data)
    {
        $mEmail = new EmailCampaignTable();
        $mSms = new SmsCampaignTable();
        $mNoti = new NotificationTemplateTable();
        $mEmailLog = new EmailLogTable();
        $mSmsLog = new SmsLogTable();
        $mNotifyLog = new NotificationTable();
        $mDeal = new CustomerDealTable();
        // Declare input: khai báo
        $time = $data['time'];
        $sms = $data['option_sms'];
        $email = $data['option_email'];
        $notify = $data['option_notify'];
        $startTime = $endTime = null;
        $dataSeries = [];
        $dataCategories = [];
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $optionSms = $mSms->getOptionSms($data);
        $optionEmail = $mEmail->getOptionEmail($data);
        $optionNotify = $mNoti->getOptionNotify($data);

        $dataRoiSms = $mDeal->getRevenueAndCostByType($data, 'sms');
        $dataRoiEmail = $mDeal->getRevenueAndCostByType($data, 'email');
        $dataRoiNotify = $mDeal->getRevenueAndCostByType($data, 'notification');
        // cụm cost
        $totalSmsCost = $dataRoiSms['cost'] ?? 0;
        $totalEmailCost = $dataRoiEmail['cost'] ?? 0;
        $totalNotifyCost = $dataRoiNotify['cost'] ?? 0;
        // cụm doanh thu
        $totalSmsRevenue = (float)$dataRoiSms['revenue'];
        $totalEmailRevenue = (float)$dataRoiEmail['revenue'];
        $totalNotifyRevenue = (float)$dataRoiNotify['revenue'];
        // cụm KHTC
        $mDataSmsLog = $mSmsLog->getCustomerApproach($data);
        $mDataEmailLog = $mEmailLog->getCustomerApproach($data);
        $mDataNotifyLog = $mNotifyLog->getCustomerApproach($data);
        // cụm chốt deal thành công
        $dataSmsDealSuccess = $mDeal->getTypeCustomerDealSuccess($data, 'sms');
        $dataSmsDealSuccess = $dataSmsDealSuccess['total_lead'] + $dataSmsDealSuccess['total_customer'];
        $dataEmailDealSuccess = $mDeal->getTypeCustomerDealSuccess($data, 'email');
        $dataEmailDealSuccess = $dataEmailDealSuccess['total_lead'] + $dataEmailDealSuccess['total_customer'];
        $dataNotifyDealSuccess = $mDeal->getTypeCustomerDealSuccess($data, 'notification');
        $dataNotifyDealSuccess = $dataNotifyDealSuccess['total_lead'] + $dataNotifyDealSuccess['total_customer'];
        // cụm tỉ lệ roi
        $roiSms = isset($dataRoiSms['cost']) == '' ? 0 : (float)($dataRoiSms['revenue'] - $dataRoiSms['cost'])/$dataRoiSms['cost'];
        $roiEmail = isset($dataRoiEmail['cost']) == '' ? 0 : (float)($dataRoiEmail['revenue'] - $dataRoiEmail['cost'])/$dataRoiEmail['cost'];
        $roiNotify = isset($dataRoiNotify['cost']) == '' ? 0 : (float)($dataRoiNotify['revenue'] - $dataRoiNotify['cost'])/$dataRoiNotify['cost'];
        $roiSms = round($roiSms,2) > 0 ? round($roiSms,2) : 0;
        $roiEmail = round($roiEmail,2) > 0 ? round($roiEmail,2) : 0;
        $roiNotify = round($roiNotify,2) > 0 ? round($roiNotify,2) : 0;
        $dataChartSms = $dataChartEmail = $dataChartNotify =[
            'arrayCategories' => [],
            'dataSeries' => []
        ];
        if($sms == ''){
            $dataChart = $mDeal->getEachRevenueAndCostByType($data, 'sms');
            $dataChartSms = $this->processCategoriesChartDetail($dataChart);
        }
        else{
            $dataChart = $mDeal->getOrderAndRevenueByType($data, 'sms');
            $dataChartSms = $this->processCategoriesChartDetailType($startTime, $endTime, $dataChart);
        }
        if($email == ''){
            $dataChart = $mDeal->getEachRevenueAndCostByType($data, 'email');
            $dataChartEmail = $this->processCategoriesChartDetail($dataChart);
        }
        else{
            $dataChart = $mDeal->getOrderAndRevenueByType($data, 'email');
            $dataChartEmail = $this->processCategoriesChartDetailType($startTime, $endTime, $dataChart);
        }
        if($notify == ''){
            $dataChart = $mDeal->getEachRevenueAndCostByType($data, 'notification');
            $dataChartNotify = $this->processCategoriesChartDetail($dataChart);
        }
        else{
            $dataChart = $mDeal->getOrderAndRevenueByType($data, 'notification');
            $dataChartNotify = $this->processCategoriesChartDetailType($startTime, $endTime, $dataChart);
        }

        $dataReturn = [
            'filter' => $data,
            'optionSms' => $optionSms,
            'optionEmail' => $optionEmail,
            'optionNotify' => $optionNotify,
            'totalSmsCost' => $totalSmsCost,
            'totalEmailCost' => $totalEmailCost,
            'totalNotifyCost' => $totalNotifyCost,
            'totalSmsRevenue' => $totalSmsRevenue,
            'totalEmailRevenue' => $totalEmailRevenue,
            'totalNotifyRevenue' => $totalNotifyRevenue,
            'mDataEmailLog' => $mDataEmailLog,
            'mDataSmsLog' => $mDataSmsLog,
            'mDataNotifyLog' => $mDataNotifyLog,
            'dataSmsDealSuccess' => $dataSmsDealSuccess,
            'dataEmailDealSuccess' => $dataEmailDealSuccess,
            'dataNotifyDealSuccess' => $dataNotifyDealSuccess,
            'roiSms' => $roiSms,
            'roiEmail' => $roiEmail,
            'roiNotify' => $roiNotify,
            'dataChartSms' => $dataChartSms,
            'dataChartEmail' => $dataChartEmail,
            'dataChartNotify' => $dataChartNotify
        ];
        return response()->json($dataReturn);
    }

    /**
     * Xử lý format categories và data series của char khi filter tổng chiến dịch
     *
     * @param $data
     * @return array
     */
    private function processCategoriesChartDetail($data)
    {
        $arrayCategories = [];
        $dataSeries = [];
        $seriesFirst = [
            'type' => 'column',
            'name' => __('Chi phí'),
            'yAxis' => 1,
            'tooltip' => [
                'valueSuffix' => __('VNĐ')
            ]
        ];
        $seriesSecond = [
            'type' => 'column',
            'name' => __('Doanh thu'),
            'yAxis' => 1,
            'tooltip' => [
                'valueSuffix' => __('VNĐ')
            ]
        ];
        $seriesThird = [
            'type' => 'spline',
            'name' => __('ROI'),
            'yAxis' => 0,
            'tooltip' => [
                'valueSuffix' => ''
            ]
        ];
        $dataFirst = [];
        $dataSecond = [];
        $dataThird = [];
        foreach ($data as $item) {
            $arrayCategories[] = $item['name'];
            $dataFirst[] = (float)$item['cost'];
            $dataSecond[] = (float)$item['revenue'];
            $roiSms = (isset($item['cost']) == '' || (float)$item['cost'] == 0) ? 0 : (float)($item['revenue'] - $item['cost'])/$item['cost'];
            $dataThird[] = round($roiSms,2) > 0 ? round($roiSms,2) : 0;
        }
        $seriesFirst['data'] = $dataFirst;
        $seriesSecond['data'] = $dataSecond;
        $seriesThird['data'] = $dataThird;
        $dataSeries[] = $seriesFirst;
        $dataSeries[] = $seriesSecond;
        $dataSeries[] = $seriesThird;
        return [
            'arrayCategories' => $arrayCategories,
            'dataSeries' => $dataSeries
        ];
    }

    /**
     * Xử lý format categories và data series của char khi filter 1 chiến dịch
     *
     * @param $startTime
     * @param $endTime
     * @param $dataType
     * @return array
     */
    private function processCategoriesChartDetailType($startTime, $endTime, $dataType){
        $arrayCategories = $dataCategories = $arrayDate = [];
        $dateDiff = ((strtotime($endTime) - strtotime($startTime)) / (60 * 60 * 24)) + 1;

        if($dateDiff < 10){
            for ($i = 0; $i < $dateDiff; $i++) {
                $arrayDate [] = Carbon::parse($startTime)->addDays($i)->format('d/m/Y');
            }
            $arrayCategories = $arrayDate;
            $dataCategories = $this->processData10DaysChartDetailType($arrayDate, $dataType);
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
            $dataCategories = $this->processDataMoreThan10DaysChartDetailType($arrayDate, $dataType);
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
            $dataCategories = $this->processDataMoreThan10DaysChartDetailType($arrayDate, $dataType);
        }
        return [
            'arrayCategories' => $arrayCategories,
            'dataColumn' => $dataCategories['dataColumn'],
            'dataSpline' => $dataCategories['dataSpline'],
        ];
    }

    /**
     * Xử lý format data của chart khi filter < 10 ngày
     *
     * @param array $arrayDate
     * @param $dataType
     * @return array
     */
    private function processData10DaysChartDetailType(array $arrayDate, $dataType)
    {
        $dataColumnTemp = [];
        $dataSplineTemp = [];
        foreach ($arrayDate as $key => $value) {
            $dataColumnTemp[$value] = 0;
            $dataSplineTemp[$value] = 0;
        }
        foreach ($dataType as $key => $value) {
            $timeTemp = $value['created_group'];
            if (isset($dataColumnTemp[$timeTemp])) {
                $dataColumnTemp[$timeTemp] = (float)$value['revenue'];
            }
            if (isset($dataSplineTemp[$timeTemp])) {
                $dataSplineTemp[$timeTemp] = (float)$value['count_order'];
            }
        }
        $dataColumn = [];
        foreach ($dataColumnTemp as $key => $value) {
            $dataColumn[] = $value;
        }
        $dataSpline = [];
        foreach ($dataSplineTemp as $key => $value) {
            $dataSpline[] = $value;
        }
        return [
            'dataColumn' => $dataColumn,
            'dataSpline' => $dataSpline,
        ];
    }

    /**
     * Xử lý format data của chart khi filter > 10 ngày
     *
     * @param array $arrayDate
     * @param $dataType
     * @return array
     */
    private function processDataMoreThan10DaysChartDetailType(array $arrayDate, $dataType)
    {
        $dataColumnTemp = [];
        $dataSplineTemp = [];
        foreach ($arrayDate as $key => $value) {
            $dataColumnTemp[$value] = 0;
            $dataSplineTemp[$value] = 0;
        }
        foreach ($dataType as $key => $value) {
            if($value['created_group'] != ''){
                $timeTemp = $value['created_group'];
                foreach ($dataColumnTemp as $k => $v){
                    $arr_filter = explode(" - ", $k);
                    $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                    $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                    $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                    if($t->gte($a0) && $t->lte($a1)){
                        $dataColumnTemp[$k] = (float)$value['revenue'];
                    }
                }
                foreach ($dataSplineTemp as $k => $v){
                    $arr_filter = explode(" - ", $k);
                    $t = Carbon::createFromFormat('d/m/Y', $timeTemp);
                    $a0 = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
                    $a1 = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
                    if($t->gte($a0) && $t->lte($a1)){
                        $dataSplineTemp[$k] = (float)$value['count_order'];
                    }
                }
            }
        }
        $dataColumn = [];
        foreach ($dataColumnTemp as $key => $value) {
            $dataColumn[] = $value;
        }
        $dataSpline = [];
        foreach ($dataSplineTemp as $key => $value) {
            $dataSpline[] = $value;
        }
        return [
            'dataColumn' => $dataColumn,
            'dataSpline' => $dataSpline,
        ];
    }
}