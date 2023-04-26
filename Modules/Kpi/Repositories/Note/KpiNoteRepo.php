<?php
namespace Modules\Kpi\Repositories\Note;

use Illuminate\Support\Facades\Auth;
use Modules\Kpi\Models\BranchesTable;
use Modules\Kpi\Models\CalculateKpiTable;
use Modules\Kpi\Models\CalculateKpiTotalTable;
use Modules\Kpi\Models\DepartmentsTable;
use Modules\Kpi\Models\KpiCriteriaTable;
use Modules\Kpi\Models\KpiCriteriaUnitTable;
use Modules\Kpi\Models\KpiNoteDetailTable;
use Modules\Kpi\Models\KpiNoteTable;
use Modules\Kpi\Models\StaffsTable;
use Modules\Kpi\Models\TeamTable;

/**
 * class KpiNoteRepo
 * @author HaoNMN
 * @since Jul 2022
 */
class KpiNoteRepo implements KpiNoteRepoInterface
{
    protected $table;


    public function __construct(KpiNoteTable $kpiNote)
    {
        $this->table = $kpiNote;
    }

    // Danh sách phiếu giao
    public function list($param = [])
    {
        $data = $this->table->list($param);

        foreach ($data as $item) {
            $staff = StaffsTable::where('staff_id', $item['created_by'])->first();
            $item['created_by']  = $staff['full_name'];

            $branch = BranchesTable::where('branch_id', $item['branch_id'])->first();
            $item['branch_name'] = $branch['branch_name'];

            $department = DepartmentsTable::where('department_id', $item['department_id'])->first();
            // Nếu trường hợp là phiếu giao của chi nhánh thì không có tên phòng ban
            if (! empty($department)) {
                $item['department_name'] = $department['department_name'];
            } else {
                $item['department_name'] = null;
            }

            $team = TeamTable::where('team_id', $item['team_id'])->first();
            // Nếu trường hợp là phiếu giao của phòng ban/ chi nhánh thì không có tên nhóm
            if (! empty($team)) {
                $item['team_name'] = $team['team_name'];
            } else {
                $item['team_name'] = null;
            }
        }
        return $data;
    }

    // Lấy danh sách chi nhánh
    public function getBranch() 
    {
        $branches = app()->get(BranchesTable::class);
        return $branches->getBranch();
    }

    // Lấy danh sách phòng ban
    public function getDepartment($branchId)
    {
        $departments = app()->get(DepartmentsTable::class);
        return $departments->getDepartment($branchId);
    }

    // Lấy danh sách nhóm
    public function getTeam($departmentId)
    {
        $team = app()->get(TeamTable::class);
        return $team->getTeam($departmentId);
    }

    // Lấy danh sách nhấn viên
    public function getStaff($param)
    {
        $staff = app()->get(StaffsTable::class);
        return $staff->getStaff($param);
    }

    // Lấy danh sách tiêu chí
    public function getCriteria($param)
    {
        $param['query'] = 1; 
        $criteria = app()->get(KpiCriteriaTable::class);
        $data     = $criteria->getList($param);

        $list = [];
        foreach ($data as $item) {
            $unit = KpiCriteriaUnitTable::where('kpi_criteria_unit_id', $item['kpi_criteria_unit_id'])->first();
            $item['unit_name'] = $unit['unit_name'];
            $list[] = $item;
        }

        return $list;
    }

