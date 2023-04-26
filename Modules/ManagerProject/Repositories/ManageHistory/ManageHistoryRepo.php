<?php


namespace Modules\ManagerProject\Repositories\ManageHistory;


use Carbon\Carbon;
use Modules\ManagerProject\Models\StaffsTable;
use Modules\ManagerProject\Models\ManageProjectHistoryTable;
use Modules\ManagerProject\Models\ManageProjectStaffTable;
use Modules\ManagerProject\Models\ManagerHistoryTable;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;

class ManageHistoryRepo implements ManageHistoryRepoInterface
{
    /**
     * Lấy danh sách nhân viên theo dự án
     * @param $data
     * @return mixed|void
     */
    public function getListStaff($data)
    {
        $listStaff = [];
        $mStaff = app()->get(StaffsTable::class);
        if (isset($data['manage_project_id'])) {
            $rProject = app()->get(ProjectRepositoryInterface::class);
            $mManageProjectStaff = app()->get(ManageProjectStaffTable::class);

            $project = $rProject->getDetail($data['manage_project_id']);
            $listStaff[] = $project['manager_id'];
            $staffSupport = $mManageProjectStaff->getListStaffByProject($data['manage_project_id']);
            if (count($staffSupport) != 0){
                $staffSupport = collect($staffSupport)->pluck('staff_id')->toArray();
                $listStaff = array_merge($staffSupport,$listStaff);
            }
        }

        $listStaffInfo = $mStaff->getListStaffByArrStaff($listStaff);

        return [
            'project' => $project,
            'listStaffInfo' => $listStaffInfo
        ];
    }

    /**
     * Tìm kiếm lịch sử
     * @param $data
     * @return mixed|void
     */
    public function searchHistory($data)
    {
        $mManageHistory = app()->get(ManagerHistoryTable::class);
        $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
        if (!isset($data['created_at'])){
            $data['created_at'] = Carbon::now()->startOfMonth()->format('d/m/Y').' - '.Carbon::now()->endOfMonth()->format('d/m/Y');
        }
        $listHistory = $mManageHistory->getListhistory($data);
        $listProjectHistory = $mManageProjectHistory->getListhistory($data);
        $arrHistory = [];
        if (count($listHistory) != 0){
            $listHistory = collect($listHistory)->groupBy('created_at_format')->sortKeysDesc()->toArray();
            foreach ($listHistory as $key => $item){
                $arrHistory[$key] = collect($item)->groupBy(function ($item){
                    return $item['manage_work_id'].'_work';
                })->toArray();
            }
        }

        $arrProjectHistory = [];
        if (count($listProjectHistory) != 0){
            $listProjectHistory = collect($listProjectHistory)->groupBy('created_at_format')->sortKeysDesc()->toArray();
            foreach ($listProjectHistory as $key => $item){
                $arrProjectHistory[$key] = collect($item)->groupBy(function ($item){
                    return $item['manage_project_id'].'_project';
                })->toArray();
            }
        }

//        $arrMerge = collect($arrHistory)->mergeRecursive($arrProjectHistory)->sortKeysDesc();
        $arrMerge = $arrProjectHistory;

        $view = view('manager-project::history.append.list-history',['listHistory' => $arrMerge])->render();
        return [
            'error' => false,
            'view' => $view
        ];
    }
}