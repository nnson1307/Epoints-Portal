<?php


namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;


class PipelineJourneyDefaultTable extends Model
{
    protected $table = "cpo_pipeline_journey_default";
    protected $primaryKey = "pipeline_journey_default_id";
    protected $fillable = [
        'pipeline_journey_default_id', 'pipeline_journey_default_code', 'pipeline_journey_default_name',
        'pipeline_category_code', 'position', 'created_by', 'updated_by', 'created_at', 'updated_at'
    ];


    /**
     * lấy danh sách hành trình mặc định theo pipeline category code
     *
     * @param $code
     * @return mixed
     */
    public function getListByPipelineCategoryCode($code)
    {
        return $this
            ->select(
                "{$this->table}.pipeline_journey_default_id",
                "{$this->table}.pipeline_journey_default_code",
                "{$this->table}.pipeline_journey_default_name",
                "{$this->table}.pipeline_category_code",
                "{$this->table}.position"
            )
            ->where("{$this->table}.pipeline_category_code", $code)
            ->orderBy("{$this->table}.position", "asc")
            ->get();
    }
}