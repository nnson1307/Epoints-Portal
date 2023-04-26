<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use PHPUnit\Framework\Constraint\Count;


class ReferralPaymentDetailTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_payment_detail";
    protected $primaryKey = "referral_payment_detail_id";
    protected $fillable = [
        'referral_payment_detail_id',
        'referral_payment_id',
        'referral_payment_member_id',
        'referral_member_detail_id',
        'total_money',
        'total_commission',
        'created_at',
    ];

    /**
     * Lấy danh sách chi tiết thanh toán bằng referral payment id
     * @param $referralPaymentId
     */
    public function getListByPaymentId($referralPaymentId){
        return $this
            ->select(
                $this->table.'.*',
                'referral_payment_member.referral_member_id',
                'referral_member_detail.referral_from',
                'referral_member_detail.referral_multi_level_id',
                'referral_member_detail.action',
                'referral_member_detail.obj_id'
            )
            ->leftJoin('referral_payment_member','referral_payment_member.referral_payment_member_id',$this->table.'.referral_payment_member_id')
            ->leftJoin('referral_member_detail','referral_member_detail.referral_member_detail_id',$this->table.'.referral_member_detail_id')
            ->where($this->table.'.referral_payment_id',$referralPaymentId)
            ->get();
    }


}
