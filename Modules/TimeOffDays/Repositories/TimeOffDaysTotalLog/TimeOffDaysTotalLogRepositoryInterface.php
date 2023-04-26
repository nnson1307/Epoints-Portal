<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffDaysTotalLog;


interface TimeOffDaysTotalLogRepositoryInterface
{
    public function add($data);

    public function edit($data, $id);

    public function updateOrNew($data, $params);
}