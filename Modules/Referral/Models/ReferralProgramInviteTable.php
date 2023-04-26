<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use PHPUnit\Framework\Constraint\Count;


class ReferralProgramInviteTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_program_invite";
    protected $primaryKey = "referral_program_invite_id";
    public $timestamps = false;

    protected $actionCommission = 'commision';
    protected $typePlus = 'plus';

    protected $fillable = [
        'referral_program_invite_id',
        'referral_program_id',
        'referral_member_id',
        'customer_id',
        'obj_id',
        'obj_code',
        'total_money',
        'total_commission',
        'total_commission_tmp',
        'status',
        'status_approve',
        'reject_approve_note',
        'is_run_approve',
        'created_at'
    ];

    protected function _getList(&$filter = []){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                "rpc.total_money",
                "rpc.total_commission",
                "rpc.commission_member_id",
                'rpc.level',
                'rpc.referral_program_commission_id',
                'rpc.status as  rpc_status',
                'referral_program.referral_program_id',
                'referral_program.referral_program_name',
                'referral_program.type as referral_program_type',
                'customers_commission.full_name as customer_commission_full_name',
                'member_commission.referral_member_id as member_commission_referral_member_id',
                'customer_buyer.full_name as customer_buyer_full_name',
                'referral_member_buyer.referral_member_id as member_buyer_referral_member_id',
            )
            ->join('referral_program_commission as rpc',function ($join){
                $join->on('rpc.referral_program_invite_id', '=', "{$this->table}.referral_program_invite_id");
            })
            ->join('referral_program','referral_program.referral_program_id',$this->table.'.referral_program_id')

            //thang gioi thieu
