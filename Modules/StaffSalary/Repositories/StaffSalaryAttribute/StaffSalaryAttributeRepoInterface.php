<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\StaffSalary\Repositories\StaffSalaryAttribute;


interface StaffSalaryAttributeRepoInterface
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
    public function deleteByStaff($staffId);
}