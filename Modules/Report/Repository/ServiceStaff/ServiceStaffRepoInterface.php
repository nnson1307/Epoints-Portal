<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 1/26/2021
 * Time: 9:50 AM
 */

namespace Modules\Report\Repository\ServiceStaff;


interface ServiceStaffRepoInterface
{
    /**
     * Load data view index
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * Load data chart + table chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function dataChart($input);

    /**
     * Table chi tiết đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function listDetail($input);

    /**
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotal($input);

    /**
     * Export excel chi tiết
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelDetail($input);
}