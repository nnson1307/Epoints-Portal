<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/07/2021
 * Time: 15:32
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerLeadTable extends Model
{
    protected $table = "cpo_customer_lead";
    protected $primaryKey = "customer_lead_id";
    protected $fillable = [
        "customer_lead_id",
        "customer_lead_code",
        "full_name",
        "email",
        "phone",
        "gender",
        "birthday",
        "address",
        "avatar",
        "tag_id",
        "pipeline_code",
        "journey_code",
        "customer_type",
        "hotline",
        "fanpage",
        "zalo",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "tax_code",
        "representative",
        "customer_source",
        "business_clue",
        "is_convert",
        "assign_by",
        "sale_id",
        "date_revoke",
        "province_id",
        "district_id",
        "custom_1",
        "custom_2",
        "custom_3",
        "custom_4",
        "custom_5",
        "custom_6",
        "custom_7",
        "custom_8",
        "custom_9",
        "custom_10"
    ];

    /**
     * Lấy thông tin KH tiềm năng
     *
     * @param $customerLeadId
     * @return mixed
     */
    public function getInfo($customerLeadId)
    {
        return $this
            ->select(
                "{$this->table}.customer_lead_id",
                "{$this->table}.customer_lead_code",
                "{$this->table}.full_name",
                "{$this->table}.email",
                "{$this->table}.phone",
                "{$this->table}.gender",
                "{$this->table}.birthday",
                "{$this->table}.address",
                "{$this->table}.avatar",
                "{$this->table}.pipeline_code",
                "{$this->table}.journey_code",
                "{$this->table}.customer_type",
                "{$this->table}.hotline",
                "{$this->table}.fanpage",
                "{$this->table}.zalo",
                "cpo_journey.position as journey_position",
                "{$this->table}.tax_code",
                "{$this->table}.representative",
                "{$this->table}.customer_source",
                "{$this->table}.business_clue",
                "{$this->table}.is_convert",
                "{$this->table}.province_id",
                "{$this->table}.district_id",
                "{$this->table}.deal_code",
                "cpo_pipelines.owner_id",
                "{$this->table}.custom_1",
                "{$this->table}.custom_2",
                "{$this->table}.custom_3",
                "{$this->table}.custom_4",
                "{$this->table}.custom_5",
                "{$this->table}.custom_6",
                "{$this->table}.custom_7",
                "{$this->table}.custom_8",
                "{$this->table}.custom_9",
                "{$this->table}.custom_10"
            )
            ->join("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->join("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->where("customer_lead_id", $customerLeadId)
            ->first();
    }
}