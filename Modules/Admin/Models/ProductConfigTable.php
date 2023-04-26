<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 15:46
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ProductConfigTable extends Model
{
    protected $table = "product_config";
    protected $primaryKey = "product_config_id";
    protected $fillable = [
        "product_config_id",
        "display_view_category",
        "is_display_bundled",
        "type_bundled_product",
        "limit_item",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin cấu hình sản phẩm kèm theo (cart)
     *
     * @return mixed
     */
    public function getInfo()
    {
        return $this->first();
    }

    /**
     * Chỉnh sửa cấu hình sản phẩm
     *
     * @param array $data
     * @param $configId
     * @return mixed
     */
    public function edit(array $data, $configId)
    {
        return $this->where("product_config_id", $configId)->update($data);
    }
}