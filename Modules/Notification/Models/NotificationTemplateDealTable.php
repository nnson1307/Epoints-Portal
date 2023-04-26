<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/07/2021
 * Time: 14:16
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NotificationTemplateDealTable extends Model
{
    protected $table = "notification_template_deal";
    protected $primaryKey = "notification_template_deal_id";
    protected $fillable = [
        "notification_template_deal_id",
        "notification_template_id",
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
                "{$this->table}.notification_template_deal_id",
                "{$this->table}.notification_template_id",
                "{$this->table}.closing_date",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "cpo_pipelines.owner_id"
            )
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->where("{$this->table}.notification_template_id", $campaignId)
            ->first();
    }

    public function add(array $data)
    {
        return $this->create($data)->notification_template_deal_id;
    }

    public function getItem($id)
    {
        return $this->select(
            "{$this->table}.notification_template_deal_id",
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
            ->where("{$this->table}.notification_template_id", $id)->first();
    }

    public function removeItem($id)
    {
        return $this->where("notification_template_id", $id)->delete();
    }
}