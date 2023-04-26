<?php

namespace Modules\Warranty\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class RepairTable extends Model
{
    use ListTableTrait;
    protected $table = "repairs";
    protected $primaryKey = "repair_id";
    protected $fillable = [
        "repair_id",
        "repair_code",
        "repair_cost",
        "insurance_pay",
        "amount_pay",
        "total_pay",
        "staff_id",
        "object_type",
        "object_id",
        "object_code",
        "object_status",
        "repair_content",
        "repair_date",
        "status",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETE = 0;
    const FINISH = 'finish';
    const PAYMENT_TYPE_REPAIR = 13;
    const PRODUCT = 'product';
    const SERVICE = 'service';
    const SERVICE_CARD = 'service_card';
    const PAID = 'paid';

    public function _getList($filter = [])
    {
        $select = $this
            ->select(
                "{$this->table}.repair_id",
                "{$this->table}.repair_code",
                "{$this->table}.repair_cost",
                "{$this->table}.amount_pay",
                "{$this->table}.total_pay",
                "{$this->table}.status",
                "{$this->table}.repair_date",
                "{$this->table}.created_at",
                "staffs.full_name as staff_name",
                "payments.status as payment_status"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("payments", "payments.document_code", "{$this->table}.repair_code")
            ->orderBy("{$this->table}.repair_id", "desc");

        // filter tên, mã
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where("{$this->table}.repair_code", 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.repair_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    /**
     * thêm mới phiếu bảo dưỡng
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * cập nhật phiếu bảo dưỡng theo id
     *
     * @param $data
     * @param $repairId
     * @return mixed
     */
    public function edit($data, $repairId)
    {
        return $this->where("{$this->primaryKey}", $repairId)->update($data);
    }

    /**
     * Lấy thông tin phiếu bảo dưỡng
     *
     * @param $repairId
     * @return mixed
     */
    public function getInfo($repairId)
    {
        return $this
            ->select(
                "{$this->table}.repair_id",
                "{$this->table}.repair_code",
                "{$this->table}.repair_cost",
                "{$this->table}.insurance_pay",
                "{$this->table}.amount_pay",
                "{$this->table}.total_pay",
                "{$this->table}.staff_id",
                "{$this->table}.object_type",
                "{$this->table}.object_id",
                "{$this->table}.object_code",
                "{$this->table}.object_status",
                "{$this->table}.repair_content",
                "{$this->table}.repair_date",
                "{$this->table}.status",
                "{$this->table}.created_by",
                "{$this->table}.created_at",
                "staffs.full_name as staff_name",
                "payments.status as payment_status"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("payments", "payments.document_code", "{$this->table}.repair_code")
            ->where("{$this->table}.repair_id", $repairId)
            ->first();
    }

    /**
     * Lất tất cả sản phẩm theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param null $branchId
     * @return mixed
     */
    public function getAllProductByFilterTimeAndBranch($startTime, $endTime, $branchId = null)
    {
        $select = $this
            ->select(
                DB::raw('SUM(repairs.total_pay) as total_pay'),
                DB::raw('SUM(repairs.repair_cost) as total_cost'),
                "{$this->table}.repair_date",
                "{$this->table}.object_code",
                "staffs.branch_id as branch_id",
                "product_childs.product_child_name as object_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
            ->leftJoin("payments", "payments.document_code", "{$this->table}.repair_code")
            ->where("{$this->table}.object_type", self::PRODUCT)
            ->where("payments.status", self::PAID)
            ->whereBetween("{$this->table}.repair_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->groupBy("{$this->table}.object_code")
            ->orderBy("total_pay", "desc");
        if ($branchId != null) {
            $select->where("staffs.branch_id", "=", $branchId);
        }
        return $select->get();
    }

    /**
     * Lất tất cả dịch vụ theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param null $branchId
     * @return mixed
     */
    public function getAllServiceByFilterTimeAndBranch($startTime, $endTime, $branchId = null)
    {
        $select = $this
            ->select(
                DB::raw('SUM(repairs.total_pay) as total_pay'),
                DB::raw('SUM(repairs.repair_cost) as total_cost'),
                "{$this->table}.repair_date",
                "{$this->table}.object_code",
                "staffs.branch_id as branch_id",
                "services.service_name as object_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("services", "services.service_code", "=", "{$this->table}.object_code")
            ->leftJoin("payments", "payments.document_code", "{$this->table}.repair_code")
            ->where("{$this->table}.object_type", self::SERVICE)
            ->where("payments.status", self::PAID)
            ->whereBetween("{$this->table}.repair_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->groupBy("{$this->table}.object_code")
            ->orderBy("total_pay", "desc");
        if ($branchId != null) {
            $select->where("staffs.branch_id", "=", $branchId);
        }
        return $select->get();
    }
    /**
     * Lất tất cả dịch vụ theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param null $branchId
     * @return mixed
     */
    public function getAllServiceCardByFilterTimeAndBranch($startTime, $endTime, $branchId = null)
    {
        $select = $this
            ->select(
                DB::raw('SUM(repairs.total_pay) as total_pay'),
                DB::raw('SUM(repairs.repair_cost) as total_cost'),
                "{$this->table}.repair_date",
                "{$this->table}.object_code",
                "staffs.branch_id as branch_id",
                "service_cards.name as object_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("service_cards", "service_cards.service_card_id", "=", "{$this->table}.object_id")
            ->leftJoin("payments", "payments.document_code", "{$this->table}.repair_code")
            ->where("{$this->table}.object_type", self::SERVICE_CARD)
            ->where("payments.status", self::PAID)
            ->whereBetween("{$this->table}.repair_date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->groupBy("{$this->table}.object_id")
            ->orderBy("total_pay", "desc");
        if ($branchId != null) {
            $select->where("staffs.branch_id", "=", $branchId);
        }
        return $select->get();
    }
}