<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\StaffSalary\Repositories\StaffSalaryConfig;


interface StaffSalaryConfigRepoInterface
{

    /***
     * add salary attribute
     */
    public function add($input);

    /**
     * get salary attribute by staff
     */
    public function getDetailByStaff($staffId);

     /**
     * delete salary attribute by staff
     */
    public function edit($input, $id);

    /**
     * get salary config by period
     */
    public function getListByPayPeriod($periodCode);
}