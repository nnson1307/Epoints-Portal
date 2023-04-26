<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 14:37
 */

namespace Modules\ManagerProject\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManageProjectCommentTable extends Model
{
    use ListTableTrait;
    protected $table = "manage_project_comment";
    protected $primaryKey = "manage_project_comment_id";
    protected $fillable = [
        "manage_project_comment_id",
        "manage_project_id",
        "parent_id",
        "staff_id",
        "message",
        "path",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    public function getListCommentWork($manage_project_id,$manage_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.manage_project_comment_id',
                $this->table.'.manage_project_id',
                $this->table.'.parent_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_project_id',$manage_project_id);

        if ($manage_parent_comment_id != null){
            $oSelect = $oSelect
                ->where($this->table.'.parent_id',$manage_parent_comment_id)
                ->orderBy($this->table.'.created_at','ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table.'.parent_id')
                ->orderBy($this->table.'.created_at','DESC');
        }

        return $oSelect->get();
    }

    public function getListCommentProject($manage_project_id,$manage_parent_comment_id = null){
        $oSelect = $this
            ->select(
                $this->table.'.manage_project_comment_id',
                $this->table.'.manage_project_id',
                $this->table.'.parent_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_project_id',$manage_project_id);

        if ($manage_parent_comment_id != null){
            $oSelect = $oSelect
                ->where($this->table.'.parent_id',$manage_parent_comment_id)
                ->orderBy($this->table.'.created_at','ASC');
        } else {
            $oSelect = $oSelect
                ->whereNull($this->table.'.parent_id')
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
    public function getDetail($manage_project_comment_id){
        $oSelect = $this
            ->select(
                $this->table.'.manage_project_comment_id',
                $this->table.'.manage_project_id',
                $this->table.'.parent_id',
                $this->table.'.staff_id',
                $this->table.'.message',
                $this->table.'.path',
                $this->table.'.created_at',
                'staffs.full_name as staff_name',
                'staffs.staff_avatar'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id')
            ->where($this->table.'.manage_project_comment_id',$manage_project_comment_id);
        return $oSelect->first();
    }
}