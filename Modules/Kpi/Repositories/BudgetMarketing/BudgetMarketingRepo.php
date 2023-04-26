<?php
namespace Modules\Kpi\Repositories\BudgetMarketing;

use Illuminate\Support\Facades\Auth;
use Modules\Kpi\Models\DepartmentsTable;
use Modules\Kpi\Models\TeamTable;
use Modules\Kpi\Models\BudgetMarketingTable;
use Modules\Kpi\Models\StaffsTable;

/**
 * class BudgetMarketingRepo
 * @author HaoNMN
 * @since Jul 2022
 */
class BudgetMarketingRepo implements BudgetMarketingRepoInterface
{
    protected $table;


    public function __construct(BudgetMarketingTable $budgetMarketingTable)
    {
        $this->table = $budgetMarketingTable;
    }

    public function list($param = [], $type)
    {
        $data = $this->table->list($param, $type);
        foreach ($data as $item) {
            $staff = StaffsTable::where('staff_id', $item['created_by'])->first();
            $item['created_by']  = $staff['full_name'];
        }
        return $data;
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

    // Thêm ngân sách
    public function add($data)
    {
        $data['effect_time'] = date("Y-m-d",strtotime($data['effect_time']));
        if (! isset($data['team_id'])) {
            $data['team_id'] = null;
        }
        unset($data['budget_allocation']);
        $data['budget']      = intval(preg_replace('/[^\d.]/', '', $data['budget']));
        $data['budget_type'] = 0;
        $data['created_by']  = Auth::id();
        $data['is_deleted']  = 0;

        if ( ! empty($data['team_id'])) {
            $checkDuplicate = BudgetMarketingTable::where('department_id', $data['department_id'])
                                                    ->where('team_id', $data['team_id'])
                                                    ->where('budget_type', 0)
                                                    ->where('effect_time', $data['effect_time'])
                                                    ->where('is_deleted', 0)->first();
            
        } else {
            $checkDuplicate = BudgetMarketingTable::where('department_id', $data['department_id'])
                                                    ->where('budget_type', 0)
                                                    ->where('effect_time', $data['effect_time'])
                                                    ->where('is_deleted', 0)->first();
        }
                                               
        if ($checkDuplicate) {
            $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
            if (! empty($data['team_id'])) {
                $teamName = TeamTable::where('team_id', $data['team_id'])->select('team_name')->first()->toArray();
                return [
                    'error' => 1,
                    'message' => __('Ngân sách marketing của phòng ban :department - nhóm :team vào tháng :effect_time đã tồn tại', [
                        'team'        => $teamName['team_name'], 
                        'department'  => $departmentName['department_name'],
                        'effect_time' => $data['effect_time']
                    ])
                ];
            }
            
            return [
                'error' => 1,
                'message' => __('Ngân sách marketing của phòng ban :department vào tháng :effect_time đã tồn tại', [
                    'department'  => $departmentName['department_name'],
                    'effect_time' => date("m/Y",strtotime($data['effect_time']))
                ])
            ];
        }

        $this->table->add($data);
        return [
            'error'   => 0,
            'message' => __('Thêm thành công')
        ];
    }

    public function addDay($data)
    {
        if (! isset($data['team_id'])) {
            $data['team_id'] = null;
        }
        unset($data['budget_allocation']);
        $data['budget']      = intval(preg_replace('/[^\d.]/', '', $data['budget']));
        $data['budget_type'] = 1;
        $data['created_by']  = Auth::id();
        $data['is_deleted']  = 0;

        if ( ! empty($data['team_id'])) {
            $checkDuplicate = BudgetMarketingTable::where('department_id', $data['department_id'])
                                                    ->where('team_id', $data['team_id'])
                                                    ->where('budget_type', 1)
                                                    ->where('effect_time', $data['effect_time'])
                                                    ->where('is_deleted', 0)->first();
            
        } else {
            $checkDuplicate = BudgetMarketingTable::where('department_id', $data['department_id'])
                                                    ->where('budget_type', 1)
                                                    ->where('effect_time', $data['effect_time'])
                                                    ->where('is_deleted', 0)->first();
        }
                                               
        if ($checkDuplicate) {
            $departmentName = DepartmentsTable::where('department_id', $data['department_id'])->select('department_name')->first()->toArray();
            if (! empty($data['team_id'])) {
                $teamName = TeamTable::where('team_id', $data['team_id'])->select('team_name')->first()->toArray();
                return [
                    'error' => 1,
                    'message' => __('Ngân sách marketing của phòng ban :department - nhóm :team vào ngày :effect_time đã tồn tại', [
                        'team'        => $teamName['team_name'], 
                        'department'  => $departmentName['department_name'],
                        'effect_time' => $data['effect_time']
                    ])
                ];
            }
            
            return [
                'error' => 1,
                'message' => __('Ngân sách marketing của phòng ban :department vào ngày :effect_time đã tồn tại', [
                    'department'  => $departmentName['department_name'],
                    'effect_time' => $data['effect_time']
                ])
            ];
        }

        $this->table->add($data);
        return [
            'error'   => 0,
            'message' => __('Thêm thành công')
        ];
    }

    // Cập nhật ngân sách
    public function update($data)
    {
        $budgetId            = $data['budget_marketing_id'];
        $data['budget'] = intval(preg_replace('/[^\d.]/', '', $data['budget']));
        $this->table->updateData($budgetId, $data);
        return [
            'error'   => 0,
            'message' => __('Cập nhật thành công')
        ];
    }

    // Cập nhật ngân sách
    public function updatebyDay($data)
    {
        $budgetId = $data['budget_marketing_id'];
        $data['budget'] = intval(preg_replace('/[^\d.]/', '', $data['budget']));
        $this->table->updateData($budgetId, $data);
        return [
            'error'   => 0,
            'message' => __('Cập nhật thành công')
        ];
    }

    public function remove($id)
    {
        $this->table->remove($id);
        return [
            'error'   => 0,
            'message' => __('Xóa thành công')
        ];
    }
} 