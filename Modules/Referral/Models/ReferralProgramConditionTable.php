<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MyCore\Models\Traits\ListTableTrait;


class ReferralProgramConditionTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_program_condition";
    protected $primaryKey = "referral_program_condition_id";
    public $timestamps = false;


    public function saveNewConditionCPI($data,$referral_program_id)
    {
        $dataInsert=[];
        $time_use_app =[
            'referral_program_id' => $referral_program_id,
            'key' => 'cpi.time_use_app',
            'value' => 1,
        ];
        $this->insertGetId($time_use_app);
        foreach ($data as $k=> $v){
            if($k == 'cpi_time_use_condition'){
                $k = 'cpi.time_use_condition';
            }elseif($k == 'cpi_time_use_time' ){
                $k = 'cpi.time_use_time';
            }elseif($k == 'cpi_time_use_date'){
                $k = 'cpi.time_use_date';
            }else{}
            $dataInsert=[
                'referral_program_id' => $referral_program_id,
                'key' => $k,
                'value' => $v,
            ];
            $this
                ->insertGetId($dataInsert);
        }
    }
    public function conditioncpi($id){
        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_condition_id",
                "{$this->table}.key",
                "{$this->table}.value"
            )
            ->where("{$this->table}.referral_program_id",$id);
        return $mSelect->get()->toArray();
    }
    public function checkCondition($referral_program_id){
        $mSelect = $this
            ->select( "{$this->table}.referral_program_condition_id")
            ->where("{$this->table}.referral_program_id",$referral_program_id);
        return $mSelect->get();
    }
    public function deleteOldCondition($referral_program_id){
        return $this
            ->where("{$this->table}.referral_program_id", $referral_program_id)
            ->delete();
    }
    public function saveConditionOrderPrice($dataConditionCPS){
        foreach ($dataConditionCPS as $k => $v ){
            $this->insertGetId($v);
        }
    }
}
