<?php


namespace Modules\Kpi\Repositories\Report;


use Carbon\Carbon;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Kpi\Models\BranchesTable;
use Modules\Kpi\Models\BudgetMarketingTable;
use Modules\Kpi\Models\CalculateKpiTable;
use Modules\Kpi\Models\DepartmentsTable;
use Modules\Kpi\Models\KpiCriteriaTable;
use Modules\Kpi\Models\KpiNoteDetailTable;
use Modules\Kpi\Models\StaffsTable;

class _ReportRepo implements _ReportRepoInterface
{

    protected $monthArr = [
        [1,2,3],
        [4,5,6],
        [7,8,9],
        [10,11,12],
    ];

    protected $arrCriteriaDefault = [26,10,11,28,29,30,31];

    public function __construct()
    {
    }

    /**
     * Lấy danh sách chi nhánh
     */
    public function getlistBranch(){
        $mBranch = app()->get(BranchesTable::class);
        return $mBranch->getBranch();
    }

    public function getDepartment($brand_id = null){
        $mDepartment = app()->get(DepartmentsTable::class);
        $list = $mDepartment->getDepartment($brand_id);

        return $list;
    }

    /**
     * Lấy danh sách phòng ban
     * @param null $brand_id
     * @return mixed|void
     */
    public function getListDepartment($brand_id = null)
    {
        if ($brand_id == null){
            $list = [];
        } else {
            $list = $this->getDepartment($brand_id);
        }

        $view = view('kpi::report.append.option_department',[
            'list' => $list
        ])->render();

        $arrDepartmentId = [];
//        if (count($list) != 0){
//            $arrDepartmentId = collect($list)->pluck('department_id');
//        }

        $infoStaff = $this->getListStaff($arrDepartmentId);

        return [
            'list_department' => $list,
            'view_department' => $view,
            'list_staff' => $infoStaff['list_staff'],
            'view_staff' => $infoStaff['view_staff'],
        ];
    }

//    Lấy danh sách nhân viên
    public function getStaff($filter){
        $mStaff = app()->get(StaffsTable::class);

        return $mStaff->getStaff($filter);
    }

    public function getListStaff($arrDepartmentId){
//        Lấy danh sách + view nhân viên theo phòng ban
        if (count($arrDepartmentId) == 0) {
            $listStaff = [];
        } else {
            $listStaff = $this->getStaff(['array_department_id' => $arrDepartmentId]);
        }
        $viewStaff = view('kpi::report.append.option_staff',[
            'list' => $listStaff
        ])->render();

        return [
            'list_staff' => $listStaff,
            'view_staff' => $viewStaff
        ];
    }


    /**
     * Thay đổi chi nhánh
     * @param $data
     * @return mixed|void
     */
    public function changeBranch($data)
    {
        try {

            $branch_id = null ;
            if (isset($data['branch_id'])){
                $branch_id = $data['branch_id'];
            }

            $listDepartment = $this->getListDepartment($branch_id);

            return [
                'error' => false,
                'view_department' => $listDepartment['view_department'],
                'view_staff' => $listDepartment['view_staff']
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Thay đổi chi nhánh thất bại')
            ];
        }
    }

    public function changeDepartment($data)
    {
        try {

            $arrDepartmentId = [] ;
            if (isset($data['department_id'])){
                $arrDepartmentId = [$data['department_id']];
            }

            $infoStaff = $this->getListStaff($arrDepartmentId);

            return [
                'error' => false,
                'view_staff' => $infoStaff['view_staff']
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Thay đổi phòng ban thất bại')
            ];
        }
    }

