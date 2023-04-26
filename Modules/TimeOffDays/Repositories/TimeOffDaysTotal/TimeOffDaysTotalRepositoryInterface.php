<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffDaysTotal;


interface TimeOffDaysTotalRepositoryInterface
{
    public function getLists($id);

    public function checkValidTotal($staffId, $typeId);

    public function edit($data, $staffId, $typeOffDaysId);
}