<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy các option nhan vien
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select(
            "staff_id as accounting_id",
            "full_name as accounting_name"
        )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);
        return $select->get();
    }

    /**
     * Chi tiết nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getItem($staffId)
    {
        return $this
            ->select(
                "staff_id as accounting_id",
                "full_name as accounting_name",
                "phone1 as phone"
            )
            ->where("staff_id", $staffId)
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->first();
    }

    /**
     * Lấy thông tin nhân viên - dùng làm history
     *
     * @param $staffId
     * @return mixed
     */
    public function getInfo($staffId)
    {
        return $this
            ->select(
                "staff_id",
                "full_name",
                "phone1 as phone"
            )
            ->where("staff_id", $staffId)
            ->first();
    }
}