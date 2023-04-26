<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManagerWorkTagTable extends Model
{
    use ListTableTrait;
    protected $table = "manage_work_tag";
    protected $primaryKey = "manage_work_tag_id";

    /**
     * Lấy danh sách tag theo công việc
     * @param $manageWorkId
     * @return mixed
     */
    public function getListTagByWork($manageWorkId){
        $oSelect = $this
            ->select(
                $this->table.'.manage_work_tag_id',
                'manage_tags.manage_tag_id',
                'manage_tags.manage_tag_name',
                'manage_tags.manage_tag_icon',
                'manage_work_id'
            )
            ->join('manage_tags','manage_tags.manage_tag_id',$this->table.'.manage_tag_id');
        if(is_array($manageWorkId)){
            $oSelect->whereIn($this->table.'.manage_work_id',$manageWorkId);
        } else {
            $oSelect->where($this->table.'.manage_work_id',$manageWorkId);
        }

        return $oSelect->get();
    }

    /**
     * Thêm các tag theo công việc
     * @param $data
     */
    public function insertArrTag($data){
        return $this->insert($data);
    }

    public function removeWorkTag($manageWorkId){
        return $this->where('manage_work_id',$manageWorkId)->delete();
    }

    public function checkTagIsUsed($manageWorkId){
        return $this->where('manage_tag_id',$manageWorkId)->get()->count();
    }

}