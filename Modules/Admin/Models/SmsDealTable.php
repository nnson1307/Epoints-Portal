<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/07/2021
 * Time: 11:39
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SmsDealTable extends Model
{
    protected $table = "sms_deal";
    protected $primaryKey = "sms_deal_id";
    protected $fillable = [
        "sms_deal_id",
        "sms_campaign_id",
        "closing_date",
        "pipeline_code",
        "journey_code",
        "amount",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin deal được tạo khi chạy campaign
     *
     * @param $campaignId
     * @return mixed
     */
    public function getDealCampaign($campaignId)
    {
        return $this
            ->select(
                "{$this->table}.sms_deal_id",
                "{$this->table}.sms_campaign_id",
                "{$this->table}.closing_date",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "cpo_pipelines.owner_id",
                "{$this->table}.amount"
            )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->where("{$this->table}.sms_campaign_id", $campaignId)
            ->first();
    }

    public function add(array $data)
    {
        return $this->create($data)->sms_deal_id;
    }

    public function getItem($id)
    {
        return $this->select(
            "{$this->table}.sms_deal_id",
            "{$this->table}.amount",
            "{$this->table}.pipeline_code",
            "{$this->table}.journey_code",
            "{$this->table}.closing_date",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "cpo_pipelines.pipeline_name",
            "cpo_journey.position as journey_position"
        )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->join("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->where("{$this->table}.sms_campaign_id", $id)->first();
    }

    public function removeItem($id)
    {
        return $this->where("sms_campaign_id", $id)->delete();
    }
}