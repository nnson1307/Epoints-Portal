<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionLogTable extends Model
{
    protected $table = "promotion_logs";
    protected $primaryKey = "promotion_log_id";
    protected $fillable = [
        "promotion_log_id",
        "promotion_id",
        "promotion_code",
        "start_date",
        "end_date",
        "order_id",
        "order_code",
        "object_type",
        "object_id",
        "object_code",
        "quantity",
        "base_price",
        "promotion_price",
        "gift_object_type",
        "gift_object_id",
        "gift_object_code",
        "quantity_gift",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm promotion log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}