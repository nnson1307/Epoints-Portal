<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2022
 * Time: 10:29
 */

namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use MyCore\Models\Traits\ListTableTrait;

class ReceiptCustomerTable extends Model
{
    use ListTableTrait;
    protected $table = "receipts";
    protected $primaryKey = "receipt_id";
    protected $fillable = [
        "receipt_id",
        "receipt_code",
        "customer_id",
        "staff_id",
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

        // filter khách hàng
        if (isset($filter['customer_id']) && $filter['customer_id'] != null) {
            $select->where("{$this->table}.customer_id", $filter['customer_id']);
        }

        unset($filter['customer_id']);

        return $select;
    }

    /**
     * Lấy lịch sử thanh toán của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getReceiptByCustomer($customerId)
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
            ->leftJoin("customer_debt as deb", "deb.customer_debt_id", "=", "{$this->table}.object_id")
            ->where("{$this->table}.object_type", 'debt')
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.customer_id", $customerId)
            ->whereNotIn("deb.status", ['cancel', 'paid'])
            ->orderBy("{$this->table}.receipt_id", "desc")
            ->get();
    }
}
