<?php


namespace Modules\Report\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderTable extends Model
{
    protected $table = "orders";
    protected $primaryKey = "order_id";

    const LIMIT = 10;
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    const ARR_STATUS = ['new', 'confirmed', 'ordercancle', 'paysuccess', 'pay-half', 'payfail'];

    /**
     * Số luộng đơn hàng theo giờ
     *
     * @param $time
     * @return mixed
     */
    public function getOrderByHour($time)
    {
        $res = $this->select
        (
            DB::raw("HOUR(orders.created_at) as hours, COUNT(orders.created_at) as usages")
        )
            ->where("orders.process_status", "<>", "ordercancle")
            ->groupBy(DB::raw("HOUR(orders.created_at)"));


        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $res->whereBetween("orders.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $res->get();
    }

    /**
     * Những khách hàng mua sản phẩm thuộc nhóm sản phẩm nhiều nhất
     *
     * @param $productCategoryId
     * @param $time
     * @param $isLimit
     * @return mixed
     */
    public function getCustomerByPurchase($productCategoryId, $time, $isLimit)
    {
        $res = $this
            ->select(
                'customers.full_name',
                'customers.email',
                'customers.phone1',
                DB::raw("SUM(order_details.quantity) as total")
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->join("order_details", "order_details.order_id", "=", "{$this->table}.order_id")
            ->join("product_childs", "product_childs.product_code", "=", "order_details.object_code")
            ->join("products", "products.product_id", "=", "product_childs.product_id")
            ->where("{$this->table}.process_status", "<>", "ordercancle")
            ->where("order_details.object_type", "product")
            ->where("product_childs.is_deleted", self::NOT_DELETE)
            ->where("products.is_deleted", self::NOT_DELETE)
            ->where("products.product_category_id", $productCategoryId)
            ->groupBy("customers.customer_id")
            ->orderBy('total', 'desc');

        if ($time != null) {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $res->whereBetween("orders.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if ($isLimit == false) {
            return $res->get();
        } else {
            return $res->limit(self::LIMIT)->get();
        }
    }

    /**
     * Lấy tất cả order và tính tổng số tiền đã trả của từng order
     *
     * @param $startTime
     * @param $endTime
     * @param array $orderStatus
     * @param $branchId
     * @param $customerGroupId
     * @return mixed
     */
    public function getAllOrderByStatus($startTime, $endTime, array $orderStatus, $branchId, $customerGroupId, $customerId = null)
    {
        // Filter customer group
        if ($customerGroupId != null) {
            $select = $this->select
            (
                "{$this->table}.order_id",
                "{$this->table}.branch_id",
                "{$this->table}.customer_id",
                "{$this->table}.amount as order_amount",
                "{$this->table}.process_status as status",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "receipts.staff_id",
                "receipts.total_money",
                DB::raw("SUM(receipts.amount_paid) as total_receipt")
            )
                ->join("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
                ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->whereIn("{$this->table}.process_status", $orderStatus)
                ->where("{$this->table}.is_deleted", self::NOT_DELETE)
                ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
                ->where("customers.customer_group_id", $customerGroupId);
        } else {
            $select = $this->select
            (
                "{$this->table}.order_id",
                "{$this->table}.branch_id",
                "{$this->table}.customer_id",
                "{$this->table}.amount as order_amount",
                "{$this->table}.process_status as status",
                "{$this->table}.created_at",
                "{$this->table}.created_by",
                "receipts.staff_id",
                "receipts.total_money",
                DB::raw("SUM(receipts.amount_paid) as total_receipt")
            )
                ->join("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
                ->whereIn("{$this->table}.process_status", $orderStatus)
                ->where("{$this->table}.is_deleted", self::NOT_DELETE)
                ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        if ($customerId != null) {
            $select->where("{$this->table}.customer_id", $customerId);
        }
        return $select->groupBy("{$this->table}.order_id")->get();
    }

    /**
     * Lấy tất cả khách hàng trong bảng đơn hàng theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getAllCustomer($startTime, $endTime, $branchId, $limit, $customerId = null)
    {
        $select = $this->select(
            "{$this->table}.customer_id",
            "{$this->table}.order_id",
            "customers.full_name as customer_name",
            "customers.phone1 as customer_phone"
        )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("customers.is_actived", self::IS_ACTIVE)
            ->where("customers.is_deleted", self::NOT_DELETE)
            ->groupBy("{$this->table}.customer_id");

        // Filter customer
        if ($customerId != null && $customerId != "") {
            $select->where("{$this->table}.customer_id", $customerId);
        }
        // Filter branch
        if ($branchId != null && $branchId != "") {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        // Filter number customer
        if ($limit != null && $limit != "") {
            $select->limit($limit);
        }

        return $select->get();
    }

    /**
     * Lấy tất cả khách hàng trong bảng order theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $limit
     * @return mixed
     */
    public function getAllStaff($startTime, $endTime, $branchId, $limit, $staffId = null)
    {
        $select = $this->select(
            "staffs.full_name as staff_name",
            "receipts.staff_id as staff_id"
        )
            ->join("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->join("staffs", "staffs.staff_id", "receipts.staff_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("staffs.is_actived", self::IS_ACTIVE)
            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->groupBy("receipts.staff_id");

        // Filter branch
        if ($branchId != null && $branchId != "") {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        if ($staffId != null && $staffId != "") {
            $select->where("receipts.created_by", $staffId);
        }
        // Filter number staff
        if ($limit != null && $limit != "") {
            $select->limit($limit);
        }
        return $select->get();
    }

    public function getObjectAndSumAmountObject($startTime, $endTime, $branchId, $numberObject, $objectType)
    {
        if ($objectType == 'product') {
            $select = $this->select(
                "pc.product_child_name as obj_name",
                DB::raw("SUM(od.amount) as total_obj_amount")
            )
                ->join("order_details as od", "od.order_id", "=", "{$this->table}.order_id")
                ->join("product_childs as pc", "pc.product_code", "=", "od.object_code");
        } elseif ($objectType == 'service') {
            $select = $this->select(
                "s.service_name as obj_name",
                DB::raw("SUM(od.amount) as total_obj_amount")
            )
                ->join("order_details as od", "od.order_id", "=", "{$this->table}.order_id")
                ->join("services as s", "s.service_code", "=", "od.object_code");
        } else {
            $select = $this->select(
                "sc.name as obj_name",
                DB::raw("SUM(od.amount) as total_obj_amount")
            )
                ->join("order_details as od", "od.order_id", "=", "{$this->table}.order_id")
                ->join("service_cards as sc", "sc.service_card_id", "=", "od.object_id");
        }
        $select
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("od.object_type", $objectType)
            ->groupBy("od.object_code")
            ->orderBy("total_obj_amount", "DESC");
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->limit($numberObject)->get();
    }

    /**
     * Lấy tất cả order theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getOrderByFilterAndStatus($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            'order_id',
            'customer_id',
            'branch_id',
            'process_status',
            'created_at'
        )
            ->where("{$this->table}.is_deleted", 0)
            ->whereIn("{$this->table}.process_status", self::ARR_STATUS)
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->get();
    }

    /**
     * Lấy số lượng đơn hàng theo từng status
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getNumberOrderByStatus($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            "{$this->table}.process_status",
            DB::raw("count({$this->table}.order_id) as number")
        )
            ->where("{$this->table}.is_deleted", 0)
            ->whereIn("{$this->table}.process_status", self::ARR_STATUS)
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->groupBy("{$this->table}.process_status")->get();
    }

    /**
     * Lấy số lượng đơn hàng theo nguồn đơn hàng
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getNumberOrderByOrderSource($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            "order_sources.order_source_name",
            DB::raw("count({$this->table}.order_id) as number")
        )
            ->join("order_sources", "order_sources.order_source_id", "=", "{$this->table}.order_source_id")
            ->where("{$this->table}.is_deleted", 0)
            ->whereIn("{$this->table}.process_status", self::ARR_STATUS)
            ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->groupBy("{$this->table}.order_source_id")->get();
    }

    /**
     * Lấy số lượng đơn hàng theo nhóm khách hàng
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param bool $isCurrent
     * @return mixed
     */
    public function getNumberOrderByCustomerGroup($startTime, $endTime, $branchId, $isCurrent = true)
    {
        // Nếu là khách hàng vãng lai
        if ($isCurrent) {
            $select = $this->select(
                DB::raw("count({$this->table}.order_id) as number")
            )
                ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
//                ->where('customer_id', 1)
                ->whereNull("customers.customer_group_id")
                ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
                ->where("{$this->table}.is_deleted", 0);
            if ($branchId != null) {
                $select->where("{$this->table}.branch_id", $branchId);
            }
            return $select->first();
        } else {
            // Các nhóm khách hàng còn lại
            $select = $this->select(
                "customer_groups.group_name",
                DB::raw("count({$this->table}.order_id) as number")
            )
                ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->join("customer_groups", "customer_groups.customer_group_id", "=", "customers.customer_group_id")
//                ->where("{$this->table}.customer_id", '<>', 1)
                ->whereBetween("{$this->table}.created_at", [$startTime . " 00:00:00", $endTime . " 23:59:59"])
                ->where("{$this->table}.is_deleted", 0);
            if ($branchId != null) {
                $select->where("{$this->table}.branch_id", $branchId);
            }
            return $select->groupBy("customer_groups.customer_group_id")->get();
        }
    }

    /**
     * Lấy tất cả đơn hàng có sử dụng voucher theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getOrderUseVoucher($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            "{$this->table}.order_id",
            "{$this->table}.customer_id",
            "{$this->table}.branch_id",
            "{$this->table}.voucher_code",
            "{$this->table}.created_at"
        )
            ->whereNotNull("{$this->table}.voucher_code")
            ->where("{$this->table}.process_status", "paysuccess")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);

        if ($branchId != null) {
            $select->where("orders.branch_id", $branchId);
        }
        return $select->get();
    }

    /**
     * Ds chi tiết của chart branch
     *
     * @param $filter
     * @return mixed
     */
    protected function _getListDetailBranch($filter)
    {
        $data = $this->select(
            "{$this->table}.order_id",
            "{$this->table}.order_code",
            "customers.full_name",
            "customer_groups.group_name",
            "branches.branch_name",
            "{$this->table}.amount",
            DB::raw("SUM(receipts.amount_paid) as total_receipt"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("customer_groups", "customer_groups.customer_group_id", "customers.customer_group_id")
            ->join("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        if (isset($filter['customer_group_detail']) != '') {
            $data->where("customer_groups.customer_group_id", "=", $filter['customer_group_detail']);
            unset($filter['customer_group_detail']);
        }
        $data->groupBy("receipts.order_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang report branch
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailBranch($filter)
    {
        $select = $this->_getListDetailBranch($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export total report branch
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotal($filter)
    {
        $data = $this->select(
            "customer_groups.group_name",
            "branches.branch_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            DB::raw("SUM(receipts.amount_paid) as total_receipt")
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("customer_groups", "customer_groups.customer_group_id", "customers.customer_group_id")
            ->leftJoin(DB::raw("(SELECT SUM(receipts.amount_paid) AS amount_paid, order_id 
                                            FROM receipts WHERE receipts.status = 'paid'
                                            GROUP BY receipts.order_id) AS receipts"),
                "receipts.order_id", "=", "{$this->table}.order_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_customer_group_total']) != '') {
            $data->where("customer_groups.customer_group_id", "=", $filter['export_customer_group_total']);
            unset($filter['export_customer_group_total']);
        }
        $data->groupBy("{$this->table}.branch_id", "customer_groups.customer_group_id");
        return $data->get()->toArray();
    }

    /**
     * Export detail report branch
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetail($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "customers.full_name",
            "customer_groups.group_name",
            "branches.branch_name",
            "{$this->table}.amount",
            DB::raw("SUM(receipts.amount_paid) as total_receipt"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("customer_groups", "customer_groups.customer_group_id", "customers.customer_group_id")
            ->leftJoin("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_customer_group_detail']) != '') {
            $data->where("customer_groups.customer_group_id", "=", $filter['export_customer_group_detail']);
            unset($filter['export_customer_group_detail']);
        }
        $data->groupBy("receipts.order_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Ds chi tiết của chart customer
     *
     * @param $filter
     * @return mixed
     */
    protected function _getListDetailCustomer($filter)
    {
        $data = $this->select(
            "{$this->table}.order_id",
            "{$this->table}.order_code",
            "customers.full_name",
            "branches.branch_name",
            "{$this->table}.amount",
            DB::raw("SUM(receipts.amount_paid) as total_receipt"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->whereIn("{$this->table}.customer_id", $filter['arr_customer'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("customers.is_deleted", self::NOT_DELETE)
            ->where("customers.is_actived", self::IS_ACTIVE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        if (isset($filter['customer_id_detail']) != '') {
            $data->where("{$this->table}.customer_id", "=", $filter['customer_id_detail']);
            unset($filter['customer_id_detail']);
        }
        $data->groupBy("receipts.order_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang report customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailCustomer($filter)
    {
        $select = $this->_getListDetailCustomer($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export total report Customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalCustomer($filter)
    {
        $data = $this->select(
            "branches.branch_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            DB::raw("SUM(receipts.amount_paid) as total_receipt")
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin(DB::raw("(SELECT SUM(receipts.amount_paid) AS amount_paid, order_id , created_at
                                            FROM receipts WHERE receipts.status in ('paid', 'part-paid')
                                            GROUP BY receipts.order_id) AS receipts"),
                "receipts.order_id", "=", "{$this->table}.order_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->whereIn("{$this->table}.customer_id", $filter['arr_customer'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_customer_id_total']) != '') {
            $data->where("{$this->table}.customer_id", "=", $filter['export_customer_id_total']);
            unset($filter['export_customer_id_total']);
        }
        $data->groupBy("{$this->table}.branch_id");
        return $data->get()->toArray();
    }

    /**
     * Export detail report Customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailCustomer($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "customers.full_name",
            "branches.branch_name",
            "{$this->table}.amount",
            DB::raw("SUM(receipts.amount_paid) as total_receipt"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->whereIn("{$this->table}.customer_id", $filter['arr_customer'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_customer_id_detail']) != '') {
            $data->where("{$this->table}.customer_id", "=", $filter['export_customer_id_detail']);
            unset($filter['export_customer_id_detail']);
        }
        $data->groupBy("receipts.order_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Ds chi tiết của chart customer
     *
     * @param $filter
     * @return mixed
     */
    protected function _getListDetailStaff($filter)
    {
        $data = $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "staffs.full_name",
                "branches.branch_name",
                "{$this->table}.amount",
                DB::raw("SUM(receipts.amount_paid) as total_receipt"),
                "{$this->table}.created_at",
                "cs.customer_id",
                "cs.full_name as customer_name"
            )
            ->join("customers as cs", "cs.customer_id", "=", "{$this->table}.customer_id")
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "receipts.staff_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->whereIn("receipts.staff_id", $filter['arr_staff'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("staffs.is_actived", self::IS_ACTIVE)
            ->where("staffs.is_deleted", self::NOT_DELETE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        if (isset($filter['staff_id_detail']) != '') {
            $data->where("receipts.staff_id", "=", $filter['staff_id_detail']);
            unset($filter['staff_id_detail']);
        }
        $data->groupBy("receipts.order_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang report customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailStaff($filter)
    {
        $select = $this->_getListDetailStaff($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export total report Customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalStaff($filter)
    {
        $data = $this->select(
            "branches.branch_name",
            DB::raw("SUM({$this->table}.amount) as amount"),
            DB::raw("SUM(receipts.amount_paid) as total_receipt")
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin(DB::raw("(SELECT SUM(receipts.amount_paid) AS amount_paid, staff_id, order_id
                                            FROM receipts WHERE receipts.status = 'paid'
                                            GROUP BY receipts.order_id,receipts.staff_id) AS receipts"),
                "receipts.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "receipts.staff_id")
            ->whereIn("receipts.staff_id", $filter['arr_staff'])
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        if (isset($filter['export_staff_id_total']) != '') {
            $data->where("receipts.staff_id", "=", $filter['export_staff_id_total']);
            unset($filter['export_staff_id_total']);
        }
        $data->groupBy("{$this->table}.branch_id");
        return $data->get()->toArray();
    }

    /**
     * Export detail report Customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailStaff($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "staffs.full_name",
            "branches.branch_name",
            "{$this->table}.amount",
            DB::raw("SUM(receipts.amount_paid) as total_receipt"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("receipts", "receipts.order_id", "=", "{$this->table}.order_id")
            ->leftJoin("staffs", "staffs.staff_id", "receipts.staff_id")
            ->whereIn("{$this->table}.process_status", ['paysuccess', 'pay-half'])
            ->whereIn("receipts.staff_id", $filter['arr_staff'])
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        if (isset($filter['export_staff_id_detail']) != '') {
            $data->where("receipts.staff_id", "=", $filter['export_staff_id_detail']);
            unset($filter['export_staff_id_detail']);
        }
        $data->groupBy("receipts.order_id")
            ->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    /**
     * Ds chi tiết của chart customer
     *
     * @param $filter
     * @return mixed
     */
    protected function _getListDetailStatisticsCustomer($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "branches.branch_name",
            "customers.full_name",
            DB::raw("(CASE 
                                WHEN orders.customer_id = 1 THEN '" . __("Khách hàng vãng lai") . "'
                                WHEN (SELECT IFNULL(count(od.order_code),0) as amount 
                                        FROM orders od 
                                        WHERE od.is_deleted = 0 
                                                AND od.customer_id = orders.customer_id 
                                                AND od.created_at < orders.created_at) = 0 THEN '" . __("Khách hàng mới") . "'
                                ELSE '" . __("Khách hàng cũ") . "'
				            END) as status"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    /**
     * Phân trang report customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailStatisticsCustomer($filter)
    {
        $select = $this->_getListDetailStatisticsCustomer($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getListExportDetailStatisticsCustomer($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "branches.branch_name",
            "customers.full_name",
            DB::raw("(CASE 
                                WHEN orders.customer_id = 1 THEN '" . __("Khách hàng vãng lai") . "'
                                WHEN (SELECT IFNULL(count(od.order_code),0) as amount 
                                        FROM orders od 
                                        WHERE od.is_deleted = 0 
                                                AND od.customer_id = orders.customer_id 
                                                AND od.created_at < orders.created_at) = 0 THEN '" . __("Khách hàng mới") . "'
                                ELSE '" . __("Khách hàng cũ") . "'
				            END) as status"),
            "{$this->table}.created_at"
        )
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id")
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->where("{$this->table}.is_deleted", self::NOT_DELETE);
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    public function _getListDetailStatisticsOrder($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "customers.full_name",
            "branches.branch_name",
            DB::raw("(
                CASE
                    WHEN {$this->table}.process_status = 'new' THEN '" . __("Mới") . "'
                    WHEN {$this->table}.process_status = 'confirmed' THEN '" . __("Đã xác nhận") . "'
                    WHEN {$this->table}.process_status = 'ordercancle' THEN '" . __("Huỷ") . "'
                    WHEN {$this->table}.process_status = 'pay-half' THEN '" . __("Thanh toán 1 phần") . "'
                    WHEN {$this->table}.process_status = 'paysuccess' THEN '" . __("Hoàn thành") . "'
                END
            ) as process_status"),
            "{$this->table}.created_at"
        )
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id");
        if (isset($filter['time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if (isset($filter['branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data;
    }

    public function getListDetailStatisticsOrder($filter)
    {
        $select = $this->_getListDetailStatisticsOrder($filter);
        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    public function getListExportDetailStatisticsOrder($filter)
    {
        $data = $this->select(
            "{$this->table}.order_code",
            "customers.full_name",
            "branches.branch_name",
            DB::raw("(
                CASE
                    WHEN {$this->table}.process_status = 'new' THEN '" . __("Mới") . "'
                    WHEN {$this->table}.process_status = 'confirmed' THEN '" . __("Đã xác nhận") . "'
                    WHEN {$this->table}.process_status = 'ordercancle' THEN '" . __("Huỷ") . "'
                    WHEN {$this->table}.process_status = 'pay-half' THEN '" . __("Thanh toán 1 phần") . "'
                    WHEN {$this->table}.process_status = 'paysuccess' THEN '" . __("Hoàn thành") . "'
                END
            ) as process_status"),
            "{$this->table}.created_at"
        )
            ->leftJoin("customers", "customers.customer_id", "{$this->table}.customer_id")
            ->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id");
        if (isset($filter['export_time_detail']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if (isset($filter['export_branch_detail']) != '') {
            $data->where("{$this->table}.branch_id", "=", $filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        $data->orderBy("{$this->table}.created_at", "DESC");
        return $data->get()->toArray();
    }

    public function getListExportTotalStatisticsOrder($filter)
    {
        $startTime = '';
        $endTime = '';
        $branchId = '';
        if (isset($filter['export_time_total']) != '') {
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        }
        if (isset($filter['export_branch_total']) != '') {
            $branchId = $filter['export_branch_total'];
        }
        $data = $this->select(
            "branches.branch_name",
            DB::raw("COUNT({$this->table}.order_code) as total"),
            DB::raw("(SELECT COUNT(od.order_code)
                            FROM orders od
                            WHERE od.branch_id = {$this->table}.branch_id 
                                and od.created_at BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and od.process_status = 'new')
                            as new"),
            DB::raw("(SELECT COUNT(od.order_code)
                            FROM orders od
                            WHERE od.branch_id = {$this->table}.branch_id 
                                and od.created_at BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and od.process_status = 'confirmed')
                            as confirmed"),
            DB::raw("(SELECT COUNT(od.order_code)
                            FROM orders od
                            WHERE od.branch_id = {$this->table}.branch_id 
                                and od.created_at BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and od.process_status = 'ordercancle')
                            as ordercancle"),
            DB::raw("(SELECT COUNT(od.order_code)
                            FROM orders od
                            WHERE od.branch_id = {$this->table}.branch_id 
                                and od.created_at BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and od.process_status = 'pay-half')
                            as payhalf"),
            DB::raw("(SELECT COUNT(od.order_code)
                            FROM orders od
                            WHERE od.branch_id = {$this->table}.branch_id 
                                and od.created_at BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and od.process_status = 'paysuccess')
                            as paysuccess")
        )->leftJoin("branches", "branches.branch_id", "{$this->table}.branch_id");
        if (isset($filter['export_time_total']) != '') {
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if (isset($filter['export_branch_total']) != '') {
            $data->where("{$this->table}.branch_id", "=", $branchId);
            unset($filter['export_branch_total']);
        }
        $data->groupBy("{$this->table}.branch_id");
        return $data->get()->toArray();
    }
}