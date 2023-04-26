<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-27
 * Time: 9:51 AM
 * @author SonDepTrai
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetailTable extends Model
{
    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';

    const NOT_DELETED = 0;
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
                "order_detail_id",
                "order_id",
                "object_id",
                "object_name",
                "object_type",
                "object_code",
                "quantity",
                "price",
                DB::raw("SUM(quantity) as quantity"),
                "discount",
                DB::raw("SUM(amount) as amount")
            )
            ->where("order_id", $orderId)
            ->where("is_deleted", self::NOT_DELETED)
            ->groupBy("object_type", "object_id")
            ->get();
    }

    /**
     * Lấy chi tiết đơn hàng bằng order_id, object_id
     *
     * @param $objectId
     * @param $orderId
     * @return mixed
     */
    public function getDetailByObject($objectId, $orderId)
    {
        return $this
            ->select(
                "order_detail_id",
                "order_id",
                "object_id",
                "object_name",
                "object_type",
                "object_code",
                "quantity",
                "amount"
            )
            ->where("order_id", $orderId)
            ->where("object_id", $objectId)
            ->first();
    }
}