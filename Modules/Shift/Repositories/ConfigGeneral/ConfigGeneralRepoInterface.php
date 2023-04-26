<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/10/2022
 * Time: 16:01
 */

namespace Modules\Shift\Repositories\ConfigGeneral;


interface ConfigGeneralRepoInterface
{
    /**
     * Lấy data cấu hình chung
     *
     * @return mixed
     */
    public function getDataGeneral();

    /**
     * Chỉnh sửa cấu hình chung
     *
     * @param $input
     * @return mixed
     */
    public function update($input);
}