<?php

namespace Modules\CustomerLead\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLeadCommentTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead_comment";
    protected $primaryKey = "customer_lead_comment_id";

    protected $fillable = [
        'customer_lead_comment_id',
        'customer_lead_id',
        'customer_lead_parent_comment_id',
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
    public function getTotalCommentByCustomer($customer_lead_id){
        return $this->where('customer_lead_id',$customer_lead_id)->count();
    }

    public function getListCommentCustomer($customer_lead_id,$customer_lead_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.customer_lead_comment_id',
                $this->table.'.customer_lead_id',
                $this->table.'.customer_lead_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.customer_lead_id',$customer_lead_id);

        if ($customer_lead_parent_comment_id != null){
            $oSelect = $oSelect
                ->where($this->table.'.customer_lead_parent_comment_id',$customer_lead_parent_comment_id)
                ->orderBy($this->table.'.created_at','ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table.'.customer_lead_parent_comment_id')
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
    public function getDetail($customer_lead_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.customer_lead_comment_id',
                $this->table.'.customer_lead_id',
                $this->table.'.customer_lead_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "c.customer_lead_id"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join("cpo_customer_lead as c", "c.customer_lead_id", "=", "{$this->table}.customer_lead_id")
            ->where($this->table.'.customer_lead_comment_id',$customer_lead_comment_id);
        return $oSelect->first();
    }


}