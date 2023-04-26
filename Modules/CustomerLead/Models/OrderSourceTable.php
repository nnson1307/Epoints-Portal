<?php


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class OrderSourceTable extends Model
{
    protected $table = "order_sources";
    protected $primaryKey = "order_source_id";
    protected $fillable = [
        "order_source_id",
        "order_source_name",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy option nguồn đơn hàng
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "order_source_id",
                "order_source_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }
}