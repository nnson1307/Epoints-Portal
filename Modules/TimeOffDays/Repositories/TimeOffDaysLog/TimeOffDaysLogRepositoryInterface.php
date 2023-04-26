<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffDaysLog;


interface TimeOffDaysLogRepositoryInterface
{
    public function getLists($data);

    public function add($data);

}