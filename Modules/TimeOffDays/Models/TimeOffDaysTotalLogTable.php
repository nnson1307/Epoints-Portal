<?php
/**
 * Created by PhpStorm
 * User: PhongDT
 */

namespace Modules\TimeOffDays\Models;


use Illuminate\Database\Eloquent\Model;

class TimeOffDaysTotalLogTable extends Model
{
    protected $table = "time_off_days_total_log";
    protected $primaryKey = "time_off_days_total_log_id";
    protected $fillable = [
        "time_working_staff_id",
        "time_off_days_id",
        "time_off_days_type_id",
        "staff_id",
        "total",
        "year",
        "total_used",
        "updated_at",
        "created_at",
        "created_by",
        "updated_by",
    ];

    /**
     * Thêm
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Cập nhật
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
     * Cập nhật or tạo mới
     *
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateOrNew($data, $params)
    {
        return $this->updateOrCreate($data, $params);
    }
    

}