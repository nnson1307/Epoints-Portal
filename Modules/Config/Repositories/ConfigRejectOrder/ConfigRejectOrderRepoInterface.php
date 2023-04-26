<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/05/2022
 * Time: 11:36
 */

namespace Modules\Config\Repositories\ConfigRejectOrder;


interface ConfigRejectOrderRepoInterface
{
    /**
     * Lấy data view
     *
     * @return mixed
     */
    public function getDataView();

    /**
     * Lưu cấu hình
     *
     * @param $input
     * @return mixed
     */
    public function save($input);
}