<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 31/08/2021
 * Time: 09:54
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ProductTagMapTable extends Model
{
    protected $table = "product_tag_map";
    protected $primaryKey = "product_tag_map_id";

    /**
     * Lấy dữ liệu tag map
     *
     * @param $productChildId
     * @return mixed
     */
    public function getMapByProduct($productChildId)
    {
        return $this->where("product_child_id", $productChildId)->get();
    }

    /**
     * Xoá tag map
     *
     * @param $productChildId
     * @return mixed
     */
    public function removeMapByProduct($productChildId)
    {
        return $this->where("product_child_id", $productChildId)->delete();
    }
}