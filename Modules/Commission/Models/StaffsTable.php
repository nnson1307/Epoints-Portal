<?php

namespace Modules\Commission\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

/**
 * Class StaffsTable
 * @author HaoNMN
 * @since Jun 2022
 */
class StaffsTable extends Model
{
    use ListTableTrait;

    protected $table = 'staffs';
    protected $primaryKey = 'staff_id';
    protected $fillable = [
        'staff_id',
        'user_name'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Danh sách nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name as staff_name",
                "{$this->table}.staff_type",
                "b.branch_name",
                "d.department_name",
                "{$this->table}.commission_rate"
            )
            ->join("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
            ->join("departments as d", "d.department_id", "=", "{$this->table}.department_id")
            ->join("staff_title as t", "t.staff_title_id", "=", "{$this->table}.staff_title_id")
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_id", "desc");

        //Nhập thông tin tìm kiếm
        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];

            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.email", 'like', '%' . $search . '%');
            });
        }

//        unset($filter['search']);

        return $ds;
    }


    /**
     * Lấy tên admin thông qua id
     * @return array
     */
    public function getStaffById($id)
    {
        return $this->select("{$this->table}.user_name")
            ->where("{$this->table}.staff_id", $id)
            ->first();
    }

    /**
     * Lấy danh sách nhân viên
     */
    public function getListStaff(array $filter = [])
    {
        $oSelect = $this->select(
            "{$this->table}.{$this->primaryKey}",
            "{$this->table}.full_name",
            'stm.staff_type_id',
            'st.type_name',
            "{$this->table}.branch_id",
            'b.branch_name',
            "{$this->table}.department_id",
            'd.department_name',
            "{$this->table}.staff_title_id",
            'stt.staff_title_name'
        )
            ->leftJoin('staff_title as stt', 'stt.staff_title_id', '=', "{$this->table}.staff_title_id")
            ->leftJoin('staff_type_map as stm', 'stm.staff_id', '=', "{$this->table}.staff_id")
            ->leftJoin('staff_type as st', 'st.staff_type_id', '=', 'stm.staff_type_id')
            ->leftJoin('branches as b', 'b.branch_id', '=', "{$this->table}.branch_id")
            ->leftJoin('departments as d', 'd.department_id', '=', "{$this->table}.department_id")
            ->where("{$this->table}.is_actived", 1);

        if (!empty($filter['staff_type_id'])) {
            $oSelect->where('stm.staff_type_id', $filter['staff_type_id']);
        }

        if (!empty($filter['branch_id'])) {
            $oSelect->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        if (!empty($filter['department_id'])) {
            $oSelect->where("{$this->table}.department_id", $filter['department_id']);
        }

        if (!empty($filter['staff_title_id'])) {
            $oSelect->where("{$this->table}.staff_title_id", $filter['staff_title_id']);
        }

        if (!empty($filter['full_name'])) {
            $oSelect->where("{$this->table}.full_name", 'LIKE', '%' . $filter['full_name'] . '%');
        }

        return $oSelect->get();
    }

    /**
     * Lấy danh sách nhân viên thực nhận hoa hồng
     */
    public function getListStaffReceived(array $filter = [])
    {
        $oSelect = $this
            ->select(
                "{$this->table}.{$this->primaryKey}",
                "{$this->table}.full_name",
                "{$this->table}.staff_type",
                "{$this->table}.branch_id",
                'b.branch_name',
                "{$this->table}.department_id",
                'd.department_name',
                'sr.commission_received'
            )
            ->leftJoin('staff_title as stt', 'stt.staff_title_id', '=', "{$this->table}.staff_title_id")
            ->join('branches as b', 'b.branch_id', '=', "{$this->table}.branch_id")
            ->join('departments as d', 'd.department_id', '=', "{$this->table}.department_id")
            ->leftJoin('staff_received as sr', 'sr.staff_id', '=', "{$this->table}.staff_id")

            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETED)
            ->orderBy("{$this->table}.staff_id", "desc")
            ->groupBy("{$this->table}.staff_id");

        if (!empty($filter['staff_type'])) {
            $oSelect->where("{$this->table}.staff_type", $filter['staff_type']);
        }

        if (!empty($filter['branch_id'])) {
            $oSelect->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        if (!empty($filter['department_id'])) {
            $oSelect->where("{$this->table}.department_id", $filter['department_id']);
        }

        if (!empty($filter['staff_title_id'])) {
            $oSelect->where("{$this->table}.staff_title_id", $filter['staff_title_id']);
        }

        if (!empty($filter['full_name'])) {
            $oSelect->where("{$this->table}.full_name", 'LIKE', '%' . $filter['full_name'] . '%');
        }

        return $oSelect->paginate(10);
    }

    /**
     * Lấy thông tin nhân viên
     *
     * @param $staffId
     * @return mixed
     */
    public function getInfo($staffId)
    {
        return $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name as staff_name",
                "{$this->table}.staff_avatar",
                "b.branch_name",
                "d.department_name"
            )
            ->join("branches as b", "b.branch_id", "=", "{$this->table}.branch_id")
            ->join("departments as d", "d.department_id", "=", "{$this->table}.department_id")
            ->where("{$this->table}.staff_id", $staffId)
            ->first();
    }
}
