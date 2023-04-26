<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\ZNS\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLeadTable extends Model
{
    use ListTableTrait;
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
        "convert_object_type",
        "convert_object_code",
        "assign_by",
        "sale_id",
        "date_revoke",
        "province_id",
        "district_id",
        "deal_code",
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

    const NOT_DELETE = 0;
    const BUSINESS = "business";

    /**
     * Danh sách KH tiềm năng
     *
     * @param array $filter
     * @return mixed
     */

    public function getListCustomerLeadCampaign($filter)
    {
        $select = $this->select(
            "{$this->table}.full_name",
            "{$this->table}.customer_lead_id",
            "{$this->table}.email",
            "{$this->table}.phone",
            DB::raw("(CASE WHEN {$this->table}.customer_type = 'business' THEN '".__('Doanh nghiệp')."'
                                WHEN {$this->table}.customer_type = 'personal' THEN '".__('Cá nhân')."'
                            ELSE '' END) as customer_type"),
            "customer_sources.customer_source_name",
            "cpo_pipelines.pipeline_name",
            "cpo_journey.journey_name",
            DB::raw("IFNULL((CASE WHEN {$this->table}.sale_id is null THEN '".__('Chưa phân công')."' 
                            ELSE '".__('Đã phân công')."' END),'1') as sale_status"),
            DB::raw("IFNULL(staffs.full_name, '') as sale_name"),
//            "staffs.full_name as sale_name",
            "{$this->table}.created_at"
        )
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.sale_id")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin("cpo_journey", function ($join) {
                $join->on("cpo_journey.journey_code", "=", "{$this->table}.journey_code")
                    ->on(DB::raw("{$this->table}.pipeline_code"), '=', "cpo_journey.pipeline_code");
            })
            ->leftJoin("customer_sources", "customer_sources.customer_source_id", "{$this->table}.customer_source")
            ->where("{$this->table}.is_deleted", 0)
            ->where("{$this->table}.is_convert", 0);

        if (isset($filter['lead_search']) && $filter['lead_search'] != "") {
            $search = $filter['lead_search'];
            $select->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("staffs.full_name", 'like', '%' . $search . '%');
            });
        }
        if (isset($filter['lead_customer_source_id']) != '') {
            $select->where("{$this->table}.customer_source", "=", $filter['lead_customer_source_id']);
        }
        if (isset($filter['lead_journey_code']) != '') {
            $select->where("{$this->table}.journey_code", "=", $filter['lead_journey_code']);
        }
        if (isset($filter['lead_pipeline_code']) != '') {
            $select->where("{$this->table}.pipeline_code", "=", $filter['lead_pipeline_code']);
        }
        if (isset($filter['lead_type_customer']) != '') {
            $select->where("{$this->table}.customer_type", "=", $filter['lead_type_customer']);
        }
        if (isset($filter['lead_sale_status']) != '') {
            if($filter['lead_sale_status'] == 1){
                $select->whereNotNull("{$this->table}.sale_id"); // đã phân công -> sale id not null
            }
            else{
                $select->whereNull("{$this->table}.sale_id"); // chưa phân công -> sale id  null
            }
        }
        if (isset($filter['listCustomerAdd']) != '') {
            $arrEmail = $filter['listCustomerAdd'];
            if (count($arrEmail) != 0) {
                $select->whereNotIn("{$this->table}.email", $arrEmail);
            }
        }
        if (isset($filter['listCustomerAddPhone']) != '') {
            $arrPhone = $filter['listCustomerAddPhone'];
            if (count($arrPhone) != 0) {
                $select->whereNotIn("{$this->table}.phone", $arrPhone);
            }
        }
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->get();
    }

}