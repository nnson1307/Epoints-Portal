<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/16/2020
 * Time: 9:35 AM
 */

namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;

class OrderLogTable extends Model
{
    protected $table = "order_log";
    protected $primaryKey = "id";

    protected $fillable = [
        "id",
        "order_id",
        "created_type",
        "type",
        "status",
//        "note",
        "created_by",
        "created_at",
        "updated_at",
        "note_vi",
        "note_en"
    ];

    /**
     * Thêm order log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->id;
    }

    /**
     * Lấy order log
     *
     * @param $orderId
     * @param $status
     * @return mixed
     */
    public function checkStatusLog($orderId, $status)
    {
        return $this
            ->select(
                "order_id",
                "status"
//                "note"
            )
            ->where("order_id", $orderId)
            ->where("status", $status)
            ->first();
    }
}