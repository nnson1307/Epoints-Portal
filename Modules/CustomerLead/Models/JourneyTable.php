<?php

namespace Modules\CustomerLead\Models;

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
        'default_system',"is_contract_created"
    ];

    const NOT_DELETE = 0;
    const NEW = 'new';
    const IS_ACTIVE = 1;

    /**
     * Lấy option hành trình KH khi edit
     *
     * @param $pipelineCode
     * @param $position
     * @return mixed
     */
    public function getOptionEdit($pipelineCode, $position)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code",
                "position"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("position", ">=", $position)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy hành trình mới của pipeline
     *
     * @param $pipelineCode
     * @return mixed
     */
    public function getJourneyNew($pipelineCode)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("default_system", self::NEW)
            ->first();
    }

    /**
     * Thêm mới hanh trinh
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->journey_id;
    }


    /**
     * Chỉnh sửa hanh trinh
     *
     * @param array $data
     * @param $journeyId
     * @return mixed
     */
    public function edit(array $data, $journeyId)
    {
        return $this->where("journey_id", $journeyId)->update($data);
    }

    /**
     * Cập nhật hành trình theo journey code
     *
     * @param array $data
     * @param $journeyCode
     * @return mixed
     */
    public function editByCode(array $data, $journeyCode)
    {
//        dd($data, $journeyCode);
        $a = $this->where("journey_code", $journeyCode)->update($data);
        return $a;
    }

    /**
     * Lấy thông tin journey
     *
     * @param $journeyCode
     * @return mixed
     */
    public function getInfo($journeyCode)
    {
        return $this->where("journey_code", $journeyCode)->first();
    }
    public function getInfoJourney($journeyCode, $pipelineCode)
    {
        return $this->where("journey_code", $journeyCode)->where("pipeline_code", $pipelineCode)->first();
    }

    /**
     * Lấy thông tin cập nhật journey
     *
     * @param $pipelineId
     * @param $journeyCode
     * @return mixed
     */
    public function getInfoUpdateJourney($pipelineId, $journeyCode)
    {
        return $this
            ->where("pipeline_id", $pipelineId)
            ->where("journey_code", $journeyCode)
            ->first();
    }

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
                "background_color",
                "default_system"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("is_actived", self::IS_ACTIVE)
            ->orderBy("position", "asc")
            ->get();
    }

    /**
     * Lấy id hành trình thông qua id pipeline và tên hành trình
     *
     * @param $journeyName
     * @param $pipelineId
     * @return mixed
     */
    public function getIdByName($journeyName, $pipelineId)
    {
        $id = $this->select('journey_id')->where('pipeline_id', $pipelineId)
            ->where('journey_name', $journeyName)->first();
        return $id;
    }

    /**
     * Lấy danh sách hành trình theo pipeline id
     *
     * @param $pipelineId
     * @return mixed
     */
    public function getListByPipelineId($pipelineId)
    {
        $list  = $this->select('*')
            ->where('pipeline_id', $pipelineId)
            ->orderBy('position')
            ->get();
        return $list;
    }

    public function deleteListJourney($pipelineId)
    {
        $this->where('pipeline_id', $pipelineId)->delete();
    }

    /**
     * Xoa hanh trinh theo pipeline code
     *
     * @param $journeyCode
     * @return mixed
     */
    public function deleteByCode($journeyCode)
    {
        return $this->where('journey_code', $journeyCode)->delete();
    }

    /**
     * Lấy journey code theo pipeline code và tên của journey
     *
     * @param $pipelineCode
     * @param $name
     * @return mixed
     */
    public function getJourneyCodeByName($pipelineCode)
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
            ->where("is_deleted", 0)
            ->where("pipeline_code", $pipelineCode)
            ->where("default_system", "new")
            ->first();
    }

    /**
     * Lấy option hành trình KH khi edit
     *
     * @param $pipelineCode
     * @param $position
     * @return mixed
     */
    public function getOptionEditNewFix($pipelineCode, $position,$newPosition)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code",
                "position",
                "default_system"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("position", ">", $position)
            ->where("position", "<=", $newPosition)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy option hành trình KH khi edit
     *
     * @param $pipelineCode
     * @param $position
     * @return mixed
     */
    public function getOptionEditNew($pipelineCode, $position)
    {
        return $this
            ->select(
                "journey_id",
                "journey_name",
                "journey_code",
                "position",
                "default_system"
            )
            ->where("pipeline_code", $pipelineCode)
            ->where("position", ">", $position)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * lấy danh sách hành trình được cấu hình các bước
     * @param $arrJourneyId
     * @return mixed
     */
    public function getListJourneyByArrId($arrJourneyId){
        return $this
            ->whereIn('journey_id',$arrJourneyId)
            ->get();
    }

    public function getByCodeJourneyPipeline($pipelineCode,$journeyCode)
    {
        return $this->where('pipeline_code', $pipelineCode)
            ->where('journey_code', $journeyCode)->first();
    }
}
