<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/09/2021
 * Time: 11:01
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ProductChildTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;
    const SURCHARGE = 0;

    /**
     * Lấy thông tin sản phẩm
     *
     * @param $productId
     * @return mixed
     */
    public function getInfo($productId)
    {
        return $this
            ->select(
                "product_child_id",
                "product_child_name",
                "product_code",
                "price",
                "unit_id",
                "is_applied_kpi"
            )
            ->where("product_child_id", $productId)
            ->first();
    }
}