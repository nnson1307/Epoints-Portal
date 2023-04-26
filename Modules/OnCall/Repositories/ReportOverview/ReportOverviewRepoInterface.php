<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/08/2021
 * Time: 09:34
 */

namespace Modules\OnCall\Repositories\ReportOverview;


interface ReportOverviewRepoInterface
{
    /**
     * Lấy các option view index
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Load chart báo cáo tổng quan
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