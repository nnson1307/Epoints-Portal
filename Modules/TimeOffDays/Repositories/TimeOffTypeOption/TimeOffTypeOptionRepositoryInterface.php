<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffTypeOption;


interface TimeOffTypeOptionRepositoryInterface
{
    public function getList($data);
    
    public function getAll($id);

    public function edit($data, $id);

    public function editConfig($time_off_type_code, $time_off_type_option_key, $time_off_type_option_value);

    public function editConfigAll($time_off_type_code);

     /**
     * Lấy danh sách người duyệt
     */
    public function getListsStaffApprove($timeOffTypeId);
}