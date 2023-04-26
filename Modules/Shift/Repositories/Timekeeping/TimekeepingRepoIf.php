<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 2:24 PM
 */

namespace Modules\Shift\Repositories\Timekeeping;


interface TimekeepingRepoIf
{
    /**
     * Danh sách
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Lấy dữ liệu filter ds
     *
     * @return mixed
     */
    public function getDataFilter();

    /**
     * Chi tiết
     *
     * @param array $filters
     * @return mixed
     */
    public function detailStaff($staffId, $month, $year);


    public function detailStaffByWorkingDay($staffId, $month, $year , $day);

    public function getDetailInfoStaff($staffId);
    
    public function getAllStaff();
}