    /**
     * Lấy thông tin chart và table
     * @param $data
     * @return mixed|void
     */
    public function showChartTable($data)
    {
        try {
            $mCalculateKpi = app()->get(CalculateKpiTable::class);
            $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);


//            Biểu đồ 1 : Tất cả chi nhánh | Tháng trước hoặc tháng này
            if (!isset($data['branch_id']) && !isset($data['department_id']) && in_array($data['date_type'],['this_month','after_month'])) {

//                Lấy số tháng , năm
                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                if ($data['date_type'] == 'after_month'){
                    if ($month == 1){
                        $month = 12;
                        $year -= 1;
                    } else{
                        $month -= 1;
                    }
                }
//                Lấy danh sách chi nhánh
                $listBranch = $this->getlistBranch();
                $listCriteria = $mKpiNoteDetail->getListCriteria([$month],$year);
                
//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpi($month,$year);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy('branch_id');
                }
                $chartData = [];
                foreach ($listBranch as $key => $item){
                    $dataList = $mCalculateKpi->getDataCriteria($month,$year,['branch_id' => $item['branch_id']]);
                    if(count($dataList) != 0){
                       
                        $dataList = collect($dataList)->keyBy('kpi_criteria_id');
                    }
                    $chartData[$key] = [
                        'name' => $item['branch_name'],
                        'y' => isset($detailKpi[$item['branch_id']]) ? round((double)$detailKpi[$item['branch_id']]['total_kpi'],2) : 0,
                        'list' => $dataList
                    ];
                }

                $view = view('kpi::report.append.table-chart-1',[
                    'data' => $chartData,
                    'listCriteria' => $listCriteria
                ])->render();

                return [
                    'error' => false,
                    'chart' => 1,
                    'data' => $chartData,
                    'view' => $view
                ];

//                Biểu đồ 2 : Tất cả chi nhánh | Quý trước hoặc Quý này
            } else if(!isset($data['branch_id']) && !isset($data['department_id']) && in_array($data['date_type'],['this_precious','after_precious'])) {

//                Lấy danh sách chi nhánh
                $listBranch = $this->getlistBranch();

                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                $arrMonth = [];
//        Kiểm tra quý tháng
                foreach ($this->monthArr as $key => $item){
                    if (in_array($month,$item)){
                        if ($data['date_type'] == 'after_precious'){
                            if ($key == 0){
                                $tmpKey = 4;
                                $year -= 1;
                            } else {
                                $tmpKey = $key - 1;
                            }
                            $arrMonth = $this->monthArr[$tmpKey];
                        } else {
                            $arrMonth = $item;
                        }
                    }
                }

                $listCriteria = $mKpiNoteDetail->getListCriteria($arrMonth,$year);

                if (count($listCriteria) != 0){
                    $listCriteria = collect($listCriteria)->groupBy(function ($item){
                        return $item['branch_id'].'_'.$item['effect_month'];
                    });
                }

                $rowSpan = 0 ;

                foreach ($listCriteria as $itemCriteria){
                    if (count($itemCriteria) > $rowSpan){
                        $rowSpan = count($itemCriteria);
                    }
                }

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpiArrMonth($arrMonth,$year);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy(function ($item, $key){
                        return $item['branch_id'].'_'.$item['month'];
                    });
                }
                $chartData = [];
                foreach ($listBranch as $key => $item){
                    $chartData['categories'][] = $item['branch_name'];

                    $chartData['categoriesList'][] = [
                        'branch_name' => $item['branch_name'],
                        'branch_id' => $item['branch_id']
                    ];
                    foreach ($arrMonth as $keymonth => $itemMonth){
                        $chartData['month'][$keymonth]['name'] = __('Tháng').' '.$itemMonth;
                        $dataMonth = $mCalculateKpi->getTotalKpiArrMonth([$itemMonth],$year);
                        $dataMonthTmp = [];
                        if (count($dataMonth) != 0){
                            $dataMonthTmp = collect($dataMonth)->keyBy(function ($item, $key){
                                return $item['branch_id'].'_'.$item['month'];
                            });
                        }
                        $chartData['month'][$keymonth]['data'][] = isset($dataMonthTmp[$item['branch_id'].'_'.$itemMonth]) ? round((double)$dataMonthTmp[$item['branch_id'].'_'.$itemMonth]['total_kpi'],2) : 0;
                        $dataList = $mCalculateKpi->getDataCriteriaArrMonth([$itemMonth],$year);
                        if (count($dataList) != 0){
                            $dataList = collect($dataList)->keyBy(function ($item,$key){
                                return $item['branch_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                            });
                        }
                        $chartData['month'][$keymonth]['list'] = $dataList;
                    }

                }

                $view = view('kpi::report.append.table-chart-2',[
                    'data' => $chartData,
                    'arrMonth' => $arrMonth,
                    'listCriteria' => $listCriteria,
                    'rowSpan' => $rowSpan
                ])->render();

                return [
                    'error' => false,
                    'chart' => 2,
                    'data' => $chartData,
                    'view' => $view
                ];

//                Biểu đồ 3 : Tất cả chi nhánh | Chọn năm
            } else if(!isset($data['branch_id']) && !isset($data['department_id']) && in_array($data['date_type'],['select_year'])) {
                $listBranch = $this->getlistBranch();

                $year = $data['yearpicker'];
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $listCriteria = $mKpiNoteDetail->getListCriteria($arrMonth,$year);

                if (count($listCriteria) != 0){
                    $listCriteria = collect($listCriteria)->groupBy(function ($item){
                        return $item['branch_id'].'_'.$item['effect_month'];
                    });
                }

                $rowSpan = 0 ;

                foreach ($listCriteria as $itemCriteria){
                    if (count($itemCriteria) > $rowSpan){
                        $rowSpan = count($itemCriteria);
                    }
                }

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpiArrMonth($arrMonth,$year);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy(function ($item, $key){
//                        return $item['branch_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                        return $item['branch_id'].'_'.$item['month'];
                    });
                }
                $chartData = [];
                foreach ($listBranch as $key => $item){
                    $chartData['data'][$key]['name'] = $item['branch_name'];
                    $chartData['data'][$key]['branch_id'] = $item['branch_id'];
                    for($i = 1; $i <= 12 ;$i++){
                        $chartData['data'][$key]['data'][] = isset($detailKpi[$item['branch_id'].'_'.$i]) ? round((double)$detailKpi[$item['branch_id'].'_'.$i]['total_kpi'],2) : 0;

                        $chartData['month'][$i]['name'] = __('Tháng').' '.$i;
                        $dataMonth = $mCalculateKpi->getTotalKpiArrMonth([$i],$year);
                        $dataMonthTmp = [];
                        if (count($dataMonth) != 0){
                            $dataMonthTmp = collect($dataMonth)->groupBy(function ($item, $key){
                                return $item['branch_id'].'_'.$item['month'];
                            });
                        }
                        $dataList = $mCalculateKpi->getDataCriteriaArrMonth([$i],$year);

                        if (count($dataList) != 0){
                            $dataList = collect($dataList)->groupBy(function ($item,$key){
                                return $item['branch_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                            });
                        }
                        $chartData['month'][$i]['list'] = $dataList;
                    }
                }

                $view = view('kpi::report.append.table-chart-3',[
                    'data' => $chartData,
                    'arrMonth' => $arrMonth,
                    'listCriteria' => $listCriteria,
                    'rowSpan' => $rowSpan,
                    'year' => $year,
                    'detailKpi' => $detailKpi
                ])->render();

                return [
                    'error' => false,
                    'chart' => 3,
                    'data' => $chartData['data'],
                    'view' => $view
                ];

//                Biểu đồ 4 : Chọn chi nhánh | Tháng trước hoặc tháng này
            } else if (isset($data['branch_id']) && !isset($data['department_id']) && in_array($data['date_type'],['this_month','after_month'])) {

//                Lấy số tháng , năm
                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                if ($data['date_type'] == 'after_month') {
                    if ($month == 1) {
                        $month = 12;
                        $year -= 1;
                    } else {
                        $month -= 1;
                    }
                }
//                Lấy danh sách phòng ban
                $listDepartment = $this->getDepartment($data['branch_id']);
                $listCriteria = $mKpiNoteDetail->getListCriteria([$month], $year);

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpi($month, $year);
                if (count($detailKpi) != 0) {
                    $detailKpi = collect($detailKpi)->keyBy('department_id');
                }
                $chartData = [];
                foreach ($listDepartment as $key => $item) {
                    $dataList = $mCalculateKpi->getDataCriteria($month, $year,['department_id' => $item['department_id']]);

                    if (count($dataList) != 0) {
                        $dataList = collect($dataList)->keyBy('kpi_criteria_id');
                    }
                    $chartData[$key] = [
                        'name' => $item['department_name'],
                        'y' => isset($detailKpi[$item['department_id']]) ? round((double)$detailKpi[$item['department_id']]['total_kpi'], 2) : 0,
                        'list' => $dataList
                    ];
                }

                $view = view('kpi::report.append.table-chart-4', [
                    'data' => $chartData,
                    'listCriteria' => $listCriteria
                ])->render();

                return [
                    'error' => false,
                    'chart' => 1,
                    'data' => $chartData,
                    'view' => $view
                ];

//                Biểu đồ 5 : Chọn chi nhánh | Quý trước hoặc quý này
            } else if(isset($data['branch_id']) && !isset($data['department_id']) && in_array($data['date_type'],['this_precious','after_precious'])) {

//                Lấy danh sách phòng ban
                $listDepartment = $this->getDepartment($data['branch_id']);

                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                $arrMonth = [];
//        Kiểm tra quý tháng
                foreach ($this->monthArr as $key => $item){
                    if (in_array($month,$item)){
                        if ($data['date_type'] == 'after_precious'){
                            if ($key == 0){
                                $tmpKey = 4;
                                $year -= 1;
                            } else {
                                $tmpKey = $key - 1;
                            }
                            $arrMonth = $this->monthArr[$tmpKey];
                        } else {
                            $arrMonth = $item;
                        }
                    }
                }

                $listCriteria = $mKpiNoteDetail->getListCriteria($arrMonth,$year);

                if (count($listCriteria) != 0){
                    $listCriteria = collect($listCriteria)->groupBy(function ($item){
                        return $item['department_id'].'_'.$item['effect_month'];
                    });
                }

                $rowSpan = 0 ;

                foreach ($listCriteria as $itemCriteria){
                    if (count($itemCriteria) > $rowSpan){
                        $rowSpan = count($itemCriteria);
                    }
                }

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpiArrMonth($arrMonth,$year);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy(function ($item, $key){
                        return $item['department_id'].'_'.$item['month'];
                    });
                }
                $chartData = [];
                foreach ($listDepartment as $key => $item){
                    $chartData['categories'][] = $item['department_name'];

                    $chartData['categoriesList'][] = [
                        'department_name' => $item['department_name'],
                        'department_id' => $item['department_id']
                    ];
                    foreach ($arrMonth as $keymonth => $itemMonth){
                        $chartData['month'][$keymonth]['name'] = __('Tháng').' '.$itemMonth;
                        $dataMonth = $mCalculateKpi->getTotalKpiArrMonth([$itemMonth],$year);
                        $dataMonthTmp = [];
                        if (count($dataMonth) != 0){
                            $dataMonthTmp = collect($dataMonth)->keyBy(function ($item, $key){
                                return $item['department_id'].'_'.$item['month'];
                            });
                        }
                        $chartData['month'][$keymonth]['data'][] = isset($dataMonthTmp[$item['department_id'].'_'.$itemMonth]) ? round((double)$dataMonthTmp[$item['department_id'].'_'.$itemMonth]['total_kpi'],2) : 0;
                        $dataList = $mCalculateKpi->getDataCriteriaArrMonth([$itemMonth],$year);
                        if (count($dataList) != 0){
                            $dataList = collect($dataList)->keyBy(function ($item,$key){
                                return $item['department_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                            });
                        }
                        $chartData['month'][$keymonth]['list'] = $dataList;
                    }

                }

                $view = view('kpi::report.append.table-chart-5',[
                    'data' => $chartData,
                    'arrMonth' => $arrMonth,
                    'listCriteria' => $listCriteria,
                    'rowSpan' => $rowSpan
                ])->render();

                return [
                    'error' => false,
                    'chart' => 2,
                    'data' => $chartData,
                    'view' => $view
                ];

//                Biểu đồ 6 : Chọn chi nhánh | Chọn năm
            } else if(isset($data['branch_id']) && !isset($data['department_id']) && in_array($data['date_type'],['select_year'])) {
                $listDepartment = $this->getDepartment($data['branch_id']);

                $year = $data['yearpicker'];
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $listCriteria = $mKpiNoteDetail->getListCriteria($arrMonth,$year);

                if (count($listCriteria) != 0){
                    $listCriteria = collect($listCriteria)->groupBy(function ($item){
                        return $item['department_id'].'_'.$item['effect_month'];
                    });
                }

                $rowSpan = 0 ;

                foreach ($listCriteria as $itemCriteria){
                    if (count($itemCriteria) > $rowSpan){
                        $rowSpan = count($itemCriteria);
                    }
                }

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpiArrMonth($arrMonth,$year);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy(function ($item, $key){
//                        return $item['branch_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                        return $item['branch_id'].'_'.$item['month'];
                    });
                }
                $chartData = [];
                foreach ($listDepartment as $key => $item){
                    $chartData['data'][$key]['name'] = $item['department_name'];
                    $chartData['data'][$key]['department_id'] = $item['department_id'];
                    for($i = 1; $i <= 12 ;$i++){
                        $chartData['data'][$key]['data'][] = isset($detailKpi[$item['department_id'].'_'.$i]) ? round((double)$detailKpi[$item['department_id'].'_'.$i]['total_kpi'],2) : 0;

                        $chartData['month'][$i]['name'] = __('Tháng').' '.$i;
                        $dataMonth = $mCalculateKpi->getTotalKpiArrMonth([$i],$year);
                        $dataMonthTmp = [];
                        if (count($dataMonth) != 0){
                            $dataMonthTmp = collect($dataMonth)->groupBy(function ($item, $key){
                                return $item['department_id'].'_'.$item['month'];
                            });
                        }
                        $dataList = $mCalculateKpi->getDataCriteriaArrMonth([$i],$year);

                        if (count($dataList) != 0){
                            $dataList = collect($dataList)->groupBy(function ($item,$key){
                                return $item['department_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                            });
                        }
                        $chartData['month'][$i]['list'] = $dataList;
                    }
                }

                $view = view('kpi::report.append.table-chart-6',[
                    'data' => $chartData,
                    'arrMonth' => $arrMonth,
                    'listCriteria' => $listCriteria,
                    'rowSpan' => $rowSpan,
                    'year' => $year,
                    'detailKpi' => $detailKpi
                ])->render();

                return [
                    'error' => false,
                    'chart' => 3,
                    'data' => $chartData['data'],
                    'view' => $view
                ];

//                Biểu đồ 7 : Chọn chi nhánh | Chọn phòng ban | Tháng trước hoặc tháng này
            }  else if (isset($data['branch_id']) && isset($data['department_id']) && in_array($data['date_type'],['this_month','after_month'])) {

//                Lấy số tháng , năm
                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                if ($data['date_type'] == 'after_month') {
                    if ($month == 1) {
                        $month = 12;
                        $year -= 1;
                    } else {
                        $month -= 1;
                    }
                }
//                Lấy danh sách nhân viên
                $listStaff = $this->getStaff($data);

                $listCriteria = $mKpiNoteDetail->getListCriteria([$month], $year);

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpi($month, $year,$data);
                if (count($detailKpi) != 0) {
                    $detailKpi = collect($detailKpi)->keyBy('staff_id');
                }
                $chartData = [];
                $chartDataUpdate = [];
                foreach ($listStaff as $key => $item) {
                    $dataList = $mCalculateKpi->getDataCriteria($month, $year,['staff_id' => $item['staff_id']]);

                    if (count($dataList) != 0) {
                        $dataList = collect($dataList)->keyBy('kpi_criteria_id');
                    }
                    $chartData[$key] = [
                        'name' => $item['full_name'],
                        'y' => isset($detailKpi[$item['staff_id']]) ? round((double)$detailKpi[$item['staff_id']]['total_kpi'], 2) : 0,
                        'list' => $dataList
                    ];

                    $chartDataUpdate['categories'][] = ' '.$item['full_name'];
                    $chartDataUpdate['data'][0]['name'] = __('Tổng Kpi');
                    $chartDataUpdate['data'][0]['data'][$key] = isset($detailKpi[$item['staff_id']]) ? round((double)$detailKpi[$item['staff_id']]['total_kpi'], 2) : 0;
                }

                $view = view('kpi::report.append.table-chart-7', [
                    'data' => $chartData,
                    'listCriteria' => $listCriteria
                ])->render();

                return [
                    'error' => false,
                    'chart' => 4,
                    'data' => $chartData,
                    'dataUpdate' => $chartDataUpdate,
                    'view' => $view
                ];

//                Biểu đồ 8 : Chọn chi nhánh | Chọn phòng ban | Quý này và quý trước
            }  else if(isset($data['branch_id']) && isset($data['department_id']) && in_array($data['date_type'],['this_precious','after_precious'])) {

//                Lấy danh sách phòng ban
                $listStaff = $this->getStaff($data);

                $year = Carbon::now()->format('Y');
                $month = Carbon::now()->format('m');
                $arrMonth = [];
//        Kiểm tra quý tháng
                foreach ($this->monthArr as $key => $item){
                    if (in_array($month,$item)){
                        if ($data['date_type'] == 'after_precious'){
                            if ($key == 0){
                                $tmpKey = 4;
                                $year -= 1;
                            } else {
                                $tmpKey = $key - 1;
                            }
                            $arrMonth = $this->monthArr[$tmpKey];
                        } else {
                            $arrMonth = $item;
                        }
                    }
                }

                $listCriteria = $mKpiNoteDetail->getListCriteria($arrMonth,$year);

                if (count($listCriteria) != 0){
                    $listCriteria = collect($listCriteria)->groupBy(function ($item){
                        return $item['staff_id'].'_'.$item['effect_month'];
                    });
                }

                $rowSpan = 0 ;

                foreach ($listCriteria as $itemCriteria){
                    if (count($itemCriteria) > $rowSpan){
                        $rowSpan = count($itemCriteria);
                    }
                }

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpiArrMonth($arrMonth,$year,$data);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy(function ($item, $key){
                        return $item['staff_id'].'_'.$item['month'];
                    });
                }
                $chartData = [];
                foreach ($listStaff as $key => $item){
                    $chartData['categories'][] = $item['full_name'];

                    $chartData['categoriesList'][] = [
                        'staff_id' => $item['staff_id'],
                        'full_name' => $item['full_name']
                    ];
                    foreach ($arrMonth as $keymonth => $itemMonth){
                        $chartData['month'][$keymonth]['name'] = __('Tháng').' '.$itemMonth;
                        $dataMonth = $mCalculateKpi->getTotalKpiArrMonth([$itemMonth],$year,$data);
                        $dataMonthTmp = [];
                        if (count($dataMonth) != 0){
                            $dataMonthTmp = collect($dataMonth)->keyBy(function ($item, $key){
                                return $item['staff_id'].'_'.$item['month'];
                            });
                        }
                        $chartData['month'][$keymonth]['data'][] = isset($dataMonthTmp[$item['staff_id'].'_'.$itemMonth]) ? round((double)$dataMonthTmp[$item['staff_id'].'_'.$itemMonth]['total_kpi'],2) : 0;
                        $dataList = $mCalculateKpi->getDataCriteriaArrMonth([$itemMonth],$year,$data);
                        if (count($dataList) != 0){
                            $dataList = collect($dataList)->keyBy(function ($item,$key){
                                return $item['staff_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                            });
                        }
                        $chartData['month'][$keymonth]['list'] = $dataList;
                    }

                }

                $view = view('kpi::report.append.table-chart-8',[
                    'data' => $chartData,
                    'arrMonth' => $arrMonth,
                    'listCriteria' => $listCriteria,
                    'rowSpan' => $rowSpan
                ])->render();

                return [
                    'error' => false,
                    'chart' => 2,
                    'data' => $chartData,
                    'view' => $view
                ];

//                Biểu đồ 9 : Chọn chi nhánh | Chọn phòng ban | Chọn năm
            }  else if(isset($data['branch_id']) && isset($data['department_id']) && in_array($data['date_type'],['select_year'])) {
                $listStaff = $this->getStaff($data);

                $year = $data['yearpicker'];
                $arrMonth = [1,2,3,4,5,6,7,8,9,10,11,12];

                $listCriteria = $mKpiNoteDetail->getListCriteria($arrMonth,$year);

                if (count($listCriteria) != 0){
                    $listCriteria = collect($listCriteria)->groupBy(function ($item){
                        return $item['staff_id'].'_'.$item['effect_month'];
                    });
                }

                $rowSpan = 0 ;

                foreach ($listCriteria as $itemCriteria){
                    if (count($itemCriteria) > $rowSpan){
                        $rowSpan = count($itemCriteria);
                    }
                }

//                Lấy tổng số % KPI
                $detailKpi = $mCalculateKpi->getTotalKpiArrMonth($arrMonth,$year,$data);
                if (count($detailKpi) != 0){
                    $detailKpi = collect($detailKpi)->keyBy(function ($item, $key){
//                        return $item['branch_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                        return $item['branch_id'].'_'.$item['month'];
                    });
                }
                $chartData = [];
                foreach ($listStaff as $key => $item){
                    $chartData['data'][$key]['name'] = $item['full_name'];
                    $chartData['data'][$key]['staff_id'] = $item['staff_id'];
                    for($i = 1; $i <= 12 ;$i++){
                        $chartData['data'][$key]['data'][] = isset($detailKpi[$item['staff_id'].'_'.$i]) ? round((double)$detailKpi[$item['staff_id'].'_'.$i]['total_kpi'],2) : 0;

                        $chartData['month'][$i]['name'] = __('Tháng').' '.$i;
                        $dataMonth = $mCalculateKpi->getTotalKpiArrMonth([$i],$year,$data);
                        $dataMonthTmp = [];
                        if (count($dataMonth) != 0){
                            $dataMonthTmp = collect($dataMonth)->groupBy(function ($item, $key){
                                return $item['staff_id'].'_'.$item['month'];
                            });
                        }
                        $dataList = $mCalculateKpi->getDataCriteriaArrMonth([$i],$year,$data);

                        if (count($dataList) != 0){
                            $dataList = collect($dataList)->groupBy(function ($item,$key){
                                return $item['staff_id'].'_'.$item['month'].'_'.$item['kpi_criteria_id'];
                            });
                        }
                        $chartData['month'][$i]['list'] = $dataList;
                    }
                }

                $view = view('kpi::report.append.table-chart-9',[
                    'data' => $chartData,
                    'arrMonth' => $arrMonth,
                    'listCriteria' => $listCriteria,
                    'rowSpan' => $rowSpan,
                    'year' => $year,
                    'detailKpi' => $detailKpi
                ])->render();

                return [
                    'error' => false,
                    'chart' => 3,
                    'data' => $chartData['data'],
                    'view' => $view
                ];

            }

        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm thất bại')
            ];
        }
    }

    /**
     * Tìm kiếm dữ liệu theo tháng
     * @param $data
     * @return mixed|void
     */
    public function searchMonth($data)
    {
        try {

            $mCalculateKpi = app()->get(CalculateKpiTable::class);
            $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);
            $mBudgetMarketing = app()->get(BudgetMarketingTable::class);
            $mKpiCriteria = app()->get(KpiCriteriaTable::class);

            if (!isset($data['department_id'])){
                $data['budget_type'] = 0;
            }

            $data['start_month'] = Carbon::createFromFormat('Y',$data['year'])->startOfYear()->format('Y-m-d');
            $data['end_month'] = Carbon::createFromFormat('Y',$data['year'])->endOfYear()->format('Y-m-d');
//            Lấy danh sách budget
            $listBudget = $mBudgetMarketing->getAll($data);

            if (count($listBudget) != 0){
                $listBudget = collect($listBudget)->keyBy(function ($item) use ($data){

                    if (!isset($data['department_id'])){
                        return $item['department_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->format('Y-m');
                    } else {
                        if ($item['budget_type'] == 0){
                            return $item['department_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->format('Y-m');
                        } else {
                            return $item['department_id'].'_'.$item['team_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->format('Y-m');

                        }
                    }
                });
            }

//            Danh sách tiêu chí hard
            $listGroupCriteriaTmp = $mKpiCriteria->getAll(['arrCriteriaId' => $this->arrCriteriaDefault]);
            $listGroupCriteria = [];
            if (count($listGroupCriteriaTmp) != 0){
                $listGroupCriteriaTmp = collect($listGroupCriteriaTmp)->keyBy('kpi_criteria_id');

                foreach ($this->arrCriteriaDefault as $item){
                    $listGroupCriteria[$item] = $listGroupCriteriaTmp[$item];
                }
            }

//            Lấy danh sách phòng ban và team có tiêu chí
            $listCriteria = $mCalculateKpi->getListCriteria($data);

            if (!isset($data['department_id']) && count($listCriteria) != 0){
                $listCriteria = collect($listCriteria)->groupBy(function ($item){
                    return $item['department_id'];
                });
            }

            if (isset($data['department_id']) && count($listCriteria) != 0){
                $listCriteria = collect($listCriteria)->groupBy(function ($item){
                    return $item['team_id'];
                });
            }


//            Xử lý mảng
            $arrData = [];

            foreach ($listCriteria as $key => $item){
                $item = collect($item)->keyBy('kpi_criteria_id')->toArray();

                $n = 0 ;
                foreach ($listGroupCriteria as $itemCriteriaDefault){
                    if ($n == 0){
                        if (!isset($data['department_id'])) {
                            $arrData[$key][] = [
                                'name' => array_values($item)[0]['department_name'],
                                'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                            ];
                        } else {
                            $arrData[$key][] = [
                                'name' => array_values($item)[0]['team_name'],
                                'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                            ];
                        }


                        $keyLast = array_key_last($arrData[$key]);

                        for ($iTmp = 1 ; $iTmp <= 12 ; $iTmp++){
                            if ($itemCriteriaDefault['kpi_criteria_id'] == 26) {
                                if (!isset($data['department_id'])){
                                    if (isset($listBudget[$key.'_'.$data['year'].'-'.($iTmp < 11 ? '0'.$iTmp : $iTmp)])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$key.'_'.$data['year'].'-'.($iTmp < 11 ? '0'.$iTmp : $iTmp)]['budget'];
                                    }else {
                                        $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                    }
                                } else {
                                    if (isset($listBudget[$data['department_id'].'_'.$data['year'].'-'.($iTmp < 11 ? '0'.$iTmp : $iTmp)])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$data['department_id'].'_'.$data['year'].'-'.($iTmp < 11 ? '0'.$iTmp : $iTmp)]['budget'];
                                    } else if (isset($listBudget[$data['department_id'].'_'.$key.'_'.$data['year'].'-'.($iTmp < 11 ? '0'.$iTmp : $iTmp)])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$data['department_id'].'_'.$key.'_'.$data['year'].'-'.($iTmp < 11 ? '0'.$iTmp : $iTmp)]['budget'];
                                    }else {
                                        $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                    }
                                }
                            }
                        }

                        $n = 1;
                    } else {
                        $arrData[$key][] = [
                            'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                        ];

                        $keyLast = array_key_last($arrData[$key]);

                        for ($iTmp = 1 ; $iTmp <= 12 ; $iTmp++){
                            if ($itemCriteriaDefault['kpi_criteria_id'] == 10){
                                if (isset($item[10]) && $item[10]['month'] == $iTmp){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[10]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 11){
                                if (isset($item[11]) && $item[11]['month'] == $iTmp){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[11]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 28){
                                if (isset($item[28]) && $item[28]['month'] == $iTmp){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[28]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 29){
                                if (isset($item[29]) && $item[29]['month'] == $iTmp){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[29]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 30){
                                if (isset($item[30]) && $item[30]['month'] == $iTmp){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[30]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 31){
                                if (isset($item[31]) && $item[31]['month'] == $iTmp){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[31]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }
                        }
                    }
                }
            }

            $view = view('kpi::report.budget-efficiency.append.month',[
                'listBudget' => $listBudget,
                'listGroupCriteria' => $listGroupCriteria,
                'listCriteria' => $listCriteria,
                'arrCriteriaDefault' => $this->arrCriteriaDefault,
                'arrData' => $arrData
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm thất bại')
            ];
        }
    }

    /**
     * Tìm kiếm theo tuần
     * @param $data
     * @return array
     * @throws \Throwable
     */
    public function searchWeek($data)
    {
        try {

            if (!isset($data['week_start'])){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn tuần bắt đầu')
                ];
            }
            if (!isset($data['week_end'])){
                return [
                    'error' => true,
                    'message' => __('Vui lòng chọn tuần kết thúc')
                ];
            }
            $data['start_month'] = date("Y-m-d", strtotime($data['week_start']));
            $data['end_month'] = Carbon::createFromFormat('Y-m-d',date("Y-m-d", strtotime($data['week_end'])))->endOfWeek()->format('Y-m-d');

            $data['arrStartDate'] = $data['start_month'];
            $data['arrEndDate'] = $data['end_month'];
            $totalWeek = Carbon::parse($data['arrStartDate'])->diffInWeeks($data['arrEndDate']);
            if (Carbon::parse($data['start_month']) > Carbon::parse($data['end_month'])){
                return [
                    'error' => true,
                    'message' => __('Tuần bắt đầu phải nhỏ hơn hoặc bằng tuần kết thúc')
                ];
            }

            $mCalculateKpi = app()->get(CalculateKpiTable::class);
            $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);
            $mBudgetMarketing = app()->get(BudgetMarketingTable::class);
            $mKpiCriteria = app()->get(KpiCriteriaTable::class);

            if (!isset($data['department_id'])){
                $data['budget_type'] = 0;
            }

//            Lấy danh sách budget
            $listBudget = $mBudgetMarketing->getAll($data);

            if (count($listBudget) != 0){
                $listBudget = collect($listBudget)->keyBy(function ($item) use ($data){

                    if (!isset($data['department_id'])){
                        return $item['department_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->weekOfYear;
                    } else {
                        if ($item['budget_type'] == 0){
                            return $item['department_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->weekOfYear;
                        } else {
                            return $item['department_id'].'_'.$item['team_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->weekOfYear;

                        }
                    }
                });
            }

//            Danh sách tiêu chí hard
            $listGroupCriteriaTmp = $mKpiCriteria->getAll(['arrCriteriaId' => $this->arrCriteriaDefault]);
            $listGroupCriteria = [];
            if (count($listGroupCriteriaTmp) != 0){
                $listGroupCriteriaTmp = collect($listGroupCriteriaTmp)->keyBy('kpi_criteria_id');

                foreach ($this->arrCriteriaDefault as $item){
                    $listGroupCriteria[$item] = $listGroupCriteriaTmp[$item];
                }
            }

//            Lấy danh sách phòng ban và team có tiêu chí
            $listCriteria = $mCalculateKpi->getListCriteria($data);

            if (!isset($data['department_id']) && count($listCriteria) != 0){
                $listCriteria = collect($listCriteria)->groupBy(function ($item){
                    return $item['department_id'];
                });
            }

            if (isset($data['department_id']) && count($listCriteria) != 0){
                $listCriteria = collect($listCriteria)->groupBy(function ($item){
                    return $item['team_id'];
                });
            }


//            Xử lý mảng
            $arrData = [];

            foreach ($listCriteria as $key => $item){
                $item = collect($item)->keyBy('kpi_criteria_id')->toArray();

                $n = 0 ;
                foreach ($listGroupCriteria as $itemCriteriaDefault){
                    if ($n == 0){
                        if (!isset($data['department_id'])) {
                            $arrData[$key][] = [
                                'name' => array_values($item)[0]['department_name'],
                                'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                            ];
                        } else {
                            $arrData[$key][] = [
                                'name' => array_values($item)[0]['team_name'],
                                'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                            ];
                        }


                        $keyLast = array_key_last($arrData[$key]);

                        for ($iTmp = 0 ; $iTmp <= $totalWeek ; $iTmp++){
                            if ($itemCriteriaDefault['kpi_criteria_id'] == 26) {
                                if (!isset($data['department_id'])){
                                    if (isset($listBudget[$key.'_'.((Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear))])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$key.'_'.(Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear)]['budget'];
                                    }else {
                                        $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                    }
                                } else {
                                    if (isset($listBudget[$data['department_id'].'_'.(Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear)])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$data['department_id'].'_'.(Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear)]['budget'];
                                    } else if (isset($listBudget[$data['department_id'].'_'.$key.'_'.(Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear)])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$data['department_id'].'_'.$key.'_'.(Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear)]['budget'];
                                    }else {
                                        $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                    }
                                }
                            }
                        }

                        $n = 1;
                    } else {
                        $arrData[$key][] = [
                            'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                        ];

                        $keyLast = array_key_last($arrData[$key]);

                        for ($iTmp = 0 ; $iTmp <= $totalWeek ; $iTmp++){
                            if ($itemCriteriaDefault['kpi_criteria_id'] == 10){
                                if (isset($item[10]) && $item[10]['week'] == Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[10]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 11){
                                if (isset($item[11]) && $item[11]['week'] == Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[11]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 28){
                                if (isset($item[28]) && $item[28]['week'] == Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[28]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 29){
                                if (isset($item[29]) && $item[29]['week'] == Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[29]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 30){
                                if (isset($item[30]) && $item[30]['week'] == Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[30]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 31){
                                if (isset($item[31]) && $item[31]['week'] == Carbon::parse($data['start_month'])->addWeeks($iTmp)->weekOfYear){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[31]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }
                        }
                    }
                }
            }

            $view = view('kpi::report.budget-efficiency.append.week',[
                'arrData' => $arrData,
                'totalWeek' => $totalWeek,
                'data' => $data
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm thất bại')
            ];
        }
    }

    /**
     * Tìm kiếm theo ngày
     * @param $data
     * @return array
     * @throws \Throwable
     */
    public function searchDay($data)
    {
        try {

            $tmp = explode(' - ',$data['daterange']);
            $data['start_month'] = Carbon::createFromFormat('d/m/Y',$tmp[0])->format('Y-m-d');
            $data['end_month'] = Carbon::createFromFormat('d/m/Y',$tmp[1])->format('Y-m-d');

            $data['arrStartDate'] = $data['start_month'];
            $data['arrEndDate'] = $data['end_month'];
            $totalDay = Carbon::parse($data['arrStartDate'])->diffInDays($data['arrEndDate']);

            $mCalculateKpi = app()->get(CalculateKpiTable::class);
            $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);
            $mBudgetMarketing = app()->get(BudgetMarketingTable::class);
            $mKpiCriteria = app()->get(KpiCriteriaTable::class);

            if (!isset($data['department_id'])){
                $data['budget_type'] = 0;
            }

//            Lấy danh sách budget
            $listBudget = $mBudgetMarketing->getAll($data);

            if (count($listBudget) != 0){
                $listBudget = collect($listBudget)->keyBy(function ($item) use ($data){

                    if (!isset($data['department_id'])){
                        return $item['department_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->format('Y-m-d');
                    } else {
                        if ($item['budget_type'] == 0){
                            return $item['department_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->format('Y-m-d');
                        } else {
                            return $item['department_id'].'_'.$item['team_id'].'_'.Carbon::createFromFormat('Y-m-d',$item['effect_time'])->format('Y-m-d');

                        }
                    }
                });
            }

//            Danh sách tiêu chí hard
            $listGroupCriteriaTmp = $mKpiCriteria->getAll(['arrCriteriaId' => $this->arrCriteriaDefault]);
            $listGroupCriteria = [];
            if (count($listGroupCriteriaTmp) != 0){
                $listGroupCriteriaTmp = collect($listGroupCriteriaTmp)->keyBy('kpi_criteria_id');

                foreach ($this->arrCriteriaDefault as $item){
                    $listGroupCriteria[$item] = $listGroupCriteriaTmp[$item];
                }
            }

//            Lấy danh sách phòng ban và team có tiêu chí
            $listCriteria = $mCalculateKpi->getListCriteria($data);

            if (!isset($data['department_id']) && count($listCriteria) != 0){
                $listCriteria = collect($listCriteria)->groupBy(function ($item){
                    return $item['department_id'];
                });
            }

            if (isset($data['department_id']) && count($listCriteria) != 0){
                $listCriteria = collect($listCriteria)->groupBy(function ($item){
                    return $item['team_id'];
                });
            }


//            Xử lý mảng
            $arrData = [];

            foreach ($listCriteria as $key => $item){
                $item = collect($item)->keyBy('kpi_criteria_id')->toArray();

                $n = 0 ;
                foreach ($listGroupCriteria as $itemCriteriaDefault){
                    if ($n == 0){
                        if (!isset($data['department_id'])) {
                            $arrData[$key][] = [
                                'name' => array_values($item)[0]['department_name'],
                                'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                            ];
                        } else {
                            $arrData[$key][] = [
                                'name' => array_values($item)[0]['team_name'],
                                'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                            ];
                        }


                        $keyLast = array_key_last($arrData[$key]);

                        for ($iTmp = 0 ; $iTmp <= $totalDay ; $iTmp++){
                            if ($itemCriteriaDefault['kpi_criteria_id'] == 26) {
                                if (!isset($data['department_id'])){
                                    if (isset($listBudget[$key.'_'.((Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')))])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$key.'_'.(Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d'))]['budget'];
                                    }else {
                                        $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                    }
                                } else {
                                    if (isset($listBudget[$data['department_id'].'_'.(Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d'))])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$data['department_id'].'_'.(Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d'))]['budget'];
                                    } else if (isset($listBudget[$data['department_id'].'_'.$key.'_'.(Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d'))])){
                                        $arrData[$key][$keyLast]['list'][$iTmp] = $listBudget[$data['department_id'].'_'.$key.'_'.(Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d'))]['budget'];
                                    }else {
                                        $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                    }
                                }
                            }
                        }

                        $n = 1;
                    } else {
                        $arrData[$key][] = [
                            'kpi_criteria_name' => $itemCriteriaDefault['kpi_criteria_name']
                        ];

                        $keyLast = array_key_last($arrData[$key]);

                        for ($iTmp = 0 ; $iTmp <= $totalDay ; $iTmp++){
                            if ($itemCriteriaDefault['kpi_criteria_id'] == 10){
                                if (isset($item[10]) && $item[10]['full_time'] == Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[10]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 11){
                                if (isset($item[11]) && $item[11]['full_time'] == Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[11]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 28){
                                if (isset($item[28]) && $item[28]['full_time'] == Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[28]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 29){
                                if (isset($item[29]) && $item[29]['full_time'] == Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[29]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 30){
                                if (isset($item[30]) && $item[30]['full_time'] == Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[30]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }

                            if ($itemCriteriaDefault['kpi_criteria_id'] == 31){
                                if (isset($item[31]) && $item[31]['full_time'] == Carbon::parse($data['start_month'])->addDays($iTmp)->format('Y-m-d')){
                                    $arrData[$key][$keyLast]['list'][$iTmp] = $item[31]['total'];
                                } else {
                                    $arrData[$key][$keyLast]['list'][$iTmp] = '-';
                                }
                            }
                        }
                    }
                }
            }

            $view = view('kpi::report.budget-efficiency.append.day',[
                'arrData' => $arrData,
                'totalDay' => $totalDay,
                'data' => $data
            ])->render();

            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Tìm kiếm thất bại')
            ];
        }
    }
}