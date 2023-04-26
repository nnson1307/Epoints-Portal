<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;
use PHPUnit\Framework\Constraint\Count;


class ReferralMemberTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_member";
    protected $primaryKey = "referral_member_id";
    protected $fillable = [
        'referral_member_id',
        'referral_code',
        'member_type',
        'member_id',
        'total_money',
        'total_commission',
        'status',
        'created_at'
    ];

    public $timestamps = false;
    public $active = 'active';

    protected function _getList(&$filter = []){
        $oSelect =  $this
            ->select(
                $this->table.'.*',
                'customers.full_name as customer_full_name',
                'total_node_nearest as total_referral'
            )
            ->join('customers','customers.customer_id',$this->table.'.member_id');

        if (isset($filter['search'])){
            $search = $filter['search'];
            $oSelect = $oSelect->where(function ($sql) use ($search){
                $sql
                    ->where($this->table.'.referral_code','like','%'.$search.'%')
                    ->orWhere('customers.full_name','like','%'.$search.'%')
                    ->orWhere('customers.phone1','like','%'.$search.'%');
            });
            unset($filter['search']);
        }

        if (isset($filter['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['status']);
            unset($filter['status']);
        }

        if (isset($filter['created_at'])){
            $time = explode(' - ', $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween("{$this->table}.created_at", [$startTime, $endTime]);
        }

        if (isset($filter['member_type'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['member_type']);
            unset($filter['member_type']);
        }

        if (isset($filter['referral_member_id'])){
            $oSelect = $oSelect->where($this->table.'.referral_member_id',$filter['referral_member_id']);
            unset($filter['referral_member_id']);
        }

        return $oSelect->orderBy($this->table.'.referral_member_id','DESC')->groupBy($this->table.'.referral_member_id');
    }

    public function listChild($filter){
        $oSelect =  $this
            ->select(
                $this->table.'.*',
                'customers.customer_avatar',
                'customers.full_name',
                'total_node_nearest as total_referral'
            )
            ->join('customers','customers.customer_id',$this->table.'.member_id');

        if (isset($filter['search'])){
            $search = $filter['search'];
            $oSelect = $oSelect->where(function ($sql) use ($search){
                $sql
                    ->where($this->table.'.referral_code','like','%'.$search.'%')
                    ->orWhere('customers.full_name','like','%'.$search.'%')
                    ->orWhere('customers.phone1','like','%'.$search.'%');
            });
            unset($filter['search']);
        }

        if (isset($filter['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['status']);
            unset($filter['status']);
        }

        if (isset($filter['created_at'])){
            $time = explode(' - ', $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween($this->table.".created_at", [$startTime, $endTime]);
        }

        if (isset($filter['member_type'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['member_type']);
            unset($filter['member_type']);
        }

//        dd($oSelect->get()->toArray());
//
        if (isset($filter['referral_member_id'])){
            $oSelect = $oSelect->where($this->table.'.parent_id', $filter['referral_member_id']);
            unset($filter['referral_member_id']);
        }

        return $oSelect
            ->orderBy($this->table.'.referral_member_id','DESC')
            ->groupBy($this->table.'.referral_member_id')
            ->get();
    }

    /**
     * Danh sách người được giới thiệu
     * @param array $filter
     * @return mixed
     */
    public function listRefferal(&$filter = []){
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect =  $this
            ->select(
                $this->table.'.*',
                'customers.full_name as customer_full_name',
                'total_node_nearest as total_referral'
            )
            ->join('customers','customers.customer_id',$this->table.'.member_id');

        if (isset($filter['search'])){
            $search = $filter['search'];
            $oSelect = $oSelect->where(function ($sql) use ($search){
                $sql
                    ->where($this->table.'.referral_code','like','%'.$search.'%')
                    ->orWhere('customers.full_name','like','%'.$search.'%')
                    ->orWhere('customers.phone1','like','%'.$search.'%');
            });
            unset($filter['search']);
        }

        if (isset($filter['status'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['status']);
            unset($filter['status']);
        }

        if (isset($filter['created_at'])){
            $time = explode(' - ', $filter['created_at']);
            $startTime = Carbon::createFromFormat('d/m/Y', $time[0])->format('Y-m-d 00:00:00');
            $endTime = Carbon::createFromFormat('d/m/Y', $time[1])->format('Y-m-d 23:59:59');
            $oSelect->whereBetween($this->table.".created_at", [$startTime, $endTime]);
        }

        if (isset($filter['member_type'])){
            $oSelect = $oSelect->where($this->table.'.status',$filter['member_type']);
            unset($filter['member_type']);
        }

//        dd($oSelect->get()->toArray());
//
        if (isset($filter['referral_member_id'])){
            $oSelect = $oSelect->where($this->table.'.parent_id', $filter['referral_member_id']);
            unset($filter['referral_member_id']);
        }

        return $oSelect
            ->orderBy($this->table.'.referral_member_id','DESC')->groupBy($this->table.'.referral_member_id')
            ->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * lấy danh sách theo mảng id
     * @param $arrId
     * @return mixed
     */
    public function getListByArrId($arrId){
        return $this
            ->whereIn('referral_member_id',$arrId)
            ->get();
    }

    /**
     * Cập nhật member theo id
     * @param $data
     * @param $id
     */
    public function updateMember($data,$id){
        return $this
            ->where('referral_member_id',$id)
            ->update($data);
    }

    /**
     * lấy chi tiết member
     * @param $id
     */
    public function getDetail($id){
        return $this
            ->where('referral_member_id',$id)
            ->first();
    }

    /**
     * lấy chi tiết member
     * @param $id
     */
    public function getDetailCustomer($id){
        return $this
            ->select(
                $this->table.'.*',
                'customers.full_name',
                'customers.birthday',
                'customers.phone1',
                'customers.address',
                'customers.email',
                'customers.customer_avatar',
                'ward.name as ward_name',
                'ward.type as ward_type',
                'district.name as district_name',
                'district.type as district_type',
                'province.name as province_name',
                'province.type as province_type',
            )
            ->leftJoin('customers','customers.customer_id',$this->table.'.member_id')
            ->leftJoin('ward','ward.ward_id','customers.ward_id')
            ->leftJoin('district','district.districtid','customers.district_id')
            ->leftJoin('province','province.provinceid','customers.province_id')
            ->where($this->table.'.referral_member_id',$id)
            ->first();
    }

    /**
     * Lấy danh sách
     */
    public function getAll(){
        return $this
            ->join('customers','customers.customer_id',$this->table.'.member_id')
            ->where($this->table.'.status',$this->active)
            ->groupBy('customers.customer_id')
            ->get();
    }

    /**
     * Lấy tổng số người đã giới thiệu
     * @param $referralMemberId
     */
    public function getTotalRefer($referralMemberId){
        return $this
            ->where('parent_member_id',$referralMemberId)
            ->count();
    }


    public function getDetailInvite($id){
        return $this
            ->select(
                "{$this->table}.*",
                "customers.*",
                'parent_id as inviter_member_id'
            )
            ->join('customers','customers.customer_id',$this->table.'.parent_member_id')
            ->where($this->table.'.referral_member_id',$id)
            ->first();
    }
}
