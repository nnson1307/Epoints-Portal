<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffTypeOption;

use Modules\TimeOffDays\Models\TimeOffTypeOptionTable;
use Modules\TimeOffDays\Models\StaffsTable;

class TimeOffTypeOptionRepository implements TimeOffTypeOptionRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffTypeOptionTable $repo)
    {
        $this->repo = $repo;
    }

    public function getList($data){
        return $this->repo->getList($data);
    }

    public function getAll($id){
        return $this->repo->getAll($id);
    }

    public function edit($data, $id){
        return $this->repo->edit($data, $id);
    }

    public function editConfigAll($time_off_type_code){
        return $this->repo->editConfigAll($time_off_type_code);
    }

    public function editConfig($time_off_type_code, $time_off_type_option_key, $time_off_type_option_value){
        return $this->repo->editConfig($time_off_type_code, $time_off_type_option_key, $time_off_type_option_value);
    }

    /**
     * Danh sách
     *
     * @param $input
     * @return mixed|void
     * @throws TimeOffDaysActivityApproveRepoException
     */
    public function getListsStaffApprove($timeOffTypeId)
    {
        $arrOption = $this->repo->getLists($timeOffTypeId);
        $optionInfo = [];
        foreach ($arrOption as $objOption) {
            if ($objOption['time_off_type_option_key'] == 'approve_level_1' || $objOption['time_off_type_option_key'] == 'approve_level_2' || $objOption['time_off_type_option_key'] == 'approve_level_3') {
                $optionInfo[$objOption['time_off_type_option_key']] = $objOption['time_off_type_option_value'];
            }
        }

        $arrayData = [];
        $mStaff = new StaffsTable();
        foreach ($optionInfo as $item) {
            $staffInfo = $mStaff->getStaffApproveInfo($item);
            if (isset($staffInfo)) {
                $arrayData[] =
                    [
                        'staff_id' => $staffInfo['staff_id'],
                        'full_name' => $staffInfo['full_name'],
                        'staff_avatar' => $staffInfo['staff_avatar'],
                        'staff_title' => $staffInfo['staff_title'],
                        'staff_title_id' => $staffInfo['staff_title_id'],
                    ];
            }
        }
        return $arrayData;
    }

    
}