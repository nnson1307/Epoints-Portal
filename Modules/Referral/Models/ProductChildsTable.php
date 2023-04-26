<?php


namespace Modules\Referral\Models;


use Illuminate\Database\Eloquent\Model;

class ProductChildsTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    public function getListCommodity($id){
            $mSelect = $this
                ->select(
                    "products.product_id as id",
                    "product_categories.product_category_id as category_id",
                    "products.product_name as name",
                    "product_categories.category_name"
                )
                ->join("products","{$this->table}.product_id","products.product_id")
                ->join("product_categories","products.product_category_id","product_categories.product_category_id")
                ->where("products.product_category_id",$id);

            return $mSelect->get()->toArray();

    }
    public function getCommodity($id){
        $mSelect = $this
            ->select(
                "products.product_id",
                "products.product_name",
                "product_categories.category_name",
                )
            ->join("products","{$this->table}.product_id","products.product_id")
            ->join("product_categories","products.product_category_id","product_categories.product_category_id")
            ->where("products.product_category_id",$id);
        return $mSelect->get()->toArray();
    }



}