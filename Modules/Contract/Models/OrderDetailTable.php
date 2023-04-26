<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2021
 * Time: 17:35
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetailTable extends Model
{
    protected $table = "order_details";
    protected $primaryKey = "order_detail_id";
    protected $fillable = [
        "order_detail_id",
        "order_id",
        "object_id",
        "object_name",
        "object_type",
        "object_code",
        "staff_id",
        "refer_id",
        "price",
        "quantity",
        "discount",
        "amount",
        "voucher_code",
        "is_deleted",
        "quantity_type",
        "case_quantity",
        "saving",
        "is_change_price",
        "is_check_promotion",
        "updated_at",
        "created_at",
        "created_by",
        "updated_by",
        "tax"
    ];

    /**
     * Lấy thông tin chi tiết đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getDetail($orderId)
    {
        return $this
            ->select(
                "{$this->table}.order_detail_id",
                "{$this->table}.order_id",
                "{$this->table}.object_id",
                "{$this->table}.object_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.staff_id",
                "{$this->table}.refer_id",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.tax",
                DB::raw("(CASE WHEN object_type='product' THEN product_childs.is_applied_kpi
                                     ELSE 1 END) as is_applied_kpi")
            )
            ->leftJoin("product_childs", function($join){
                $join->on("product_childs.product_child_id", "=", "{$this->table}.object_id")
                    ->where("{$this->table}.object_type", "=", "product");
            })
            ->where("order_id", $orderId)
            ->get();
    }

    /**
     * Xoá chi tiết đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function removeDetailByOrder($orderId)
    {
        return $this->where("order_id", $orderId)->delete();
    }

    /**
     * Thêm chi tiết đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->order_detail_id;
    }
}