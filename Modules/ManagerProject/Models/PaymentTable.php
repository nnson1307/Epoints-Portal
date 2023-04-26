<?php
namespace Modules\ManagerProject\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class PaymentTable extends Model
{
protected $table = "payments";
protected $primaryKey = "payment_id";
    protected $casts  = [
        'total_money' => 'double'
    ];
    protected $fillable = [
        'payment_id', 'payment_code', 'branch_code', 'staff_id', 'created_by', 'updated_by', 'total_amount',
        'approved_by', 'status', 'note', 'payment_date', 'created_at', 'updated_at',
        'object_accounting_type_code', 'accounting_id', 'accounting_name',
        'payment_type', 'document_code', 'payment_method', 'is_delete'
    ];

    public function getListPayment($filter = [],$param = []){
        $mSelect = $this
            ->select(
                "{$this->table}.payment_id",
                "{$this->table}.payment_code",
                "{$this->table}.staff_id",
                "staffs.full_name",
                "{$this->table}.total_amount as total_money",
                "{$this->table}.status",
                "{$this->table}.accounting_name",
                "{$this->table}.created_at",
                "{$this->table}.payment_date as date_record",
                "{$this->table}.payment_method as method",
                "payment_method.payment_method_code as method_code",
                "payment_method.payment_method_name_vi as method_name_vi",
                "payment_method.payment_method_name_en as method_name_en"

            )
        ->leftJoin("staffs", "{$this->table}.staff_id","staffs.staff_id")
        ->leftJoin("payment_method", "{$this->table}.payment_method","payment_method.payment_method_code")
        ->leftJoin("branches", "{$this->table}.branch_code","branches.branch_code");
        if(isset($filter['arrIdPayment']) && $filter['arrIdPayment'] != null && $filter['arrIdPayment'] != []){
            $mSelect->whereIn("{$this->table}.payment_id",$filter['arrIdPayment']);
        }
        if(isset($filter['obj_id']) && $filter['obj_id'] != null && $filter['obj_id'] != []){
            $mSelect->where("{$this->table}.payment_id",$filter['obj_id']);
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != null) {
            $arr_filter_create = explode(" - ", $filter["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        if(isset($param['search']) && $param['search'] != null && $param['search'] != []){
            $mSelect->where("{$this->table}.payment_code",'like','%'.$param['search'].'%');
        }
        if(isset($param['status']) && $param['status'] != null && $param['status'] != []){
            $mSelect->where("{$this->table}.status",$param['status']);
        }
        if(isset($param['staff_id']) && $param['staff_id'] != null && $param['staff_id'] != []){
            $mSelect->where("{$this->table}.staff_id",$param['staff_id']);
        }
        if(isset($param['branch_id']) && $param['branch_id'] != null && $param['branch_id'] != []){
            $mSelect->where("branches.branch_id",$param['branch_id']);
        }
        if (isset($param["created_at"]) && $param["created_at"] != null) {
            $arr_filter_create = explode(" - ", $param["created_at"]);
            $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
            $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
            $mSelect->whereBetween("{$this->table}.created_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
        }
        return $mSelect->get()->toArray();
    }
    public function getPaymentMaxId()
    {
        $data = $this->select('payment_code')
            ->where('payment_id', '=', \DB::raw("(select max(payment_id) from {$this->table})"))->first();
        return $data;
    }
    public function createPayment($dataCreate)
    {
        return $this->create($dataCreate)->{$this->primaryKey};
    }
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
}