<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 4:49 PM
 */

namespace Modules\Report\Repository\ProductCategory;


interface ReportProductCategoryRepoInterface
{
    /**
     * Load chart bao cáo danh mục sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function loadChart($input);
}