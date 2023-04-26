<?php

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TimeOffTypeTable extends Model
{
    use ListTableTrait;

    protected $table = 'time_off_type';
    protected $primaryKey = 'time_off_type_id';
    protected $fillable = [
        'time_off_type_id',
        'time_off_type_name',
        'total_number',
        'month_reset',
        'month_start_reset',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'direct_management_approve',
        'staff_id_approve_level2',
        'staff_id_approve_level3'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách hoa hồng nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_type_name",
                "{$this->table}.time_off_type_code",
                "{$this->table}.direct_management_approve",
                "{$this->table}.staff_id_approve_level2",
                "{$this->table}.staff_id_approve_level3",
                "{$this->table}.is_status",
                "{$this->table}.created_at",
            )
            ->where("{$this->table}.time_off_type_parent_id", '!=', 0)
            ->orderBy("{$this->table}.time_off_type_id", "desc");
        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    /**
     * Thêm loại thông tin kèm theo
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật loại thông tin kèm theo
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function edit($data, $id)
    {
        return $this->where("{$this->primaryKey}", $id)->update($data);
    }

    /**
     * Chi tiết loại
     *
     * @param $id
     * @return mixed
     */
    public function getDetail($id)
    {
        return $this
            ->select(
                "{$this->table}.time_off_type_id",
                "{$this->table}.time_off_type_name",
                "{$this->table}.time_off_type_parent_id",
                "{$this->table}.time_off_type_description",
                "{$this->table}.time_off_type_code",
                "{$this->table}.is_status",
                "{$this->table}.total_number",
                "{$this->table}.month_reset",
                "{$this->table}.direct_management_approve",
                "{$this->table}.staff_id_approve_level2",
                "{$this->table}.staff_id_approve_level3"
            )
            ->where("{$this->primaryKey}", $id)
            ->first();
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }
}
