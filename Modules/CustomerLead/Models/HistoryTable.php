<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 28/07/2021
 * Time: 16:06
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class HistoryTable extends Model
{
    protected $table = "oc_histories";
    protected $primaryKey = "history_id";
    protected $fillable = [
        "history_id",
        "uid",
        "object_id_call",
        "extension_number",
        "source_code",
        "object_id",
        "object_code",
        "object_name",
        "object_phone",
        "start_time",
        "end_time",
        "reply_time",
        "ring_time",
        "total_ring_time",
        "total_reply_time",
        "postage",
        "history_type",
        "status",
        "error_text",
        "link_record",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lưu lịch sử cuộc gọi
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->history_id;
    }

    /**
     * Chỉnh sửa lịch sử
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("history_id", $id)->update($data);
    }
}