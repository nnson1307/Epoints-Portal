<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 4:26 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerCareTable extends Model
{
    protected $table = "cpo_customer_care";
    protected $primaryKey = "customer_care_id";
    protected $fillable = [
        "customer_care_id",
        "customer_lead_code",
        "care_type",
        "content",
        "created_by",
        "created_at",
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
     * @param $leadCode
     * @return mixed
     */
    public function getCustomerCare($leadCode)
    {
        return $this
            ->select(
                "{$this->table}.care_type",
                "{$this->table}.content",
                "{$this->table}.created_at",
                DB::raw('DATE_FORMAT(cpo_customer_care.created_at, "%d/%m/%Y") as created_group'),
                "staffs.full_name",
                "manage_type_work.manage_type_work_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("manage_type_work", "manage_type_work.manage_type_work_key", "=", "{$this->table}.care_type")
            ->where("customer_lead_code", $leadCode)
            ->orderBy("created_at", "desc")
            ->get();
    }

    /**
     * ds CSKH phân trang table
     *
     * @param $filter
     * @return mixed
     */
    public function getListCustomerCare($filter)
    {
        $data = $this
            ->select(
                "{$this->table}.care_type",
                "{$this->table}.content",
                "{$this->table}.created_at",
                DB::raw('DATE_FORMAT(cpo_customer_care.created_at, "%d/%m/%Y") as created_group'),
                "staffs.full_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->where("customer_lead_code", $filter['customer_lead_code'])
            ->orderBy("created_at", "desc");
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? 6);
        return $data->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}