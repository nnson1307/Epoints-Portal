<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 19/05/2021
 * Time: 11:39
 */

namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductInventoryTable extends Model
{
    use ListTableTrait;
    protected $table = "product_inventorys";
    protected $primaryKey = "product_inventory_id";

    /**
     * Lấy danh sách sản phẩm tồn kho
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "{$this->table}.product_id",
                "pr.product_child_name as product_name",
                "{$this->table}.product_code",
                "pr.cost"
            )
            ->join("product_childs as pr", "pr.product_child_id", "=", "{$this->table}.product_id")
            ->where("{$this->table}.warehouse_id", "<>", 0)
            ->groupBy("{$this->table}.product_id");

        //Filter theo sản phẩm
        if (isset($filter['product_id']) && $filter['product_id'] != null) {
            $ds->where("{$this->table}.product_id", $filter['product_id']);
            unset($filter['product_id']);
        }

        return $ds;
    }

    /**
     * Lấy ds sản phẩm tồn kho ko phân trang
     *
     * @return mixed
     */
    public function getListProductInventory()
    {
        return $this
            ->select(
                "{$this->table}.product_id",
                "pr.product_child_name as product_name",
                "{$this->table}.product_code",
                "pr.cost"
            )
            ->join("product_childs as pr", "pr.product_child_id", "=", "{$this->table}.product_id")
            ->where("{$this->table}.warehouse_id", "<>", 0)
            ->groupBy("{$this->table}.product_id")
            ->get();
    }
}