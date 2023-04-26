<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 11:30
 */

namespace Modules\Report\Repository\ProductInventory;


interface ProductInventoryRepoInterface
{
    /**
     * Lấy data view báo cáo tồn kho
     *
     * @return mixed
     */
    public function dataViewIndex();

    /**
     * Danh sách tồn kho
     *
     * @param array $filter
     * @return mixed
     */
    public function list(array $filter = []);

    /**
     * Export chi tiết tồn kho
     *
     * @param $input
     * @return mixed
     */
    public function exportExcelDetail($input);

    /**
     * Lấy option sản phẩm load more
     *
     * @param $input
     * @return mixed
     */
    public function getListChild($input);
}