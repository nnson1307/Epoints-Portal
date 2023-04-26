<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 3/5/2021
 * Time: 11:19 AM
 */

namespace Modules\Payment\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class PaymentTable extends Model
{
    use ListTableTrait;
    protected $table = "payments";
    protected $primaryKey = "payment_id";

    protected $fillable = [
        'payment_id', 'payment_code', 'branch_code', 'staff_id', 'created_by', 'updated_by', 'total_amount',
        'approved_by', 'status', 'note', 'payment_date', 'created_at', 'updated_at',
        'object_accounting_type_code', 'accounting_id', 'accounting_name',
        'payment_type', 'document_code', 'payment_method', 'is_delete'
    ];

    /**
     * Lấy tất cả thông tin phiếu chi
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select('payments.payment_id',
                'payments.payment_code',
                'payments.object_accounting_type_code',
                'payments.accounting_id',
                'payments.accounting_name',
                'object_accounting_type.object_accounting_type_name_vi',
                'object_accounting_type.object_accounting_type_name_en',
                'payments.created_by',
                'payments.created_at',
                'payments.total_amount',
                'payments.branch_code',
                'payments.status',
                'payments.payment_date',
                'branches.branch_name',
                'staffs.full_name',
                'customers.full_name as customer_name',
                'suppliers.supplier_name as supplier_name',
                DB::raw("(SELECT full_name FROM staffs where staffs.staff_id = payments.created_by) as staff_name"),
                DB::raw("(SELECT full_name FROM staffs where staffs.staff_id = payments.accounting_id) as employee_name"))
            ->leftJoin('branches', 'branches.branch_code', '=', 'payments.branch_code')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'payments.staff_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'payments.accounting_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'payments.accounting_id')
            ->leftJoin('object_accounting_type', 'object_accounting_type.object_accounting_type_code', '=', 'payments.object_accounting_type_code')
            ->where('is_delete', 0);

        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('payment_code', 'like', '%' . $search . '%')
                    ->orWhere('staffs.full_name', 'like', '%' . $search . '%');
            });
        }

        if (isset($filter['branch_code']) != "") {
            $ds->where('payments.branch_code', '=', $filter['branch_code']);
        }

        unset($filter['branch_code']);

        if (isset($filter['status']) != "") {
            $status = $filter['status'];
            $ds->where('payments.status', '=', $status);
        }
        unset($filter['status']);

        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (isset($filter['created_by']) != "") {
            $created_by = $filter['created_by'];
            $ds->where('payments.created_by', '=', $created_by);
        }
        unset($filter['created_by']);

        return $ds->orderBy('created_at', 'desc');
    }

    /**
     * Tạo phiếu chi mới
     *
     * @param $dataCreate
     * @return mixed
     */
    public function createPayment($dataCreate)
    {
        return $this->create($dataCreate)->{$this->primaryKey};
    }

    /**
     * Lấy payment_code lớn nhất
     *
     * @return mixed
     */
    public function getPaymentMaxId()
    {
        $data = $this->select('payment_code')
            ->where('payment_id', '=', \DB::raw("(select max(payment_id) from {$this->table})"))->first();
        return $data;
    }

