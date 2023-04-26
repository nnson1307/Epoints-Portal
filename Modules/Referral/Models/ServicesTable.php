<?php


namespace Modules\Referral\Models;


use Illuminate\Database\Eloquent\Model;

class ServicesTable extends Model
{
    protected $table = "services";
    protected $primaryKey = "service_id";

    public function getListCommodity($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_id as id",
                "{$this->table}.service_category_id as category_id",
                "{$this->table}.service_name as name",
                "service_categories.name as category_name"
            )
            ->join("service_categories","{$this->table}.service_category_id","service_categories.service_category_id")
            ->where(  "{$this->table}.service_category_id", $id);
        return $mSelect->get()->toArray();

    }
    public function getCommodity($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_id as id",
                "{$this->table}.service_name as name",
                "service_categories.name as category_name"
            )
            ->join("service_categories","{$this->table}.service_category_id","service_categories.service_category_id")
            ->where( "{$this->table}.service_category_id",$id);
        return $mSelect->get()->toArray();

    }
    public function getListCommodityAll2($params =[]){
        $mSelect = $this
            ->select(
                "{$this->table}.service_id as id",
                "{$this->table}.service_name as name",
                "{$this->table}.service_category_id as category_id",
                "service_categories.name as category_name"
            )
            ->join("service_categories", "{$this->table}.service_category_id","service_categories.service_category_id");
        if(isset($params) && $params!=[]){
            $mSelect = $mSelect ->where( "{$this->table}.service_category_id", $params);
        }
        return $mSelect->get()->toArray();
    }
    public function getCommodityChoose($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_id as id",
                "{$this->table}.service_category_id as category_id",
                "{$this->table}.service_name as name",
                "service_categories.name as category_name"
            )
            ->join("service_categories","{$this->table}.service_category_id","service_categories.service_category_id")
            ->where( "{$this->table}.service_id",$id);
        return $mSelect->get()->toArray();

    }
    public function getInfo($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_id as id",
                "{$this->table}.service_category_id as category_id",
                "{$this->table}.service_name as name",
                "service_categories.name as category_name"
            )
            ->join("service_categories","{$this->table}.service_category_id","service_categories.service_category_id")
            ->where( "{$this->table}.service_id",$id);
        return $mSelect->first()->toArray();

    }
    public function getInfoService($id){
        $mSelect = $this
            ->select(
                "{$this->table}.service_id as id",
                "{$this->table}.service_category_id as category_id",
                "{$this->table}.service_name as name",
                "service_categories.name as category_name",
                "{$this->table}.price_standard as price",
            )
            ->join("service_categories","{$this->table}.service_category_id","service_categories.service_category_id")
            ->where( "{$this->table}.service_id",$id);
        return $mSelect->first()->toArray();

    }

}