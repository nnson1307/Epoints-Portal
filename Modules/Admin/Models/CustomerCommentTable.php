<?php

namespace Modules\Admin\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerCommentTable extends Model
{
    use ListTableTrait;
    protected $table = "customers_comment";
    protected $primaryKey = "customer_comment_id";

    protected $fillable = [
        'customer_comment_id',
        'customer_id',
        'customer_parent_comment_id',
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
    public function getTotalCommentByCustomer($customer_id){
        return $this->where('customer_id',$customer_id)->count();
    }

    public function getListCommentCustomer($customer_id,$customer_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.customer_comment_id',
                $this->table.'.customer_id',
                $this->table.'.customer_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.customer_id',$customer_id);

        if ($customer_parent_comment_id != null){
            $oSelect = $oSelect
                ->where($this->table.'.customer_parent_comment_id',$customer_parent_comment_id)
                ->orderBy($this->table.'.created_at','ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table.'.customer_parent_comment_id')
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
    public function getDetail($customer_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.customer_comment_id',
                $this->table.'.customer_id',
                $this->table.'.customer_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar',
                "c.customer_id"
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->join("customers as c", "c.customer_id", "=", "{$this->table}.customer_id")
            ->where($this->table.'.customer_comment_id',$customer_comment_id);
        return $oSelect->first();
    }


}