<?php

namespace Modules\Payment\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;

class ReceiptTable extends Model
{
    use ListTableTrait;

    protected $table = "receipts";
    protected $primaryKey = "receipt_id";
    protected $fillable = [
        "receipt_id",
        "receipt_code",
        "customer_id",
        "staff_id",
        "branch_id",
        "order_id",
        "total_money",
        "voucher_code",
        "status",
        "discount",
        "custom_discount",
        "is_discount",
        "amount",
        "amount_paid",
        "amount_return",
        "note",
        "object_id",
        "object_type",
        "receipt_type_code",
        "object_accounting_type_code",
        "object_accounting_id",
        "object_accounting_name",
        "type_insert",
        "document_code",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const NOT_DELETE = 0;

    public function _getList(&$filter = [])
    {
        $lang = Config::get('app.locale');

        $select = $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.customer_id",
                "{$this->table}.staff_id",
                "{$this->table}.order_id",
                "{$this->table}.total_money",
                "{$this->table}.voucher_code",
                "{$this->table}.status",
                "{$this->table}.discount",
                "{$this->table}.custom_discount",
                "{$this->table}.is_discount",
                "{$this->table}.amount",
                "{$this->table}.amount_paid",
                "{$this->table}.amount_return",
                "{$this->table}.note",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.receipt_source",
                "{$this->table}.receipt_type_code",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.object_accounting_id",
                "{$this->table}.object_accounting_name",
                "{$this->table}.type_insert",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "receipt_type.receipt_type_name_$lang as receipt_type_name",
                "oat.object_accounting_type_name_$lang as object_accounting_type_name",
                "staffs.full_name as staff_name",
                "cs.full_name as customer_name",
                "cs1.full_name as customer_name_debt"
            )
            ->leftJoin("receipt_type", "receipt_type.receipt_type_code", "=", "{$this->table}.receipt_type_code")
            ->leftJoin("object_accounting_type as oat", "oat.object_accounting_type_code", "=", "{$this->table}.object_accounting_type_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customers as cs", "cs.customer_id", "=", "or.customer_id")
            ->leftJoin("customers as cs1", "cs1.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.receipt_id", "desc");
        // filter name, code
        if (isset($filter['search']) && $filter['search'] != "") {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('receipt_id', 'like', '%' . $search . '%')
                    ->orWhere('receipt_code', 'like', '%' . $search . '%');
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

    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    public function edit($data, $id)
    {
        return $this->where('receipt_id', $id)->update($data);
    }

    public function removeOrderReceipt($data, $orderId)
    {
        return $this->where('order_id', $orderId)->update($data);
    }

    /**
     * Lấy thông tin phiếu thu
     *
     * @param $id
     * @return mixed
     */
    public function getReceiptInfo($id)
    {
        $select = $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.customer_id",
                "{$this->table}.staff_id",
                "{$this->table}.order_id",
                "{$this->table}.total_money",
                "{$this->table}.voucher_code",
                "{$this->table}.status",
                "{$this->table}.discount",
                "{$this->table}.custom_discount",
                "{$this->table}.is_discount",
                "{$this->table}.amount",
                "{$this->table}.amount_paid",
                "{$this->table}.amount_return",
                "{$this->table}.note",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.receipt_type_code",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.object_accounting_id",
                "{$this->table}.object_accounting_name",
                "{$this->table}.type_insert",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "staffs.full_name as staff_name",
                "staffs.branch_id as branch_id",
                "cs.full_name as customer_name",
                "cs1.full_name as customer_name_debt",
                "or.order_id",
                "or.order_code"
            )
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customers as cs", "cs.customer_id", "=", "or.customer_id")
            ->leftJoin("customers as cs1", "cs1.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.receipt_id", $id)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        return $select->first();
    }

    public function getAllReceiptByFilter($time, $branchId, $receiptType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $select = $this->select(
            "{$this->table}.receipt_id",
            "{$this->table}.receipt_code",
            "{$this->table}.customer_id",
            "{$this->table}.staff_id",
            "{$this->table}.total_money",
            "{$this->table}.amount",
            "{$this->table}.amount_paid",
            "{$this->table}.note",
            "{$this->table}.receipt_type_code",
            "{$this->table}.object_accounting_type_code",
            "{$this->table}.object_accounting_id",
            "{$this->table}.object_accounting_name",
            "{$this->table}.type_insert"
        )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("receipt_details", "receipt_details.receipt_id", "=", "{$this->table}.receipt_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.status", "=", "paid")
            ->groupBy("{$this->table}.receipt_id");

        if ($branchId != null) {
            $select->where("staffs.branch_id", $branchId);
        }
        if ($receiptType != null) {
            $select->where("{$this->table}.receipt_type_code", $receiptType);
        }
        if ($paymentMethod != null) {
            $select->where("receipt_details.payment_method_code", $paymentMethod);
        }

        return $select->get();
    }

    /**
     * Lấy chi tiết phiếu thu
     *
     * @param $receiptId
     * @return mixed
     */
    public function getInfoByDetail($receiptId)
    {
        $lang = Config::get('app.locale');

        return $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.customer_id",
                "{$this->table}.staff_id",
                "{$this->table}.order_id",
                "{$this->table}.total_money",
                "{$this->table}.voucher_code",
                "{$this->table}.status",
                "{$this->table}.discount",
                "{$this->table}.custom_discount",
                "{$this->table}.is_discount",
                "{$this->table}.amount",
                "{$this->table}.amount_paid",
                "{$this->table}.amount_return",
                "{$this->table}.note",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.receipt_type_code",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.object_accounting_id",
                "{$this->table}.object_accounting_name",
                "{$this->table}.type_insert",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "receipt_type.receipt_type_name_$lang as receipt_type_name",
                "oat.object_accounting_type_name_$lang as object_accounting_type_name",
                "staffs.full_name as staff_name",
                "cs.full_name as customer_name",
                "cs1.full_name as customer_name_debt"
            )
            ->leftJoin("receipt_type", "receipt_type.receipt_type_code", "=", "{$this->table}.receipt_type_code")
            ->leftJoin("object_accounting_type as oat", "oat.object_accounting_type_code", "=", "{$this->table}.object_accounting_type_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customers as cs", "cs.customer_id", "=", "or.customer_id")
            ->leftJoin("customers as cs1", "cs1.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.receipt_id", $receiptId)
            ->first();
    }

