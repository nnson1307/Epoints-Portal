<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use PHPUnit\Framework\Constraint\Count;


class ReferralPaymentMemberTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_payment_member";
    protected $primaryKey = "referral_payment_member_id";
    protected $fillable = [
        'referral_payment_member_id',
        'referral_payment_id',
        'referral_member_id',
        'payment_id',
        'total_money',
        'total_commission',
        'status',
        'created_at'
    ];

    public $timestamps = false;

    protected function _getList(&$filter = []){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                'payments.payment_code',
                'payments.payment_date',
                'referral_payment.name',
                'customers.full_name',
                'staffs.full_name as staff_full_name',
                'payment_method.payment_method_name_vi',
                'payment_method.payment_method_name_en'
            )
            ->leftJoin('payments','payments.payment_id',$this->table.'.payment_id')
            ->leftJoin('referral_payment','referral_payment.referral_payment_id',$this->table.'.referral_payment_id')
            ->leftJoin('payment_method','payment_method.payment_method_code','payments.payment_method')
            ->leftJoin('staffs','staffs.staff_id','payments.staff_id')
            ->leftJoin('referral_member','referral_member.referral_member_id',$this->table.'.referral_member_id')
            ->leftJoin('customers','customers.customer_id','referral_member.member_id');

        if (isset($filter['search'])){
            $search = $filter['search'];
            $oSelect = $oSelect->where(function ($sql) use ($search){
                $sql->where('payments.payment_code','like','%'.$search.'%')
                    ->orWhere('customers.full_name','like','%'.$search.'%');
            });
            unset($filter['search']);
        }

        if (isset($filter['search_history'])){
            $search = $filter['search_history'];
            $oSelect = $oSelect->where(function ($sql) use ($search){
                $sql->where('customers.full_name','like','%'.$search.'%');
            });
            unset($filter['search_history']);
        }

        if (isset($filter['object_accounting_type_code'])){
            $oSelect = $oSelect->where('payments.object_accounting_type_code',$filter['object_accounting_type_code']);
            unset($filter['object_accounting_type_code']);
        }

        if (isset($filter['object_accounting_type_code'])){
            $oSelect = $oSelect->where('payments.object_accounting_type_code',$filter['object_accounting_type_code']);
            unset($filter['object_accounting_type_code']);
        }

        if (isset($filter['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['status']);
            unset($filter['status']);
        }

        if (isset($filter['staff_id'])){
            $oSelect = $oSelect->where('payments.staff_id',$filter['staff_id']);
            unset($filter['staff_id']);
        }

        if (isset($filter['not_status'])){
            $oSelect = $oSelect->where($this->table.'.status','<>',$filter['not_status']);
            unset($filter['not_status']);
        }

        if (isset($filter['referral_payment_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_payment_id',$filter['referral_payment_id']);
            unset($filter['referral_payment_id']);
        }

        if (isset($filter['referral_member_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_member_id',$filter['referral_member_id']);
            unset($filter['referral_member_id']);
        }

        if (isset($filter['period'])){
            $oSelect = $oSelect->where('payments.period',$filter['period']);
            unset($filter['period']);
        }

        if (isset($filter['payment_date'])){
            $time = explode(' - ', $filter['payment_date']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("payments.payment_date", [$startTime, $endTime]);
            unset($filter['payment_date']);
        }


        return $oSelect->orderBy($this->table.'.referral_payment_member_id','DESC');
    }

    /**
     * Cập nhật thông tin payment member
     * @param $data
     * @param $referral_payment_member_id
     */
    public function updatePaymentMember($data,$referral_payment_member_id){
        return $this
            ->where($this->table.'.referral_payment_member_id',$referral_payment_member_id)
            ->update($data);
    }

    /**
     * Lấy chi tiết
     * @param $referral_payment_member_id
     */
    public function getDetail($referral_payment_member_id){
        return $this
            ->where($this->table.'.referral_payment_member_id',$referral_payment_member_id)
            ->first();
    }

    /**
     * Lấy danh sách theo member id
     * @param $arrMemberId
     */
    public function getListByIdReferralMember($arrMemberId){
        return $this
            ->whereIn($this->table.'.referral_member_id',$arrMemberId)
            ->orderBy($this->table.'.referral_payment_member_id','DESC')
            ->groupBy($this->table.'.referral_member_id')
            ->get();
    }
}
