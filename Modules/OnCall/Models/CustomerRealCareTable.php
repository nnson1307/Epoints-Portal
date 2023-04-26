<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 4:26 PM
 */

namespace Modules\OnCall\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerRealCareTable extends Model
{
    protected $table = "customer_care";
    protected $primaryKey = "customer_care_id";
    protected $fillable = [
        "customer_care_id",
        "customer_code",
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
     * @param $customerCode
     * @return mixed
     */
    public function getCustomerCare($customerCode)
    {
        return $this
            ->select(
                "{$this->table}.care_type",
                "{$this->table}.content",
                "{$this->table}.created_at",
                DB::raw('DATE_FORMAT(customer_care.created_at, "%d/%m/%Y") as created_group'),
                "staffs.full_name",
                "manage_type_work.manage_type_work_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("manage_type_work", "manage_type_work.manage_type_work_key", "=", "{$this->table}.care_type")
            ->where("customer_code", $customerCode)
            ->orderBy("created_at", "desc")
            ->get();
    }

    /**
     * ds CSKH phân trang table
     *
     * @param $filter
     * @return mixed
     */
    public function getListCustomerCare(array &$filter = null)
    {
        $data = $this
            ->select(
                "{$this->table}.care_type",
                "{$this->table}.content",
                "{$this->table}.created_at",
                DB::raw('DATE_FORMAT(customer_care.created_at, "%d/%m/%Y %H:%i:%s") as created_group'),
                "staffs.full_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->where("customer_code", $filter['customer_code'])
            ->orderBy("created_at", "desc");
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? 6);
        return $data->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}