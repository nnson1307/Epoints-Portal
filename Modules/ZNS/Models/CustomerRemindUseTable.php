<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 13:28
 */

namespace Modules\ZNS\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerRemindUseTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_remind_use";
    protected $primaryKey = "customer_remind_use_id";
    protected $fillable = [
        "customer_remind_use_id",
        "customer_id",
        "order_id",
        "object_type",
        "object_id",
        "object_code",
        "object_name",
        "sent_at",
        "is_finish",
        "is_queue",
        "note",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;
    const NOT_CANCEL = "ordercancle";

    /**
     * Lấy thông tin dự kiến nhắc sử dụng
     *
     * @param $remindUseId
     * @return mixed
     */
    public function getInfo($remindUseId)
    {
        return $this
            ->select(
                "{$this->table}.customer_remind_use_id",
                "cs.full_name",
                "{$this->table}.object_type",
                "{$this->table}.object_name",
                "{$this->table}.sent_at",
                "{$this->table}.is_finish",
                "ord.order_code",
                "{$this->table}.note"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->join("orders as ord", "ord.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.customer_remind_use_id", $remindUseId)
            ->first();
    }

}