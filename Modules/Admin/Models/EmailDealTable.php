<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/07/2021
 * Time: 14:16
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailDealTable extends Model
{
    protected $table = "email_deal";
    protected $primaryKey = "email_deal_id";
    protected $fillable = [
        "email_deal_id",
        "email_campaign_id",
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
                "{$this->table}.email_deal_id",
                "{$this->table}.email_campaign_id",
                "{$this->table}.closing_date",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "cpo_pipelines.owner_id",
                "{$this->table}.amount"
            )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->where("{$this->table}.email_campaign_id", $campaignId)
            ->first();
    }

    public function add(array $data)
    {
        return $this->create($data)->email_deal_id;
    }

    public function getItem($id)
    {
        return $this->select(
            "{$this->table}.email_deal_id",
            "{$this->table}.amount",
            "{$this->table}.pipeline_code",
            "{$this->table}.journey_code",
            "{$this->table}.closing_date",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            "cpo_pipelines.pipeline_name",
            "cpo_journey.position as journey_position"
        )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", "cpo_journey.journey_code", "=", "{$this->table}.journey_code")
            ->where("{$this->table}.email_campaign_id", $id)->first();
    }

    public function removeItem($id)
    {
        return $this->where("email_campaign_id", $id)->delete();
    }
}