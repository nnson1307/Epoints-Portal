<?php


namespace Modules\CustomerLead\Repositories\Report;


use App\Exports\ExportFile;
use App\Exports\ExportLeadReportCs;
use App\Exports\ExportLeadReportStaff;
use App\Exports\ExportReportConvert;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\CustomerLead\Models\CpoCustomerLogTable;
use Modules\CustomerLead\Models\CustomerDealTable;
use Modules\CustomerLead\Models\CustomerLeadTable;
use Modules\CustomerLead\Models\CustomerSourceTable;
use Modules\CustomerLead\Models\JourneyTable;
use Modules\CustomerLead\Models\PipelineTable;
use Modules\CustomerLead\Models\StaffsTable;

class ReportRepo implements ReportRepoInterface
{
    /**
     * Lấy option pipeline
     *
     * @param $pipeCatCode
     * @return mixed
     */
    public function getListPipeline($pipeCatCode)
    {
        $mPipeline = new PipelineTable();
        return $mPipeline->getOption($pipeCatCode);
    }

    /**
     * View table báo cáo lead theo nguồn khách hàng
     *
     * @param $input
     * @return array
     */
    public function dataViewLeadReportCS($input)
    {
        $mCustomerSource = new CustomerSourceTable();
        $mJourney = new JourneyTable();
        $mCustomerLead = new CustomerLeadTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listCustomerSource = $mCustomerSource->getOptionByFilter($input);

        $startTime = null;
        $endTime = null;

        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $kq = [];
        $quantity = [];
        foreach ($listCustomerSource as $k => $v) {
            $sumRow = 0;
            $kq = $mCustomerLead->getQuantityJourneyByCS($v['customer_source_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
                $sumRow += $quantity[$k][$k1];
            }
            // tổng từng dòng
            $quantity[$k][count($listJourney)] = $sumRow;
        }

        // Tính tổng từng hành trình
        for ($i = 0; $i <= count($listJourney); $i++) {
            $sum = 0;
            foreach ($listCustomerSource as $k => $v) {
                $sum += $quantity[$k][$i];
            }
            $quantity['sumColumn'][] = $sum;
        }
        $html = \View::make('customer-lead::report.report-according-to-cs', [
            'listCustomerSource' => $listCustomerSource,
            'listJourney' => $listJourney,
            'quantity' => $quantity
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Export view báo cáo lead report cs
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelViewLeadReportCs($input)
    {
        $mCustomerSource = new CustomerSourceTable();
        $mJourney = new JourneyTable();
        $mCustomerLead = new CustomerLeadTable();
        $mPipeline = new PipelineTable();
        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listCustomerSource = $mCustomerSource->getOptionByFilter($input);
        $pipelineName = $mPipeline->getDetailByCode($input['pipeline_code']);

        $startTime = null;
        $endTime = null;

        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $kq = [];
        $quantity = [];
        foreach ($listCustomerSource as $k => $v) {
            $sumRow = 0;
            $kq = $mCustomerLead->getQuantityJourneyByCS($v['customer_source_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
                $sumRow += $quantity[$k][$k1];
            }
            // tổng từng dòng
            $quantity[$k][count($listJourney)] = $sumRow;
        }

        // Tính tổng từng hành trình
        for ($i = 0; $i <= count($listJourney); $i++) {
            $sum = 0;
            foreach ($listCustomerSource as $k => $v) {
                $sum += $quantity[$k][$i];
            }
            $quantity['sumColumn'][] = $sum;
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'listCustomerSource' => $listCustomerSource,
            'listJourney' => $listJourney,
            'quantity' => $quantity,
            'created_at' => $input['time'],
            'pipeline_name' => $pipelineName['pipeline_name']
        ];

        return Excel::download(new ExportLeadReportCs($data), 'export-lead-report.xlsx');
    }

    /**
     * data popup lead report cs
     *
     * @param $filter
     * @return mixed
     */
    public function dataPopupLeadReportCS(&$filter)
    {
        $mCustomerLead = new CustomerLeadTable();
        return $mCustomerLead->getListCustomerLead($filter);
    }

    /**
     * Export excel popup lead report cs, report convert, lead report staff
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function ExportExcelPopupLeadReportCS($input)
    {
        $heading = [
            __('TÊN LEAD'),
            __('SỐ ĐIỆN THOẠI'),
            __('EMAIL'),
            __('ĐỊA CHỈ'),
            __('GIỚI TÍNH'),
            __('NGÀY DIỄN RA'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mCustomerLead = new CustomerLeadTable();
        $allData = [];
        $allData = $mCustomerLead->getListCustomerLeadExport($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $gender = __('Nữ');
                if($item['gender']=='male'){
                    $gender = __('Nam');
                }
                $data [] = [
                    $item['full_name'],
                    $item['phone'],
                    $item['email'],
                    $item['address'],
                    $gender,
                    date("d/m/Y",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-popup-report-cs.xlsx');
    }

    public function exportExcelViewDealReportStaff($input)
    {

        $mPipeline = new PipelineTable();
        $pipelineName = $mPipeline->getDetailByCode($input['pipeline_code']);
        $mStaff = new StaffsTable();
        $mJourney = new JourneyTable();
        $mCustomerDeal = new CustomerDealTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listStaff = $mStaff->getStaffOptionByFilter($input);
        $startTime = null;
        $endTime = null;


        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $kq = [];
        $quantity = [];
        foreach ($listStaff as $k => $v) {
            $sumRow = 0;
            $kq = $mCustomerDeal->getQuantityJourneyByStaff($v['staff_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
                $sumRow += $quantity[$k][$k1];
            }
            // tổng từng dòng
            $quantity[$k][count($listJourney)] = $sumRow;
        }

        // Tính tổng từng hành trình
        for ($i = 0; $i <= count($listJourney); $i++) {
            $sum = 0;
            foreach ($listStaff as $k => $v) {
                $sum += $quantity[$k][$i];
            }
            $quantity['sumColumn'][] = $sum;
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'listJourney' => $listJourney,
            'listStaff' => $listStaff,
            'quantity' => $quantity,
            'created_at' => $input['time'],
            'pipeline_name' => $pipelineName['pipeline_name']
        ];

        return Excel::download(new ExportLeadReportStaff($data), 'export-lead-report-staff.xlsx');
    }
    /**
     * View table báo cáo lead theo nhân viên
     *
     * @param $input
     * @return array
     */
    public function dataViewLeadReportStaff($input)
    {
        $mStaff = new StaffsTable();
        $mJourney = new JourneyTable();
        $mCustomerLead = new CustomerLeadTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listStaff = $mStaff->getStaffOptionByFilter($input);
        $startTime = null;
        $endTime = null;


        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $kq = [];
        $quantity = [];
        foreach ($listStaff as $k => $v) {
            $sumRow = 0;
            $kq = $mCustomerLead->getQuantityJourneyByStaff($v['staff_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
                $sumRow += $quantity[$k][$k1];
            }
            // tổng từng dòng
            $quantity[$k][count($listJourney)] = $sumRow;
        }

        // Tính tổng từng hành trình
        for ($i = 0; $i <= count($listJourney); $i++) {
            $sum = 0;
            foreach ($listStaff as $k => $v) {
                $sum += $quantity[$k][$i];
            }
            $quantity['sumColumn'][] = $sum;
        }

        $html = \View::make('customer-lead::report.report-according-to-staff', [
            'listStaff' => $listStaff,
            'listJourney' => $listJourney,
            'quantity' => $quantity
        ])->render();

        return [
            'html' => $html
        ];
    }

    /**
     * Export excel view lead report staff
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelViewLeadReportStaff($input)
    {
        $mPipeline = new PipelineTable();

        $pipelineName = $mPipeline->getDetailByCode($input['pipeline_code']);

        $mStaff = new StaffsTable();
        $mJourney = new JourneyTable();
        $mCustomerLead = new CustomerLeadTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listStaff = $mStaff->getStaffOptionByFilter($input);
        $startTime = null;
        $endTime = null;


        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $kq = [];
        $quantity = [];
        foreach ($listStaff as $k => $v) {
            $sumRow = 0;
            $kq = $mCustomerLead->getQuantityJourneyByStaff($v['staff_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
                $sumRow += $quantity[$k][$k1];
            }
            // tổng từng dòng
            $quantity[$k][count($listJourney)] = $sumRow;
        }

        // Tính tổng từng hành trình
        for ($i = 0; $i <= count($listJourney); $i++) {
            $sum = 0;
            foreach ($listStaff as $k => $v) {
                $sum += $quantity[$k][$i];
            }
            $quantity['sumColumn'][] = $sum;
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'listJourney' => $listJourney,
            'listStaff' => $listStaff,
            'quantity' => $quantity,
            'created_at' => $input['time'],
            'pipeline_name' => $pipelineName['pipeline_name']
        ];

        return Excel::download(new ExportLeadReportStaff($data), 'export-lead-report-staff.xlsx');
    }
    /**
     * View table báo cáo deal theo nhân viên
     *
     * @param $input
     * @return array
     */
    public function dataViewDealReportStaff($input)
    {
        $mStaff = new StaffsTable();
        $mJourney = new JourneyTable();
        $mCustomerDeal = new CustomerDealTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listStaff = $mStaff->getStaffOptionByFilter($input);
        $startTime = null;
        $endTime = null;


        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $kq = [];
        $quantity = [];
        foreach ($listStaff as $k => $v) {
            $sumRow = 0;
            $kq = $mCustomerDeal->getQuantityJourneyByStaff($v['staff_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
                $sumRow += $quantity[$k][$k1];
            }
            // tổng từng dòng
            $quantity[$k][count($listJourney)] = $sumRow;
        }

        // Tính tổng từng hành trình
        for ($i = 0; $i <= count($listJourney); $i++) {
            $sum = 0;
            foreach ($listStaff as $k => $v) {
                $sum += $quantity[$k][$i];
            }
            $quantity['sumColumn'][] = $sum;
        }

        $html = \View::make('customer-lead::report.report-according-to-deal-staff', [
            'listStaff' => $listStaff,
            'listJourney' => $listJourney,
            'quantity' => $quantity
        ])->render();

        return [
            'html' => $html
        ];
    }


    /**
     * Data deal popup deal report staff
     *
     * @param $filter
     * @return mixed
     */
    public function dataPopupDealReportStaff(&$filter)
    {
        $mCustomerDeal = new CustomerDealTable();
        return $mCustomerDeal->getListCustomerDeal($filter);
    }

    /**
     * Export excel popup deal report staff
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function ExportExcelPopupDealReportStaff($input)
    {
        $heading = [
            __('TÊN DEAL'),
            __('MÃ DEAL'),
            __('TÊN NHÂN VIÊN'),
            __('NGÀY TẠO'),
        ];

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        $data = [];
        $mCustomerDeal = new CustomerDealTable();
        $allData = [];
        $allData = $mCustomerDeal->getListCustomerDealExport($input);
        if (count($allData) > 0) {
            foreach ($allData as $item) {
                $data [] = [
                    $item['deal_name'],
                    $item['deal_code'],
                    $item['full_name'],
                    date("d/m/Y",strtotime($item['created_at']))
                ];
            }
        }

        return Excel::download(new ExportFile($heading, $data), 'export-popup-deal-report.xlsx');
    }
    /**
     * View table báo cáo chuyển đổi khách hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function dataViewReportConvert($input)
    {
        $mJourney = new JourneyTable();
        $mCustomerSource = new CustomerSourceTable();
        $mCustomerLead = new CustomerLeadTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listCustomerSource = $mCustomerSource->getOptionByFilter($input);

        $startTime = null;
        $endTime = null;

        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $quantity = [];
        $kq = [];
        foreach ($listCustomerSource as $k => $v) {
            $kq = $mCustomerLead->getQuantityJourneyConverted($v['customer_source_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
            }
        }

        $html = \View::make('customer-lead::report.table-report-convert', [
            'listCustomerSource' => $listCustomerSource,
            'listJourney' => $listJourney,
            'quantity' => $quantity
        ])->render();
        return [
            'html' => $html
        ];
    }

    /**
     * Repo xử lý export view report convert
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcelViewReportConvert($input)
    {
        $mPipeline = new PipelineTable();
        $pipelineName = $mPipeline->getDetailByCode($input['pipeline_code']);
        $mJourney = new JourneyTable();
        $mCustomerSource = new CustomerSourceTable();
        $mCustomerLead = new CustomerLeadTable();

        $listJourney = $mJourney->getJourneyByPipeline($input['pipeline_code']);
        $listCustomerSource = $mCustomerSource->getOptionByFilter($input);

        $startTime = null;
        $endTime = null;

        if ($input["time"] != null) {
            $time = explode(" - ", $input["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d');
        }

        $quantity = [];
        $kq = [];
        foreach ($listCustomerSource as $k => $v) {
            $kq = $mCustomerLead->getQuantityJourneyConverted($v['customer_source_id'], $input['pipeline_code'], $startTime, $endTime);
            // gán các phần tử mảng số lượng của các hành trình = 0
            foreach ($listJourney as $temp){
                $quantity[$k][] = 0;
            }
            // gán số lượng của từng hành trình
            foreach ($listJourney as $k1 => $v1) {
                foreach ($kq as $k2 => $v2) {
                    if ($v1['journey_code'] == $v2['journey_code']){
                        $quantity[$k][$k1] = $v2['quantity'];
                        break;
                    }
                }
            }
        }
        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        //Lấy dữ liệu export
        $data = [
            'listCustomerSource' => $listCustomerSource,
            'listJourney' => $listJourney,
            'quantity' => $quantity,
            'created_at' => $input['time'],
            'pipeline_name' => $pipelineName['pipeline_name']
        ];

        return Excel::download(new ExportReportConvert($data), 'export-lead-report.xlsx');
    }

    /**
     * Lấy data chart
     * @param $input
     * @return mixed|void
     */
    public function getDataChartLead($input)
    {
        try {
            $customerLead = app()->get(CustomerLeadTable::class);
            $mCustomerLog = app()->get(CpoCustomerLogTable::class);
            $journey = app()->get(JourneyTable::class);

            $listJourney = $journey->getJourneyByPipeline($input['pipeline']);

            $listLead = $mCustomerLog->getListLog($input['pipeline'],$input);

            if (count($listLead) != 0){
                $listLead = collect($listLead)->groupBy('value_new');
            }

            $data = [];
            $dataPercent = [];
            $color = [];
            $colorPercent = [];
            foreach ($listJourney as $key => $item){

                $data[] = [
                    $item['journey_name'],
                    isset($listLead[$item['journey_code']]) ? count($listLead[$item['journey_code']]) : 0
                ];

                if (isset($listLead[$item['journey_code']])){
                    $color[] = $item['pipeline_color'];
                }

                if ($key == 0) {
                    $dataPercent[] = [
                        $item['journey_name'],
                        isset($listLead[$item['journey_code']]) ? 100 : 0
                    ];
                    if (isset($listLead[$item['journey_code']])){
                        $colorPercent[] = $item['pipeline_color'];
                    }
                } else {
                    $dataPercent[] = [
                        $item['journey_name'],
                        isset($listLead[$item['journey_code']]) ? count($listLead[$item['journey_code']]) / $data[0][1] * 100 : 0
                    ];

                    if (isset($listLead[$item['journey_code']])){
                        $colorPercent[] = $item['pipeline_color'];
                    }
                }
            }

            $viewListLead = $this->tableLeadSearch($input);

            if ($viewListLead['error'] == true){
                return [
                    'error' => true,
                    'message' => $viewListLead['message'],
                ];
            }

            $viewListSource = $this->tableSourceSearch($input);

            if ($viewListSource['error'] == true){
                return [
                    'error' => true,
                    'message' => $viewListSource['message'],
                ];
            }

            $convertDeal = 0;
            $convertCustomer = 0;
            $convertFail = 0;
//            Tổng số khách hàng tiềm năng
            $totalLead = count($customerLead->getListLead($input));
            if ($totalLead != 0){
                $input1 = $input;
                $input1['is_convert'] = 1;
                $input1['convert_object_type'] = 'deal';
                $convertDeal = count($customerLead->getListLead($input1));
                $convertDeal = $convertDeal/$totalLead*100;

                $input2 = $input;
                $input2['is_convert'] = 1;
                $input2['convert_object_type'] = 'customer';
                $convertCustomer = count($customerLead->getListLead($input2));
                $convertCustomer = $convertCustomer/$totalLead*100;

                $input3 = $input;
                $input3['is_convert_fail'] = 1;

                $convertFail = count($customerLead->getListLead($input3));
                $convertFail = $convertFail/$totalLead*100;
            }
            $data = collect($data)->where('1','<>',0)->toArray();
            $dataPercent = collect($dataPercent)->where('1','<>',0)->toArray();

            return [
                'error' => false,
                'data' => array_values($data),
                'dataPercent' => array_values($dataPercent),
                'viewLead' => $viewListLead['view'],
                'viewSource' => $viewListSource['view'],
                'totalLead' => $totalLead,
                'convertDeal' => round($convertDeal,2),
                'convertCustomer' => round($convertCustomer,2),
                'convertFail' => round($convertFail,2),
                'color' => $color,
                'colorPercent' => $colorPercent
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Lấy data thất bại',
                '__message' => $e->getMessage()
            ];
        }
    }

    public function tableLeadSearch($input){
        try {

            $customerLead = app()->get(CustomerLeadTable::class);

            $journey = app()->get(JourneyTable::class);


            $listLead = $customerLead->getListLeadPaginate($input);

            if (count($listLead) != 0){
                foreach($listLead as $key => $item){
                    $total = $customerLead->getTotalJourney($item['sale_id'],$item['pipeline_code']);
                    $listLead[$key]['total_pipeline'] = 0;
                    $listLead[$key]['pipeline'] = [];
                    if (count($total) != 0){
                        $listLead[$key]['total_pipeline'] = count($total);
                        $listLead[$key]['pipeline'] = collect($total)->groupBy('journey_code');
                    }

                }
            }

            $listJourney = $journey->getJourneyByPipeline($input['pipeline']);

            $view = view('customer-lead::report.append.append-table-lead',[
                'listJourney' => $listJourney,
                'listLead' => $listLead
            ])->render();

            return [
                'error' => false,
                'view' => $view,
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Lấy data thất bại',
                '__message' => $e->getMessage()
            ];
        }
    }

    public function tableSourceSearch($input){
        try {

            $customerLead = app()->get(CustomerLeadTable::class);

            $mCustomerSource = app()->get(CustomerSourceTable::class);

            $journey = app()->get(JourneyTable::class);

            $listSource = $mCustomerSource->getList($input);

            if (count($listSource) != 0){
                foreach($listSource as $key => $item){
                    $total = $customerLead->getTotalJourneySource($item['customer_source_id'],$input['pipeline'],$input);
                    $listSource[$key]['total_pipeline'] = 0;
                    $listSource[$key]['pipeline'] = [];
                    if (count($total) != 0){
                        $listSource[$key]['total_pipeline'] = count($total);
                        $listSource[$key]['pipeline'] = collect($total)->groupBy('journey_code');
                    }

                }
            }

            $listJourney = $journey->getJourneyByPipeline($input['pipeline']);

            $view = view('customer-lead::report.append.append-table-lead-source',[
                'listJourney' => $listJourney,
                'listSource' => $listSource
            ])->render();

            return [
                'error' => false,
                'view' => $view,
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Lấy data thất bại',
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy data chart
     * @param $input
     * @return mixed|void
     */
    public function getDataChartDeal($input)
    {
        try {
            $customerLead = app()->get(CustomerLeadTable::class);
            $customerDeal = app()->get(CustomerDealTable::class);
            $mCustomerLog = app()->get(CpoCustomerLogTable::class);
            $journey = app()->get(JourneyTable::class);

            $listJourney = $journey->getJourneyByPipeline($input['pipeline']);

            $listDeal = $mCustomerLog->getListLogDeal($input['pipeline'],$input);

            if (count($listDeal) != 0){
                $listDeal = collect($listDeal)->groupBy('value_new');
            }

            $data = [];
            $dataPercent = [];
            $color = [];
            $colorPercent = [];
            foreach ($listJourney as $key => $item){

                $data[] = [
                    $item['journey_name'],
                    isset($listDeal[$item['journey_code']]) ? count($listDeal[$item['journey_code']]) : 0
                ];

                if (isset($listDeal[$item['journey_code']])){
                    $color[] = $item['pipeline_color'];
                }

                if ($key == 0) {
                    $dataPercent[] = [
                        $item['journey_name'],
                        isset($listDeal[$item['journey_code']]) ? 100 : 0
                    ];
                    if (isset($listDeal[$item['journey_code']])){
                        $colorPercent[] = $item['pipeline_color'];
                    }
                } else {
                    $dataPercent[] = [
                        $item['journey_name'],
                        isset($listDeal[$item['journey_code']]) ? count($listDeal[$item['journey_code']]) / $data[0][1] * 100 : 0
                    ];
                    if (isset($listDeal[$item['journey_code']])){
                        $colorPercent[] = $item['pipeline_color'];
                    }
                }
            }

            $viewListLead = $this->tableDealSearch($input);

            if ($viewListLead['error'] == true){
                return [
                    'error' => true,
                    'message' => $viewListLead['message'],
                ];
            }

            $convertDeal = 0;
            $convertCustomer = 0;
            $convertFail = 0;
//            Tổng số khách hàng tiềm năng
            $totalLead = count($customerDeal->getListDeal($input));
//            $totalLead = 0;
            if ($totalLead != 0){
                $input1 = $input;
                $input1['is_convert_contract'] = 1;
                $input1['convert_contract_type'] = 'paysuccess';
                $convertDeal = count($customerDeal->getListDeal($input1));
                $convertDeal = $convertDeal/$totalLead*100;

                $input2 = $input;
                $input2['is_convert_contract'] = 1;
                $input2['convert_contract_type'] = 'paysuccess';
                $convertCustomer = count($customerDeal->getListDeal($input2));
                $convertCustomer = $convertCustomer/$totalLead*100;

                $input3 = $input;
                $input3['is_convert_contract_fail'] = 1;
                $input3['convert_contract_type'] = 'ordercancle';
                $convertFail = count($customerDeal->getListDeal($input3));
                $convertFail = $convertFail/$totalLead*100;
            }

            $data = collect($data)->where('1','<>',0)->toArray();
            $dataPercent = collect($dataPercent)->where('1','<>',0)->toArray();

            return [
                'error' => false,
                'data' => array_values($data),
                'dataPercent' => array_values($dataPercent),
                'viewLead' => $viewListLead['view'],
                'totalLead' => $totalLead,
                'convertDeal' => round($convertDeal,2),
                'convertCustomer' => round($convertCustomer,2),
                'convertFail' => round($convertFail,2),
                'color' => $color,
                'colorPercent' => $colorPercent
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Lấy data thất bại',
                '__message' => $e->getMessage()
            ];
        }
    }

    public function tableDealSearch($input){
        try {

            $customerDeal = app()->get(CustomerDealTable::class);

            $journey = app()->get(JourneyTable::class);


            $listDeal = $customerDeal->getListLeadPaginate($input);

            if (count($listDeal) != 0){
                foreach($listDeal as $key => $item){
                    $total = $customerDeal->getTotalJourney($item['sale_id'],$item['pipeline_code'],$input);
                    $listDeal[$key]['total_pipeline'] = 0;
                    $listDeal[$key]['pipeline'] = [];
                    if (count($total) != 0){
                        $listDeal[$key]['total_pipeline'] = count($total);
                        $listDeal[$key]['pipeline'] = collect($total)->groupBy('journey_code');
                    }
                }
            }

            $listJourney = $journey->getJourneyByPipeline($input['pipeline']);

            $view = view('customer-lead::report.append.append-table-lead',[
                'listJourney' => $listJourney,
                'listLead' => $listDeal
            ])->render();

            return [
                'error' => false,
                'view' => $view,
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => 'Lấy data thất bại',
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Thay đổi phòng ban lấy danh sách nhân viên
     * @param $input
     * @return mixed|void
     */
    public function changeDepartment($input)
    {
        try {
            $mStaff = app()->get(StaffsTable::class);
            if (isset($input['department_id'])){
                $listStaff = $mStaff->getOptionStaffByDepartment([$input['department_id']]);
            } else {
                $listStaff = $mStaff->getStaffOption();
            }

            $view = view('customer-lead::report.append.append-option',[
                'optionStaff' => $listStaff
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Lấy danh sách nhân viên thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }
}