    /**
     * Lấy dữ liệu phiếu thu khi export excel
     *
     * @param array $filter
     * @return mixed
     */
    public function getReceiptExportExcel($filter = [])
    {
        $lang = Config::get('app.locale');

        $select = $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.customer_id",
                "{$this->table}.staff_id",
                "{$this->table}.order_id",
                "{$this->table}.total_money",
                "{$this->table}.voucher_code",
                "{$this->table}.status",
                "{$this->table}.discount",
                "{$this->table}.custom_discount",
                "{$this->table}.is_discount",
                "{$this->table}.amount",
                "{$this->table}.amount_paid",
                "{$this->table}.amount_return",
                "{$this->table}.note",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.receipt_type_code",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.object_accounting_id",
                "{$this->table}.object_accounting_name",
                "{$this->table}.type_insert",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "receipt_type.receipt_type_name_$lang as receipt_type_name",
                "oat.object_accounting_type_name_$lang as object_accounting_type_name",
                "staffs.full_name as staff_name",
                "cs.full_name as customer_name",
                "cs1.full_name as customer_name_debt"
            )
            ->leftJoin("receipt_type", "receipt_type.receipt_type_code", "=", "{$this->table}.receipt_type_code")
            ->leftJoin("object_accounting_type as oat", "oat.object_accounting_type_code", "=", "{$this->table}.object_accounting_type_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customers as cs", "cs.customer_id", "=", "or.customer_id")
            ->leftJoin("customers as cs1", "cs1.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.receipt_id", "desc");

        // filter name, code
        if (isset($filter['search']) && $filter['search'] != null) {
            $search = $filter['search'];
            $select->where(function ($query) use ($search) {
                $query->where('receipt_id', 'like', '%' . $search . '%')
                    ->orWhere('receipt_code', 'like', '%' . $search . '%');
            });
        }

        // filter ngày tạo
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        // filter trạng thái
        if (isset($filter["status"]) && $filter["status"] != null) {
            $select->where("{$this->table}.status", $filter['status']);
        }


        return $select->get();
    }

    /**
     * Lấy tất cả phiếu thu của đơn hàng - công nợ
     *
     * @param $receiptId
     * @param $receiptTypeCode
     * @param $objectTypeId
     * @return mixed
     */
    public function getReceiptByObject($receiptId = null, $receiptTypeCode, $objectTypeId)
    {
        $lang = Config::get('app.locale');

        $ds = $this
            ->select(
                "{$this->table}.receipt_id",
                "{$this->table}.receipt_code",
                "{$this->table}.customer_id",
                "{$this->table}.staff_id",
                "{$this->table}.order_id",
                "{$this->table}.total_money",
                "{$this->table}.voucher_code",
                "{$this->table}.status",
                "{$this->table}.discount",
                "{$this->table}.custom_discount",
                "{$this->table}.is_discount",
                "{$this->table}.amount",
                "{$this->table}.amount_paid",
                "{$this->table}.amount_return",
                "{$this->table}.note",
                "{$this->table}.object_id",
                "{$this->table}.object_type",
                "{$this->table}.receipt_type_code",
                "{$this->table}.object_accounting_type_code",
                "{$this->table}.object_accounting_id",
                "{$this->table}.object_accounting_name",
                "{$this->table}.type_insert",
                "{$this->table}.created_at",
                "{$this->table}.updated_at",
                "receipt_type.receipt_type_name_$lang as receipt_type_name",
                "oat.object_accounting_type_name_$lang as object_accounting_type_name",
                "staffs.full_name as staff_name",
                "cs.full_name as customer_name",
                "cs1.full_name as customer_name_debt"
            )
            ->leftJoin("receipt_type", "receipt_type.receipt_type_code", "=", "{$this->table}.receipt_type_code")
            ->leftJoin("object_accounting_type as oat", "oat.object_accounting_type_code", "=", "{$this->table}.object_accounting_type_code")
            ->leftJoin("staffs", "staffs.staff_id", "=", "{$this->table}.created_by")
            ->leftJoin("orders as or", "or.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("customers as cs", "cs.customer_id", "=", "or.customer_id")
            ->leftJoin("customers as cs1", "cs1.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.receipt_type_code", $receiptTypeCode)
            ->where("{$this->table}.object_accounting_id", $objectTypeId)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereNotIn("{$this->table}.status", ["cancel", "fail"]);

        if ($receiptId != null) {
            $ds->where("{$this->table}.receipt_id", "<>", $receiptId);
        }

        return $ds->get();
    }

    /**
     * Huỷ phiếu thu của công nợ
     *
     * @param $debtId
     * @return mixed
     */
    public function removeReceiptByDebt($data, $debtId)
    {
        return $this
            ->where("object_type", "debt")
            ->where("object_id", $debtId)
            ->update($data);
    }
}