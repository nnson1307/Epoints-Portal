<?php


namespace Modules\CallCenter\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerDealTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";
    protected $fillable = [
        "deal_id",
        "deal_code",
        "deal_name",
        "type_customer",
        "customer_code",
        "contract_code",
        "branch_code",
        "total",
        "discount",
        "amount",
        "probability",
        "owner",
        "sale_id",
        "date_revoke",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "pipeline_code",
        "journey_code",
        "deal_description",
        "order_source_id",
        "voucher_code",
        "discount_member",
        "customer_contact_code",
        "is_deleted",
        "closing_date",
        "closing_due_date",
        "reason_lose_code",
        "tag",
        "deal_type_code",
        "deal_type_object_id",
        "phone",
    ];

    const NOT_DELETE = 0;
    const RECEIPT_STATUS = ['paid', 'part-paid'];
    const ORDER_STATUS = ['paysuccess', 'pay-half'];

     /**
     * Tab ds deal phân trangg (KHTN detail)
     *
     * @param $filter
     * @return mixed
     */
    public function getListDealLeadDetail($filter)
    {
       
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? 6);
        $select = $this->select(
            "{$this->table}.deal_id",
            "{$this->table}.deal_code",
            "{$this->table}.amount",
            "{$this->table}.closing_date",
            "{$this->table}.created_at",
            "{$this->table}.updated_at",
            DB::raw("IFNULL((GROUP_CONCAT(cpo_deal_details.object_name)),".__("'Không có sản phẩm'").") as product"),
            "cpo_pipelines.pipeline_name",
            "cpo_journey.journey_name",
            "staffs.full_name as full_name"
        )
            ->leftJoin("cpo_deal_details", "cpo_deal_details.deal_code", "=", "{$this->table}.deal_code")
            // ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.created_by")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->where("{$this->table}.customer_code", $filter['customer_code']);

        $select->groupBy("{$this->table}.deal_code");
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

}