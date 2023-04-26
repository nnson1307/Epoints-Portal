<?php

namespace Modules\ConfigDisplay\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyTable extends Model
{
    protected $table = 'survey';
    protected $primaryKey = 'survey_id';
    protected $fillable = [
        'survey_id', 'survey_name', 'survey_code',
        'survey_description', 'survey_banner',
        'is_exec_time', 'start_date', 'end_date', 'close_date',
        'frequency', 'frequency_value', 'is_limit_exec_time',
        'exec_time_from', 'exec_time_to',
        'frequency_monthly_type', 'day_in_monthly',
        'day_in_week', 'day_in_week_repeat',
        'period_in_date_type', 'period_in_date_start',
        'period_in_date_end', 'max_times', 'branch_max_times_per_day',
        'branch_max_times', 'allow_all_branch',
        'is_active', 'is_delete', 'created_at',
        'created_by', 'updated_at', 'updated_by', 'status', 'type_user', 'type_apply',
        'status_notifi', 'job_notifi'
    ];

    const IS_ACTIVE = 1;
    const IS_DELETED = 0;

    /**
     * lất tất cả khảo sát đã duyệt 
     * @return mixed
     */

    public function getAll()
    {
        return $this->where("status", 'R')
            ->where("is_delete", self::IS_DELETED)
            ->where("is_active", self::IS_ACTIVE)
            ->select("survey_id", "survey_name")
            ->get();
    }
}
