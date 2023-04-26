<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ReferralConfigTable extends Model
{
    protected $table = "referral_config";
    protected $primaryKey = "referral_config_id";
    public $timestamps = false;

    public function getInfoOld(){
        $mSelect = $this
            ->select(
                "{$this->table}.referral_config_id",
                "{$this->table}.config_description",
                "{$this->table}.config_code",
                "{$this->table}.config_code_type",
                "{$this->table}.config_number_random",
                "{$this->table}.date_auto_confirm",
                "{$this->table}.payment_cycle_type",
                "{$this->table}.payment_cycle_type",
                "{$this->table}.payment_cycle_value",
            )
        ->orderBy("{$this->table}.referral_config_id", "desc");
        return $mSelect->first()->toArray();
    }
    public function getInfoOldById($id){
        $mSelect = $this
            ->select(
                "{$this->table}.referral_config_id",
                "{$this->table}.config_description",
                "{$this->table}.config_code",
                "{$this->table}.config_code_type",
                "{$this->table}.config_number_random",
                "{$this->table}.date_auto_confirm",
                "{$this->table}.payment_cycle_type",
                "{$this->table}.payment_cycle_type",
                "{$this->table}.payment_cycle_value",
                )
            ->where("{$this->table}.referral_config_id", $id);
        return $mSelect->first();
    }
    //luu chinh sua cau hinh chung
    public function saveGeneralConfig($input){
        return $this
            ->insertGetId($input);
    }
    ///update trang thai cau hinh chung truoc do
    public function updateOldGeneralConfig($oldID,$dataUpdate){
        return $this
            ->where("{$this->table}.referral_config_id",$oldID)
            ->update($dataUpdate);

    }
    public function getHistoryGeneralConfig($params){
        $page    = (int) ($params['page'] ?? 1);
        $display = (int) ($params['perpage'] ?? PAGING_ITEM_PER_PAGE);
        $mSelect = $this
            ->select(
                "{$this->table}.referral_config_id",
                "{$this->table}.config_description",
                "{$this->table}.start",
                "{$this->table}.end",
                "{$this->table}.created_at",
                "{$this->table}.payment_cycle_status",
                "{$this->table}.created_by",
            );
        if(isset($params['created_by'])){
            $mSelect = $mSelect->where($this->table.'.created_by',$params['created_by']);
        }

        if(isset($params['status'])){
            $mSelect = $mSelect->where($this->table.'.payment_cycle_status',$params['status']);
        }
        if(isset($params['start'])){
            $mSelect = $mSelect->whereBetween($this->table.'.start',[$params['date_start_0'] . " 00:00:00", $params['date_start_1'] . " 23:59:59"]);
        }
        if(isset($params['end'])){
            $mSelect = $mSelect->whereBetween($this->table.'.end',[$params['date_end_0'] . " 00:00:00", $params['date_end_1'] . " 23:59:59"]);
        }

        return $mSelect->orderBy("{$this->table}.referral_config_id", "desc")->paginate($display, $columns = ['*'], $pageName = 'page', $page);

    }
}
