<?php

namespace Modules\CallCenter\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class PipelineTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_pipelines";
    protected $primaryKey = "pipeline_id";
    protected $fillable = [
        'pipeline_id', 'pipeline_name', 'pipeline_code', 'is_default', 'is_deleted',
        'created_by', 'updated_by', 'created_at', 'updated_at', 'pipeline_category_code',
        'time_revoke_lead', 'owner_id'
    ];

    const NOT_DELETE = 0;
    const CUSTOMER = 'CUSTOMER';
    const DEAL = 'DEAL';
    const DEFAULT = 1;


    /**
     * Lấy option pipeline
     *
     * @param $pipelineCatCode
     * @param null $ownerId
     * @return mixed
     */
    public function getOption($pipelineCatCode, $ownerId = null)
    {
        $res = $this
            ->select(
                "{$this->table}.pipeline_id",
                "{$this->table}.pipeline_name",
                "{$this->table}.pipeline_code",
                "{$this->table}.is_default"
            )
            ->join("cpo_pipeline_categories", "cpo_pipeline_categories.pipeline_category_code", "=", "{$this->table}.pipeline_category_code")
            ->where("cpo_pipeline_categories.pipeline_category_code", $pipelineCatCode)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        // filter theo chủ sở hữu
        if ($ownerId != null) {
            $res->where("{$this->table}.owner_id", $ownerId);
        }
        return $res->get();
    }

    public function getDetailByCode($pipelineCode)
    {
        return $this->select(
            "{$this->table}..pipeline_id",
            "{$this->table}..pipeline_code",
            "{$this->table}..pipeline_name",
            "{$this->table}..is_default",
            "{$this->table}..created_at",
            "{$this->table}..pipeline_category_code",
            "{$this->table}.owner_id"
        )
            ->where('cpo_pipelines.pipeline_code', $pipelineCode)->first();
    }
}