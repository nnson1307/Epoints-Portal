<?php

namespace Modules\CustomerLead\Models;

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
}