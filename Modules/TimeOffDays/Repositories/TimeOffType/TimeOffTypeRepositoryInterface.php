<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffType;


interface TimeOffTypeRepositoryInterface
{
    public function getList($data);

    public function getAll();

    public function edit($data, $id);

    public function getDetail($id);

    public function getListsStaffApprove($timeOffTypeId);
}