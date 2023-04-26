<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/08/2021
 * Time: 11:08
 */

namespace Modules\OnCall\Repositories\ReportStaff;


interface ReportStaffRepoInterface
{
    /**
     * Lấy các option view index
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Load dữ liệu báo cáo
     *
     * @param $input
     * @return mixed
     */
    public function loadChart($input);

    /**
     * Load dữ liệu list 1
     *
     * @param $input
     * @return mixed
     */
    public function loadList1($input);
}