<?php

namespace Modules\Report\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderCommissionTable extends Model
{
    protected $table = 'order_commission';
    protected $primaryKey = 'id';

    const NOT_DELETE = 0;

    /**
     * Lấy staff id, name, tổng tiền hoa hồng của mỗi nhân viên
     *
     * @param $startTime
     * @param $endTime
     * @param $limit
     * @return mixed
     */
    public function getInfoCommissionGroupByStaff($startTime, $endTime, $limit,$staffId = null)
    {
        $select = $this
            ->select(
                "{$this->table}.staff_id",
                DB::raw("SUM({$this->table}.staff_money) as total_staff_money"),
                "staffs.full_name as staff_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->whereNotNull("{$this->table}.staff_id")
            ->where("{$this->table}.status", "approve")
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->orderBy("total_staff_money")
            ->groupBy("{$this->table}.staff_id");
        if ($staffId != null) {
            $select->where("{$this->table}.staff_id", $staffId);
        }
        if (isset($limit)) {
            $select->limit($limit);
        }
        return $select->get();
    }

    /**
     * Lấy thông tin chi tiết hoa hồng nv
     *
     * @param $startTime
     * @param $endTime
     * @param array $arrStaffId
     * @return mixed
     */
    public function getCommissionStaff($startTime, $endTime, $arrStaffId = [],$staffId = null)
    {
        $data = $this
            ->select(
                "staffs.full_name as staff_name",
                "{$this->table}.staff_money",
                "{$this->table}.staff_commission_rate",
                "branches.branch_name"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("order_details", "order_details.order_detail_id", "=", "{$this->table}.order_detail_id")
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->leftJoin("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("staffs.is_deleted", self::NOT_DELETE)
//            ->where("branches.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if($arrStaffId != null){
            $data->whereIn("{$this->table}.staff_id", $arrStaffId);
        }
        if($staffId != null){
            $data->where("{$this->table}.staff_id", $staffId);
        }
        return $data->get();
    }

    /**
     * Lấy thông tin hoa hồng nv group by branch
     *
     * @param $startTime
     * @param $endTime
     * @param array $arrStaffId
     * @return mixed
     */
    public function getStaffGroupBranch($startTime, $endTime, $arrStaffId = [],$staffId = null)
    {
        $data = $this
            ->select(
                "staffs.full_name as staff_name",
                "branches.branch_name",
                DB::raw("SUM({$this->table}.staff_money) as staff_money")
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("order_details", "order_details.order_detail_id", "=", "{$this->table}.order_detail_id")
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->leftJoin("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("staffs.is_deleted", self::NOT_DELETE)
//            ->where("branches.is_deleted", self::NOT_DELETE)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if($arrStaffId != null){
            $data->whereIn("{$this->table}.staff_id", $arrStaffId);
        }
        if($staffId != null){
            $data->where("{$this->table}.staff_id", $staffId);
        }
        $data->groupBy("staffs.staff_id", "branches.branch_id");

        return $data->get();
    }

    /**
     * Lấy deal id, name, tổng tiền hoa hồng của mỗi deal
     *
     * @param $startTime
     * @param $endTime
     * @param $limit
     * @return mixed
     */
    public function getInfoCommissionGroupByDeal($startTime, $endTime, $limit, $dealId = null)
    {
        $select = $this
            ->select(
                "{$this->table}.deal_id",
                DB::raw("SUM({$this->table}.deal_money) as total_deal_money"),
                "cpo_deals.deal_name as deal_name"
            )
            ->join("cpo_deals", "cpo_deals.deal_id", "=", "{$this->table}.deal_id")
            ->whereNotNull("{$this->table}.deal_id")
            ->where("{$this->table}.status", "approve")
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59'])
            ->where("cpo_deals.is_deleted", self::NOT_DELETE)
            ->orderBy("total_deal_money")
            ->groupBy("{$this->table}.deal_id");
        if($dealId != null){
            $select->where("{$this->table}.deal_id", $dealId);
        }
        if (isset($limit)) {
            $select->limit($limit);
        }
        return $select->get();
    }

    /**
     * ds chi tiết hoa hồng nv chưa phân trang
     *
     * @param $filter
     * @return mixed
     */
    public function _getListStaffCommission($filter){
        $data = $this
            ->select(
                "staffs.full_name as staff_name",
                "{$this->table}.staff_money",
                "{$this->table}.staff_commission_rate",
                "branches.branch_name",
                "{$this->table}.created_at"
            )
            ->join("staffs", "staffs.staff_id", "=", "{$this->table}.staff_id")
            ->leftJoin("order_details", "order_details.order_detail_id", "=", "{$this->table}.order_detail_id")
            ->leftJoin("orders", "orders.order_id", "=", "order_details.order_id")
            ->leftJoin("branches", "branches.branch_id", "=", "orders.branch_id")
            ->where("staffs.is_deleted", self::NOT_DELETE)
            ->whereIn("{$this->table}.staff_id", $filter['arr_staff']);
        if(isset($filter['time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if(isset($filter['staff_id_detail']) != ''){
            $data->where("{$this->table}.staff_id","=",$filter['staff_id_detail']);
            unset($filter['staff_id_detail']);
        }
        return $data->orderBy("{$this->table}.created_at","DESC");
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListStaffCommission($filter){
        $select  = $this->_getListStaffCommission($filter);
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    /**
     * ds chi tiết hoa hồng nv chưa phân trang
     *
     * @param $filter
     * @return mixed
     */
    public function _getListDetailDealCommission($filter){
        $data = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_money as total_deal_money",
                "cpo_deals.deal_name as deal_name",
                "{$this->table}.created_at"
            )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "=", "{$this->table}.deal_id")
            ->whereNotNull("{$this->table}.deal_id")
            ->where("{$this->table}.status", "approve")
            ->where("cpo_deals.is_deleted", self::NOT_DELETE)
            ->whereIn("{$this->table}.deal_id", $filter['arr_deal']);
        if(isset($filter['time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if(isset($filter['deal_id_detail']) != ''){
            $data->where("{$this->table}.deal_id","=",$filter['deal_id_detail']);
            unset($filter['deal_id_detail']);
        }
         $data->orderBy("{$this->table}.created_at","DESC");
        return $data;
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailDealCommission($filter){
        $select  = $this->_getListDetailDealCommission($filter);
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    /**
     * Export total report Customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalDealCommission($filter){
        $data = $this
            ->select(
                "{$this->table}.deal_id",
                DB::raw("SUM({$this->table}.deal_money) as total_deal_money"),
                "cpo_deals.deal_name as deal_name"
            )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "=", "{$this->table}.deal_id")
            ->whereNotNull("{$this->table}.deal_id")
            ->where("{$this->table}.status", "approve")
            ->where("cpo_deals.is_deleted", self::NOT_DELETE)
            ->whereIn("{$this->table}.deal_id", $filter['arr_deal']);
        if(isset($filter['export_time_total']) != ''){
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if(isset($filter['export_deal_id_total']) != ''){
            $data->where("{$this->table}.deal_id","=",$filter['export_deal_id_total']);
            unset($filter['export_deal_id_total']);
        }
        $data->orderBy("total_deal_money")
            ->groupBy("{$this->table}.deal_id");
        return $data->get()->toArray();
    }

    /**
     * Export detail report Customer
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailDealCommission($filter){
        $data = $this
            ->select(
                "{$this->table}.deal_id",
                "{$this->table}.deal_money as total_deal_money",
                "cpo_deals.deal_name as deal_name",
                "{$this->table}.created_at"
            )
            ->leftJoin("cpo_deals", "cpo_deals.deal_id", "=", "{$this->table}.deal_id")
            ->whereNotNull("{$this->table}.deal_id")
            ->where("{$this->table}.status", "approve")
            ->where("cpo_deals.is_deleted", self::NOT_DELETE)
            ->whereIn("{$this->table}.deal_id", $filter['arr_deal']);
        if(isset($filter['export_time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if(isset($filter['export_deal_id_detail']) != ''){
            $data->where("{$this->table}.deal_id","=",$filter['export_deal_id_detail']);
            unset($filter['export_deal_id_detail']);
        }
        $data->orderBy("{$this->table}.created_at","DESC");
        return $data->get()->toArray();
    }
}