//            ->join('referral_member','referral_member.referral_member_id',$this->table.'.referral_member_id')
//            ->join('customers','customers.customer_id','referral_member.member_id')

            //thang nhan hoa hong
            ->join('referral_member as member_commission','member_commission.referral_member_id','rpc.commission_member_id')
            ->join('customers as customers_commission','customers_commission.customer_id','member_commission.member_id')

            // thang mua
            ->join('customers as customer_buyer','customer_buyer.customer_id',$this->table.'.customer_id')
            ->join('referral_member as referral_member_buyer','referral_member_buyer.member_id','customer_buyer.customer_id');

        if (isset($filter['search'])){
            $oSelect = $oSelect
                ->where('customers_commission.full_name','like','%'.$filter['search'].'%')
                ->orWhere('customer_buyer.full_name','like','%'.$filter['search'].'%')
                ->orWhere($this->table.'.obj_code','like','%'.$filter['search'].'%');
        }

        if (isset($filter['referral_member_id'])){
            $oSelect = $oSelect->where('rpc.commission_member_id',$filter['referral_member_id']);
            unset($filter['referral_member_id']);
        }

        if (isset($filter['created_at'])){
            $time = explode(' - ', $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("{$this->table}.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['referral_program_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_program_id',$filter['referral_program_id']);
            unset($filter['referral_program_id']);
        }

        if (isset($filter['referral_program_invite_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_program_invite_id',$filter['referral_program_invite_id']);
            unset($filter['referral_program_invite_id']);
        }

        if (isset($filter['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['status']);
            unset($filter['status']);
        }

        return $oSelect->orderBy($this->table.'.referral_program_invite_id','DESC');
    }

    public function getListCommissionOrder($filter = []){

        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.*',
                'referral_program.referral_program_id',
                'referral_program.referral_program_name',
                'referral_program.type as referral_program_type',
                'customer_buyer.full_name as customer_buyer_full_name',
                'referral_member_buyer.referral_member_id as member_buyer_referral_member_id',
            )
            ->join('referral_program','referral_program.referral_program_id',$this->table.'.referral_program_id')

            //thang gioi thieu
//            ->join('referral_member','referral_member.referral_member_id',$this->table.'.referral_member_id')
//            ->join('customers','customers.customer_id','referral_member.member_id')

            // thang mua
            ->join('customers as customer_buyer','customer_buyer.customer_id',$this->table.'.customer_id')
            ->join('referral_member as referral_member_buyer','referral_member_buyer.member_id','customer_buyer.customer_id');

        if (isset($filter['search'])){
            $oSelect = $oSelect
                ->where('customers_commission.full_name','like','%'.$filter['search'].'%')
                ->orWhere('customer_buyer.full_name','like','%'.$filter['search'].'%')
                ->orWhere($this->table.'.obj_code','like','%'.$filter['search'].'%');
        }

        if (isset($filter['created_at'])){
            $time = explode(' - ', $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("{$this->table}.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['referral_member_id'])){
            $oSelect = $oSelect->where('rpc.commission_member_id',$filter['referral_member_id']);
            unset($filter['referral_member_id']);
        }

        if (isset($filter['referral_program_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_program_id',$filter['referral_program_id']);
            unset($filter['referral_program_id']);
        }

        if (isset($filter['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['status']);
            unset($filter['status']);
        }

        $oSelect->orderBy($this->table.'.referral_program_invite_id','DESC');

        return $oSelect->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Lấy danh sách người giới thiệu cấp 1
     */
    public function getListReferralLevel(){
        return $this
            ->join('referral_member','referral_member.referral_member_id',$this->table.'.referral_member_id')
            ->join('customers','customers.customer_id','referral_member.member_id')
            ->groupBy($this->table.'.referral_member_id')
            ->orderBy($this->table.'.referral_program_invite_id','DESC')
            ->get();
    }

    /**
     * Cập nhật hoa hồng
     */
    public function updateProgramInvite($data,$id){
        return $this
            ->where('referral_program_invite_id',$id)
            ->update($data);
    }

    /**
     * Lấy chi tiết hoa hồng
     * @param $id
     */
    public function getDetail($id){
        return $this
            ->select(
                $this->table.'.*',
                'referral_program.type as referral_program_type',
                'referral_member.total_money as referral_member_total_money',
                'referral_member.total_commission as referral_member_total_commission'
            )
            ->join('referral_program', 'referral_program.referral_program_id', "{$this->table}.referral_program_id")
            ->join('referral_member','referral_member.referral_member_id',$this->table.'.referral_member_id')
            ->where($this->table.'.referral_program_invite_id',$id)
            ->first();
    }

    /**
     * Lấy chi tiết hoa hồng
     * @param $id
     */
    public function getDetailCPS($id){


        $oSelect =  $this
            ->select(
                'obj_code',
                'orders.amount as total_amount',
                'order_code',
                'orders.created_at as orders_created_at',
                'referral_program_name',
                "{$this->table}.status as invite_status",
                "{$this->table}.created_at as invite_created_at",
                "{$this->table}.approve_date as invite_approve_date",
                DB::raw('max(receipts.receipt_id) as receipt_id'),
                DB::raw('max(receipts.created_at) as receipts_created_at'),
                'invitee.full_name as invitee_full_name',
                'member_invitee.referral_member_id as invitee_referral_member_id',
                'inviter.full_name as inviter_full_name',
                'member_inviter.referral_member_id as inviter_referral_member_id',
            )
            ->join('orders', 'orders.order_id', "{$this->table}.obj_id")
            ->join('receipts', 'receipts.order_id', 'orders.order_id')
            ->join('referral_program', 'referral_program.referral_program_id', "{$this->table}.referral_program_id")
            //người mua
            ->join('customers as invitee', 'invitee.customer_id',  "{$this->table}.customer_id")
            ->join('referral_member as member_invitee','member_invitee.member_id', 'invitee.customer_id')
            //người mời
            ->join('referral_member as member_inviter','member_inviter.referral_member_id', $this->table.'.referral_member_id')
            ->join('customers as inviter', 'inviter.customer_id',  "member_inviter.member_id")



            ->where($this->table.'.referral_program_invite_id',$id);

        $oSelect->orderBy("orders.order_id", 'asc');

        $oSelect->orderBy("receipts.receipt_id",'asc');

        $oSelect->groupBy("orders.order_id");

        return $oSelect->first();
    }

    /**
     * update dựa vào member id
     */
    public function getByMemberId($referral_member_id){
        return $this
            ->join('referral_member_detail','referral_member_detail.obj_id',$this->table.'.referral_program_invite_id')
            ->where('referral_member_detail.action',$this->actionCommission)
            ->where('referral_member_detail.type',$this->typePlus)
            ->where($this->table.'.referral_member_id',$referral_member_id)
            ->get();
    }

    /**
     * update dựa vào member id
     */
    public function updateByMemberId($data,$referral_member_id){
        return $this
            ->join('referral_member_detail','referral_member_detail.obj_id',$this->table.'.referral_program_invite_id')
            ->where('referral_member_detail.action',$this->actionCommission)
            ->where('referral_member_detail.type',$this->typePlus)
            ->where($this->table.'.referral_member_id',$referral_member_id)
            ->update($data);
    }

   public function checkMoneyCommisson($id){
      $mSelect = $this
          ->select("{$this->table}.referral_program_invite_id")
      ->where("{$this->table}.referral_program_id",$id);
      return $mSelect->first();
   }

    /**
     * Cập nhật từ trạng thái waiting_payment thành payment
     * @param $data
     * @param $referralMemberId
     * @param $status
     * @return mixed
     */
   public function updateByStatusMemberId($data,$referralMemberId,$status){
        return $this
            ->where($this->table.'.referral_member_id',$referralMemberId)
            ->where($this->table.'.status',$status)
            ->update($data);
   }

}
