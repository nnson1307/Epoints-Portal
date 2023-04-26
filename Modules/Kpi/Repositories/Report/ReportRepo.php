<?php

namespace Modules\Kpi\Repositories\Report;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Kpi\Models\BranchesTable;
use Modules\Kpi\Models\DepartmentsTable;
use Modules\Kpi\Models\KpiNoteDetailTable;
use Modules\Kpi\Models\TeamTable;
use Modules\Kpi\Models\StaffsTable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReportRepo implements ReportRepoInterface
{
    const NOTE_TYPE_BRANCH = "B";
    const NOT_TYPE_DEPARTMENT = "D";
    const NOT_TYPE_TEAM = "T";
    const NOT_TYPE_STAFF = "S";

    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getOptionBranch()
    {
        $mBranch = app()->get(BranchesTable::class);

        //Lấy option chi nhánh
        return $mBranch->getBranch();
    }

    /**
     * Lấy option phòng ban
     *
     * @param $branchId
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getOptionDepartment($branchId = null)
    {
        $mDepartment = app()->get(DepartmentsTable::class);

        return $mDepartment->getDepartment($branchId);
    }

    /**
     * Lấy option nhóm
     *
     * @param $departmentId
     * @return mixed
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getOptionTeam($departmentId = null)
    {
        $mTeam = app()->get(TeamTable::class);

        return $mTeam->getTeam($departmentId);
    }

    /**
     * Load dữ liệu báo cáo kpi
     *
     * @param $input
     * @return mixed|void
     */
    public function loadData($input)
    {
        //Lấy tháng theo điều kiện lọc
        $getMonth = $this->getDataMonth($input['date_type'], $input['year_picker']);

        $data = [];

        if (isset($getMonth['arrMonth']) && count($getMonth['arrMonth']) > 0) {
            if ($input['branch_id'] == null) {
                //Load chart tất cả chi nhánh
                $data = $this->loadChartBranch($input, $getMonth);
            }

            if ($input['branch_id'] != null && $input['department_id'] == null) {
                //Load chart tất cả phòng ban
                $data = $this->loadChartDepartment($input, $getMonth);
            }

            if ($input['department_id'] != null) {
                //Load chart theo nhóm
                $data = $this->loadChartTeam($input, $getMonth);
            }

            if ($input['team_id'] != null) {
                $data = $this->loadChartStaff($input, $getMonth);
            }
        }

        return $data;
    }

    /**
     * Xử lý load dữ liệu theo tất cả chi nhánh
     *
     * @param $input
     * @param $getMonth
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadChartBranch($input, $getMonth)
    {
        $mBranch = app()->get(BranchesTable::class);
        $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);

        //Lấy danh sách chi nhánh
        $getBranch = $mBranch->getBranch();

        $data = [];
        //Trả dữ liệu để load chart và table chi tiết
        $dataChart = [
            'categories' => []
        ];

        $htmlTable = '';

        $series = [];

        if (count($getBranch) > 0) {
            foreach ($getBranch as $v) {
                $dataChart ['categories'] [] = $v['branch_name'];

                foreach ($getMonth['arrMonth'] as $v1) {
                    //Lấy dữ liệu phiếu giao kpi theo chi nhánh
                    $getKpiDetail = $mKpiNoteDetail->getKpiClosing(self::NOTE_TYPE_BRANCH, $v['branch_id'], $v1['month'], $v1['year'])->toArray();

                    $totalPercent = 0;

                    $kpiDetail = [];

                    if (count($getKpiDetail) > 0) {
                        foreach ($getKpiDetail as $v2) {
                            //Tính tổng % kpi của tất cả tiêu chí chi nhánh trong 1 tháng
                            $totalPercent += floatval($v2['weighted_total_percent']);
                            //Gom mãng chứa tiêu chí của chi nhánh này trong 1 tháng
                            $kpiDetail [$v2['kpi_criteria_id']] = $v2;
                        }
                    }

                    //Lấy data chart
                    $seriesName = __('Tháng') . ' ' . $v1['month'];

                    if (!isset($series[$seriesName])) {
                        $series [$seriesName] = [
                            'name' => $seriesName,
                            'data' => []
                        ];
                    }

                    $series[$seriesName] ['data'] [] = $totalPercent;

                    //Lưu data để xử lý mãng show table
                    $data [] = [
                        'branch_id' => $v['branch_id'],
                        'branch_name' => $v['branch_name'],
                        'month' => $v1['month'],
                        'total_percent' => $totalPercent,
                        'kpi_detail' => $kpiDetail
                    ];
                }
            }
        }

        $dataChart ['series'] = array_values($series);

        //Show loại chart theo define
        $chartType = 'vertical';

        if ($input['date_type'] == 'select_year') {
            $chartType = 'line';
        }

        $dataProcess = [];

        //Xử lý chi nhánh nào không có tiêu chí thì bỏ qua
        if (count($data) > 0) {
            foreach ($data as $v) {
                if (count($v['kpi_detail']) > 0) {
                    $dataProcess [] = $v;
                }
            }
        }

        //Xử lý html table
        switch ($getMonth['typeTable']) {
            case 'one':
                //1 tháng
                $htmlTable = $this->_tableBranchOneMonth($getMonth, $dataProcess);
                break;
            case 'multiple':
                //Nhiều tháng
                $htmlTable = $this->_tableBranchMultipleMonth($getMonth, $dataProcess);
                break;
            case 'year':
                //Năm
                $htmlTable = $this->_tableBranchYear($getMonth, $dataProcess);

                break;
        }

        return [
            'chart_type' => $chartType,
            'data_chart' => $dataChart,
            'html_table' => $htmlTable
        ];
    }

    /**
     * Xử lý data lấy table chi nhánh 1 tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableBranchOneMonth($getMonth, $data)
    {
        return \View::make('kpi::report.include-index.branch.one-month', [
            'data' => $data
        ])->render();
    }

    /**
     * Xử luý data lấy table chi nhánh nhiều tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableBranchMultipleMonth($getMonth, $data)
    {
        $dataProcess = [];

        //Group mãng đó theo nhóm
        if (count($data) > 0) {
            foreach ($data as $v) {
                $dataProcess [$v['branch_name']] [] = $v;
            }
        }

        $dataTable = [];

        foreach ($dataProcess as $k => $v) {
            $criteriaName = [];
            $dataDetail = [];

            foreach ($v as $v1) {
                $detail = [];

                if (count($v1['kpi_detail']) > 0) {
                    foreach ($v1['kpi_detail'] as $v2) {
                        $criteriaName [] = $v2['kpi_criteria_name'];

                        $detail [$v2['kpi_criteria_name']] = $v2;
                    }
                }

                $dataDetail [$v1['month']] = [
                    'branch_name' => $v1['branch_name'],
                    'total_percent' => $v1['total_percent'],
                    'detail' => $detail
                ];
            }

            $dataTable [] = [
                'branch_name' => $k,
                'total_criteria_name' => array_values(array_unique($criteriaName)),
                'data_detail' => $dataDetail
            ];
        }

        return \View::make('kpi::report.include-index.branch.multiple-month', [
            'data' => $dataTable,
            'getMonth' => $getMonth
        ])->render();
    }

    /**
     * Xử luý data lấy table chi nhánh trong năm
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableBranchYear($getMonth, $data)
    {
        //Lấy mãng nhóm
        $arrBranch = [];
        //Lấy số tiêu chí trong 1 tháng
        $numberRowMonth = [];
        //Xử lý data gán cho table
        $dataTable = [];
        //Data từ input sau khi đã xử lý
        $dataProcess = [];

        if (count($data) > 0) {
            foreach ($data as $v) {
                $arrBranch [$v['branch_id']] = [
                    'branch_id' => $v['branch_id'],
                    'branch_name' => $v['branch_name']
                ];

                if (count($v['kpi_detail']) > 0) {
                    foreach ($v['kpi_detail'] as $v2) {
                        //Xử lý lấy số dòng trong tháng
                        $numberRowMonth[$v['month']] [$v2['kpi_criteria_id']] = $v2['kpi_criteria_name'];
                    }
                }
                //Gắn id nhóm + tháng vào key cho dễ lấy data
                $dataProcess [$v['branch_id'] . '-' . $v['month']] = $v;
            }
        }

        foreach ($getMonth['arrMonth'] as $v) {
            $dataDetail = [];

            if (isset($numberRowMonth[$v['month']]) && count($numberRowMonth[$v['month']]) > 0) {
                foreach ($arrBranch as $v2) {
                    $dataDetail [] = [
                        'branch_id' => $v2['branch_id'],
                        'branch_name' => $v2['branch_name'],
                        'total_percent' => 0,
                        'data' => []
                    ];
                }
            }

            $dataTable [$v['month']] = [
                'month' => $v['month'],
                'year' => $v['year'],
                'number_row' => isset($numberRowMonth[$v['month']]) ? count($numberRowMonth[$v['month']]) : 0,
                'criteria_name' => isset($numberRowMonth[$v['month']]) ? $numberRowMonth[$v['month']] : [],
                'data_detail' => $dataDetail
            ];
        }


        foreach ($dataTable as $v) {
            if ($v['number_row'] > 0) {
                foreach ($v['data_detail'] as $k1 => $v1) {
                    foreach ($v['criteria_name'] as $k2 => $v2) {
                        if (isset($dataProcess[$v1['branch_id'] . '-' . $v['month']])) {
                            $dataTable[$v['month']]['data_detail'][$k1]['total_percent'] = $dataProcess[$v1['branch_id'] . '-' . $v['month']]['total_percent'];

                            if (isset($dataProcess[$v1['branch_id'] . '-' . $v['month']]['kpi_detail'][$k2])) {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = $dataProcess[$v1['branch_id'] . '-' . $v['month']]['kpi_detail'][$k2];
                            } else {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                            }
                        } else {
                            $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                        }
                    }
                }
            }
        }

        return \View::make('kpi::report.include-index.branch.year', [
            'data' => $dataTable,
            'getMonth' => $getMonth,
            'numberRowMonth' => $numberRowMonth,
            'arrBranch' => $arrBranch
        ])->render();
    }

    /**
     * Xử lý load dữ liệu theo tất cả phòng ban
     *
     * @param $input
     * @param $getMonth
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadChartDepartment($input, $getMonth)
    {
        $mDepartment = app()->get(DepartmentsTable::class);
        $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);

        //Lấy danh sách phòng ban của chi nhánh
        $getDepartment = $mDepartment->getDepartment($input['branch_id']);

        $data = [];
        //Trả dữ liệu để load chart và table chi tiết
        $dataChart = [
            'categories' => []
        ];

        $htmlTable = '';

        $series = [];

        if (count($getDepartment) > 0) {
            foreach ($getDepartment as $v) {
                $dataChart ['categories'] [] = $v['department_name'];

                foreach ($getMonth['arrMonth'] as $v1) {
                    //Lấy dữ liệu phiếu giao kpi theo phòng ban
                    $getKpiDetail = $mKpiNoteDetail->getKpiClosing(self::NOT_TYPE_DEPARTMENT, $v['department_id'], $v1['month'], $v1['year'])->toArray();

                    $totalPercent = 0;

                    $kpiDetail = [];

                    if (count($getKpiDetail) > 0) {
                        foreach ($getKpiDetail as $v2) {
                            //Tính tổng % kpi của tất cả tiêu chí chi nhánh trong 1 tháng
                            $totalPercent += floatval($v2['weighted_total_percent']);
                            //Gom mãng chứa tiêu chí của chi nhánh này trong 1 tháng
                            $kpiDetail [$v2['kpi_criteria_id']] = $v2;
                        }
                    }

                    //Lấy data chart
                    $seriesName = __('Tháng') . ' ' . $v1['month'];

                    if (!isset($series[$seriesName])) {
                        $series [$seriesName] = [
                            'name' => $seriesName,
                            'data' => []
                        ];
                    }

                    $series[$seriesName] ['data'] [] = $totalPercent;

                    //Lưu data để xử lý mãng show table
                    $data [] = [
                        'department_id' => $v['department_id'],
                        'department_name' => $v['department_name'],
                        'month' => $v1['month'],
                        'total_percent' => $totalPercent,
                        'kpi_detail' => $kpiDetail
                    ];
                }
            }
        }

        $dataChart ['series'] = array_values($series);

        //Show loại chart theo define
        $chartType = 'vertical';

        if ($input['date_type'] == 'select_year') {
            $chartType = 'line';
        }

        $dataProcess = [];

        //Xử lý chi nhánh nào không có tiêu chí thì bỏ qua
        if (count($data) > 0) {
            foreach ($data as $v) {
                if (count($v['kpi_detail']) > 0) {
                    $dataProcess [] = $v;
                }
            }
        }

        //Xử lý html table
        switch ($getMonth['typeTable']) {
            case 'one':
                //1 tháng
                $htmlTable = $this->_tableDepartmentOneMonth($getMonth, $dataProcess);
                break;
            case 'multiple':
                //Nhiều tháng
                $htmlTable = $this->_tableDepartmentMultipleMonth($getMonth, $dataProcess);
                break;
            case 'year':
                //Năm
                $htmlTable = $this->_tableDepartmentYear($getMonth, $dataProcess);

                break;
        }

        return [
            'chart_type' => $chartType,
            'data_chart' => $dataChart,
            'html_table' => $htmlTable
        ];
    }

    /**
     * Xử lý data lấy table phòng ban 1 tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableDepartmentOneMonth($getMonth, $data)
    {
        return \View::make('kpi::report.include-index.department.one-month', [
            'data' => $data
        ])->render();
    }

    /**
     * Xử lý data lấy table phòng ban nhiều tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableDepartmentMultipleMonth($getMonth, $data)
    {
        $dataProcess = [];

        //Group mãng đó theo nhóm
        if (count($data) > 0) {
            foreach ($data as $v) {
                $dataProcess [$v['department_name']] [] = $v;
            }
        }

        $dataTable = [];

        foreach ($dataProcess as $k => $v) {
            $criteriaName = [];
            $dataDetail = [];

            foreach ($v as $v1) {
                $detail = [];

                if (count($v1['kpi_detail']) > 0) {
                    foreach ($v1['kpi_detail'] as $v2) {
                        $criteriaName [] = $v2['kpi_criteria_name'];

                        $detail [$v2['kpi_criteria_name']] = $v2;
                    }
                }

                $dataDetail [$v1['month']] = [
                    'department_name' => $v1['department_name'],
                    'total_percent' => $v1['total_percent'],
                    'detail' => $detail
                ];
            }

            $dataTable [] = [
                'department_name' => $k,
                'total_criteria_name' => array_values(array_unique($criteriaName)),
                'data_detail' => $dataDetail
            ];
        }

        return \View::make('kpi::report.include-index.department.multiple-month', [
            'data' => $dataTable,
            'getMonth' => $getMonth
        ])->render();
    }

    /**
     * Xử lý data lấy table phòng ban trong năm
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableDepartmentYear($getMonth, $data)
    {
        //Lấy mãng nhóm
        $arrDepartment = [];
        //Lấy số tiêu chí trong 1 tháng
        $numberRowMonth = [];
        //Xử lý data gán cho table
        $dataTable = [];
        //Data từ input sau khi đã xử lý
        $dataProcess = [];

        if (count($data) > 0) {
            foreach ($data as $v) {
                $arrDepartment [$v['department_id']] = [
                    'department_id' => $v['department_id'],
                    'department_name' => $v['department_name']
                ];

                if (count($v['kpi_detail']) > 0) {
                    foreach ($v['kpi_detail'] as $v2) {
                        //Xử lý lấy số dòng trong tháng
                        $numberRowMonth[$v['month']] [$v2['kpi_criteria_id']] = $v2['kpi_criteria_name'];
                    }
                }
                //Gắn id nhóm + tháng vào key cho dễ lấy data
                $dataProcess [$v['department_id'] . '-' . $v['month']] = $v;
            }
        }

        foreach ($getMonth['arrMonth'] as $v) {
            $dataDetail = [];

            if (isset($numberRowMonth[$v['month']]) && count($numberRowMonth[$v['month']]) > 0) {
                foreach ($arrDepartment as $v2) {
                    $dataDetail [] = [
                        'department_id' => $v2['department_id'],
                        'department_name' => $v2['department_name'],
                        'total_percent' => 0,
                        'data' => []
                    ];
                }
            }

            $dataTable [$v['month']] = [
                'month' => $v['month'],
                'year' => $v['year'],
                'number_row' => isset($numberRowMonth[$v['month']]) ? count($numberRowMonth[$v['month']]) : 0,
                'criteria_name' => isset($numberRowMonth[$v['month']]) ? $numberRowMonth[$v['month']] : [],
                'data_detail' => $dataDetail
            ];
        }


        foreach ($dataTable as $v) {
            if ($v['number_row'] > 0) {
                foreach ($v['data_detail'] as $k1 => $v1) {
                    foreach ($v['criteria_name'] as $k2 => $v2) {
                        if (isset($dataProcess[$v1['department_id'] . '-' . $v['month']])) {
                            $dataTable[$v['month']]['data_detail'][$k1]['total_percent'] = $dataProcess[$v1['department_id'] . '-' . $v['month']]['total_percent'];

                            if (isset($dataProcess[$v1['department_id'] . '-' . $v['month']]['kpi_detail'][$k2])) {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = $dataProcess[$v1['department_id'] . '-' . $v['month']]['kpi_detail'][$k2];
                            } else {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                            }
                        } else {
                            $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                        }
                    }
                }
            }
        }

        return \View::make('kpi::report.include-index.department.year', [
            'data' => $dataTable,
            'getMonth' => $getMonth,
            'numberRowMonth' => $numberRowMonth,
            'arrDepartment' => $arrDepartment
        ])->render();
    }

    /**
     * Xử lý load dữ liệu theo tất cả phòng ban
     *
     * @param $input
     * @param $getMonth
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadChartTeam($input, $getMonth)
    {
        $mTeam = app()->get(TeamTable::class);
        $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);

        //Lấy danh sách nhóm của phòng ban
        $getTeam = $mTeam->getTeam($input['department_id']);

        $data = [];
        //Trả dữ liệu để load chart và table chi tiết
        $dataChart = [
            'categories' => []
        ];

        $htmlTable = '';

        $series = [];

        if (count($getTeam) > 0) {
            foreach ($getTeam as $v) {
                $dataChart ['categories'] [] = $v['team_name'];

                foreach ($getMonth['arrMonth'] as $v1) {
                    //Lấy dữ liệu phiếu giao kpi theo nhóm
                    $getKpiDetail = $mKpiNoteDetail->getKpiClosing(self::NOT_TYPE_TEAM, $v['team_id'], $v1['month'], $v1['year'])->toArray();

                    $totalPercent = 0;

                    $kpiDetail = [];

                    if (count($getKpiDetail) > 0) {
                        foreach ($getKpiDetail as $v2) {
                            //Tính tổng % kpi của tất cả tiêu chí chi nhánh trong 1 tháng
                            $totalPercent += floatval($v2['weighted_total_percent']);
                            //Gom mãng chứa tiêu chí của chi nhánh này trong 1 tháng
                            $kpiDetail [$v2['kpi_criteria_id']] = $v2;
                        }
                    }

                    //Lấy data chart
                    $seriesName = __('Tháng') . ' ' . $v1['month'];

                    if (!isset($series[$seriesName])) {
                        $series [$seriesName] = [
                            'name' => $seriesName,
                            'data' => []
                        ];
                    }

                    $series[$seriesName] ['data'] [] = $totalPercent;

                    //Lưu data để xử lý mãng show table
                    $data [] = [
                        'team_id' => $v['team_id'],
                        'team_name' => $v['team_name'],
                        'month' => $v1['month'],
                        'total_percent' => $totalPercent,
                        'kpi_detail' => $kpiDetail
                    ];
                }
            }
        }

        $dataChart ['series'] = array_values($series);

        //Show loại chart theo define
        $chartType = 'vertical';

        if ($input['date_type'] == 'select_year') {
            $chartType = 'line';
        }

        $dataProcess = [];

        //Xử lý chi nhánh nào không có tiêu chí thì bỏ qua
        if (count($data) > 0) {
            foreach ($data as $v) {
                if (count($v['kpi_detail']) > 0) {
                    $dataProcess [] = $v;
                }
            }
        }

        //Xử lý html table
        switch ($getMonth['typeTable']) {
            case 'one':
                //1 tháng
                $htmlTable = $this->_tableTeamOneMonth($getMonth, $dataProcess);
                break;
            case 'multiple':
                //Nhiều tháng
                $htmlTable = $this->_tableTeamMultipleMonth($getMonth, $dataProcess);
                break;
            case 'year':
                //Năm
                $htmlTable = $this->_tableTeamYear($getMonth, $dataProcess);

                break;
        }

        return [
            'chart_type' => $chartType,
            'data_chart' => $dataChart,
            'html_table' => $htmlTable
        ];
    }

    /**
     * Xử lý data lấy table nhóm 1 tháng
     *
     * @param $getMonth
     * @param $data
     * @return void
     */
    private function _tableTeamOneMonth($getMonth, $data)
    {
        return \View::make('kpi::report.include-index.team.one-month', [
            'data' => $data
        ])->render();
    }

    /**
     * Xử lý data lấy table nhóm nhiều tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableTeamMultipleMonth($getMonth, $data)
    {
        $dataProcess = [];

        //Group mãng đó theo nhóm
        if (count($data) > 0) {
            foreach ($data as $v) {
                $dataProcess [$v['team_name']] [] = $v;
            }
        }

        $dataTable = [];

        foreach ($dataProcess as $k => $v) {
            $criteriaName = [];
            $dataDetail = [];

            foreach ($v as $v1) {
                $detail = [];

                if (count($v1['kpi_detail']) > 0) {
                    foreach ($v1['kpi_detail'] as $v2) {
                        $criteriaName [] = $v2['kpi_criteria_name'];

                        $detail [$v2['kpi_criteria_name']] = $v2;
                    }
                }

                $dataDetail [$v1['month']] = [
                    'team_name' => $v1['team_name'],
                    'total_percent' => $v1['total_percent'],
                    'detail' => $detail
                ];
            }

            $dataTable [] = [
                'team_name' => $k,
                'total_criteria_name' => array_values(array_unique($criteriaName)),
                'data_detail' => $dataDetail
            ];
        }

        return \View::make('kpi::report.include-index.team.multiple-month', [
            'data' => $dataTable,
            'getMonth' => $getMonth
        ])->render();
    }

    /**
     * Xử lý data lấy table nhóm trong năm
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableTeamYear($getMonth, $data)
    {
        //Lấy mãng nhóm
        $arrTeam = [];
        //Lấy số tiêu chí trong 1 tháng
        $numberRowMonth = [];
        //Xử lý data gán cho table
        $dataTable = [];
        //Data từ input sau khi đã xử lý
        $dataProcess = [];

        if (count($data) > 0) {
            foreach ($data as $v) {
                $arrTeam [$v['team_id']] = [
                    'team_id' => $v['team_id'],
                    'team_name' => $v['team_name']
                ];

                if (count($v['kpi_detail']) > 0) {
                    foreach ($v['kpi_detail'] as $v2) {
                        //Xử lý lấy số dòng trong tháng
                        $numberRowMonth[$v['month']] [$v2['kpi_criteria_id']] = $v2['kpi_criteria_name'];
                    }
                }
                //Gắn id nhóm + tháng vào key cho dễ lấy data
                $dataProcess [$v['team_id'] . '-' . $v['month']] = $v;
            }
        }

        foreach ($getMonth['arrMonth'] as $v) {
            $dataDetail = [];

            if (isset($numberRowMonth[$v['month']]) && count($numberRowMonth[$v['month']]) > 0) {
                foreach ($arrTeam as $v2) {
                    $dataDetail [] = [
                        'team_id' => $v2['team_id'],
                        'team_name' => $v2['team_name'],
                        'total_percent' => 0,
                        'data' => []
                    ];
                }
            }

            $dataTable [$v['month']] = [
                'month' => $v['month'],
                'year' => $v['year'],
                'number_row' => isset($numberRowMonth[$v['month']]) ? count($numberRowMonth[$v['month']]) : 0,
                'criteria_name' => isset($numberRowMonth[$v['month']]) ? $numberRowMonth[$v['month']] : [],
                'data_detail' => $dataDetail
            ];
        }

        foreach ($dataTable as $v) {
            if ($v['number_row'] > 0) {
                foreach ($v['data_detail'] as $k1 => $v1) {
                    foreach ($v['criteria_name'] as $k2 => $v2) {
                        if (isset($dataProcess[$v1['team_id'] . '-' . $v['month']])) {
                            $dataTable[$v['month']]['data_detail'][$k1]['total_percent'] = $dataProcess[$v1['team_id'] . '-' . $v['month']]['total_percent'];

                            if (isset($dataProcess[$v1['team_id'] . '-' . $v['month']]['kpi_detail'][$k2])) {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = $dataProcess[$v1['team_id'] . '-' . $v['month']]['kpi_detail'][$k2];
                            } else {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                            }
                        } else {
                            $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                        }
                    }
                }
            }
        }

        return \View::make('kpi::report.include-index.team.year', [
            'data' => $dataTable,
            'getMonth' => $getMonth,
            'arrTeam' => $arrTeam
        ])->render();
    }

    /**
     * Xử lý load dữ liệu theo tất cả nhân viên
     *
     * @param $input
     * @param $getMonth
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function loadChartStaff($input, $getMonth)
    {
        $mStaff = app()->get(StaffsTable::class);
        $mKpiNoteDetail = app()->get(KpiNoteDetailTable::class);

        //Lấy ds nhân viên theo nhóm
        $getStaff = $mStaff->getStaffByTeam($input['team_id']);

        $data = [];
        //Trả dữ liệu để load chart và table chi tiết
        $dataChart = [
            'categories' => []
        ];

        $htmlTable = '';

        $series = [];

        if (count($getStaff) > 0) {
            foreach ($getStaff as $v) {
                $dataChart ['categories'] [] = $v['staff_name'];

                foreach ($getMonth['arrMonth'] as $v1) {
                    //Lấy dữ liệu phiếu giao kpi theo nhóm
                    $getKpiDetail = $mKpiNoteDetail->getKpiClosing(self::NOT_TYPE_STAFF, $v['staff_id'], $v1['month'], $v1['year'])->toArray();

                    $totalPercent = 0;

                    $kpiDetail = [];

                    if (count($getKpiDetail) > 0) {
                        foreach ($getKpiDetail as $v2) {
                            //Tính tổng % kpi của tất cả tiêu chí chi nhánh trong 1 tháng
                            $totalPercent += floatval($v2['weighted_total_percent']);
                            //Gom mãng chứa tiêu chí của chi nhánh này trong 1 tháng
                            $kpiDetail [$v2['kpi_criteria_id']] = $v2;
                        }
                    }

                    //Lấy data chart
                    $seriesName = __('Tháng') . ' ' . $v1['month'];

                    if (!isset($series[$seriesName])) {
                        $series [$seriesName] = [
                            'name' => $seriesName,
                            'data' => []
                        ];
                    }

                    $series[$seriesName] ['data'] [] = round($totalPercent, 2);

                    //Lưu data để xử lý mãng show table
                    $data [] = [
                        'staff_id' => $v['staff_id'],
                        'staff_name' => $v['staff_name'],
                        'month' => $v1['month'],
                        'total_percent' => $totalPercent,
                        'kpi_detail' => $kpiDetail
                    ];
                }
            }
        }

        $dataChart ['series'] = array_values($series);

        //Show loại chart theo define
        $chartType = 'horizontal';

        if ($input['date_type'] == 'select_year') {
            $chartType = 'line';
        }

        $dataProcess = [];

        //Xử lý chi nhánh nào không có tiêu chí thì bỏ qua
        if (count($data) > 0) {
            foreach ($data as $v) {
                if (count($v['kpi_detail']) > 0) {
                    $dataProcess [] = $v;
                }
            }
        }

        //Xử lý html table
        switch ($getMonth['typeTable']) {
            case 'one':
                //1 tháng
                $htmlTable = $this->_tableStaffOneMonth($getMonth, $dataProcess);
                break;
            case 'multiple':
                //Nhiều tháng
                $htmlTable = $this->_tableStaffMultipleMonth($getMonth, $dataProcess);
                break;
            case 'year':
                //Năm
                $htmlTable = $this->_tableStaffYear($getMonth, $dataProcess);

                break;
        }

        return [
            'chart_type' => $chartType,
            'data_chart' => $dataChart,
            'html_table' => $htmlTable
        ];
    }

    /**
     * Xử lý data lấy table nhân viên 1 tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableStaffOneMonth($getMonth, $data)
    {
        return \View::make('kpi::report.include-index.staff.one-month', [
            'data' => $data
        ])->render();
    }

    /**
     * Xử lý data lấy table nhân viên nhiều tháng
     *
     * @param $getMonth
     * @param $data
     * @return mixed
     */
    private function _tableStaffMultipleMonth($getMonth, $data)
    {
        $dataProcess = [];

        //Group mãng đó theo nhân viên
        if (count($data) > 0) {
            foreach ($data as $v) {
                $dataProcess [$v['staff_id']] = $v;
            }
        }


        $dataTable = [];

        foreach ($dataProcess as $k => $v) {
            $criteriaName = [];
            $dataDetail = [];


            $detail = [];

            if (count($v['kpi_detail']) > 0) {
                foreach ($v['kpi_detail'] as $v1) {
                    $criteriaName [] = $v1['kpi_criteria_name'];

                    $detail [$v1['kpi_criteria_name']] = $v1;
                }
            }

            $dataDetail [$v['month']] = [
                'staff_name' => $v['staff_name'],
                'total_percent' => $v['total_percent'],
                'detail' => $detail
            ];


            $dataTable [] = [
                'staff_name' => $v['staff_name'],
                'total_criteria_name' => array_values(array_unique($criteriaName)),
                'data_detail' => $dataDetail
            ];
        }

        return \View::make('kpi::report.include-index.staff.multiple-month', [
            'data' => $dataTable,
            'getMonth' => $getMonth
        ])->render();
    }

    private function _tableStaffYear($getMonth, $data)
    {
        //Lấy mãng nhóm
        $arrStaff = [];
        //Lấy số tiêu chí trong 1 tháng
        $numberRowMonth = [];
        //Xử lý data gán cho table
        $dataTable = [];
        //Data từ input sau khi đã xử lý
        $dataProcess = [];

        if (count($data) > 0) {
            foreach ($data as $v) {
                $arrStaff [$v['staff_id']] = [
                    'staff_id' => $v['staff_id'],
                    'staff_name' => $v['staff_name']
                ];

                if (count($v['kpi_detail']) > 0) {
                    foreach ($v['kpi_detail'] as $v2) {
                        //Xử lý lấy số dòng trong tháng
                        $numberRowMonth[$v['month']] [$v2['kpi_criteria_id']] = $v2['kpi_criteria_name'];
                    }
                }
                //Gắn id nhóm + tháng vào key cho dễ lấy data
                $dataProcess [$v['staff_id'] . '-' . $v['month']] = $v;
            }
        }

        foreach ($getMonth['arrMonth'] as $v) {
            $dataDetail = [];

            if (isset($numberRowMonth[$v['month']]) && count($numberRowMonth[$v['month']]) > 0) {
                foreach ($arrStaff as $v2) {
                    $dataDetail [] = [
                        'staff_id' => $v2['staff_id'],
                        'staff_name' => $v2['staff_name'],
                        'total_percent' => 0,
                        'data' => []
                    ];
                }
            }

            $dataTable [$v['month']] = [
                'month' => $v['month'],
                'year' => $v['year'],
                'number_row' => isset($numberRowMonth[$v['month']]) ? count($numberRowMonth[$v['month']]) : 0,
                'criteria_name' => isset($numberRowMonth[$v['month']]) ? $numberRowMonth[$v['month']] : [],
                'data_detail' => $dataDetail
            ];
        }

        foreach ($dataTable as $v) {
            if ($v['number_row'] > 0) {
                foreach ($v['data_detail'] as $k1 => $v1) {
                    foreach ($v['criteria_name'] as $k2 => $v2) {
                        if (isset($dataProcess[$v1['staff_id'] . '-' . $v['month']])) {
                            $dataTable[$v['month']]['data_detail'][$k1]['total_percent'] = $dataProcess[$v1['staff_id'] . '-' . $v['month']]['total_percent'];

                            if (isset($dataProcess[$v1['staff_id'] . '-' . $v['month']]['kpi_detail'][$k2])) {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = $dataProcess[$v1['staff_id'] . '-' . $v['month']]['kpi_detail'][$k2];
                            } else {
                                $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                            }
                        } else {
                            $dataTable[$v['month']]['data_detail'][$k1]['data'] [] = null;
                        }
                    }
                }
            }
        }

        return \View::make('kpi::report.include-index.staff.year', [
            'data' => $dataTable,
            'getMonth' => $getMonth,
            'arrStaff' => $arrStaff
        ])->render();
    }

    /**
     * Xử lý bộ lọc lấy ra số tháng để lấy dữ liệu
     *
     * @param $dateType
     * @param $yearType
     * @return void
     */
    private function getDataMonth($dateType, $yearType)
    {
        $arrMonth = [];
        $typeTable = null;
        $yearData = null;

        switch ($dateType) {
            case 'this_month':
                //Tháng này
                $arrMonth [] = [
                    'month' => Carbon::now()->format('m'),
                    'year' => Carbon::now()->format('Y')
                ];

                $typeTable = 'one';
                break;
            case 'after_month':
                //Tháng trước
                $arrMonth [] = [
                    'month' => Carbon::now()->subMonths(1)->format('m'),
                    'year' => Carbon::now()->subMonths(1)->format('Y')
                ];

                $typeTable = 'one';
                break;
            case 'this_precious':
                //Quý này
                $currentQuarter = $this->date_quarter(Carbon::now()->format('m'));

                $startRange = null;
                $endRange = null;

                switch ($currentQuarter) {
                    case 1:
                        $startRange = 1;
                        $endRange = 3;
                        break;
                    case 2:
                        $startRange = 4;
                        $endRange = 6;
                        break;
                    case 3:
                        $startRange = 7;
                        $endRange = 9;
                        break;
                    case 4:
                        $startRange = 10;
                        $endRange = 12;
                        break;
                }

                for ($i = $startRange; $i <= $endRange; $i++) {
                    $arrMonth [] = [
                        'month' => $i,
                        'year' => Carbon::now()->format('Y')
                    ];
                }

                $typeTable = 'multiple';
                break;
            case 'after_precious':
                //Quý trước
                $currentQuarter = $this->date_quarter(Carbon::now()->format('m'));

                $startRange = null;
                $endRange = null;
                $year = null;

                switch ($currentQuarter) {
                    case 1:
                        //Lấy quý 4 của năm ngoái
                        $startRange = 10;
                        $endRange = 12;
                        $year = Carbon::now()->subYears(1)->format('Y');

                        break;
                    case 2:
                        //Lấy quý 1 năm nay
                        $startRange = 1;
                        $endRange = 3;
                        $year = Carbon::now()->format('Y');

                        break;
                    case 3:
                        //Lấy quý 2 năm nay
                        $startRange = 4;
                        $endRange = 6;
                        $year = Carbon::now()->format('Y');

                        break;
                    case 4:
                        //Lấy quý 3 năm
                        $startRange = 7;
                        $endRange = 9;
                        $year = Carbon::now()->format('Y');

                        break;
                }

                for ($i = $startRange; $i <= $endRange; $i++) {
                    $arrMonth [] = [
                        'month' => $i,
                        'year' => $year
                    ];
                }

                $typeTable = 'multiple';
                $yearData = $year;
                break;
            case 'select_year':
                //Chọn năm
                for ($i = 1; $i <= 12; $i++) {
                    $arrMonth [] = [
                        'month' => $i,
                        'year' => $yearType
                    ];
                }

                $typeTable = 'year';
                $yearData = $yearType;
                break;
        }

        return [
            'arrMonth' => $arrMonth,
            'typeTable' => $typeTable,
            'year' => $yearData
        ];
    }

    /**
     * Lấy quý hiện tại
     *
     * @return false|float
     */
    function date_quarter($month)
    {
        return ceil($month / 3);
    }
}