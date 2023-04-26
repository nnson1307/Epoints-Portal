<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 16:35
 */

namespace Modules\Admin\Repositories\ProductConfig;


interface ProductConfigRepoInterface
{
    /**
     * Lấy dữ liệu view
     *
     * @return mixed
     */
    public function getDataView();

    /**
     * Lưu thông tin
     *
     * @param $input
     * @return mixed
     */
    public function update($input);
}