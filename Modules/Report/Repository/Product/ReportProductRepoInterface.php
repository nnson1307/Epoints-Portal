<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 11:10 AM
 */

namespace Modules\Report\Repository\Product;


interface ReportProductRepoInterface
{
    /**
     * Load chart báo cáo sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function loadChart($input);

    /**
     * Export excel tổng
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelTotal($input);
}