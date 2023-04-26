<?php

namespace Modules\Report\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatisticCustomerTable extends Model
{
    protected $table = "statistic_customer";
    protected $primaryKey = "statistic_customer_id";

    /**
     * Lấy tất cả dòng theo filter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getAllByFilterGroupByGender($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            'statistic_customer_id',
            DB::raw("SUM({$this->table}.customer_new) as customer_new"),
            DB::raw("SUM({$this->table}.customer_old) as customer_old"),
            DB::raw("SUM({$this->table}.customer_haunt) as customer_haunt"),
            'branch_id',
            'gender',
            'created_at'
        )
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $select->where('branch_id', $branchId);
        }
        return $select->groupBy('gender')->get();
    }

    /**
     * Lấy dữ liệu theo nguồn khách hàng
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getAllByFilterGroupByCS($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            "{$this->table}.statistic_customer_id",
            DB::raw("SUM({$this->table}.customer_new) as customer_new"),
            DB::raw("SUM({$this->table}.customer_old) as customer_old"),
            DB::raw("SUM({$this->table}.customer_haunt) as customer_haunt"),
            "{$this->table}.branch_id",
            "{$this->table}.customer_source_id",
            "{$this->table}.created_at",
            "cs.customer_source_name"
        )
            ->join("customer_sources as cs", "cs.customer_source_id", "=", "{$this->table}.customer_source_id")
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->groupBy("{$this->table}.customer_source_id")->get();
    }

    /**
     * Lấy tất cả
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getAllByFilter($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            'statistic_customer_id',
            'customer_new',
            'customer_old',
            'customer_haunt',
            'branch_id',
            'gender',
            'created_at'
        )
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $select->where('branch_id', $branchId);
        }
        return $select->get();
    }
    public function getListExportTotalStatisticsCustomer($filter){
        $data = $this->select(
            "branch_name",
            DB::raw("SUM({$this->table}.customer_new) as customer_new"),
            DB::raw("SUM({$this->table}.customer_old) as customer_old"),
            DB::raw("SUM({$this->table}.customer_haunt) as customer_haunt")
        )
            ->leftJoin("branches","branches.branch_id","{$this->table}.branch_id");
        if(isset($filter['export_time_total']) && $filter['export_time_total'] != ''){
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if(isset($filter['export_branch_total']) != ''){
            $data->where("{$this->table}.branch_id","=",$filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        $data->groupBy("{$this->table}.branch_id");
        return $data->get()->toArray();
    }
}