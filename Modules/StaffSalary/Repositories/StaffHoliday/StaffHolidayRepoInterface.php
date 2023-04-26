<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/04/2022
 * Time: 10:50
 */

namespace Modules\StaffSalary\Repositories\StaffHoliday;


interface StaffHolidayRepoInterface
{

     /**
     * Add Holiday
     *
     * @return mixed
     */
    public function add($input);

     /**
     * Add Holiday
     *
     * @return mixed
     */
    public function edit($input, $id);

    /**
     * Get List Holiday
     *
     * @return mixed
     */
    public function getList(array $filters = []);

    /**
     * Get Detail salary
     *
     * @return mixed
     */
    public function getDetail($id);

     /**
     * Get Detail salary
     *
     * @return mixed
     */
    public function delete($id);

    /**
     * Get holiday by date
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getHolidayByDate($startDate);

}