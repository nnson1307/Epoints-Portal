<?php

namespace Modules\ZNS\Models;

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
     * Danh sách pipeline
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $list = $this
            ->select(
                "cpo_pipelines.pipeline_id",
                "cpo_pipelines.pipeline_code",
                "cpo_pipelines.pipeline_name",
                "cpo_pipelines.is_default",
                "cpo_pipelines.created_at",
                "cpo_pipelines.pipeline_category_code",
                "cpo_pipelines.time_revoke_lead",
                "cpo_pipeline_categories.pipeline_category_name"
            )
            ->leftJoin("cpo_pipeline_categories", "cpo_pipeline_categories.pipeline_category_code","=","cpo_pipelines.pipeline_category_code")
            ->where("cpo_pipelines.is_deleted", 0)
            ->orderBy("cpo_pipelines.pipeline_id", "desc");

        // filter name, code
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $list->where(function ($query) use ($search) {
                $query->where('pipeline_name', 'like', '%' . $search . '%')
                    ->orWhere('pipeline_code', 'like', '%' . $search . '%');
            });

        }
        return $list;
    }


    /**
     * Thêm mới pipeline
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->pipeline_id;
    }


    /**
     * Chỉnh sửa pipeline
     *
     * @param array $data
     * @param $pipelineId
     * @return mixed
     */
    public function edit(array $data, $pipelineId)
    {
        return $this->where("pipeline_id", $pipelineId)->update($data);
    }

    /**
     * Chi tiet pipeline
     *
     * @param $pipelineId
     * @return mixed
     */
    public function getDetail($pipelineId)
    {
        return $this->select(
            "cpo_pipelines.pipeline_id",
            "cpo_pipelines.pipeline_code",
            "cpo_pipelines.pipeline_name",
            "cpo_pipelines.is_default",
            "cpo_pipelines.created_at",
            "cpo_pipelines.pipeline_category_code",
            "cpo_pipelines.time_revoke_lead",
            "cpo_pipelines.owner_id",
            "cpo_pipeline_categories.pipeline_category_name"
        )->leftJoin(
            'cpo_pipeline_categories',
            'cpo_pipeline_categories.pipeline_category_code',
            '=',
            'cpo_pipelines.pipeline_category_code'
        )->leftJoin(
            'cpo_journey',
            'cpo_journey.pipeline_code',
            '=',
            'cpo_pipelines.pipeline_code'
        )
            ->where('cpo_pipelines.pipeline_id', $pipelineId)->first();
    }

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

    /**
     * Lấy thông tin pipeline mặc định
     *
     * @return mixed
     */
    public function getPipelineDefault()
    {
        return $this
            ->select(
                "{$this->table}.pipeline_id",
                "{$this->table}.pipeline_name",
                "{$this->table}.pipeline_code",
                "{$this->table}.pipeline_category_code"
            )
            ->join("cpo_pipeline_categories", "cpo_pipeline_categories.pipeline_category_code", "=", "{$this->table}.pipeline_category_code")
            ->where("{$this->table}.is_default", self::DEFAULT)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("cpo_pipeline_categories.pipeline_category_code", self::CUSTOMER)
            ->first();
    }

    /**
     * Lấy thông tin pipeline mặc định
     *
     * @return mixed
     */
    public function getPipelineDealDefault()
    {
        return $this
            ->select(
                "{$this->table}.pipeline_id",
                "{$this->table}.pipeline_name",
                "{$this->table}.pipeline_code",
                "{$this->table}.pipeline_category_code",
                "{$this->table}.time_revoke_lead"
            )
            ->join("cpo_pipeline_categories", "cpo_pipeline_categories.pipeline_category_code", "=", "{$this->table}.pipeline_category_code")
            ->where("{$this->table}.is_default", self::DEFAULT)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("cpo_pipeline_categories.pipeline_category_code", self::DEAL)
            ->first();
    }

    public function setDefaultZero()
    {
        return $this->where('is_default', 1)->update(['is_default' => 0]);
    }
    public function setDefaultZeroCategory($pipelineCate)
    {
        return $this->where('is_default', 1)
            ->where('pipeline_category_code', $pipelineCate)
            ->update(['is_default' => 0]);
    }

    /**
     * Lấy pipeline code theo tên pipeline
     *
     * @param $name
     * @return mixed
     */
    public function getCodePipelineByName($name)
    {
        return $this
            ->select(
                "{$this->table}..pipeline_id",
                "{$this->table}..pipeline_code",
                "{$this->table}..pipeline_name",
                "{$this->table}..is_default",
                "{$this->table}..created_at",
                "{$this->table}..pipeline_category_code",
                "{$this->table}.owner_id"
            )
            ->where("is_deleted", 0)
            ->where("pipeline_name", $name)
            ->first();
    }

    /**
     * Lấy thông tin pipeline theo pipeline code
     *
     * @param $pipelineCode
     * @return mixed
     */
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
    public function getDetailPipelineByCode($pipelineCode)
    {
        return $this->select(
            "{$this->table}.pipeline_id",
            "{$this->table}.pipeline_code",
            "{$this->table}.pipeline_name",
            "{$this->table}.is_default",
            "{$this->table}.created_at",
            "{$this->table}.pipeline_category_code",
            "{$this->table}.time_revoke_lead",
            "{$this->table}.owner_id"
        )
            ->where('cpo_pipelines.pipeline_code', $pipelineCode)->first();
    }
}