<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 10/8/2020
 * Time: 3:17 PM
 */

namespace Modules\Report\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class OrderDetailTable extends Model
{
    use ListTableTrait;
    protected $table = "order_details";
    protected $primaryKey = "order_detail_id";

    const PRODUCT = "product";
    const LIMIT = 20;
    const NOT_DELETE = 0;
    const ID_CUSTOMER_CURRENT = 1;
    const PAY_SUCCESS = 'paysuccess';
    const ARR_PAY_SUCCESS = ['paysuccess', 'pay-half'];
    const IS_SURCHARGE = 1;
    const NOT_SURCHARGE = 0;
    const ARR_STATISTICS_BRANCH = ['product', 'service', 'service_card'];


    /**
     * SP được mua nhiều nhất
     *
     * @param $time
     * @return mixed
     */
    public function getProductBuyTheMost($time, $proudctId = null)
    {
        $ds = $this
            ->select(
                "product_childs.product_child_name as product_name",
                DB::raw("SUM({$this->table}.quantity) as total")
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->where("{$this->table}.object_type", self::PRODUCT)
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->groupBy("product_childs.product_code")
            ->orderBy('total', 'desc');

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("orders.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if ($proudctId != null) {
            $ds->where("product_childs.product_id", $proudctId);
        }

        return $ds->limit(self::LIMIT)->get();
    }

    /**
     * Danh mục SP được mua nhiều nhất
     *
     * @param $time
     * @param null $productCategoryId
     * @return mixed
     */
    public function getProductCategoryBuyTheMost($time, $productCategoryId = null)
    {
        $ds = $this
            ->select(
                "product_categories.category_name",
                DB::raw("SUM({$this->table}.quantity) as total")
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("product_childs", "product_childs.product_code", "=", "{$this->table}.object_code")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->join("product_categories", "product_categories.product_category_id", "=", "products.product_category_id")
            ->where("{$this->table}.object_type", self::PRODUCT)
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("product_categories.is_deleted", self::NOT_DELETE)
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->groupBy("product_categories.category_name")
            ->orderBy('total', 'desc');

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("orders.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if ($productCategoryId != null) {
            $ds->where("products.product_category_id", $productCategoryId);
        }

        return $ds->limit(self::LIMIT)->get();
    }

    /**
     * Lấy tên object + tổng giá object
     *
     * @param $arrOrderId
     * @param $objectType
     * @param $numberObject
     * @return mixed
     */
    public function getObjectAndSumAmountObject($arrOrderId, $objectType, $numberObject)
    {
        if ($objectType == 'product') {
            $select = $this->select(
                "pc.product_child_name as obj_name",
                DB::raw("SUM({$this->table}.amount * {$this->table}.quantity) as total_obj_amount")
            )
                ->join("product_childs as pc", "pc.product_code", "=", "{$this->table}.object_code");
        } elseif ($objectType == 'service') {
            $select = $this->select(
                "s.service_name as obj_name",
                DB::raw("SUM({$this->table}.amount * {$this->table}.quantity) as total_obj_amount")
            )
                ->join("services as s", "s.service_code", "=", "{$this->table}.object_code");
        } else {
            $select = $this->select(
                "sc.name as obj_name",
                DB::raw("SUM({$this->table}.amount * {$this->table}.quantity) as total_obj_amount")
            )
                ->join("service_cards as sc", "sc.service_card_id", "=", "{$this->table}.object_id");
        }
        $select->where("{$this->table}.object_type", $objectType)
            ->whereIn("{$this->table}.order_id", $arrOrderId)
            ->groupBy("{$this->table}.object_code")
            ->orderBy("total_obj_amount", "DESC")
            ->limit($numberObject);
        return $select->get();
    }

    /**
     * Tất cả order detail theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getAllDetailByFilter($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            "{$this->table}.object_id as object_id",
            "{$this->table}.object_type as object_type",
            "{$this->table}.quantity as quantity",
            "{$this->table}.order_id as order_id",
            "orders.customer_id as customer_id",
            "orders.branch_id as branch_id",
            "{$this->table}.created_at as created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        return $select->get();
    }

    /**
     * Tất cả order detail có sử dụng voucher theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getObjectHaveUseVoucher($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            "{$this->table}.object_id as object_id",
            "{$this->table}.object_type as object_type",
            "{$this->table}.quantity as quantity",
            "{$this->table}.order_id as order_id",
            "orders.customer_id as customer_id",
            "orders.branch_id as branch_id",
            "{$this->table}.created_at as created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->whereNotNull("{$this->table}.voucher_code")
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        return $select->get();
    }

    /**
     * Lấy số lượng sản phẩm theo object theo chi nhánh
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $objectType
     * @return mixed
     */
    public function getQuantityGroupObject($startTime, $endTime, $branchId, $objectType)
    {
        if ($objectType == 'product') {
            $select = $this->select(
                "p_cat.category_name as product_category_name",
                "p_cat.product_category_id as product_category_id",
                DB::raw("SUM({$this->table}.quantity) as quantity")
            )
                ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
                ->join("product_childs as pc", "pc.product_code", "=", "{$this->table}.object_code")
                ->join("products", "products.product_id", "=", "pc.product_id")
                ->join("product_categories as p_cat", "products.product_category_id", "=", "p_cat.product_category_id")
                ->where("{$this->table}.object_type", $objectType)
                ->where("orders.process_status", "paysuccess")
                ->where("orders.is_deleted", self::NOT_DELETE)
                ->where("{$this->table}.is_deleted", self::NOT_DELETE)
                ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            if ($branchId != null) {
                $select->where("orders.branch_id", $branchId);
            }
            return $select->groupBy("p_cat.product_category_id")->get();
        } elseif ($objectType == 'service') {
            $select = $this->select(
                "scat.name as service_category_name",
                "scat.service_category_id as service_category_id",
                DB::raw("SUM({$this->table}.quantity) as quantity")
            )
                ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
                ->join("services", "services.service_code", "=", "{$this->table}.object_code")
                ->join("service_categories as scat", "scat.service_category_id", "=", "services.service_category_id")
                ->where("{$this->table}.object_type", $objectType)
                ->where("orders.process_status", "paysuccess")
                ->where("orders.is_deleted", self::NOT_DELETE)
                ->where("{$this->table}.is_deleted", self::NOT_DELETE)
                ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            if ($branchId != null) {
                $select->where("orders.branch_id", $branchId);
            }
            return $select->groupBy("scat.service_category_id")->get();
        } else {
            $select = $this->select(
                "scg.name as service_card_group_name",
                "scg.service_card_group_id as service_card_group_id",
                DB::raw("SUM({$this->table}.quantity) as quantity")
            )
                ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
                ->join("service_cards as sc", "sc.service_card_id", "=", "{$this->table}.object_id")
                ->join("service_card_groups as scg", "scg.service_card_group_id", "=", "sc.service_card_group_id")
                ->where("{$this->table}.object_type", $objectType)
                ->where("orders.process_status", "paysuccess")
                ->where("orders.is_deleted", self::NOT_DELETE)
                ->where("{$this->table}.is_deleted", self::NOT_DELETE)
                ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

            if ($branchId != null) {
                $select->where("orders.branch_id", $branchId);
            }
            return $select->groupBy("scg.service_card_group_id")->get();
        }
    }

    /**
     * Số lượng sản phẩm theo nhóm khách hàng, không tính khách hàng vãng lai
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getQuantityObjectGroupCustomer($startTime, $endTime, $branchId)
    {
        if (Auth::user()->is_admin != 1) {
            $branchId = Auth::user()->branch_id;
        }
        $select = $this
            ->select(
                "customer_groups.group_name as customer_group_name",
                "customer_groups.customer_group_id as customer_group_id",
                DB::raw("SUM({$this->table}.quantity) as quantity")
            )
            ->join("orders", "orders.order_id", '=', "{$this->table}.order_id")
            ->join("customers", "customers.customer_id", "=", "orders.customer_id")
            ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("orders.process_status", 'paysuccess')
            ->where("customers.customer_id", '<>', self::ID_CUSTOMER_CURRENT)// id khách hàng vãng lai
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchId != null) {
            $select->where('orders.branch_id', $branchId);
        }
        return $select->groupBy("customer_groups.customer_group_id")->get();
    }

    /**
     * Số lượng sản phẩm của khách hàng vãng lai
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getQuantityObjectByCustomerCurrent($startTime, $endTime, $branchId)
    {
        if (Auth::user()->is_admin != 1) {
            $branchId = Auth::user()->branch_id;
        }
        $select = $this
            ->select(
                DB::raw("SUM({$this->table}.quantity) as quantity")
            )
            ->join("orders", "orders.order_id", '=', "{$this->table}.order_id")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("orders.process_status", 'paysuccess')
            ->where("orders.customer_id", self::ID_CUSTOMER_CURRENT)// id khách hàng vãng lai
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchId != null) {
            $select->where('orders.branch_id', $branchId);
        }
        return $select->first();
    }

    /**
     *
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @param $objectType
     * @return mixed
     */
    public function getAllDetailByFilterAndObjectType($startTime, $endTime, $objectId, $objectType)
    {
        $select = $this->select(
            "{$this->table}.object_id as object_id",
            "{$this->table}.object_type as object_type",
            "{$this->table}.quantity as quantity",
            "{$this->table}.order_id as order_id",
            "orders.customer_id as customer_id",
            "orders.branch_id as branch_id",
            "{$this->table}.created_at as created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where("{$this->table}.object_type", $objectType)
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->get();
    }

    /**
     * Lấy số lượng thẻ dịch vụ gom nhóm theo nhóm thẻ dịch vụ
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @return mixed
     */
    public function getQuantityServiceCardGroupByServiceCardGroup($startTime, $endTime, $objectId)
    {
        $select = $this->select(
            DB::raw("SUM({$this->table}.quantity) as quantity"),
            "scg.name as object_name"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("service_cards as sc", "sc.service_card_id", "=", "{$this->table}.object_id")
            ->join("service_card_groups as scg", "scg.service_card_group_id", "=", "sc.service_card_group_id")
            ->where("{$this->table}.object_type", 'service_card')
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->groupBy("sc.service_card_group_id")->get();
    }

    /**
     * Lấy số lượng thẻ dịch vụ (thẻ dịch vụ, sản phẩm) gom nhóm theo nhóm khách hàng (không tính khách hàng vãng lai)
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @param $objectType
     * @return mixed
     */
    public function getQuantityObjectGroupByCustomerGroup($startTime, $endTime, $objectId, $objectType)
    {
        $select = $this->select(
            DB::raw("SUM({$this->table}.quantity) as quantity"),
            "cg.group_name as object_name"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("customers", "customers.customer_id", "=", "orders.customer_id")
            ->join("customer_groups as cg", "cg.customer_group_id", "=", "customers.customer_group_id")
            ->where("{$this->table}.object_type", $objectType)
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->groupBy("cg.customer_group_id")->get();
    }

    /**
     * Lấy số lượng thẻ dịch vụ (dịch vụ, sản phẩm) của khách hàng vãng lai
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @param $objectType
     * @return mixed
     */
    public function getQuantityObjectGroupByCustomerGroupCurrent($startTime, $endTime, $objectId, $objectType)
    {
        $select = $this->select(
            DB::raw("SUM({$this->table}.quantity) as quantity")
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("customers", "customers.customer_id", "=", "orders.customer_id")
            ->where("{$this->table}.object_type", $objectType)
            ->whereNull("customers.customer_group_id")
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->first();
    }

    /**
     * Lấy số lượng thẻ dịch vụ gom nhóm theo chi nhánh
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @return mixed
     */
    public function getQuantityServiceCardGroupByBranch($startTime, $endTime, $objectId)
    {
        $select = $this->select(
            DB::raw("SUM({$this->table}.quantity) as quantity"),
            "branches.branch_name as object_name"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("{$this->table}.object_type", 'service_card')
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->groupBy("branches.branch_id")->get();
    }

    /**
     * Lấy số lượng dịch vụ gom nhóm theo nhóm dịch vụ
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @return mixed
     */
    public function getQuantityServiceGroupByServiceCategory($startTime, $endTime, $objectId)
    {
        $select = $this->select(
            DB::raw("SUM({$this->table}.quantity) as quantity"),
            "scat.name as object_name"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("services as s", "s.service_id", "=", "{$this->table}.object_id")
            ->join("service_categories as scat", "scat.service_category_id", "=", "s.service_category_id")
            ->where("{$this->table}.object_type", 'service')
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->groupBy("scat.service_category_id")->get();
    }

    /**
     * Lấy số lượng dịch vụ gom nhóm theo chi nhánh
     *
     * @param $startTime
     * @param $endTime
     * @param $objectId
     * @return mixed
     */
    public function getQuantityServiceGroupByBranch($startTime, $endTime, $objectId)
    {
        $select = $this->select(
            DB::raw("SUM({$this->table}.quantity) as quantity"),
            "branches.branch_name as object_name"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("{$this->table}.object_type", 'service')
            ->where("orders.process_status", "paysuccess")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($objectId != null) {
            $select->where("{$this->table}.object_id", $objectId);
        }
        return $select->groupBy("branches.branch_id")->get();
    }

    /**
     * Lấy doanh thu nhân viên phục vụ
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getRevenueServiceStaff($startTime, $endTime, $branchId, $limit)
    {
        $ds = $this
            ->select(
                "{$this->table}.order_id",
                "order_details.amount",
                "{$this->table}.staff_id",
                "branches.branch_name"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->whereIn("orders.process_status", self::ARR_PAY_SUCCESS)
            ->where("branches.is_deleted", self::NOT_DELETE)
            ->whereNotNull("{$this->table}.staff_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);

        if ($branchId != null) {
            $ds->where("orders.branch_id", $branchId);
        }

        return $ds->get();
    }

    /**
     * Danh sách chi tiết của báo cáo nv phục vụ
     *
     * @param array $filter
     * @return mixed
     */
    public function getListDetailServiceStaff(&$filter = [])
    {
        $ds = $this
            ->select(
                "orders.order_code",
                "{$this->table}.staff_id",
                "branches.branch_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.object_name",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.created_at",
                "cs.customer_id",
                "cs.full_name as customer_name"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->join("customers as cs", "cs.customer_id", "=", "orders.customer_id")
            ->whereIn("orders.process_status", self::ARR_PAY_SUCCESS)
            ->whereNotNull("{$this->table}.staff_id")
            ->where("branches.is_deleted", self::NOT_DELETE);

        //Filter ngày tạo
        if (!empty($filter["time_detail"])) {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        //Filter chi nhánh
        if (!empty($filter["branch_detail"])) {
            $ds->where("orders.branch_id", $filter["branch_detail"]);
        }
        unset($filter["time_detail"], $filter["branch_detail"], $filter["staff_id_detail"]);

        return $ds->get()->toArray();
    }

    /**
     * Danh sách chi tiết đơn hàng của nv phục vụ
     *
     * @param array $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
//                "staffs.full_name as staff_name",
                "orders.order_code",
                "branches.branch_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.object_name",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.created_at"
            )
//            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("orders.process_status", self::PAY_SUCCESS)
//            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->where("branches.is_deleted", self::NOT_DELETE);

        //Filter ngày tạo
        if (!empty($filter["time_detail"])) {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        //Filter chi nhánh
        if (!empty($filter["branch_detail"])) {
            $ds->where("orders.branch_id", $filter["branch_detail"]);
        }
        if (!empty($filter["staff_id_detail"])) {
            $ds->where("{$this->table}.staff_id", 'like', '%' . $filter["staff_id_detail"] . '%');
        }

        unset($filter["time_detail"], $filter["branch_detail"], $filter["staff_id_detail"]);

        return $ds;
    }

    /**
     * Lấy ds chi tiết đơn hàng của nv phục vụ ko phân trang
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getDetailNotPaging($startTime, $endTime, $branchId, $limit, $staffId = null)
    {
        $ds = $this
            ->select(
                "staffs.full_name as staff_name",
                "orders.order_code",
                "branches.branch_name",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.object_name",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.created_at"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("orders.process_status", self::PAY_SUCCESS)
            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->where("branches.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);

        if ($branchId != null) {
            $ds->where("orders.branch_id", $branchId);
        }
        if ($staffId != null) {
            $ds->where("{$this->table}.staff_id", $staffId);
        }

        return $ds->limit($limit)->get();
    }

    public function getListExportDetailServiceStaff($startTime, $endTime, $branchId, $limit, $staffId = null)
    {
        $ds = $this
            ->select(
//                "staffs.full_name as staff_name",
                "orders.order_code",
                "branches.branch_name",
                "{$this->table}.staff_id",
                "{$this->table}.object_type",
                "{$this->table}.object_code",
                "{$this->table}.object_name",
                "{$this->table}.price",
                "{$this->table}.quantity",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.created_at"
            )
//            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("orders.process_status", self::PAY_SUCCESS)
//            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->where("branches.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);

        if ($branchId != null) {
            $ds->where("orders.branch_id", $branchId);
        }
        if ($staffId != null) {
            $ds->where("{$this->table}.staff_id", 'like', '%' . $staffId . '%');
        }

        return $ds->limit($limit)->get();
    }

    /**
     * Lấy doanh thu theo thẻ dịch vụ, dịch vụ, sản phẩm
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @param $objectType
     * @return mixed
     */
    public function getRevenueByObject($startTime, $endTime, $branchId, $limit, $objectType, $serviceCardId = null)
    {
        $select = $this->select(
            "{$this->table}.object_id as obj_id",
            "{$this->table}.object_name as obj_name",
            DB::raw("SUM({$this->table}.amount) as total_obj_amount")
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("orders.process_status", 'paysuccess');
        if ($objectType == 'service_card') {
            $select->where("{$this->table}.object_type", 'service_card');
        } elseif ($objectType == 'service') {
            $select->where("{$this->table}.object_type", 'service');
        } elseif ($objectType == 'product') {
            $select->where("{$this->table}.object_type", 'product');
        }
        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        if ($serviceCardId != null) {
            $select->where("{$this->table}.object_id", $serviceCardId);
        }
        return $select->groupBy("{$this->table}.object_id")
            ->orderBy("total_obj_amount", "DESC")
            ->limit($limit)->get();
    }

    /**
     * Lấy doanh thu theo dịch vụ phụ thu
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getRevenueBySurchargeService($startTime, $endTime, $branchId, $limit, $surchargeServiceId = null)
    {
        $select = $this->select(
            "{$this->table}.object_id as obj_id",
            "{$this->table}.object_name as obj_name",
            DB::raw("SUM({$this->table}.amount) as total_obj_amount")
        )
            ->join("services", "services.service_id", "=", "{$this->table}.object_id")
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("services.is_surcharge", self::IS_SURCHARGE)
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service');
        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        if ($surchargeServiceId != null) {
            $select->where("{$this->table}.object_id", $surchargeServiceId);
        }
        return $select->groupBy("{$this->table}.object_id")
            ->orderBy("total_obj_amount", "DESC")
            ->limit($limit)->get();
    }

    /**
     * Lấy doanh thu theo dịch vụ, không phụ thu
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getRevenueByService($startTime, $endTime, $branchId, $limit, $serviceId = null, $serviceCategoryId = null)
    {
        $select = $this->select(
            "{$this->table}.object_id as obj_id",
            "{$this->table}.object_name as obj_name",
            DB::raw("SUM({$this->table}.amount) as total_obj_amount")
        )
            ->join("services", "services.service_id", "=", "{$this->table}.object_id")
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("services.is_surcharge", self::NOT_SURCHARGE)
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service');
        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        if ($serviceId != null) {
            $select->where("{$this->table}.object_id", $serviceId);
        }
        if ($serviceCategoryId != null) {
            $select->where("services.service_category_id", $serviceCategoryId);
        }
        return $select->groupBy("{$this->table}.object_id")
            ->orderBy("total_obj_amount", "DESC")
            ->limit($limit)->get();
    }

    /**
     * Lấy doanh thu theo dịch vụ, không phụ thu
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getRevenueByServiceGroup($startTime, $endTime, $branchId, $serviceCategoryId = null)
    {
        $select = $this->select(
            "service_categories.service_category_id as obj_id",
            "service_categories.name as obj_name",
            DB::raw("SUM({$this->table}.amount) as total_obj_amount")
        )
            ->join("services", "services.service_id", "=", "{$this->table}.object_id")
            ->join("service_categories", "service_categories.service_category_id", "=", "services.service_category_id")
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("services.is_surcharge", self::NOT_SURCHARGE)
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service');
        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        if ($serviceCategoryId != null) {
            $select->where("services.service_category_id", $serviceCategoryId);
        }
        return $select
//            ->groupBy("{$this->table}.object_id")
            ->groupBy("services.service_category_id")
            ->orderBy("total_obj_amount", "DESC")->get();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart product
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailProduct($filter)
    {
        $data = $this->select(
            "orders.order_id",
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'product')
            ->whereIn("{$this->table}.object_id", $filter['arr_product']);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailProduct($filter)
    {
        $select = $this->_getListDetailProduct($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report product
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalProduct($filter)
    {
        $data = $this->select(
            "{$this->table}.object_name",
            "branches.branch_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'product')
            ->whereIn("{$this->table}.object_id", $filter['arr_product']);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        $data->groupBy("{$this->table}.object_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report product
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailProduct($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'product')
            ->whereIn("{$this->table}.object_id", $filter['arr_product']);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart service
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailService($filter)
    {
        $data = $this
            ->select(
                "orders.order_code",
                "{$this->table}.object_name",
                "service_categories.name as service_category_name",
                "branches.branch_name",
                "{$this->table}.amount",
                "{$this->table}.created_at",
                "cs.customer_id",
                "cs.full_name as customer_name"
            )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->join("customers as cs", "cs.customer_id", "=", "orders.customer_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->join("services", "services.service_id", "{$this->table}.object_id")
            ->leftJoin("service_categories", "service_categories.service_category_id", "services.service_category_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service');

//            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        if (isset($filter['service_id_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['service_id_detail']);
            unset($filter['service_id_detail']);
        }
        if (isset($filter['service_category_id_detail']) && $filter['service_category_id_detail'] != '') {
            $data->where("services.service_category_id", "=", $filter['service_category_id_detail']);
            unset($filter['service_category_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailService($filter)
    {
        $select = $this->_getListDetailService($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalService($filter)
    {
        $data = $this->select(
            "{$this->table}.object_name",
            "branches.branch_name",
            "service_categories.name as service_category_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->join("services", "services.service_id", "{$this->table}.object_id")
            ->leftJoin("service_categories", "service_categories.service_category_id", "services.service_category_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service')
            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_service_id_total']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_id_total']);
            unset($filter['export_service_id_total']);
        }
        $data
            ->groupBy("services.service_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export tổng report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalServiceGroup($filter)
    {
        $data = $this->select(
            "{$this->table}.object_name",
            "branches.branch_name",
            "service_categories.name as service_category_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->join("services", "services.service_id", "{$this->table}.object_id")
            ->leftJoin("service_categories", "service_categories.service_category_id", "services.service_category_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service');
//            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_service_id_total']) != '') {
            $data->where("service_categories.service_category_id", "=", $filter['export_service_id_total']);
            unset($filter['export_service_id_total']);
        }
        $data->groupBy("service_categories.service_category_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailService($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
//            "service_categories.name as service_category_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
//            ->join("services","services.service_id","{$this->table}.object_id")
//            ->leftJoin("service_categories","service_categories.service_category_id","services.service_category_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service')
            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_service_id_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_id_detail']);
            unset($filter['export_service_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailServiceGroup($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "service_categories.name as service_category_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->join("services", "services.service_id", "{$this->table}.object_id")
            ->leftJoin("service_categories", "service_categories.service_category_id", "services.service_category_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service');
//            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_service_id_detail']) != '') {
            $data->where("service_categories.service_category_id", "=", $filter['export_service_id_detail']);
            unset($filter['export_service_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart service_card
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailServiceCard($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service_card')
            ->whereIn("{$this->table}.object_id", $filter['arr_service_card']);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        if (isset($filter['service_card_id_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['service_card_id_detail']);
            unset($filter['service_card_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailServiceCard($filter)
    {
        $select = $this->_getListDetailServiceCard($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report service_card
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalServiceCard($filter)
    {
        $data = $this->select(
            "{$this->table}.object_name",
            "branches.branch_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service_card')
            ->whereIn("{$this->table}.object_id", $filter['arr_service_card']);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_service_card_id_total']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_card_id_total']);
            unset($filter['export_service_card_id_total']);
        }
        $data->groupBy("{$this->table}.object_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report service_card
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailServiceCard($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service_card')
            ->whereIn("{$this->table}.object_id", $filter['arr_service_card']);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_service_card_id_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_card_id_detail']);
            unset($filter['export_service_card_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart surcharge service
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailSurchargeService($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->leftJoin("services", function ($join) {
                $join->on("services.service_id", "=", "{$this->table}.object_id");
                $join->on("services.is_surcharge", "=", DB::raw("'1'"));
                $join->on("{$this->table}.object_type", "=", DB::raw("'service'"));
            })
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service')
            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        if (isset($filter['surcharge_service_id_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['surcharge_service_id_detail']);
            unset($filter['surcharge_service_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailSurchargeService($filter)
    {
        $select = $this->_getListDetailSurchargeService($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalSurchargeService($filter)
    {
        $data = $this->select(
            "{$this->table}.object_name",
            "branches.branch_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            "{$this->table}.created_at"
        )
            ->leftJoin("services", function ($join) {
                $join->on("services.service_id", "=", "{$this->table}.object_id");
                $join->on("services.is_surcharge", "=", DB::raw("'1'"));
                $join->on("{$this->table}.object_type", "=", DB::raw("'service'"));
            })
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service')
            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_surcharge_service_id_total']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_surcharge_service_id_total']);
            unset($filter['export_surcharge_service_id_total']);
        }
        $data->groupBy("{$this->table}.object_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailSurchargeService($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "{$this->table}.object_name",
            "branches.branch_name",
            "{$this->table}.amount",
            "{$this->table}.created_at"
        )
            ->leftJoin("services", function ($join) {
                $join->on("services.service_id", "=", "{$this->table}.object_id");
                $join->on("services.is_surcharge", "=", DB::raw("'1'"));
                $join->on("{$this->table}.object_type", "=", DB::raw("'service'"));
            })
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", 'paysuccess')
            ->where("{$this->table}.object_type", 'service')
            ->whereIn("{$this->table}.object_id", $filter['arr_service']);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_surcharge_service_id_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_surcharge_service_id_detail']);
            unset($filter['export_surcharge_service_id_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart statistics branch
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailStatisticsBranch($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "branches.branch_name",
            "{$this->table}.object_name",
            "{$this->table}.object_type",
            "{$this->table}.quantity",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->whereIn("{$this->table}.object_type", self::ARR_STATISTICS_BRANCH)
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách statistics branch
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailStatisticsBranch($filter)
    {
        $select = $this->_getListDetailStatisticsBranch($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report statistics branch
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalStatisticsBranch($filter)
    {
        $data = $this->select(
            "branches.branch_name",
            "{$this->table}.object_type",
            DB::raw("SUM({$this->table}.quantity) as usages")
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->whereIn("{$this->table}.object_type", self::ARR_STATISTICS_BRANCH)
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->groupBy("{$this->table}.object_type", "orders.branch_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report statistics branch
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailStatisticsBranch($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "branches.branch_name",
            "{$this->table}.object_name",
            "{$this->table}.object_type",
            "{$this->table}.quantity",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->whereIn("{$this->table}.object_type", self::ARR_STATISTICS_BRANCH)
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("orders.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
//        $data->groupBy("{$this->table}.object_id");
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart service card
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailStatisticsServiceCard($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "branches.branch_name",
            "{$this->table}.object_name",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->where("{$this->table}.object_type", "=", "service_card")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['service_card_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['service_card_detail']);
            unset($filter['service_card_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách service card
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailStatisticsServiceCard($filter)
    {
        $select = $this->_getListDetailStatisticsServiceCard($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report service card
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalStatisticsServiceCard($filter)
    {
        $data = $this->select(
            "branches.branch_name",
            "{$this->table}.object_name",
            DB::raw("SUM({$this->table}.quantity) as usages")
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->where("{$this->table}.object_type", "=", "service_card")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_service_card_total']) != '') {
            $data->where("{$this->table}.object_type", "=", $filter['export_service_card_total']);
            unset($filter['export_service_card_total']);
        }
        $data->groupBy("{$this->table}.object_id");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report service card
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailStatisticsServiceCard($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "branches.branch_name",
            "{$this->table}.object_name",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->where("{$this->table}.object_type", "=", "service_card")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_service_card_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_card_detail']);
            unset($filter['export_service_card_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Danh sách các sản phẩm chi tiết của chart service
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailStatisticsService($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "branches.branch_name",
            "{$this->table}.object_name",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->where("{$this->table}.object_type", "=", "service")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['service_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['service_detail']);
            unset($filter['service_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang danh sách service
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailStatisticsService($filter)
    {
        $select = $this->_getListDetailStatisticsService($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalStatisticsService($filter)
    {
        $data = $this->select(
            "branches.branch_name",
            "{$this->table}.object_name",
            DB::raw("SUM({$this->table}.quantity) as usages")
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->where("{$this->table}.object_type", "=", "service")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_service_total']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_total']);
            unset($filter['export_service_total']);
        }
        $data->groupBy("{$this->table}.object_id");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết report service
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailStatisticsService($filter)
    {
        $data = $this->select(
            "orders.order_code",
            "branches.branch_name",
            "{$this->table}.object_name",
            "{$this->table}.created_at"
        )
            ->join("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("branches", "branches.branch_id", "orders.branch_id")
            ->where("orders.process_status", "paysuccess")
            ->where("{$this->table}.object_type", "=", "service")
            ->where("orders.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_service_detail']) != '') {
            $data->where("{$this->table}.object_id", "=", $filter['export_service_detail']);
            unset($filter['export_service_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }
}