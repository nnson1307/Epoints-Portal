<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 3/5/2021
 * Time: 11:19 AM
 */

namespace Modules\Ticket\Models;

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
        'payment_id', 'payment_code', 'branch_code','staff_id', 'created_by', 'updated_by', 'total_amount',
        'approved_by', 'status', 'note','payment_date', 'created_at', 'updated_at',
        'object_accounting_type_code', 'accounting_id', 'accounting_name',
        'payment_type','document_code','payment_method','is_delete'
    ];
    
    public function staff_created()
    {
        return $this->belongsTo('Modules\Ticket\Models\StaffTable', 'created_by', 'staff_id');
    }
    /**
     * Tạo phiếu chi mới
     *
     * @param $dataCreate
     * @return mixed
     */
    public function createPayment($dataCreate){
        return $this->create($dataCreate)->{$this->primaryKey};
    }

    /**
     * Lấy payment_code lớn nhất
     *
     * @return mixed
     */
    public function getPaymentMaxId(){
        $data = $this->select('payment_code')
                ->where('payment_id','=',\DB::raw("(select max(payment_id) from {$this->table})"))->first();
        return $data;
    }

    /**
     * Xoá phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function deletePayment($id){
        return $this->where($this->primaryKey, $id)->update(['is_delete' => 1]);
    }

    /**
     * Lấy thông tin 1 phiếu chi
     *
     * @param $id
     * @return mixed
     */
    public function getDataById($id){
        return $this->where($this->primaryKey, $id)->first()->toArray();
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

    public function getItemByRefundID($id)
    {
        return $this->select('payments.*',
        'branches.branch_name as branch_name',
        'object_accounting_type.object_accounting_type_name_vi',
        'object_accounting_type.object_accounting_type_name_en'
        )
        ->leftJoin('branches', 'branches.branch_code', '=', 'payments.branch_code')
        ->leftJoin('object_accounting_type', 'object_accounting_type.object_accounting_type_code', '=', 'payments.object_accounting_type_code')
        ->where('payment_method', 'CASH')
        ->where('note','LIKE', 'Chi trả tiền vật tư phát sinh cho nhân viên%')
        ->where('document_code', 'refund_id_'.$id)
        ->first();
    }
}