<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 10:33 AM
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class StaffTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy thông tin nv thực hiện
     *
     * @return mixed
     */
    public function getStaff()
    {
        return $this
            ->select(
                "staff_id",
                "full_name as staff_name",
                "phone1 as phone"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Thông tin chi tiết nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getItem($staffId)
    {
        $select = $this->select(
            "{$this->table}.staff_id",
            "{$this->table}.branch_id",
            "{$this->table}.full_name",
            "{$this->table}.phone1 as phone",
            "branches.branch_code"
        )
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->where("branches.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        return $select->first();
    }
}