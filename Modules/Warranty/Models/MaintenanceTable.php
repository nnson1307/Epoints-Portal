<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/2/2021
 * Time: 2:46 PM
 */

namespace Modules\Warranty\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class MaintenanceTable extends Model
{
    use ListTableTrait;
    protected $table = "maintenance";
    protected $primaryKey = "maintenance_id";
    protected $fillable = [
        "maintenance_id",
        "maintenance_code",
        "customer_code",
        "warranty_code",
        "maintenance_cost",
        "warranty_value",
        "insurance_pay",
        "amount_pay",
        "total_amount_pay",
        "staff_id",
        "object_type",
        "object_type_id",
        "object_code",
        "object_serial",
        "object_status",
        "maintenance_content",
        "date_estimate_delivery",
        "status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETE = 0;
    const FINISH = 'finish';

    /**
     * Danh sách phiếu bảo hành điện tử
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.maintenance_id",
                "{$this->table}.maintenance_code",
                "{$this->table}.total_amount_pay",
                "staffs.full_name as staff_name",
                "{$this->table}.status",
                "{$this->table}.created_at",
                "customers.full_name as customer_name",
                "{$this->table}.date_estimate_delivery"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->where("customers.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.maintenance_id", "desc");

        // filter tên, mã
        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where("{$this->table}.maintenance_code", 'like', '%' . $search . '%')
                    ->orWhere("customers.full_name", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        //filter ngày trả hàng dự kiến
        if (isset($filter["date_estimate_delivery"]) && $filter["date_estimate_delivery"] != null) {
            $arr_filter = explode(" - ", $filter["date_estimate_delivery"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.date_estimate_delivery", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            unset($filter['date_estimate_delivery']);
        }

        return $ds;
    }

    /**
     * Thêm phiếu bảo trì
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->maintenance_id;
    }

    /**
     * Lấy thông tin phiếu bảo trì
     *
     * @param $maintenanceId
     * @return mixed
     */
    public function getInfo($maintenanceId)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_id",
                "{$this->table}.maintenance_code",
                "{$this->table}.customer_code",
                "{$this->table}.warranty_code",
                "{$this->table}.maintenance_cost",
                "{$this->table}.warranty_value",
                "{$this->table}.insurance_pay",
                "{$this->table}.amount_pay",
                "{$this->table}.total_amount_pay",
                "{$this->table}.staff_id",
                "{$this->table}.object_type",
                "{$this->table}.object_type_id",
                "{$this->table}.object_code",
                "{$this->table}.object_serial",
                "{$this->table}.object_status",
                "{$this->table}.maintenance_content",
                "{$this->table}.date_estimate_delivery",
                "{$this->table}.status",
                "customers.customer_id",
                "customers.full_name",
                "customers.phone1 as phone"
            )
            ->join("customers", "customers.customer_code", "=", "{$this->table}.customer_code")
            ->where("{$this->table}.maintenance_id", $maintenanceId)
            ->first();
    }

    /**
     * Chỉnh sửa phiếu bảo trì
     *
     * @param array $data
     * @param $maintenanceId
     * @return mixed
     */
    public function edit(array $data, $maintenanceId)
    {
        return $this->where("maintenance_id", $maintenanceId)->update($data);
    }

    /**
     * Lấy số phiếu bảo trì đã hoàn tất của 1 phiếu bảo hành trừ chính nó
     *
     * @param $warrantyCode
     * @param $maintenanceId
     * @return mixed
     */
    public function getMaintenanceFinish($warrantyCode, $maintenanceId)
    {
        return $this
            ->select(
                "{$this->table}.maintenance_id"
            )
            ->where("warranty_code", $warrantyCode)
            ->where("maintenance_id", "<>", $maintenanceId)
            ->where("status", self::FINISH)
            ->get();
    }
}