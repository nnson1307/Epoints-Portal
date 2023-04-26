<?php

namespace Modules\CustomerLead\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerDealCommentTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_deals_comment";
    protected $primaryKey = "deal_comment_id";

    protected $fillable = [
        'deal_comment_id',
        'deal_id',
        'deal_parent_comment_id',
        'staff_id',
        'message',
        'path',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    /**
     * Lấy tổng comment theo công việc
     * @param $ticket_work_id
     */
    public function getTotalCommentByCustomer($deal_id){
        return $this->where('deal_id',$deal_id)->count();
    }

    public function getListCommentCustomer($deal_id,$deal_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.deal_comment_id',
                $this->table.'.deal_id',
                $this->table.'.deal_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.deal_id',$deal_id);

        if ($deal_parent_comment_id != null){
            $oSelect = $oSelect
                ->where($this->table.'.deal_parent_comment_id',$deal_parent_comment_id)
                ->orderBy($this->table.'.created_at','ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table.'.deal_parent_comment_id')
                ->orderBy($this->table.'.created_at','DESC');
        }

        return $oSelect->get();
    }

    /**
     * Tạo comment
     * @param $data
     */
    public function createdComment($data){
        return $this->insertGetId($data);
    }

    /**
     * Chi tiết comment
     * @return mixed
     */
    public function getDetail($deal_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.deal_comment_id',
                $this->table.'.deal_id',
                $this->table.'.deal_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "c.deal_id"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join("cpo_deals as c", "c.deal_id", "=", "{$this->table}.deal_id")
            ->where($this->table.'.deal_comment_id',$deal_comment_id);
        return $oSelect->first();
    }


}