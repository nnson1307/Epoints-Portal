<?php


namespace Modules\Referral\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCardsTable extends Model
{
    protected $table = "service_cards";
    protected  $primaryKey = "service_card_id";

    public function getListCommodity($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_id as id",
                "{$this->table}.service_card_group_id as category_id",
                "{$this->table}.name",
                "service_card_groups.name as category_name"
                )
            ->join("service_card_groups", "{$this->table}.service_card_group_id","service_card_groups.service_card_group_id")
            ->where(  "{$this->table}.service_card_group_id", $id);
        return $mSelect->get()->toArray();
    }
    public function getCommodity($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_id",
                "{$this->table}.name",
                "service_card_groups.name as category_name"
                )
            ->join("service_card_groups", "{$this->table}.service_card_group_id","service_card_groups.service_card_group_id")
            ->where(  "{$this->table}.service_card_group_id", $id);
        return $mSelect->get()->toArray();
    }
    public function getListCommodityAll3($params = []){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_id as id",
                "{$this->table}.name",
                "{$this->table}.service_card_group_id as category_id",
                 "service_card_groups.name as category_name"
            )
            ->join("service_card_groups", "{$this->table}.service_card_group_id","service_card_groups.service_card_group_id");
        if(isset($params) && $params!=[]){
            $mSelect = $mSelect ->where( "{$this->table}.service_card_group_id", $params);
        }
        return $mSelect->get()->toArray();
    }
    public function getCommodityChoose($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_id as id",
                "{$this->table}.service_card_group_id as category_id",
                "{$this->table}.name",
                "service_card_groups.name as category_name"
            )
            ->join("service_card_groups", "{$this->table}.service_card_group_id","service_card_groups.service_card_group_id")
            ->where(  "{$this->table}.service_card_id", $id);
        return $mSelect->get()->toArray();
    }
    public function getInfo($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_id as id",
                "{$this->table}.service_card_group_id as category_id",
                "{$this->table}.name",
                "service_card_groups.name as category_name"
            )
            ->join("service_card_groups", "{$this->table}.service_card_group_id","service_card_groups.service_card_group_id")
            ->where(  "{$this->table}.service_card_id", $id);
        return $mSelect->first()->toArray();
    }
    public function getInfoServiceCard($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_card_id as id",
                "{$this->table}.service_card_group_id as category_id",
                "{$this->table}.name",
                "service_card_groups.name as category_name",
                 "{$this->table}.price",
            )
            ->join("service_card_groups", "{$this->table}.service_card_group_id","service_card_groups.service_card_group_id")
            ->where(  "{$this->table}.service_card_id", $id);
        return $mSelect->first()->toArray();
    }

}