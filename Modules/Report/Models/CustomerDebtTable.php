<?php

namespace Modules\Report\Models;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CustomerDebtTable extends Model
{
    protected $table = 'customer_debt';
    protected $primaryKey = 'customer_debt_id';

    /**
     * Lất tất cả dữ liệu công nợ theo thơi gian + chi nhánh
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getAllDataDebt($startTime, $endTime, $branchId)
    {
        $select = $this
            ->select(
                'branches.branch_name',
                'branches.branch_id',
                'customer_debt.amount',
                'customer_debt.status',
                'customer_debt.amount_paid',
                'customer_debt.created_at'
            )
            ->join('staffs', 'staffs.staff_id', '=', 'customer_debt.created_by')
            ->join('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->whereBetween('customer_debt.created_at', [$startTime. ' 00:00:00', $endTime. ' 23:59:59']);
        if (isset($branchId)) {
            $select->where('branches.branch_id', $branchId);
        }
        return $select->get();
    }

    /**
     * Danh sách chi tiết báo cáo công nợ theo chi nhánh
     *
     * @param $filter
     * @return mixed
     */
    protected function _getListDetailDebtByBranch($filter){
        $data = $this
            ->select(
                'branches.branch_name',
                'customer_debt.debt_code',
                'customers.full_name',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.created_at'
            )
            ->leftJoin('customers', 'customers.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.created_by')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id');
        if(isset($filter['time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if(isset($filter['branch_detail']) != ''){
            $data->where("branches.branch_id","=",$filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        return $data->orderBy("customer_debt.created_at","DESC");
    }

    /**
     * Phân trang danh sách
     *
     * @param $filter
     * @return mixed
     */
    public function getListDetailDebtByBranch($filter){
        $select  = $this->_getListDetailDebtByBranch($filter);
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }

    /**
     * Export tổng công nợ theo chi nhánh
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportTotalDebtByBranch($filter){
        $data = $this
            ->select(
                'branches.branch_name',
                DB::raw("SUM(customer_debt.amount) as amount"),
                DB::raw("SUM(customer_debt.amount_paid) as amount_paid"),
                'customer_debt.created_at'
            )
            ->leftJoin('customers', 'customers.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.created_by')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id');
        if(isset($filter['export_time_total']) != ''){
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if(isset($filter['export_branch_total']) != ''){
            $data->where("branches.branch_id","=",$filter['export_branch_total']);
            unset($filter['export_branch_total']);
        }
        $data->groupBy("branches.branch_id");
        $data->orderBy("customer_debt.created_at","DESC");
        return $data->get()->toArray();
    }

    /**
     * Export chi tiết công nợ theo chi nhánh
     *
     * @param $filter
     * @return mixed
     */
    public function getListExportDetailDebtByBranch($filter){
        $data = $this
            ->select(
                'branches.branch_name',
                'customer_debt.debt_code',
                'customers.full_name',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.created_at'
            )
            ->leftJoin('customers', 'customers.customer_id', '=', "{$this->table}.customer_id")
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.created_by')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id');
        if(isset($filter['export_time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if(isset($filter['export_branch_detail']) != ''){
            $data->where("branches.branch_id","=",$filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        $data->orderBy("customer_debt.created_at","DESC");
        return $data->get()->toArray();
    }
}