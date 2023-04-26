<?php

namespace Modules\TimeOffDays\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TimeOffDaysTimeTable extends Model
{
    use ListTableTrait;
    protected $table = 'time_off_days_time';
    protected $primaryKey = 'time_off_days_time_id';
    protected $fillable = [
        'time_off_days_time_id',
        'time_off_days_time_value',
        'time_off_days_time_unit',
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách hoa hồng nhân viên
     *
     * @param array $filter
     * @return mixed
     */
    public function getOptionList()
    {
        $select = $this
            ->select(
                "{$this->table}.time_off_days_time_id",
                "{$this->table}.time_off_days_time_value",
                "{$this->table}.time_off_days_time_unit"
            )->get();
            
        return $select;
    }

}