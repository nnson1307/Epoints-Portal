<?php

/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:54
 */

namespace Modules\ReportSale\Models;


use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CustomersTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customers_id";

    protected $casts = [
        "amount" => 'float',
    ];


    /**
     * Lấy công nợ
     *
     * @param $branchId
     * @param $startDate
     * @param $endDate
     * @return mixed
     */
    public function getTotalCustomer($time, $branchId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("COUNT({$this->table}.customer_id) as number")
            )
            ->where("{$this->table}.is_deleted", 0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($branchId != null) {
            $ds->where("{$this->table}.branch_id", $branchId);
        }
        return $ds->first();
    }

    public function getTotalCustomerByStaff($time, $staffId)
    {
        $startTime = $endTime = null;
        if ($time != null) {
            $time2 = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $time2[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $time2[1])->format('Y-m-d');
        }
        $ds = $this
            ->select(
                DB::raw("COUNT({$this->table}.customer_id) as number")
            )
            ->where("{$this->table}.is_deleted", 0)
            ->whereBetween("{$this->table}.created_at", [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        if ($staffId != null) {
            $ds->where("{$this->table}.created_by", $staffId);
        }
        return $ds->first();
    }

     //search product by keyword
     public function searchCustomer($keyword, $customerGroupId)
     {
         $ds = $this->where('is_deleted', 0);
         $ds->where(function ($select)use ($keyword)  {
                $select->where('full_name', 'like', '%' . $keyword . '%')
                ->orWhere('phone1', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
            });
            if($customerGroupId != null){
                $ds->where('customer_group_id', $customerGroupId);
            }
         return $ds->get();
     }
}