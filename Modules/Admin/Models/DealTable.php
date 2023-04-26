<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/07/2021
 * Time: 15:14
 */

namespace Modules\Admin\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DealTable extends Model
{
    protected $table = "cpo_deals";
    protected $primaryKey = "deal_id";
    protected $fillable = [
        "deal_id",
        "deal_code",
        "deal_name",
        "type_customer",
        "customer_code",
        "branch_code",
        "total",
        "discount",
        "amount",
        "probability",
        "owner",
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
        "type_customer",
        "branch_code",
        "deal_type_code",
        "deal_type_object_id",
        "phone"
    ];

    const NOT_DELETE = 0;

    /**
     * Thêm deal
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->deal_id;
    }

    /**
     * Cap nhat deal
     *
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function edit($id, array $data)
    {
        return $this->where("deal_id", $id)->update($data);
    }

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
            ->leftJoin("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "cpo_deals.created_by")
            ->leftJoin("cpo_pipelines", "cpo_pipelines.pipeline_code", "=", "{$this->table}.pipeline_code")
            ->leftJoin('cpo_journey', function ($join) {
                $join->on("cpo_journey.journey_code", '=', "{$this->table}.journey_code")
                    ->on("cpo_pipelines.pipeline_code", '=', "cpo_journey.pipeline_code");
            })
            ->where("customers.customer_id", $filter['customer_id']);

        $select->groupBy("{$this->table}.deal_code");
        $select->orderBy("{$this->table}.created_at", "DESC");
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}