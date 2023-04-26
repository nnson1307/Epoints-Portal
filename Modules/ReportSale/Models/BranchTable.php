<?php

namespace Modules\ReportSale\Models;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    const NOT_DELETED = 0;

    /**
     * Lấy option chi nhánh
     *
     * @return mixed
     */
    public function getOption($branchId = null)
    {
       
        $select = $this->select('branch_id', 'branch_name', 'address', 'phone', 'branch_code');
        // if (Auth::user()->is_admin != 1) {
        //     $select->where('branch_id', Auth::user()->branch_id);
        // }
        if($branchId != null){
            
            $select->where('branch_id', $branchId);
        }
        return $select->get()->toArray();
    }

    /**
     * Lấy thông tin chi nhánh
     *
     * @param $branchCode
     * @return mixed
     */
    public function getBranchByCode($branchCode)
    {
        return $this
            ->select(
                "branch_id",
                "branch_code",
                "branch_name"
            )
            ->where("branch_code", $branchCode)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Lấy thông tin tổng thu tổng chi tồn quỹ theo từng chi nhánh
     *
     * @param $time
     * @param $branchCode
     * @param $branchId
     * @param $paymentType
     * @param $receiptType
     * @param $paymentMethod
     * @return mixed
     */
    public function getTotalPaymentEachBranch($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $lang = Config::get('app.locale');
        $select = $this->select(
            "{$this->table}.branch_id",
            "{$this->table}.branch_name",
            DB::raw('SUM(payments.total_amount) as total_amount'),
            DB::raw('SUM(receipt_details.amount) as amount'),
            DB::raw('SUM(receipt_details.amount) - SUM(payments.total_amount) as balance')
        )
            ->leftJoin("payments","payments.branch_code","=","{$this->table}.branch_code")
            ->leftJoin("staffs","staffs.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("receipts","receipts.staff_id","=","staffs.staff_id")
            ->leftJoin("receipt_details","receipt_details.receipt_id","=","receipts.receipt_id")
            ->where("payments.status","=","paid")
            ->where("receipts.status","=","paid")
            ->whereBetween("payments.payment_date",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->whereBetween("receipts.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("staffs.is_deleted", self::NOT_DELETED);

        if ($branchCode != null) {
            $select->where("payments.branch_code", $branchCode);
        }
        if ($branchId != null) {
            $select->where("staffs.branch_id", $branchId);
        }
        if ($paymentType != null) {
            $select->where("payments.payment_type", $paymentType);
        }
        if ($receiptType != null) {
            $select->where("receipts.receipt_type_code", $receiptType);
        }
        if ($paymentMethod != null) {
            $select->where("payments.payment_method", $paymentMethod);
            $select->where("receipt_details.payment_method_code", $paymentMethod);
        }
        $select->groupBy("{$this->table}.branch_id","{$this->table}.branch_name","payments.total_amount","receipt_details.amount");
        return $select->get();
    }

    public function getTotalRecordPaymentEachBranch($time, $branchCode,  $paymentType, $paymentMethod){
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $data = $this->select(
            "{$this->table}.branch_id",
            "{$this->table}.branch_name",
            DB::raw("SUM(payments.total_amount) as total_amount"))
            ->leftJoin("payments","payments.branch_code","=","{$this->table}.branch_code")
            ->where("payments.status","=","paid")
            ->whereBetween("payments.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchCode != null) {
            $data->where("payments.branch_code", $branchCode);
        }
        if ($paymentType != null) {
            $data->where("payments.payment_type", $paymentType);
        }
        if ($paymentMethod != null) {
            $data->where("payments.payment_method", $paymentMethod);
        }
        $data->groupBy("{$this->table}.branch_id", "{$this->table}.branch_name");
        return $data->get();
    }
    public function getTotalRecordReceiptEachBranch($time, $branchId, $receiptType, $paymentMethod){
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $data = $this->select(
            "{$this->table}.branch_id",
            "{$this->table}.branch_name",
            DB::raw('SUM(receipt_details.amount) as amount'))
            ->leftJoin("staffs","staffs.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("receipts","receipts.staff_id","=","staffs.staff_id")
            ->leftJoin("receipt_details","receipt_details.receipt_id","=","receipts.receipt_id")
            ->where("receipts.status","=","paid")
            ->whereBetween("receipts.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("staffs.is_deleted", self::NOT_DELETED);
        if ($branchId != null) {
            $data->where("staffs.branch_id", $branchId);
        }
        if ($receiptType != null) {
            $data->where("receipts.receipt_type_code", $receiptType);
        }
        if ($paymentMethod != null) {
            $data->where("receipt_details.payment_method_code", $paymentMethod);
        }
        $data->groupBy("{$this->table}.branch_id", "{$this->table}.branch_name");
        return $data->get();
    }

    /**
     * Lấy thông tin tổng phiếu chi theo từng chi nhánh của từng ngày
     *
     * @param $time
     * @param $branchCode
     * @param $paymentType
     * @param $paymentMethod
     * @return mixed
     */
    public function getDataChartPaymentEachBranch($time, $branchCode, $paymentType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $data = $this->select(
            "{$this->table}.branch_id",
            "{$this->table}.branch_name",
            DB::raw("SUM(payments.total_amount) as total_amount"),
            DB::raw("CAST(payments.payment_date AS DATE) as payment_date"))
            ->leftJoin("payments","payments.branch_code","=","{$this->table}.branch_code")
            ->where("payments.status","=","paid")
            ->whereBetween("payments.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"]);
        if ($branchCode != null) {
            $data->where("payments.branch_code", $branchCode);
        }
        if ($paymentType != null) {
            $data->where("payments.payment_type", $paymentType);
        }
        if ($paymentMethod != null) {
            $data->where("payments.payment_method", $paymentMethod);
        }
        $data->groupBy("{$this->table}.branch_id", "{$this->table}.branch_name",DB::raw("CAST(payments.payment_date AS DATE)"))
            ->orderBy("{$this->table}.branch_name","ASC")
        ->orderBy("payments.payment_date","ASC");
        return $data->get();
    }

    /**
     * Lấy thông tin tổng phiếu thu của từng chi nhánh theo từng ngày
     *
     * @param $time
     * @param $branchId
     * @param $receiptType
     * @param $paymentMethod
     * @return mixed
     */
    public function getDataChartReceiptEachBranch($time, $branchId, $receiptType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $data = $this->select(
            "{$this->table}.branch_id",
            "{$this->table}.branch_name",
            DB::raw('SUM(receipt_details.amount) as total_amount'),
            DB::raw("CAST(receipts.created_at AS DATE) as payment_date"))
            ->leftJoin("staffs","staffs.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("receipts","receipts.staff_id","=","staffs.staff_id")
            ->leftJoin("receipt_details","receipt_details.receipt_id","=","receipts.receipt_id")
            ->where("receipts.status","=","paid")
            ->whereBetween("receipts.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("staffs.is_deleted", self::NOT_DELETED);
        if ($branchId != null) {
            $data->where("staffs.branch_id", $branchId);
        }
        if ($receiptType != null) {
            $data->where("receipts.receipt_type_code", $receiptType);
        }
        if ($paymentMethod != null) {
            $data->where("receipt_details.payment_method_code", $paymentMethod);
        }
        $data->groupBy("{$this->table}.branch_id", "{$this->table}.branch_name",DB::raw("CAST(receipts.created_at AS DATE)"))
            ->orderBy("{$this->table}.branch_name","ASC")
            ->orderBy("receipts.created_at","ASC");
        return $data->get();
    }
    public function getDataChartBalanceEachBranch($time, $branchCode, $branchId, $paymentType, $receiptType, $paymentMethod)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $lang = Config::get('app.locale');
        $select = $this->select(
            "{$this->table}.branch_id",
            "{$this->table}.branch_name",
            DB::raw('SUM(receipt_details.amount) - SUM(payments.total_amount) as balance'),
            DB::raw("CAST(receipts.created_at AS DATE) as payment_date")
        )
            ->leftJoin("payments","payments.branch_code","=","{$this->table}.branch_id")
            ->leftJoin("staffs","staffs.branch_id", "=", "{$this->table}.branch_id")
            ->leftJoin("receipts","receipts.staff_id","=","staffs.staff_id")
            ->leftJoin("receipt_details","receipt_details.receipt_id","=","receipts.receipt_id")
            ->where("payments.status","=","paid")
            ->where("receipts.status","=","paid")
            ->whereBetween("payments.payment_date",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->whereBetween("receipts.created_at",[$startTime . " 00:00:00", $endTime . " 23:59:59"])
            ->where("staffs.is_deleted", self::NOT_DELETED);

        if ($branchCode != null) {
            $select->where("payments.branch_code", $branchCode);
        }
        if ($branchId != null) {
            $select->where("staffs.branch_id", $branchId);
        }
        if ($paymentType != null) {
            $select->where("payments.payment_type", $paymentType);
        }
        if ($receiptType != null) {
            $select->where("receipts.receipt_type_code", $receiptType);
        }
        if ($paymentMethod != null) {
            $select->where("payments.payment_method", $paymentMethod);
            $select->where("receipt_details.payment_method_code", $paymentMethod);
        }
        $select->groupBy(DB::raw("CAST(receipts.created_at AS DATE)"),
            DB::raw("CAST(payments.payment_date AS DATE)"));
        return $select->get();
    }

    /**
     * Lấy chi tiết chi nhánh
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->leftJoin('province', 'province.provinceid', '=', 'branches.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'branches.districtid')
            ->select('branches.branch_id',
                'branches.branch_name',
                'branches.address',
                'branches.description',
                'branches.phone',
                'branches.is_actived',
                'branches.is_deleted',
                'branches.created_by',
                'branches.updated_by',
                'branches.created_at',
                'branches.updated_at',
                'branches.email',
                'branches.hot_line',
                'branches.provinceid', 'branches.districtid',
                'branches.is_representative', 'branches.representative_code',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name',
                'branches.hot_line',
                'branches.hot_line',
                'latitude',
                'longitude'
            )
            ->where('branches.branch_id', $id)->first();
    }
}