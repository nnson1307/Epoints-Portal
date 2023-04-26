<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 4:26 PM
 */

namespace Modules\CustomerLead\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DealCareTable extends Model
{
    protected $table = "cpo_deal_care";
    protected $primaryKey = "deal_care_id";
    protected $fillable = [
        "deal_care_id",
        "deal_id",
        "care_type",
        "content",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at",
        "object_id"
    ];

    /**
     * Thêm chăm sóc khách hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy thông tin chăm sóc KH
     *
     * @param $dealId
     * @return mixed
     */
    public function getDealCare($dealId)
    {
        return $this
            ->select(
                "{$this->table}.care_type",
                "{$this->table}.content",
                "{$this->table}.created_at",
                DB::raw('DATE_FORMAT(cpo_deal_care.created_at, "%d/%m/%Y") as created_group'),
                "staffs.full_name",
                "manage_type_work.manage_type_work_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("manage_type_work", "manage_type_work.manage_type_work_key", "=", "{$this->table}.care_type")
            ->where("deal_id", $dealId)
            ->orderBy("created_at", "desc")
            ->get();
    }

    /**
     * Ds khách hàng tiếp cận, chuyển đổi theo filter
     *
     * @param $filter
     * @return mixed
     */
    public function getCustomerApproachPerformance($filter)
    {
        $data = $this->select(
            DB::raw("SUM(IF(cpo_deals.type_customer = 'lead', 1, 0)) as sum_lead"),
            DB::raw("SUM(IF(cpo_deals.type_customer = 'customer', 1, 0)) as sum_customer"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_code) as sum_lead_convert")
        )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "{$this->table}.deal_id")
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_code", "cpo_deals.customer_code")
                    ->where("cpo_deals.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.convert_object_type", '=', 'customer')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereNotNull("cpo_deals.type_customer")
            ->groupBy("{$this->table}.deal_id");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }
    public function getListLeadCodeDealCare($filter)
    {
        $data = $this->select(
            "cpo_customer_lead.customer_lead_code"
        )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "{$this->table}.deal_id")
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_code", "cpo_deals.customer_code")
                    ->where("cpo_deals.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->whereNotNull("cpo_deals.type_customer")
            ->whereNotNull("cpo_customer_lead.customer_lead_code");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $data->get()->toArray();
    }
    public function getCustomerApproachByStaff($filter, $staffId)
    {
        $data = $this->select(
            DB::raw("SUM(IF(cpo_deals.type_customer = 'lead', 1, 0)) as sum_lead"),
            DB::raw("SUM(IF(cpo_deals.type_customer = 'customer', 1, 0)) as sum_customer"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_code) as sum_lead_convert")
        )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "{$this->table}.deal_id")
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_code", "cpo_deals.customer_code")
                    ->where("cpo_deals.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereNotNull("cpo_deals.type_customer")
            ->groupBy("{$this->table}.created_by");
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if ($staffId != ""){
            $data->where("{$this->table}.created_by", $staffId);
        }
        return $data->first();
    }

    public function getDataChartRateConvert($filter)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT({$this->table}.created_at,'%d/%m/%Y') as created_group"),
            DB::raw("COUNT(cpo_customer_lead.customer_lead_code) as total")
        )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "{$this->table}.deal_id")
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_code", "cpo_deals.customer_code")
                    ->where("cpo_deals.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1')
                    ->where("cpo_customer_lead.convert_object_type", "=", "customer");
            })
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereNotNull("cpo_deals.type_customer")
            ->groupBy(DB::raw("DATE_FORMAT({$this->table}.created_at,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }
    public function getDataChartRateLead($filter)
    {
        $data = $this->select(
            DB::raw("DATE_FORMAT({$this->table}.created_at,'%d/%m/%Y') as created_group"),
            DB::raw("SUM(IF(cpo_deals.type_customer = 'lead', 1, 0)) as total")
        )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "{$this->table}.deal_id")
            ->leftJoin("cpo_customer_lead", function ($join) {
                $join->on("cpo_customer_lead.customer_lead_code", "cpo_deals.customer_code")
                    ->where("cpo_deals.type_customer", '=', 'lead')
                    ->where("cpo_customer_lead.is_convert", '=', '1');
            })
            ->leftJoin("staffs", "staffs.staff_id", "{$this->table}.created_by")
            ->leftJoin("departments", "departments.department_id", "staffs.department_id")
            ->leftJoin("branches", "branches.branch_id", "staffs.branch_id")
            ->whereNotNull("cpo_deals.type_customer")
            ->groupBy(DB::raw("DATE_FORMAT({$this->table}.created_at,'%d/%m/%Y')"));
        if (isset($filter["time"]) != "") {
            $arr_filter = explode(" - ", $filter["time"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (isset($filter['department_id']) != ""){
            $data->where("departments.department_id", $filter['department_id']);
        }
        if (isset($filter['branch_code']) != ""){
            $data->where("branches.branch_code", $filter['branch_code']);
        }
        if (isset($filter['staff_id']) != ""){
            $data->where("staffs.staff_id", $filter['staff_id']);
        }
        return $data->get()->toArray();
    }
}