<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use PHPUnit\Framework\Constraint\Count;


class ReferralPaymentTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_payment";
    protected $primaryKey = "referral_payment_id";
    protected $fillable = [
        'referral_payment_id',
        'name',
        'date_start',
        'date_end',
        'period',
        'total_money',
        'status',
        'created_at'
    ];

    protected function _getList(&$filter = []){
        $oSelect = $this;

        if (isset($filter['period'])){
            $oSelect = $oSelect->where($this->table.'.period', $filter['period']);
            unset($filter['period']);
        }

        if (isset($filter['referral_payment_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_payment_id',$filter['referral_payment_id']);
            unset($filter['referral_payment_id']);
        }

        return $oSelect->orderBy($this->table.'.referral_payment_id','DESC');
    }

    /**
     * Lấy chi tiết
     * @param $id
     */
    public function getDetail($id){
        return $this
            ->where('referral_payment_id',$id)
            ->first();
    }

    /**
     * Cập nhật hoa hồng
     * @param $data
     * @param $id
     */
    public function updatePayment($data,$id){
        return $this
            ->where('referral_payment_id',$id)
            ->update($data);
    }

    public function getAll(){
        return $this
            ->orderBy('referral_payment_id','DESC')
            ->get();
    }
}
