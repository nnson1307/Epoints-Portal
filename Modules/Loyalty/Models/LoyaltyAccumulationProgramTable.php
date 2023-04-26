<?php

namespace Modules\Loyalty\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Survey\Models\SurveyTable;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;
use Modules\Admin\Models\MemberLevelTable;
use phpDocumentor\Reflection\DocBlock\Description;

class LoyaltyAccumulationProgramTable extends Model
{
    use ListTableTrait;
    public $timestamps = false;
    protected $table = 'loy_accumulation_program';
    protected $primaryKey = 'accumulation_program_id';
    protected $fillable
    = [
        'accumulation_program_id',
        'survey_id',
        'accumulation_program_name',
        'source_point_key',
        'obj_id',
        'validity_period_type',
        'date_start',
        'date_end',
        'apply_type',
        'accumulation_point',
        'available_point',
        'is_active',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'allow_all_outlet',
        'description'
    ];

    const IS_ACTIVE = 1;
    const IS_DELETE = 0;

    /**
     * function create new accumulate point
     * @param array $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->accumulation_program_id;
    }



    /**
     * function get detail
     * @param array $filter
     * @return mixed
     */
    public function getListCore(&$filter)
    {
        $select = $this->select(
            'loy_accumulation_program.accumulation_program_id',
            'loy_accumulation_program.accumulation_program_name', // tên chương trình acumulate
            'loy_accumulation_program.source_point_key',
            'loy_accumulation_program.is_active', //trạng thái
            'date_start', // thời gian
            'date_end', // thời gian
            'survey.survey_name'
        )
            ->join("survey", "survey.survey_id", "{$this->table}.survey_id")
            ->where($this->table . '.is_deleted', 0)
            ->where($this->table . '.is_active', 1)
            ->groupBy($this->table . '.accumulation_program_id')
            ->orderBy($this->table . '.accumulation_program_id', 'desc');


        if (isset($filter['nameProgram']) && $filter['nameProgram'] != null) {
            $select->where($this->table . '.accumulation_program_name', 'like', '%' . $filter['nameProgram'] . '%');
            unset($filter['nameProgram']);
        }

        if (isset($filter['time_start']) && !isset($filter['time_end'])) {
            $filter['time_start'] = Carbon::createFromFormat('d/m/Y H:i', $filter['time_start'])->format('Y-m-d H:i:00');
            $select->where($this->table . '.date_start',  '>=', $filter['time_start']);
            unset($filter['time_start']);
        }
        if (isset($filter['time_end']) && !isset($filter['time_start'])) {
            $filter['time_end'] = Carbon::createFromFormat('d/m/Y H:i', $filter['time_end'])->format('Y-m-d H:i:00');
            $select->where($this->table . '.date_end',  '<=', $filter['time_end']);
            unset($filter['time_end']);
        }
        if (isset($filter['time_start']) && isset($filter['time_end'])) {
            $startTime = Carbon::createFromFormat('d/m/Y H:i', $filter['time_start'])->format('Y-m-d H:i:00');
            $endTime = Carbon::createFromFormat('d/m/Y H:i', $filter['time_end'])->format('Y-m-d H:i:00');
            $select->where(function ($query) use ($startTime) {
                $query->where($this->table . '.date_start', '>=', $startTime)
                    ->orWhere($this->table . '.date_end', '>=', $startTime);
            })->where(function ($query) use ($endTime) {
                $query->where($this->table . '.date_start', '<=', $endTime)
                    ->orWhere($this->table . '.date_end', '<=', $endTime);
            });
            unset($filter['time_start']);
            unset($filter['time_end']);
        }

        if (isset($filter['program_type'])) {
            $select->where($this->table . '.source_point_key', $filter['program_type']);
            unset($filter['program_type']);
        }

        if (isset($filter['status'])) {
            $select->where($this->table . '.is_active', $filter['status']);
            unset($filter['status']);
        }


        return $select;
    }

    // ORM relatishionShip //

    public function survey()
    {
        return $this->belongsTo(SurveyTable::class, 'survey_id');
    }

    public function ranks()
    {
        return $this->belongsToMany(
            MemberLevelTable::class,
            'loy_accumulation_program_rank',
            'accumulation_program_id',
            'rank_id'
        )->withPivot('accumulation_point');
    }
}
