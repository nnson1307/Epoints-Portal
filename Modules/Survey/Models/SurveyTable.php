<?php

namespace Modules\Survey\Models;

use Carbon\Carbon;
use Modules\Admin\Models\StaffsTable;
use Illuminate\Database\Eloquent\Model;
use Modules\Admin\Models\CustomerTable;
use MyCore\Models\Traits\ListTableTrait;
use Modules\Survey\Models\StaffGroupTable;
use Modules\Survey\Models\SurveyBlockTable;
use Modules\Survey\Models\SurveyQuestionTable;
use Modules\Survey\Models\SurveyConfigPointTable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Survey\Models\SurveyAnswerQuestionTable;
use Modules\Survey\Models\SurveyConditionApplyTable;

class SurveyTable extends Model
{
    use ListTableTrait;

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
        'status_notifi', 'job_notifi',
        'count_point',
        'is_short_link',
        'short_link'
    ];

    /**
     * Danh sách khảo sát.
     * @param array $filters
     * @return mixed
     */
    public function getListCore(&$filters = [])
    {
        $select = $this->select("{$this->table}.*");
        if (isset($filters['nameOrCodeSurvey'])) {
            $select->where(function ($query) use ($filters) {
                $query->where("survey_name", 'like', '%' . $filters['nameOrCodeSurvey'] . '%')
                    ->orWhere("survey_code", 'like', '%' . $filters['nameOrCodeSurvey'] . '%');
            });
            unset($filters['nameOrCodeSurvey']);
        }
        if (isset($filters['dateCreated'])) {
            $arrFilter = explode(" - ", $filters["dateCreated"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arrFilter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arrFilter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filters['dateCreated']);
        }
        if (isset($filters['status'])) {
            $select->where("{$this->table}.status", $filters['status']);
            unset($filters['status']);
        }
        $select
            ->where("{$this->table}.is_delete", 0)
            ->orderBy("{$this->table}.{$this->primaryKey}", "DESC");
        return $select;
    }

    /**
     * Add one record
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        $select = $this->create($data);
        return $select->{$this->primaryKey};
    }

    /**
     * Edit by survey_id
     * @param $id
     * @param $data
     * @return mixed
     */
    public function edit($id, $data)
    {
        $select = $this->where("{$this->table}.survey_id", $id)->update($data);
        return $select;
    }

    /**
     * Get by survey_id
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $select = $this->select("{$this->table}.*")
            ->where("{$this->table}.survey_id", $id)
            ->where("{$this->table}.is_delete", 0)
            ->first();
        return $select;
    }

    /**
     * get list survey theo điều kiện 
     * @return mixed
     */

    public function getListSurveyCondition()
    {
        $select = $this->select("{$this->table}.*")
            ->where(function ($query) {
                $query->where("status", 'R');
                $query->orWhere("status", 'N');
            })
            ->get();
        return $select;
    }


    /**
     * Khi ngày hiện tại > ngày kết thúc thì đóng khảo sát lại
     */
    public function closeSurvey()
    {
        $this->where('is_exec_time', 1)
            ->where('end_date', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->update([
                'status' => 'C',
                'updated_at' => Carbon::now()
            ]);
    }

    /**
     * lấy các khối block của survey
     * @param $idSurrvey
     * @return mixed
     */

    public function getBlockSurvey($idSurrvey)
    {
        $select = $this->select(
            "{$this->table}.*",
            "survey_block.survey_block_id as block_id",
            "survey_block.survey_block_position"
        )
            ->where("{$this->table}.survey_id", $idSurrvey)
            ->where("{$this->table}.is_delete", 0)
            ->join('survey_block', "survey_block.survey_id", "{$this->table}.survey_id")
            ->orderBy('survey_block.survey_block_position')
            ->get();
        return $select;
    }
    /**
     * Lấy cấu hình tính điểm của khảo sát
     * @param $idSurvey
     * @return void
     */
    public function getConfigPoint($idSurvey)
    {
        return $this->find($idSurvey)
            ->configPoint;
    }
    /**
     * quan hệ một nhiều với bảng câu hỏi
     */
    public function questions()
    {
        return $this->hasMany(SurveyQuestionTable::class, 'survey_id');
    }

    /**
     * Quan hệ một nhiều với block
     */
    public function blocks()
    {
        return $this->hasMany(SurveyBlockTable::class, 'survey_id');
    }
    /**
     * quan hệ một nhiều với bảng câu hỏi và cau trả lời 
     */
    public function answerQuestion()
    {
        return $this->hasManyThrough(
            SurveyAnswerQuestionTable::class,
            SurveyQuestionTable::class,
            'survey_id',
            'survey_question_id'
        );
    }
    /**
     * quan hệ một nhiều với bảng staffs
     */
    public function staffs()
    {
        return $this->belongsToMany(
            StaffsTable::class,
            'survey_apply_user',
            'survey_id',
            'user_id'
        );
    }

    /**
     * quan hệ một nhiều với bảng staffs
     */
    public function customers()
    {
        return $this->belongsToMany(
            CustomerTable::class,
            'survey_apply_user',
            'survey_id',
            'user_id'
        );
    }

    /**
     * quan hệ một một với bảng survey_condition_apply
     */
    public function conditionApply()
    {
        return $this->hasOne(
            SurveyConditionApplyTable::class,
            'survey_id'
        );
    }

    /**
     * quan hê một một với bảng survey_group_staffs
     */

    public function staffConditionApply()
    {
        return $this->hasOne(
            StaffGroupTable::class,
            'survey_id'
        );
    }

    /**
     * quan hệ một một với bảng survey_config_point
     */

    public function configPoint(): HasOne
    {
        return $this->HasOne(SurveyConfigPointTable::class, 'survey_id');
    }
}