    // Lưu phiếu giao
    public function save($data)
    {
        // Check độ quan trọng của phiếu giao phải = 100
        if ($data['kpi_note_type'] !== 'S') {
            $priorityFlag = $this->validatePriorityforGroup($data);

            if ($priorityFlag == 1) {
                return [
                    'error' => 1,
                    'message' => __('Tổng độ quan trọng của phiếu giao phải bằng 100')
                ];
            }
        }
        else {
            $priorityFlag = $this->validatePriorityForStaff($data);
            if ($priorityFlag != 0) {
                $staffName = StaffsTable::where('staff_id', $priorityFlag)->select('full_name')->first()->toArray();
                return [
                    'error' => 1,
                    'message' => __('Tổng độ quan trọng của nhân viên :name phải bằng 100', [
                        'name' => $staffName['full_name']
                    ])
                ];
            }
        }
        
        

        // Trường hợp phiếu giao không lặp lại
        if ($data['is_loop'] == 0) {
            // Kiểm tra phiếu giao đã được tạo chưa
            $kpiNoteRecord = $this->table->checkKpiNoteExist(null, $data);

            $noteData = [
                'kpi_note_name' => $data['kpi_note_name'],
                'effect_year'   => $data['effect_year'],
                'effect_month'  => $data['effect_month'],
                'is_loop'       => $data['is_loop'],
                'branch_id'     => $data['branch_id'],
                'department_id' => isset($data['department_id']) ? $data['department_id'] : null,
                'team_id'       => isset($data['team_id']) ? $data['team_id'] : null,
                'kpi_note_type' => $data['kpi_note_type'],
                'status'        => 'N',
                'is_deleted'    => 0,
                'created_by'    => Auth::id()
            ];

            // Nếu chưa thì thêm mới
            if ($kpiNoteRecord === null) {
                $kpiNoteId = $this->table->add($noteData);
            } 

            // Đã tạo rồi thì báo lỗi
            else {
                $branchname = BranchesTable::where('branch_id', $data['branch_id'])->select('branch_name')->first()->toArray();
                switch ($data['kpi_note_type']) {
                    case 'B':
                        return [
                            'error' => 1,
                            'message' => __('Phiếu giao KPI cho :branch trong tháng :month/:year đã tồn tại', [
                                'branch' => $branchname['branch_name'],
                                'month'  => $data['effect_month'],
                                'year'   => $data['effect_year']
                            ])
                        ];
                        break;
                    case 'D':
                        $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                        return [
                            'error' => 1,
                            'message' => __('Phiếu giao KPI cho :department thuộc :branch trong tháng :month/:year đã tồn tại', [
                                'branch'     => $branchname['branch_name'],
                                'department' => $departmentName['department_name'],
                                'month'  => $data['effect_month'],
                                'year'   => $data['effect_year']
                            ])
                        ];
                        break;
                    case 'T':
                        $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                        $teamName = TeamTable::where('team_id', $data['team_id'])->select('team_name')->first()->toArray();
                        return [
                            'error' => 1,
                            'message' => __('Phiếu giao KPI cho :team thuộc :department trong tháng :month/:year đã tồn tại', [
                                'team'       => $teamName['team_name'], 
                                'department' => $departmentName['department_name'],
                                'month'  => $data['effect_month'],
                                'year'   => $data['effect_year']
                            ])
                        ];
                        break;
                    case 'S':
                        return [
                            'error' => 1,
                            'message' => __(':name vào tháng :month/:year đã tồn tại', [
                                'name'   => $kpiNoteRecord['kpi_note_name'], 
                                'month'  => $data['effect_month'],
                                'year'   => $data['effect_year']
                            ])
                        ];
                        break;
                }
            }

            // Chuẩn bị dữ liệu để thêm bảng chi tiết phiếu giao
            if ($data['kpi_note_type'] !== 'S') {
                $noteDetailData = [];
                foreach ($data['priority_id_row'] as $criteriaId => $priorityValue) {
                    $noteDetailData[] = [
                        'kpi_note_id'     => $kpiNoteId,
                        'staff_id'        => null,
                        'kpi_criteria_id' => $criteriaId,
                        'priority'        => $priorityValue,
                        'kpi_value'       => floatval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$criteriaId])),
                    ];
                }
            } else {
                $noteDetailData = [];
                foreach ($data['priority_id_row'] as $staffId => $criteriaValue) {
                    foreach ($criteriaValue as $criteriaId => $priorityValue) {
                        $noteDetailData[] = [
                            'kpi_note_id'     => $kpiNoteId,
                            'staff_id'        => $staffId,
                            'kpi_criteria_id' => $criteriaId,
                            'priority'        => $priorityValue,
                            'kpi_value'       => intval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$staffId][$criteriaId])),
                        ];
                    }
                }
            }
            // Thêm chi tiết phiếu giao vào database
            $detailTable = app()->get(KpiNoteDetailTable::class);
            $detailTable->add($noteDetailData);
        } 
        // Trường hợp phiếu giao lặp lại hằng tháng
        else {
            $effectMonth = [];
            for ($i = 12; $i >= $data['effect_month']; $i--) {
                // Kiểm tra phiếu giao đã được tạo chưa
                $kpiNoteRecord = $this->table->checkKpiNoteExist($i, $data);
                if ($kpiNoteRecord != null) {
                    $branchname = BranchesTable::where('branch_id', $data['branch_id'])->select('branch_name')->first()->toArray();
                    switch ($data['kpi_note_type']) {
                        case 'B':
                            return [
                                'error' => 1,
                                'message' => __('Phiếu giao KPI cho :branch trong tháng :month/:year đã tồn tại', [
                                    'branch'     => $branchname['branch_name'],
                                    'month'      => $i,
                                    'year'       => $data['effect_year']
                                ])
                            ];
                            break;
                        case 'D':
                            $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                            return [
                                'error' => 1,
                                'message' => __('Phiếu giao KPI cho :department thuộc :branch trong tháng :month/:year đã tồn tại', [
                                    'branch'     => $branchname['branch_name'],
                                    'department' => $departmentName['department_name'],
                                    'month'      => $i,
                                    'year'       => $data['effect_year']
                                ])
                            ];
                            break;
                        case 'T':
                            $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                            $teamName = TeamTable::where('team_id', $data['team_id'])->select('team_name')->first()->toArray();
                            return [
                                'error' => 1,
                                'message' => __('Phiếu giao KPI cho :team thuộc :department trong tháng :month/:year đã tồn tại', [
                                    'team'       => $teamName['team_name'], 
                                    'department' => $departmentName['department_name'],
                                    'month'      => $i,
                                    'year'       => $data['effect_year']
                                ])
                            ];
                            break;
                        case 'S':
                            return [
                                'error' => 1,
                                'message' => __(':name vào tháng :month/:year đã tồn tại', [
                                    'name'   => $kpiNoteRecord['kpi_note_name'], 
                                    'month'  => $i,
                                    'year'   => $data['effect_year']
                                ])
                            ];
                            break;
                    }
                }

                $effectMonth[] = $i; 
            }

            foreach ($effectMonth as $month) {
                $noteData = [
                    'kpi_note_name' => $data['kpi_note_name'],
                    'effect_year'   => $data['effect_year'],
                    'effect_month'  => $month,
                    'is_loop'       => $data['is_loop'],
                    'branch_id'     => $data['branch_id'],
                    'department_id' => isset($data['department_id']) ? $data['department_id'] : null,
                    'team_id'       => isset($data['team_id']) ? $data['team_id'] : null,
                    'kpi_note_type' => $data['kpi_note_type'],
                    'status'        => 'N',
                    'is_deleted'    => 0,
                    'created_by'    => Auth::id()
                ];

                $kpiNoteId = $this->table->add($noteData);

                // Chuẩn bị dữ liệu để thêm bảng chi tiết phiếu giao
                if ($data['kpi_note_type'] !== 'S') {
                    $noteDetailData = [];
                    foreach ($data['priority_id_row'] as $criteriaId => $priorityValue) {
                        $noteDetailData[] = [
                            'kpi_note_id'     => $kpiNoteId,
                            'staff_id'        => null,
                            'kpi_criteria_id' => $criteriaId,
                            'priority'        => $priorityValue,
                            'kpi_value'       => floatval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$criteriaId])),
                        ];
                    }
                } else {
                    $noteDetailData = [];
                    foreach ($data['priority_id_row'] as $staffId => $criteriaValue) {
                        foreach ($criteriaValue as $criteriaId => $priorityValue) {
                            $noteDetailData[] = [
                                'kpi_note_id'     => $kpiNoteId,
                                'staff_id'        => $staffId,
                                'kpi_criteria_id' => $criteriaId,
                                'priority'        => $priorityValue,
                                'kpi_value'       => intval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$staffId][$criteriaId])),
                            ];
                        }
                    }
                }

                // Thêm chi tiết phiếu giao vào database
                $detailTable = app()->get(KpiNoteDetailTable::class);
                $detailTable->add($noteDetailData);
            }
        }
        
        return [
            'error' => 0,
            'message' => __('Thêm phiếu giao thành công')
        ];
    }

    // Xóa phiếu giao
    public function remove($id)
    {
        $this->table->remove($id);
        return [
            'error'   => 0,
            'message' => __('Xóa thành công')
        ];
    }

    // Chi tiết phiếu giao
    public function detail($id)
    {
        // Thông tin phiếu giao
        $data                  = [];
        $data['generalDetail'] = $this->table->detail($id);

        // Data chi tiết phiếu giao
        $tableDetail           = app()->get(KpiNoteDetailTable::class);
        $listDetail            = $tableDetail->listById($id);
        $data['listDetail']    = $listDetail;

        // Lấy danh sách id nhân viên
        $listStaff             = [];
        foreach ($data['listDetail'] as $item) {
            $listStaff[] = $item['staff_id'];
        }
        $data['listStaff']     = array_unique($listStaff);

        // Format dữ liệu phân bổ tiêu chí kpi cho nhân vi
        $calculateKpiTable      = app()->get(CalculateKpiTable::class);
        $calculateKpiTotalTable = app()->get(CalculateKpiTotalTable::class);
        $mergeArrayWithHash = [];
        $param              = [];
        $totalPercentKpi    = 0;

        if ($data['generalDetail']['kpi_note_type'] !== 'S') {
            $listDetail = [];
            foreach ($data['listDetail'] as $key => $value) {
                // Param gọi hàm tính kpi thực tế
                $param['branch_id']       = $data['generalDetail']['branch_id'];
                $param['department_id']   = $data['generalDetail']['department_id'];
                $param['team_id']         = $data['generalDetail']['team_id'];
                $param['effect_month']    = $data['generalDetail']['effect_month'];
                $param['effect_year']     = $data['generalDetail']['effect_year'];
                $param['kpi_criteria_id'] = $value['kpi_criteria_id']; 

                if ($data['generalDetail']['status'] == 'A') {
                    // Nếu là tiêu chí tạo tay thì lấy từ bảng tổng tháng
                    if ($value['is_customize'] == 0) {
                        $kpiValue = $calculateKpiTable->getTotalByGroupInDay($param);
                    } else {
                        $kpiValue = $calculateKpiTotalTable->getTotalByGroupInMonth($param);
                    }
                } elseif ($data['generalDetail']['status'] == 'D') {
                    $kpiValue = $calculateKpiTotalTable->getTotalByGroupInMonth($param);
                } else {
                    $kpiValue = null;
                }

                // Nếu giá trị thực tế kpi null thì gán = 0
                if (! empty($kpiValue)) {
                    if ($kpiValue['total'] == null) {
                        $kpiValueTotal = 0;
                    } else {
                        $kpiValueTotal = $kpiValue['total'];
                    }
                } else {
                    $kpiValueTotal = 0;
                }

                // Format lại data liên quan theo từng tiêu chí
                $listDetail[$key]['kpi_note_detail_id']   = $value['kpi_note_detail_id'];
                $listDetail[$key]['kpi_criteria_id']      = $value['kpi_criteria_id'];
                $listDetail[$key]['kpi_criteria_name']    = $value['kpi_criteria_name'];
                $listDetail[$key]['kpi_criteria_trend']   = $value['kpi_criteria_trend'];
                $listDetail[$key]['is_blocked']           = $value['is_blocked'];
                $listDetail[$key]['is_customize']         = $value['is_customize'];
                $listDetail[$key]['priority']             = $value['priority'];
                $listDetail[$key]['kpi_value']            = $value['kpi_value'];
                $listDetail[$key]['kpi_criteria_unit_id'] = $value['kpi_criteria_unit_id'];
                $listDetail[$key]['unit_name']            = $value['unit_name'];
                $listDetail[$key]['kpi_calculate_value']  = $kpiValueTotal;

                /**
                 * Tính % hoàn thành KPI theo số thực tế
                 */
                $priority = intval($value['priority']);   // Độ quan trọng
                $target   = intval($value['kpi_value']);  // Chỉ tiêu
                $total    = intval($kpiValueTotal);       // Thực tế
                $block    = $value['is_blocked'];         // Chỉ số chặn
                $trend    = $value['kpi_criteria_trend']; // Chiều hướng tăng - giảm

                // Công thức tính nếu tiêu chí có chiều hướng tăng
                if ($trend == 1) {
                    $listDetail[$key]['kpi_report']      = $total - $target;

                    // Nếu tiêu chí có chỉ số chặn
                    if ($block == 1 && $total > $target) {
                        $listDetail[$key]['kpi_percent'] = $priority;
                    } else {
                        $listDetail[$key]['kpi_percent'] = ($total / $target) * $priority;
                    }
                }
                
                // Công thức tính nếu tiêu chí có chiều hướng giảm
                else {
                    $listDetail[$key]['kpi_report']      = $target - $total;
                    if ($kpiValueTotal == 0) {
                        $listDetail[$key]['kpi_percent'] = $priority;
                    } else {
                        // Nếu tiêu chí có chỉ số chặn
                        if ($block == 1 && $total < $target) {
                            $listDetail[$key]['kpi_percent'] = $priority;
                        } else {
                            $listDetail[$key]['kpi_percent'] = ($target - $total) * ($priority / $target) + $priority;
                        }
                    }
                }

                // Phần trăm hoàn thành tiêu chí kpi
                $totalPercentKpi += $listDetail[$key]['kpi_percent'];
            }
            $data['listDetail']      = $listDetail;
            $data['totalPercentKpi'] = $totalPercentKpi;
        } 
        else {
            foreach ($data['listDetail'] as $key => $value) {
                // Format lại data liên quan theo từng tiêu chí
                $mergeArrayWithHash[$value['staff_id']]['kpi_note_detail_id'][]   = $value['kpi_note_detail_id'];
                $mergeArrayWithHash[$value['staff_id']]['staff_id']               = $value['staff_id'];
                $mergeArrayWithHash[$value['staff_id']]['full_name']              = $value['full_name'];
                $mergeArrayWithHash[$value['staff_id']]['kpi_criteria_id'][]      = $value['kpi_criteria_id'];
                $mergeArrayWithHash[$value['staff_id']]['is_customize'][]         = $value['is_customize'];
                $mergeArrayWithHash[$value['staff_id']]['kpi_criteria_name'][]    = $value['kpi_criteria_name'];
                $mergeArrayWithHash[$value['staff_id']]['kpi_criteria_trend'][]   = $value['kpi_criteria_trend'];
                $mergeArrayWithHash[$value['staff_id']]['is_blocked'][]           = $value['is_blocked'];
                $mergeArrayWithHash[$value['staff_id']]['priority'][]             = $value['priority'];
                $mergeArrayWithHash[$value['staff_id']]['kpi_value'][]            = $value['kpi_value'];
                $mergeArrayWithHash[$value['staff_id']]['kpi_criteria_unit_id'][] = $value['kpi_criteria_unit_id'];
                $mergeArrayWithHash[$value['staff_id']]['unit_name'][]            = $value['unit_name'];
                // Param gọi hàm tính kpi thực tế
                $param['branch_id']          = $data['generalDetail']['branch_id'];
                $param['department_id']      = $data['generalDetail']['department_id'];
                $param['team_id']            = $data['generalDetail']['team_id'];
                $param['staff_id']           = $value['staff_id'];
                $param['kpi_criteria_id']    = $value['kpi_criteria_id'];
                $param['kpi_note_detail_id'] = $value['kpi_note_detail_id'];
                $param['effect_month']       = $data['generalDetail']['effect_month'];
                $param['effect_year']        = $data['generalDetail']['effect_year'];
                
                if ($data['generalDetail']['status'] == 'A') {
                    // Nếu là tiêu chí tạo tay thì lấy từ bảng tổng tháng
                    if ($value['is_customize'] == 0) {
                        $kpiValue = $calculateKpiTable->getTotalByStaffInDay($param);
                    } else {
                        $kpiValue = $calculateKpiTotalTable->getTotalByStaffInMonth($param);
                    }
                } elseif ($data['generalDetail']['status'] == 'D') {
                    $kpiValue = $calculateKpiTotalTable->getTotalByStaffInMonth($param);
                } else {
                    $kpiValue = null;
                }

                // Nếu giá trị thực tế kpi null thì gán = 0
                if (! empty($kpiValue)) {
                    if ($kpiValue['total'] == null) {
                        $kpiValueTotal = 0;
                    } else {
                        $kpiValueTotal = $kpiValue['total'];
                    }
                } else {
                    $kpiValueTotal = 0;
                }

                /**
                 * Nếu có giá trị kpi thực tế thì hiển thị theo công thức
                 * Ngược lại thì hiện 0
                 */
                $priority = intval($value['priority']);   // Độ quan trọng
                $target   = intval($value['kpi_value']);  // Chỉ tiêu
                $total    = intval($kpiValueTotal);       // Thực tế
                $block    = $value['is_blocked'];         // Chỉ số chặn
                $trend    = $value['kpi_criteria_trend']; // Chiều hướng tăng - giảm
                if ($kpiValue != null & !empty($total)) {
                    $mergeArrayWithHash[$value['staff_id']]['kpi_calculate_value'][] = $total;
                    if ($trend == 1) {
                        if ($block == 1 && $total > $target) {
                            $mergeArrayWithHash[$value['staff_id']]['total_kpi_percent'][] = $priority;
                        } else {
                            $mergeArrayWithHash[$value['staff_id']]['total_kpi_percent'][] = ($total / $target) * $priority;
                        }
                    } else {
                        if ($block == 1 && $total < $target) {
                            $mergeArrayWithHash[$value['staff_id']]['total_kpi_percent'][] = $priority;
                        } else {
                            $mergeArrayWithHash[$value['staff_id']]['total_kpi_percent'][] = ($target - $total) * ($priority / $target) + $priority;
                        }
                    }
                } 
                else {
                    $mergeArrayWithHash[$value['staff_id']]['kpi_calculate_value'][] = 0;
                    if ($trend == 1) {
                        $mergeArrayWithHash[$value['staff_id']]['total_kpi_percent'][] = (0 / $target) * $priority;
                    } else {
                        $mergeArrayWithHash[$value['staff_id']]['total_kpi_percent'][] = $priority;
                    }
                }
            }

            // Sắp xếp lại mảng và tính tổng phần trăm hoàn thành kpi
            $data['listDetail'] = array_values($mergeArrayWithHash);
            foreach ($data['listDetail'] as $item) {
                $totalPercentKpi += array_sum($item['total_kpi_percent']);
            }
            $data['totalPercentKpi'] = $totalPercentKpi / count($data['listDetail']);
        }

        return $data;
    }

    // Lưu chỉnh sửa phiếu giao
    public function update($data)
    {
        // Kiểm tra thông tin hiện tại của phiếu giao
        $kpiNoteRecord = KpiNoteTable::where('kpi_note_id', $data['kpi_note_id'])->first();

        // Nếu thông tin thời gian áp dụng được giữ nguyên khi update thì thực hiện update
        // Nếu thông tin thời gian áp dụng khác với hiện tại thì kiểm tra thời gian áp dụng đã tồn tại chưa
        if ($kpiNoteRecord['effect_month'] == $data['effect_month'] && $kpiNoteRecord['effect_year'] == $data['effect_year']) {
            $noteData = [
                'kpi_note_name' => $data['kpi_note_name'],
                'effect_year'   => $data['effect_year'],
                'effect_month'  => $data['effect_month'],
                'is_loop'       => $data['is_loop'],
                'branch_id'     => $data['branch_id'],
                'department_id' => isset($data['department_id']) ? $data['department_id'] : null,
                'team_id'       => isset($data['team_id']) ? $data['team_id'] : null,
                'kpi_note_type' => $data['kpi_note_type'],
                'created_by'    => Auth::id()
            ];
    
            $this->table->updateData($data['kpi_note_id'], $noteData);
    
            // Chuẩn bị dữ liệu để update bảng chi tiết phiếu giao
            if ($data['kpi_note_type'] !== 'S') {
                $noteDetailData = [];
                foreach ($data['priority_id_row'] as $criteriaId => $priorityValue) {
                    $noteDetailData[] = [
                        'kpi_note_id'     => $data['kpi_note_id'],
                        'staff_id'        => null,
                        'kpi_criteria_id' => $criteriaId,
                        'priority'        => $priorityValue,
                        'kpi_value'       => floatval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$criteriaId])),
                    ];
                }
            } 
            else {
                $noteDetailData = [];
                foreach ($data['priority_id_row'] as $staffId => $criteriaValue) {
                    foreach ($criteriaValue as $criteriaId => $priorityValue) {
                        $noteDetailData[] = [
                            'kpi_note_id'     => $data['kpi_note_id'],
                            'staff_id'        => $staffId,
                            'kpi_criteria_id' => $criteriaId,
                            'priority'        => $priorityValue,
                            'kpi_value'       => intval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$staffId][$criteriaId])),
                        ];
                    }
                }
            }
            
            // Clear chi tiết cũ và thêm chi tiết phiếu giao vào database
            $detailTable = app()->get(KpiNoteDetailTable::class);
            $detailTable->where('kpi_note_id', $data['kpi_note_id'])->delete();

            foreach ($noteDetailData as $value) {
                $param = [
                    'kpi_note_id'     => $value['kpi_note_id'],
                    'staff_id'        => $value['staff_id'],
                    'kpi_criteria_id' => $value['kpi_criteria_id']
                ];
                $detailTable->updateData($param, $value);
            }
        } 

        else {
            // Nếu không lặp lại hằng tháng
            if ($data['is_loop'] == 0) {
                $kpiNoteRecord = $this->table->checkKpiNoteExist(null, $data);
                if ($kpiNoteRecord === null) {
                    $noteData = [
                        'kpi_note_name' => $data['kpi_note_name'],
                        'effect_year'   => $data['effect_year'],
                        'effect_month'  => $data['effect_month'],
                        'is_loop'       => $data['is_loop'],
                        'branch_id'     => $data['branch_id'],
                        'department_id' => isset($data['department_id']) ? $data['department_id'] : null,
                        'team_id'       => isset($data['team_id']) ? $data['team_id'] : null,
                        'kpi_note_type' => $data['kpi_note_type'],
                        'created_by'    => Auth::id()
                    ];
            
                    $this->table->updateData($data['kpi_note_id'], $noteData);
            
                    // Chuẩn bị dữ liệu để update bảng chi tiết phiếu giao
                    if ($data['kpi_note_type'] === 'S') {
                        $noteDetailData = [];
                        foreach ($data['priority_id_row'] as $criteriaId => $priorityValue) {
                            $noteDetailData[] = [
                                'kpi_note_id'     => $data['kpi_note_id'],
                                'staff_id'        => null,
                                'kpi_criteria_id' => $criteriaId,
                                'priority'        => $priorityValue,
                                'kpi_value'       => intval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$criteriaId])),
                            ];
                        }
                    } else {
                        $noteDetailData = [];
                        foreach ($data['priority_id_row'] as $staffId => $criteriaValue) {
                            foreach ($criteriaValue as $criteriaId => $priorityValue) {
                                $noteDetailData[] = [
                                    'kpi_note_id'     => $data['kpi_note_id'],
                                    'staff_id'        => $staffId,
                                    'kpi_criteria_id' => $criteriaId,
                                    'priority'        => $priorityValue,
                                    'kpi_value'       => floatval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$staffId][$criteriaId])),
                                ];
                            }
                        }
                    }
                    
                    // Clear chi tiết cũ và thêm chi tiết phiếu giao vào database
                    $detailTable = app()->get(KpiNoteDetailTable::class);
                    $detailTable->where('kpi_note_id', $data['kpi_note_id'])->delete();
        
                    foreach ($noteDetailData as $value) {
                        $param = [
                            'kpi_note_id'     => $value['kpi_note_id'],
                            'staff_id'        => $value['staff_id'],
                            'kpi_criteria_id' => $value['kpi_criteria_id']
                        ];
                        $detailTable->updateData($param, $value);
                    }
                } else {
                    $branchname = BranchesTable::where('branch_id', $data['branch_id'])->select('branch_name')->first()->toArray();
                    switch ($data['kpi_note_type']) {
                        case 'B':
                            return [
                                'error' => 1,
                                'message' => __('Phiếu giao KPI cho :branch trong tháng :month/:year đã tồn tại', [
                                    'branch' => $branchname['branch_name'],
                                    'month'  => $data['effect_month'],
                                    'year'   => $data['effect_year']
                                ])
                            ];
                            break;
                        case 'D':
                            $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                            return [
                                'error' => 1,
                                'message' => __('Phiếu giao KPI cho :department thuộc :branch trong tháng :month/:year đã tồn tại', [
                                    'branch'     => $branchname['branch_name'],
                                    'department' => $departmentName['department_name'],
                                    'month'  => $data['effect_month'],
                                    'year'   => $data['effect_year']
                                ])
                            ];
                            break;
                        case 'T':
                            $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                            $teamName = TeamTable::where('team_id', $data['team_id'])->select('team_name')->first()->toArray();
                            return [
                                'error' => 1,
                                'message' => __('Phiếu giao KPI cho :team thuộc :department trong tháng :month/:year đã tồn tại', [
                                    'team'       => $teamName['team_name'], 
                                    'department' => $departmentName['department_name'],
                                    'month'  => $data['effect_month'],
                                    'year'   => $data['effect_year']
                                ])
                            ];
                            break;
                        case 'S':
                            return [
                                'error' => 1,
                                'message' => __(':name vào tháng :month/:year đã tồn tại', [
                                    'name'   => $kpiNoteRecord['kpi_note_name'], 
                                    'month'  => $data['effect_month'],
                                    'year'   => $data['effect_year']
                                ])
                            ];
                            break;
                    }
                }   
            }
            // Nếu lặp lại hằng tháng 
            else {
                $effectMonth = [];
                for ($i = 12; $i >= $data['effect_month']; $i--) {
                    // Kiểm tra phiếu giao đã được tạo cho nhóm này chưa
                    $kpiNoteRecord = $this->table->checkKpiNoteExist($i, $data);

                    if ($kpiNoteRecord != null) {
                        $branchname = BranchesTable::where('branch_id', $data['branch_id'])->select('branch_name')->first()->toArray();
                        switch ($data['kpi_note_type']) {
                            case 'B':
                                return [
                                    'error' => 1,
                                    'message' => __('Phiếu giao KPI cho :branch trong tháng :month/:year đã tồn tại', [
                                        'branch'     => $branchname['branch_name'],
                                        'month'      => $i,
                                        'year'       => $data['effect_year']
                                    ])
                                ];
                                break;
                            case 'D':
                                $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                                return [
                                    'error' => 1,
                                    'message' => __('Phiếu giao KPI cho :department thuộc :branch trong tháng :month/:year đã tồn tại', [
                                        'branch'     => $branchname['branch_name'],
                                        'department' => $departmentName['department_name'],
                                        'month'      => $i,
                                        'year'       => $data['effect_year']
                                    ])
                                ];
                                break;
                            case 'T':
                                $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
                                $teamName = TeamTable::where('team_id', $data['team_id'])->select('team_name')->first()->toArray();
                                return [
                                    'error' => 1,
                                    'message' => __('Phiếu giao KPI cho :team thuộc :department trong tháng :month/:year đã tồn tại', [
                                        'team'       => $teamName['team_name'], 
                                        'department' => $departmentName['department_name'],
                                        'month'      => $i,
                                        'year'       => $data['effect_year']
                                    ])
                                ];
                                break;
                            case 'S':
                                return [
                                    'error' => 1,
                                    'message' => __(':name vào tháng :month/:year đã tồn tại', [
                                        'name'   => $kpiNoteRecord['kpi_note_name'], 
                                        'month'  => $i,
                                        'year'   => $data['effect_year']
                                    ])
                                ];
                                break;
                        }
                    }

                    $effectMonth[] = $i; 
                }

                // Clear phiếu giao hiện tại
                KpiNoteTable::where('kpi_note_id', $data['kpi_note_id'])->update(['is_deleted' => 1]);
                // Clear chi tiết cũ và thêm chi tiết phiếu giao vào database
                $detailTable = app()->get(KpiNoteDetailTable::class);
                $detailTable->where('kpi_note_id', $data['kpi_note_id'])->delete();
                
                foreach ($effectMonth as $month) {
                    $noteData = [
                        'kpi_note_name' => $data['kpi_note_name'],
                        'effect_year'   => $data['effect_year'],
                        'effect_month'  => $month,
                        'is_loop'       => $data['is_loop'],
                        'branch_id'     => $data['branch_id'],
                        'department_id' => $data['department_id'],
                        'team_id'       => $data['team_id'],
                        'kpi_note_type' => $data['kpi_note_type'],
                        'status'        => 'N',
                        'is_deleted'    => 0,
                        'created_by'    => Auth::id()
                    ];
                    $kpiNoteId = $this->table->add($noteData);
    
                    // Chuẩn bị dữ liệu để thêm bảng chi tiết phiếu giao
                    if ($data['kpi_note_type'] !== 'S') {
                        $noteDetailData = [];
                        foreach ($data['priority_id_row'] as $criteriaId => $priorityValue) {
                            $noteDetailData[] = [
                                'kpi_note_id'     => $kpiNoteId,
                                'staff_id'        => null,
                                'kpi_criteria_id' => $criteriaId,
                                'priority'        => $priorityValue,
                                'kpi_value'       => floatval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$criteriaId])),
                            ];
                        }
                    } else {
                        $noteDetailData = [];
                        foreach ($data['priority_id_row'] as $staffId => $criteriaValue) {
                            foreach ($criteriaValue as $criteriaId => $priorityValue) {
                                $noteDetailData[] = [
                                    'kpi_note_id'     => $kpiNoteId,
                                    'staff_id'        => $staffId,
                                    'kpi_criteria_id' => $criteriaId,
                                    'priority'        => $priorityValue,
                                    'kpi_value'       => intval(preg_replace('/[^\d.]/', '', $data['kpi_value_row'][$staffId][$criteriaId])),
                                ];
                            }
                        }
                    }
                    // Thêm chi tiết phiếu giao vào database
                    $detailTable->add($noteDetailData);
                }
            }
        }

        return [
            'error' => 0,
            'message' => __('Chỉnh sửa phiếu giao thành công')
        ];  
    }

    // Lấy data bảng danh sách tiêu chí cho nhân viên
    public function listCriteriaTable($id)
    {
        // Data chi tiết phiếu giao
        $tableDetail  = app()->get(KpiNoteDetailTable::class);
        $listDetail   = $tableDetail->listById($id);
        $data         = $listDetail;
        
        // Format dữ liệu phân bổ tiêu chí kpi cho nhân vi
        $mergeArrayWithHash = [];
        foreach ($data as $value) {
            $mergeArrayWithHash[$value['staff_id']]['staff_id'] = $value['staff_id'];
            $mergeArrayWithHash[$value['staff_id']]['full_name'] = $value['full_name'];
            $mergeArrayWithHash[$value['staff_id']]['kpi_criteria_id'][] = $value['kpi_criteria_id'];
            $mergeArrayWithHash[$value['staff_id']]['kpi_criteria_name'][] = $value['kpi_criteria_name'];
            $mergeArrayWithHash[$value['staff_id']]['is_customize'][] = $value['is_customize'];
            $mergeArrayWithHash[$value['staff_id']]['priority'][] = $value['priority'];
            $mergeArrayWithHash[$value['staff_id']]['kpi_value'][] = $value['kpi_value'];
            $mergeArrayWithHash[$value['staff_id']]['unit_name'][] = $value['unit_name'];
        }
        $data    = array_values($mergeArrayWithHash);

        $staffDataArr    = [];
        $criteriaDataArr = [];     

        foreach ($data as $key => $item) {
            $staffDataArr[] = [
                'staff_id'   => $item['staff_id'],
                'staff_name' => $item['full_name']
            ];

            foreach ($item['kpi_criteria_id'] as $criteriaKey => $criteriaItem) {
                $criteriaDataArr[] = [
                    'criteria_id'   => $criteriaItem,
                    'criteria_name' => $item['kpi_criteria_name'][$criteriaKey],
                    'is_customize'  => $item['is_customize'][$criteriaKey]
                ];
            }

            $priorityArr = $item['priority'];
            $kpiValueArr = $item['kpi_value'];
            $unitArr     = $item['unit_name'];
        }
        
        $tableData = [
            'staff'     => $staffDataArr,
            'criteria'  => array_unique($criteriaDataArr, SORT_REGULAR),
            'priority'  => $priorityArr,
            'kpi_value' => $kpiValueArr,
            'unit'      => $unitArr 
        ];

        return $tableData;
    }

    // Thêm record chỉ số kpi thực tế cho tiêu chí custom vào bảng calculate_kpi_total
    public function addKpiCalculate($data)
    {
        $calculateKpiTotalTable = app()->get(CalculateKpiTotalTable::class);
        return $calculateKpiTotalTable->addOrUpdate($data);
    }

    public function validatePriorityforGroup($data) 
    {
        if (array_sum($data['priority_id_row']) != 100) {
            $priorityFlag = 1;
        } else {
            $priorityFlag = 0;
        }

        return $priorityFlag;
    }

    public function validatePriorityForStaff($data) 
    {
        foreach ($data['priority_id_row'] as $key => $value) {
            if (array_sum($value) != 100) {
                $priorityFlag = $key;
                return $priorityFlag;
            } else {
                $priorityFlag = 0;
            }
        }

        return $priorityFlag;
    }
}