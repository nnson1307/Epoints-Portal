<?php

namespace Modules\Report\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomerAppointmentTable extends Model
{
    protected $table = 'customer_appointments';
    protected $primaryKey = 'customer_appointment_id';

    /**
     * Lấy tất cả lịch hẹn
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getAllAppointment($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            'customer_id',
            'branch_id',
            'appointment_source_id',
            'date',
            'time',
            'status',
            'created_at'
        )->whereBetween("{$this->table}.date", [$startTime. ' 00:00:00', $endTime. ' 23:59:59']);
        if ($branchId != null) {
            $select->where('branch_id', $branchId);
        }
        return $select->get();
    }

    /**
     * Thống kê dữ liệu số lượng lịch hẹn theo giới tính
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getDataStatisticGender($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            'customers.gender',
            DB::raw('count(customers.gender) as number')
        )
            ->leftJoin('customers', 'customers.customer_id', '=', "{$this->table}.customer_id")
            ->whereBetween("{$this->table}.date", [$startTime. ' 00:00:00', $endTime. ' 23:59:59']);
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->groupBy('customers.gender')->get();
    }

    /**
     * Thống kê dữ liệu số lượng lịch hẹn theo nguồn lịch hẹn
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getDataStatisticAppointmentSource($startTime, $endTime, $branchId)
    {
        $select = $this->select(
            'appointment_source.appointment_source_name',
            DB::raw("count({$this->table}.appointment_source_id) as number")
        )
            ->leftJoin('appointment_source', 'appointment_source.appointment_source_id', '=',
                "{$this->table}.appointment_source_id")
            ->whereBetween("{$this->table}.date", [$startTime. ' 00:00:00', $endTime. ' 23:59:59']);
        if ($branchId != null) {
            $select->where("{$this->table}.branch_id", $branchId);
        }
        return $select->groupBy("{$this->table}.appointment_source_id")->get();
    }

    /**
     * Thống kê dữ liệu số lượng lịch hẹn theo nhóm khách hàng
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @param $isCurrent
     * @return mixed
     */
    public function getDataStatisticCustomerGroup($startTime, $endTime, $branchId, $isCurrent = true)
    {
        // Không phải là khách hàng vãng lai
        if (!$isCurrent) {
            $select = $this->select(
                'customer_groups.group_name',
                DB::raw('count(customer_groups.customer_group_id) as number')
            )
                ->join('customers', 'customers.customer_id', '=', "{$this->table}.customer_id")
                ->join('customer_groups', 'customer_groups.customer_group_id', '=', 'customers.customer_group_id')
                ->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            if ($branchId != null) {
                $select->where("{$this->table}.branch_id", $branchId);
            }
            return $select->groupBy('customer_groups.customer_group_id')->get();
        } else {
            // Khách hàng vãng lai (customer_id = 1 hoặc customer_group_id is null)
            $select = $this->select(
                DB::raw("count({$this->table}.customer_appointment_id) as number")
            )
                ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
                ->whereNull("customers.customer_group_id")
                ->whereBetween("{$this->table}.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"]);
            if ($branchId != null) {
                $select->where("{$this->table}.branch_id", $branchId);
            }
            return $select->first();
        }
    }
    public function _getListDetailStatisticsCustomerAppointment($filter){
        $data = $this->select(
            "{$this->table}.customer_appointment_code",
            "customers.full_name",
            "branches.branch_name",
            DB::raw("(
                CASE
                WHEN {$this->table}.status = 'new' THEN '".__("Mới")."'
                WHEN {$this->table}.status = 'confirm' THEN '".__("Đã xác nhận")."'
                WHEN {$this->table}.status = 'wait' THEN '".__("Chờ phục vụ")."'
                WHEN {$this->table}.status = 'cancel' THEN '".__("Huỷ")."'
                ELSE '".__("Hoàn thành")."' END
            ) as status"),
            "{$this->table}.date",
            "{$this->table}.time"
            )
            ->leftJoin("customers","customers.customer_id","{$this->table}.customer_id")
            ->leftJoin("branches","branches.branch_id","{$this->table}.branch_id");
        if(isset($filter['time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['time_detail']);
        }
        if(isset($filter['branch_detail']) != ''){
            $data->where("{$this->table}.branch_id","=",$filter['branch_detail']);
            unset($filter['branch_detail']);
        }
        $data->orderBy("{$this->table}.date","DESC");
        return $data;
    }
    public function getListDetailStatisticsCustomerAppointment($filter){
        $select  = $this->_getListDetailStatisticsCustomerAppointment($filter);
        $page    = (int) ($filter['page'] ?? 1);
        $display = (int) ($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
    public function getListExportDetailStatisticsCustomerAppointment($filter){
        $data = $this->select(
            "{$this->table}.customer_appointment_code",
            "customers.full_name",
            "branches.branch_name",
            DB::raw("(
                CASE
                WHEN {$this->table}.status = 'new' THEN '".__("Mới")."'
                WHEN {$this->table}.status = 'confirm' THEN '".__("Đã xác nhận")."'
                WHEN {$this->table}.status = 'wait' THEN '".__("Chờ phục vụ")."'
                WHEN {$this->table}.status = 'cancel' THEN '".__("Huỷ")."'
                ELSE '".__("Hoàn thành")."' END
            ) as status"),
            "{$this->table}.date",
            "{$this->table}.time"
        )
            ->leftJoin("customers","customers.customer_id","{$this->table}.customer_id")
            ->leftJoin("branches","branches.branch_id","{$this->table}.branch_id");
        if(isset($filter['export_time_detail']) != ''){
            $arr_filter = explode(" - ", $filter["export_time_detail"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $data->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_detail']);
        }
        if(isset($filter['export_branch_detail']) != ''){
            $data->where("{$this->table}.branch_id","=",$filter['export_branch_detail']);
            unset($filter['export_branch_detail']);
        }
        $data->orderBy("{$this->table}.date","DESC");
        return $data->get()->toArray();
    }
    public function getListExportTotalStatisticsCustomerAppointment($filter){
        $startTime = '';
        $endTime = '';
        $branchId = '';
        if(isset($filter['export_time_total']) != ''){
            $arr_filter = explode(" - ", $filter["export_time_total"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        }
        if(isset($filter['export_branch_total']) != ''){
            $branchId = ['export_branch_total'];
        }
        $data = $this->select(
            "branches.branch_name",
            DB::raw("COUNT({$this->table}.customer_appointment_code) as total"),
            DB::raw("(SELECT COUNT(cus_app.customer_appointment_code)
                            FROM customer_appointments cus_app
                            WHERE cus_app.branch_id = {$this->table}.branch_id 
                                and cus_app.date BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and cus_app.status = 'new')
                            as new"),
            DB::raw("(SELECT COUNT(cus_app.customer_appointment_code)
                            FROM customer_appointments cus_app
                            WHERE cus_app.branch_id = {$this->table}.branch_id 
                                and cus_app.date BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and cus_app.status = 'confirm')
                            as confirm"),
            DB::raw("(SELECT COUNT(cus_app.customer_appointment_code)
                            FROM customer_appointments cus_app
                            WHERE cus_app.branch_id = {$this->table}.branch_id 
                                and cus_app.date BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and cus_app.status = 'wait')
                            as wait"),
            DB::raw("(SELECT COUNT(cus_app.customer_appointment_code)
                            FROM customer_appointments cus_app
                            WHERE cus_app.branch_id = {$this->table}.branch_id 
                                and cus_app.date BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and cus_app.status = 'cancel')
                            as cancel"),
            DB::raw("(SELECT COUNT(cus_app.customer_appointment_code)
                            FROM customer_appointments cus_app
                            WHERE cus_app.branch_id = {$this->table}.branch_id 
                                and cus_app.date BETWEEN '$startTime 00:00:00' and '$endTime 23:59:59'
                                and cus_app.status = 'finish')
                            as finish")
        )->leftJoin("branches","branches.branch_id","{$this->table}.branch_id");
        if(isset($filter['export_time_total']) != ''){
            $data->whereBetween("{$this->table}.date", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
            unset($filter['export_time_total']);
        }
        if(isset($filter['export_branch_total']) != ''){
            $data->where("{$this->table}.branch_id","=",$branchId);
            unset($filter['export_branch_total']);
        }
        $data->groupBy("{$this->table}.branch_id");
        return $data->get()->toArray();
    }
}