<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 15:46
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ProductConfigDetailTable extends Model
{
    protected $table = "product_config_details";
    protected $primaryKey = "product_config_detail_id";

    /**
     * Lấy chi tiết cấu hình theo loại
     *
     * @param $configId
     * @return mixed
     */
    public function getConfigDetail($configId)
    {
        return $this->where("product_config_id", $configId)->get();
    }

    /**
     * Xoá chi tiết cấu hình theo loại
     *
     * @param $configId
     * @return mixed
     */
    public function removeConfigDetail($configId)
    {
        return $this->where("product_config_id", $configId)->delete();
    }
}