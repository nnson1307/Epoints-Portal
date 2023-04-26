<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class ReferralProgramLogTable extends Model
{
    protected $table = "referral_program_log";
    protected $primaryKey = "referral_program_log_id";

   public function saveLogCreateCommission($dataLog){
       return $this
           ->insertGetId($dataLog);
   }
    public function getLog($params){

       $mSelect = $this
           ->select(
            "{$this->table}.content",
            "{$this->table}.created_at",
            "{$this->table}.staff_id",
           )
           ->where( "{$this->table}.referral_program_id",$params['referral_program_id'] )
           ->orderBy( "{$this->table}.referral_program_log_id" ,'desc' );

        if(isset($params['perfomer'])){
                $mSelect = $mSelect->where($this->table.'.staff_id',$params['perfomer']);
            }
        if(isset($params['created_at'])){
            $mSelect = $mSelect->whereBetween($this->table.'.created_at',[$params['dateSearch_from'] . " 00:00:00", $params['dateSearch_to'] . " 23:59:59"]);
        }
       return $mSelect->get()->toArray();
    }
}
