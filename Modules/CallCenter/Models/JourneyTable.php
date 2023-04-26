<?php

namespace Modules\CallCenter\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class JourneyTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_journey";
    protected $primaryKey = "journey_id";
    protected $fillable = [
        'journey', 'pipeline_id', 'pipeline_code', 'journey_name', 'journey_code', 'journey_updated',
        'position', 'is_actived', 'is_deleted','is_deal_created', 'created_by', 'updated_by', 'created_at', 'updated_at',
        'default_system',"is_contract_created",'pipeline_color'
    ];

    const NOT_DELETE = 0;
    const NEW = 'new';
    const IS_ACTIVE = 1;

    /**
     * Lấy hành trình KH theo pipeline
     *
     * @param $pipelineCode
     * @return mixed
     */
    public function getJourneyByPipeline($pipelineCode)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code",
                "journey_updated",
                "position",
                "default_system"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("is_actived", self::IS_ACTIVE)
            ->orderBy("position", "asc")
            ->get();
    }
}