    /**
     * Xoá phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function deletePayment($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_delete' => 1]);
    }

    /**
     * Lấy thông tin 1 phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id)
    {
        return $this
            ->select(
                $this->table.'.*',
                'referral_payment_member.referral_payment_member_id',
                'referral_payment_member.referral_member_id',
                'referral_payment_member.referral_payment_id',
                'referral_payment.total_commission as referral_payment_total_commission'
            )
            ->leftJoin('referral_payment_member','referral_payment_member.payment_id',$this->table.'.payment_id')
            ->leftJoin('referral_payment','referral_payment.referral_payment_id','referral_payment_member.referral_payment_id')
            ->where($this->table.'.payment_id', $id)->first()
            ->toArray();
    }

    /**
     * Cập nhật phiếu chi
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    /**
     * Lấy thông tin 1 phiếu chi để in
     *
     * @param $id
     * @return mixed
     */
    public function getDataDetail($id)
    {
        $ds = $this->select('payments.payment_id',
            'payments.payment_code',
            'payments.object_accounting_type_code',
            'payments.accounting_id',
            'payments.accounting_name',
            'object_accounting_type.object_accounting_type_name_vi',
            'object_accounting_type.object_accounting_type_name_en',
            'payments.note',
            'payments.document_code',
            'payments.created_by',
            'payments.created_at',
            'payments.total_amount',
            'payments.branch_code',
            'payments.status',
            'payments.payment_date',
            'branches.branch_name',
            'staffs.full_name',
            'customers.full_name as customer_name',
            'suppliers.supplier_name as supplier_name',
            DB::raw("(SELECT full_name FROM staffs where staffs.staff_id = payments.created_by) as staff_name"),
            DB::raw("(SELECT full_name FROM staffs where staffs.staff_id = payments.accounting_id) as employee_name"),
            'payment_type.payment_type_name_vi',
            'payment_method.payment_method_name_vi')
            ->leftJoin('branches', 'branches.branch_code', '=', 'payments.branch_code')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'payments.staff_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'payments.accounting_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'payments.accounting_id')
            ->leftJoin('object_accounting_type', 'object_accounting_type.object_accounting_type_code', '=', 'payments.object_accounting_type_code')
            ->leftJoin('payment_type', 'payment_type.payment_type_id', '=', 'payments.payment_type')
            ->leftJoin('payment_method', 'payment_method.payment_method_code', '=', 'payments.payment_method')
            ->where('payment_id', $id)->first()->toArray();
        return $ds;
    }

    /**
     * Lấy tất cả record phiếu chi theo filter
     *
     * @param $time
     * @param $branchCode
     * @param $paymentType
     * @param $paymentMethod
     * @return mixed
     */
    public function getAllPaymentByFilter($time, $branchCode, $paymentType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }

        $select = $this->select(
            "payment_id",
            "payment_code",
            "branch_code",
            "staff_id",
            "total_amount",
            "approved_by",
            "status",
            "payment_date",
            "object_accounting_type_code",
            "accounting_id",
            "accounting_name",
            "payment_type",
            "document_code",
            "payment_method"
        )->where("{$this->table}.status", "=", "paid")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("{$this->table}.status", "=", "paid")
            ->where("{$this->table}.is_delete", 0);

        if ($branchCode != null) {
            $select->where("{$this->table}.branch_code", $branchCode);
        }
        if ($paymentType != null) {
            $select->where("{$this->table}.payment_type", $paymentType);
        }
        if ($paymentMethod != null) {
            $select->where("{$this->table}.payment_method", $paymentMethod);
        }


        return $select->get();
    }

    /**
     * Lấy data export excel
     *
     * @param array $filter
     * @return mixed
     */
    public function getDataExportExcel($filter = [])
    {
        $ds = $this
            ->select('payments.payment_id',
                'payments.payment_code',
                'payments.object_accounting_type_code',
                'payments.accounting_id',
                'payments.accounting_name',
                'object_accounting_type.object_accounting_type_name_vi',
                'object_accounting_type.object_accounting_type_name_en',
                'payments.created_by',
                'payments.created_at',
                'payments.total_amount',
                'payments.branch_code',
                'payments.status',
                'payments.payment_date',
                'branches.branch_name',
                'staffs.full_name',
                'customers.full_name as customer_name',
                'suppliers.supplier_name as supplier_name',
                DB::raw("(SELECT full_name FROM staffs where staffs.staff_id = payments.created_by) as staff_name"),
                DB::raw("(SELECT full_name FROM staffs where staffs.staff_id = payments.accounting_id) as employee_name"))
            ->leftJoin('branches', 'branches.branch_code', '=', 'payments.branch_code')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'payments.staff_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'payments.accounting_id')
            ->leftJoin('suppliers', 'suppliers.supplier_id', '=', 'payments.accounting_id')
            ->leftJoin('object_accounting_type', 'object_accounting_type.object_accounting_type_code', '=', 'payments.object_accounting_type_code')
            ->where('is_delete', 0);

        if (isset($filter['search']) &&  $filter['search'] != null) {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('payment_code', 'like', '%' . $search . '%')
                    ->orWhere('staffs.full_name', 'like', '%' . $search . '%');
            });
        }

        if (isset($filter['branch_code']) && $filter['branch_code'] != null) {
            $ds->where('payments.branch_code', '=', $filter['branch_code']);
        }


        if (isset($filter['status']) && $filter['status'] != null) {
            $status = $filter['status'];
            $ds->where('payments.status', '=', $status);
        }

        if (isset($filter["created_at"]) && $filter['created_at'] != null) {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (isset($filter['created_by']) && $filter['created_by'] != null) {
            $created_by = $filter['created_by'];
            $ds->where('payments.created_by', '=', $created_by);
        }

        return $ds->orderBy('created_at', 'desc')->get();
    }

    /**
     * Cập nhật payment
     * @param $data
     * @param $id
     */
    public function updatePayment($data,$id){
        return $this
            ->where($this->table.'.payment_id',$id)
            ->update($data);
    }
}