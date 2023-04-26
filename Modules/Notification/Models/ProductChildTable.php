<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/14/2020
 * Time: 10:48 AM
 */

namespace Modules\Notification\Models;


use Illuminate\Database\Eloquent\Model;

class ProductChildTable extends Model
{
    protected $table = "product_childs";
    protected $primaryKey = "product_child_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy danh sách sản phẩm bán hàng
     *
     * @param null $filter
     * @return array
     */
    public function getList($filter = null)
    {
        $ds = $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code",
                "{$this->table}.product_child_name as product_name",
                "{$this->table}.cost",
                "products.avatar",
                "units.name as unit_name"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->leftJoin("units", "units.unit_id", "=", "{$this->table}.unit_id")
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->orderBy("{$this->table}.created_at", "desc");

        if (isset($filter["product_name"]) && $filter["product_name"] != null) {
            $ds->where("{$this->table}.product_child_name", "like", "%". $filter["product_name"] ."%");
        }
        
        $page = (isset($filter["page"])) ? (int)$filter["page"] : 1;
        return $ds->paginate(END_POINT_PAGING,  $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Chi tiết sản phẩm
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $ds = $this
            ->select(
                "{$this->table}.product_child_id as product_id",
                "{$this->table}.product_code",
                "{$this->table}.product_child_name as product_name",
                "{$this->table}.cost",
                "products.avatar",
                "units.name as unit_name"
            )
            ->join("products", "products.product_id", "=", "{$this->table}.product_id")
            ->leftJoin("units", "units.unit_id", "=", "{$this->table}.unit_id")
            ->where("products.is_actived", self::IS_ACTIVE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.product_child_id", $id);

        return $ds->first();
    }
}