<?php

namespace Modules\Referral\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use MyCore\Models\Traits\ListTableTrait;


class ReferralProgramTable extends Model
{
    use ListTableTrait;
    protected $table = "referral_program";
    protected $primaryKey = "referral_program_id";
    public $timestamps = false;

    public function getListCommission($input)
    {

        $page    = (int) ($input['page'] ?? 1);
        $display = (int) ($input['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_id",
                "{$this->table}.referral_program_name",
                "{$this->table}.type",
                "{$this->table}.updated_at",
                "{$this->table}.status",
                "{$this->table}.updated_by"
                );

        if(isset($input['referral_program_name'])){
            $mSelect = $mSelect->where($this->table.'.referral_program_name','like','%'.$input['referral_program_name'].'%');
        }

        if(isset($input['status']) && $input['status']!="all"){
            $mSelect = $mSelect->where($this->table.'.status',$input['status']);
        }

        if(isset($input['type']) && $input['type']!="all"){
            $mSelect = $mSelect->where($this->table.'.type',$input['type']);
        }

        if(isset($input['apply_for'])){
            $mSelect = $mSelect->where($this->table.'.apply_for',$input['apply_for']);
        }


        return $mSelect->orderBy("{$this->table}.updated_at", "desc")->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function checkListCommission($name)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_id"
                )
            ->where("{$this->table}.referral_program_name", $name);
        return $mSelect->get()->toArray();
    }
    public function checkTime($input){
        $mSelect = $this
            ->select(
                "{$this->table}.date_start",
                "{$this->table}.date_end"
            )
            ->where("{$this->table}.type", $input['type'])
            ->where("{$this->table}.status", __('actived'));
        return $mSelect->get()->toArray();
    }

    public function createNewCommission($input)
    {
        return $this
            ->insertGetId($input);

    }
    public function saveMoneyCommissionCPI($data,$referral_program_id)
    {
        return $this
            ->where("{$this->table}.referral_program_id", $referral_program_id)
        ->update($data);

    }
    public function getInfoById($id){
        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_name",
                "{$this->table}.type",
                "{$this->table}.referral_criteria_code",
                "{$this->table}.apply_for",
                "{$this->table}.description",
                "{$this->table}.date_start",
                "{$this->table}.date_end",
                "{$this->table}.img"

            )
            ->where( "{$this->table}.referral_program_id",$id);
        return $mSelect ->first()->toArray();
    }
    public function saveConditionOrderPrice($referral_program_id,$input){
        return $this
            ->where("{$this->table}.referral_program_id", $referral_program_id)
            ->update($input);
    }
    public function getInfoCondition($id){
        $mSelect  = $this
            -> select (
                "{$this->table}.commission_type",
                "{$this->table}.commission_value",
                "{$this->table}.commission_max_value"
            )
            ->where( "{$this->table}.referral_program_id",$id);
        return $mSelect->first()->toArray();
    }
    public function editInfoCommission($id,$params){
        return $this
            ->where("{$this->table}.referral_program_id", $id)
            ->update($params);
    }
    public function deleteCommission($id){
        return $this
            ->where("{$this->table}.referral_program_id", $id)
            ->delete();
    }
    public function getDetailCommission($id){
        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_id",
                "{$this->table}.referral_program_name",
                "{$this->table}.status",
                "{$this->table}.type",
                "{$this->table}.apply_for",
                "{$this->table}.date_start",
                "{$this->table}.date_end",
                "{$this->table}.referral_criteria_code",
                "{$this->table}.description",
                "{$this->table}.img",
                "{$this->table}.commission_type",
                "{$this->table}.commission_value",
                "{$this->table}.commission_max_value"
            )
            ->where("{$this->table}.referral_program_id",$id);
        return $mSelect->first()->toArray();
    }
    public function stateChange($referral_program_id,$statusUpdate){
        return $this
            ->where("{$this->table}.referral_program_id", $referral_program_id)
            ->update($statusUpdate);
    }
    public function getAll($filter){
        $oSelect = $this
            ->orderBy('referral_program_id','DESC');

        if(isset($filter['status']) && $filter['status'] !=''){
            $oSelect->where('status', $filter['status']);
        }

        $result = $oSelect->get();
        if($result){
            return $result;
        }

        return [];
    }
    public function checkCommission($params){
        $mSelect = $this
            ->select(
                "{$this->table}.referral_program_id"
                )
            ->where("{$this->table}.referral_program_name", $params['referral_program_name'])
            ->where("{$this->table}.referral_program_id","<>",$params['referral_program_id']);
        return $mSelect->get()->toArray();
    }
    public function timeSameType($conditionRequest){
        $mSelect = $this
            ->select(
                "{$this->table}.date_start",
                "{$this->table}.date_end",
                "{$this->table}.referral_program_name"
            )
            ->where("{$this->table}.type", $conditionRequest['type'])
            ->where("{$this->table}.status", 'approved');
        return $mSelect->get()->toArray();
    }

}
