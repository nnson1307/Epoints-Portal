<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeWorkingStaffs;


interface TimeWorkingStaffsRepositoryInterface
{
    public function getAll();

    public function edit($data, $id);

    public function removeTimeOffDay($id);
}