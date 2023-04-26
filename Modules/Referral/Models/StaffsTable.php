<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class StaffsTable extends Model
{
    protected $table = "staffs";
    protected $primaryKey = "staff_id";
    protected $isActive = 1;
    protected $isDeleted = 0;

    public function getNameStaff($id){
            $mSelect = $this
                ->select(
                    "{$this->table}.full_name"
                )
            ->where( "{$this->table}.staff_id",$id);
            return $mSelect->first();
    }
    public function getFillter(){
        $mSelect = $this
            ->select(
                "{$this->table}.staff_id",
                "{$this->table}.full_name",
            );
        return $mSelect->get()->toArray();
    }


    /**
     * Lấy tất cả staff đang hoạt động
     */
    public function getAll(){
        return $this
            ->where($this->table.'.is_actived',$this->isActive)
            ->where($this->table.'.is_deleted',$this->isDeleted)
            ->get();
    }
}
