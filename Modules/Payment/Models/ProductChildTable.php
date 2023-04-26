<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/01/2022
 * Time: 11:17
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class ProductChildTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    const IS_NOT_DELETED = 0;
    const IS_ACTIVE = 1;
    const IS_SALE = 1;

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
                "{$this->table}.product_child_id",
                "{$this->table}.product_id",
                "{$this->table}.product_code",
                "{$this->table}.product_child_name",
                "{$this->table}.unit_id",
                "units.name as unit_name",
                "{$this->table}.cost",
                "{$this->table}.price",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "{$this->table}.created_by",
                "{$this->table}.updated_by",
                "{$this->table}.is_deleted",
                "{$this->table}.is_actived",
                "{$this->table}.slug",
                "products.type_refer_commission",
                "products.refer_commission_value",
                "products.type_staff_commission",
                "products.staff_commission_value",
                "{$this->table}.is_sales",
                "{$this->table}.type_app",
                "{$this->table}.percent_sale",
                "{$this->table}.is_remind",
                "{$this->table}.remind_value"
            )
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->leftJoin("units", "units.unit_id", "=", "product_childs.unit_id")
            ->where("product_childs.product_child_id", $productId)
            ->first();
    }

    /**
     * Lấy thông tin sản phẩm khuyến mãi
     *
     * @param $productCode
     * @return mixed
     */
    public function getProductPromotion($productCode)
    {
        return $this
            ->select(
                "product_child_id",
                "product_code",
                "product_child_name",
                "cost as old_price",
                "price as new_price"
            )
            ->where("product_code", $productCode)
            ->first();
    }
}