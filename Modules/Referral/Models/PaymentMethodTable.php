<?php


namespace Modules\Referral\Models;


use Illuminate\Database\Eloquent\Model;

class PaymentMethodTable extends Model
{
    protected $table = "payment_method";
    protected $primaryKey = "payment_method_id";

    public $active = 1;
    public $delete = 0;

    /**
     * Lấy danh sách hình thức thanh toán bỏ member card vs member money
     */
    public function getAll(){
        return $this
            ->whereNotIn('payment_method_id',[6,7])
            ->where('is_active',$this->active)
            ->where('is_delete',$this->delete)
            ->get();
    }

}