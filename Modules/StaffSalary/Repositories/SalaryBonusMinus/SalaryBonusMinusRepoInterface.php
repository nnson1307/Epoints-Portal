<?php


namespace Modules\StaffSalary\Repositories\SalaryBonusMinus;


interface SalaryBonusMinusRepoInterface
{
    /**
     * @return mixed
     */
    public function getList();

    /**
     * @param $id
     * @return mixed
     */
    public function getDetail($id);
}