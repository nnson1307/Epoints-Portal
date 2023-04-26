<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ReferralProgramCommissionTable extends Model
{
    protected $table = "referral_program_commission";
    protected $primaryKey = "referral_program_commission_id";
    protected $isActive = 1;
    protected $isDeleted = 0;

    /**
     * Cập nhật hoa hồng
     */
    public function updateItems($data,$id){
        return $this
            ->where($this->primaryKey,$id)
            ->update($data);
    }

    /**
     * Lấy chi tiết hoa hồng
     * @param $id
     */
    public function getDetail($id, $filter){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                'referral_program.type as referral_program_type',
                'referral_member.total_money as referral_member_total_money',
                'referral_member.total_commission as referral_member_total_commission'
            )
            ->join('referral_member','referral_member.referral_member_id',$this->table.'.commission_member_id')
            ->join('referral_program','referral_program.referral_program_id',$this->table.'.referral_program_id')
            ->where($this->primaryKey,$id)
            ;

        if(isset($filter['statusIn'])){
            $oSelect->whereIn($this->table.'.status', $filter['statusIn']);
        }

        return $oSelect->first();
    }

    public function getAllByInviteId($id){
        return $this->where('referral_program_invite_id', $id)
            ->whereIn('status', ['new', 'approve'])->get();
    }
}
