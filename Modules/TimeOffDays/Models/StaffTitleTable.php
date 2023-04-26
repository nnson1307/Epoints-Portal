<?php

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffTitleTable extends Model
{
    use ListTableTrait;
    protected $table = 'staff_title';
    protected $primaryKey = 'staff_title_id';
    protected $fillable = [
        'staff_title_id',
        'staff_title_name',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.staff_title_id",
                "{$this->table}.staff_title_name",
            )
            ->where("{$this->table}.is_delete", self::NOT_DELETE)
            ->orderBy("{$this->table}.staff_title_id", "desc");

        

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    public function getInfo($id)
    {
        $select = $this
            ->select(
                "{$this->table}.staff_title_id",
                "{$this->table}.staff_title_name",
            )
            ->where("{$this->table}.is_delete", self::NOT_DELETE)
            ->where("{$this->table}.staff_title_id", $id);

        return $select->first();
    }

}