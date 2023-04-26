<?php


namespace Modules\Referral\Models;


use Illuminate\Database\Eloquent\Model;

class ProductsTable extends Model
{
    protected $table = "products";
    protected $primaryKey = "product_id";

    public function getListCommodityAll1($params = []){
        $mSelect = $this
            ->select(
                "{$this->table}.product_id as id",
                "{$this->table}.product_category_id as category_id",
                "{$this->table}.product_name as name" ,
                "product_categories.category_name"
            )
            ->join("product_categories","{$this->table}.product_category_id","product_categories.product_category_id");
        if(isset($params) && $params!=[]){
            $mSelect = $mSelect ->where( "{$this->table}.product_category_id", $params);
        }
        return $mSelect->get()->toArray();
    }
    public function getCommodityChoose($id){
        $mSelect = $this
            ->select(
                "{$this->table}.product_id as id",
                "{$this->table}.product_category_id as category_id",
                "{$this->table}.product_name as name" ,
                "product_categories.category_name"
            )
            ->join("product_categories","{$this->table}.product_category_id","product_categories.product_category_id")
        ->where(  "{$this->table}.product_id", $id);
        return $mSelect->get()->toArray();
    }
    public function getInfo($id){
        $mSelect = $this
            ->select(
                "{$this->table}.product_id as id",
                "{$this->table}.product_category_id as category_id",
                "{$this->table}.product_name as name" ,
                "product_categories.category_name"
            )
            ->join("product_categories","{$this->table}.product_category_id","product_categories.product_category_id")
            ->where(  "{$this->table}.product_id", $id);
        return $mSelect->first()->toArray();
    }
    public function getInfoProduct($id){
        $mSelect = $this
            ->select(
                "{$this->table}.product_id as id",
                "{$this->table}.product_category_id as category_id",
                "{$this->table}.product_name as name" ,
                "product_categories.category_name",
                "{$this->table}.cost" ,
                "{$this->table}.price_standard as price " ,
            )
            ->join("product_categories","{$this->table}.product_category_id","product_categories.product_category_id")
            ->where(  "{$this->table}.product_id", $id);
        return $mSelect->first()->toArray();

    }


}