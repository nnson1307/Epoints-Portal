<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerPersonContactTable extends Model
{
    use ListTableTrait;
    protected $table = "customer_person_contacts";
    protected $primaryKey = "customer_person_contact_id";
    protected $fillable = [
        "customer_person_contact_id",
        "customer_id",
        "person_name",
        "person_phone",
        "person_email",
        "staff_title_id",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy danh sách người liên hệ
     *
     * @param $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.customer_person_contact_id",
                "{$this->table}.customer_id",
                "{$this->table}.person_name",
                "{$this->table}.person_phone",
                "{$this->table}.person_email",
                "{$this->table}.staff_title_id",
                "t.staff_title_name",
                "{$this->table}.created_at"
            )
            ->leftJoin("staff_title as t", "t.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->orderBy("{$this->table}.customer_person_contact_id", "desc");

        return $ds;
    }

    /**
     * Tạo thông tin người liên hệ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_person_contact_id;
    }

    /**
     * Lấy thông tin người liên hệ
     *
     * @param $personContactId
     * @return mixed
     */
    public function getInfo($personContactId)
    {
        return $this->where("customer_person_contact_id", $personContactId)->first();
    }

    /**
     * Chỉnh sửa thông tin người liên hệ
     *
     * @param array $data
     * @param $personContactId
     * @return mixed
     */
    public function edit(array $data, $personContactId)
    {
        return $this->where("customer_person_contact_id", $personContactId)->update($data);
    }

    /**
     * Check sdt đã tồn tại chưa
     *
     * @param $phone
     * @param $customerId
     * @return mixed
     */
    public function checkUniquePhone($phone, $customerId, $personContactId)
    {
        return $this
            ->where("person_phone", $phone)
            ->where("customer_id", $customerId)
            ->where("customer_person_contact_id", "<>", $personContactId)
            ->first();
    }
}