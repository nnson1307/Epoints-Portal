<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/25/2020
 * Time: 5:26 PM
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryHistoryLogTable extends Model
{
    protected $table = "delivery_history_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "delivery_history_id",
        "status",
        "created_by",
        "created_type",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}