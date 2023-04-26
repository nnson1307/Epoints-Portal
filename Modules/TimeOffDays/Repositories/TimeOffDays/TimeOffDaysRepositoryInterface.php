<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffDays;


interface TimeOffDaysRepositoryInterface
{
    public function add(array $data);

    public function getList($data);

    public function getDetail($id);

    public function edit(array $data, $id);

    public function total($id);

    public function reportByType($params);

    public function reportByTopTen($params);

    public function reportByPrecious($params);

    public function getOptionDaysOffTime();

    public function getOptionDepartment();
}