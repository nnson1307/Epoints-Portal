<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\Staffs;


interface StaffsRepositoryInterface
{
    public function getAll();

    public function getListById($input);

    public function getListStaffApprove();

    public function getListStaffDepartment($departmentId);

    public function getDetailStaffApproveInfo($staffId);

    public function getDetailApproveLevel1($departmentId);

    public function getListStaffApproveInfo($arrStaffs);
}