<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 4:10 PM
 */

namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerPotentialLogTable extends Model
{
    protected $table = "customer_potential_log";
    protected $primaryKey = "customer_potential_log";

    const PRODUCT = "product";
    const LIMIT= 20;
    const NOT_DELETE = 0;

    /**
     * SP được xem nhiều nhất
     *
     * @param $time
     * @return mixed
     */
    public function getMostViewProduct($time, $productId = null)
    {
        $ds = $this
            ->select(
                "product_childs.product_child_name as product_name",
                DB::raw("count(product_childs.product_code) as total")
            )
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.obj_code")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->where("{$this->table}.type", self::PRODUCT)
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->groupBy("product_childs.product_code")
            ->orderBy('total', 'desc');

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if ($productId != null) {
            $ds->where("product_childs.product_id", $productId);
        }

        return $ds->limit(self::LIMIT)->get();
    }

    /**
     * Danh mục SP được xem nhiều nhất
     *
     * @param $time
     * @param null $productCategoryId
     * @return mixed
     */
    public function getMostViewProductCategory($time, $productCategoryId = null)
    {
        $ds = $this
            ->select(
                "product_categories.category_name",
                DB::raw("count({$this->table}.obj_code) as total")
            )
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.obj_code")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->where("{$this->table}.type", self::PRODUCT)
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->groupBy("product_categories.category_name")
            ->orderBy('total', 'desc');

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if ($productCategoryId != null) {
            $ds->where("products.product_category_id", $productCategoryId);
        }

        return $ds->limit(self::LIMIT)->get();
    }

    /**
     * Những khách hàng xem sản phẩm thuộc nhóm sản phẩm nhiều nhất
     *
     * @param $productCategoryId
     * @param $time
     * @param $isLimit
     * @return mixed
     */
    public function getCustomerByView($productCategoryId, $time, $isLimit)
    {
        $res = $this
            ->select(
                'customers.full_name',
                'customers.email',
                'customers.phone1',
                DB::raw("count(product_childs.product_code) as total")
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.obj_code")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.product_category_id", $productCategoryId)
            ->groupBy("customers.customer_id")
            ->orderBy('total', 'desc');

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $res->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if ($isLimit == false) {
            return $res->get();
        } else {
            return $res->limit(self::LIMIT)->get();
        }
    }
}