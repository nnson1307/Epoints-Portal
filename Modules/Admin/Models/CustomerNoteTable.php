<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerNoteTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_notes";
    protected $primaryKey = "customer_note_id";
    protected $fillable = [
        "customer_note_id",
        "customer_id",
        "note",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy danh sách ghi chú
     *
     * @param $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        return $this
            ->select(
                "{$this->table}.customer_note_id",
                "{$this->table}.customer_id",
                "{$this->table}.note",
                "s.full_name as staff_name"
            )
            ->join("staffs as s", "s.staff_id", "=", "{$this->table}.created_by")
            ->orderBy("{$this->table}.customer_note_id", "desc");
    }

    /**
     * Tạo ghi chú
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_note_id;
    }

    /**
     * Lấy thông tin ghi chú
     *
     * @param $noteId
     * @return mixed
     */
    public function getInfo($noteId)
    {
        return $this->where("customer_note_id", $noteId)->first();
    }

    /**
     * Chỉnh sửa thông tin người liên hệ
     *
     * @param array $data
     * @param $noteId
     * @return mixed
     */
    public function edit(array $data, $noteId)
    {
        return $this->where("customer_note_id", $noteId)->update($data);
    }

    /**
     * Lấy ghi chú gần nhất của KH
     *
     * @param $customerId
     * @return mixed
     */
    public function getLastNoteByCustomer($customerId)
    {
        return $this->where("customer_id", $customerId)->orderBy("customer_note_id", "desc")->first();
    }
}