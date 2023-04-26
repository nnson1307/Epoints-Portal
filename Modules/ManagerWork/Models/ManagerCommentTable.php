<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManagerCommentTable extends Model
{
    use ListTableTrait;
    protected $table = "manage_comment";
    protected $primaryKey = "manage_comment_id";

    protected $fillable = [
        'manage_comment_id',
        'manage_work_id',
        'manage_parent_comment_id',
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
     * @param $manage_work_id
     */
    public function getTotalCommentByWork($manage_work_id){
        return $this->where('manage_work_id',$manage_work_id)->count();
    }

    public function getListCommentWork($manage_work_id,$manage_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.manage_comment_id',
                $this->table.'.manage_work_id',
                $this->table.'.manage_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_work_id',$manage_work_id);

        if ($manage_parent_comment_id != null){
            $oSelect = $oSelect
                ->where($this->table.'.manage_parent_comment_id',$manage_parent_comment_id)
                ->orderBy($this->table.'.created_at','ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table.'.manage_parent_comment_id')
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
     * @param $manage_work_id
     * @param null $manage_comment_id
     * @return mixed
     */
    public function getDetail($manage_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.manage_comment_id',
                $this->table.'.manage_work_id',
                $this->table.'.manage_parent_comment_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_comment_id',$manage_comment_id);
        return $oSelect->first();
    }


}