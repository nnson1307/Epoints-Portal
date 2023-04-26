<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/04/2021
 * Time: 09:48
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    /**
     * Lấy thông tin đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getInfo($orderId)
    {
        return $this
            ->select(
                "order_id",
                "order_code",
                "order_source_id",
                "process_status"
            )
            ->where("order_id", $orderId)
            ->first();
    }
}