<?php

namespace Modules\Payment\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;

class ReceiptTypeTable extends Model
{
    use ListTableTrait;
    protected $table = "receipt_type";
    protected $primaryKey = "receipt_type_id";
    protected $fillable = [
        "receipt_type_id",
        "receipt_type_code",
        "receipt_type_name_vi",
        "receipt_type_name_en",
        "is_active",
        "is_system",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    public function _getList(&$filter = [])
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "{$this->table}.receipt_type_id",
            "{$this->table}.receipt_type_code",
            "{$this->table}.receipt_type_name_vi",
            "{$this->table}.receipt_type_name_en",
            "{$this->table}.is_active",
            "{$this->table}.is_system",
            "{$this->table}.created_by",
            "{$this->table}.created_at"
        )
//            ->where("{$this->table}.is_active", self::IS_ACTIVE)
            ->orderBy("{$this->table}.receipt_type_id", "desc");
        // filter name, code
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('receipt_type_code', 'like', '%' . $search . '%')
                    ->orWhere('receipt_type_name_vi', 'like', '%' . $search . '%')
                    ->orWhere('receipt_type_name_en', 'like', '%' . $search . '%');
            });
        }
        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        return $select;
    }

    /**
     * Lấy các option loại phiếu thu
     *
     * @return mixed
     */
    public function getOption()
    {
        $lang = Config::get('app.locale');
        $select = $this->select(
            "receipt_type_id",
            "receipt_type_code",
            "receipt_type_name_$lang as receipt_type_name"
        )->where("is_active", self::IS_ACTIVE);
        return $select->get();
    }

    /**
     * Tổng tiền theo từng loại phiếu thu
     *
     * @return mixed
     */
    public function getTotalReceiptByReceiptType($time, $branchId, $receiptType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $lang = Config::get('app.locale');

        $select = $this
            ->select(
                "{$this->table}.receipt_type_id",
                "{$this->table}.receipt_type_name_$lang as receipt_type_name",
                DB::raw('SUM(receipt_details.amount) as amount_paid')
            )
            ->leftJoin("receipts", "receipts.receipt_type_code", "=", "{$this->table}.receipt_type_code")
            ->leftJoin("receipt_details", "receipt_details.receipt_id", "=", "receipts.receipt_id")
            ->leftJoin("staffs", "staffs.staff_id", "=", "receipts.staff_id")
            ->where("receipts.status", "=", "paid")
            ->whereBetween("receipts.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);


        if ($branchId != null) {
            $select->where("staffs.branch_id", $branchId);
        }
        if ($receiptType != null) {
            $select->where("{$this->table}.receipt_type_code", $receiptType);
        }
        if ($paymentMethod != null) {
            $select->where("receipt_details.payment_method_code", $paymentMethod);
        }
        $select->groupBy("receipts.receipt_id");

        return $select->get();
    }

    /**
     * thêm loại phiếu thu
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * cập nhật loại phiếu thu
     *
     * @param $data
     * @param $receiptTypeId
     * @return mixed
     */
    public function edit($data, $receiptTypeId)
    {
        return $this->where("{$this->primaryKey}", $receiptTypeId)->update($data);
    }

    /**
     * chi tiết loại t  hanh toán
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $select = $this->select(
            "receipt_type_id",
            "receipt_type_code",
            "receipt_type_name_vi",
            "receipt_type_name_en",
            "is_active",
            "is_system",
            "created_by",
            "updated_by",
            "created_at",
        )->where("{$this->primaryKey}", $id);
        return $select->first();
    }

    /**
     * Xoá loại phiếu thu
     *
     * @param $id
     * @return mixed
     */
    public function deleteType($id)
    {
        return $this->where("{$this->primaryKey}", $id)->delete();
    }
}