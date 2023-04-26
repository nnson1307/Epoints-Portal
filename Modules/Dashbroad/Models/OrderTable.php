<?php
/**
 * Created by PhpStorm.
 * User: tuanva
 * Date: 2019-03-26
 * Time: 09:31
 */

namespace Modules\Dashbroad\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class OrderTable extends Model
{
    use ListTableTrait;
    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        'order_id', 'order_code', 'customer_id', 'total', 'discount', 'amount', 'tranport_charge', 'created_by', 'updated_by',
        'created_at', 'updated_at', 'process_status', 'order_description', 'customer_description', 'payment_method_id',
        'order_source_id', 'transport_id', 'voucher_code', 'is_deleted', 'branch_id'
    ];


    public function getOrders($status)
    {
        $date = Carbon::now()->format('Y-m-d');
        $ds = $this
//            ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
//            ->leftJoin('branches', 'branches.branch_id', '=', 'orders.branch_id')
//            ->leftJoin('staffs', 'staffs.staff_id', '=', 'orders.created_by')
            ->where('orders.is_deleted', 0)
//            ->where('orders.process_status', 'new')
            ->whereDate('orders.created_at', $date);
//            ->whereBetween("orders.created_at", [$date . ' 00:00:00', $date . ' 23:59:59']);
        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $ds->count();
    }

    public function orderByMonthYear($month, $year)
    {
        $select=$this->whereMonth('created_at', $month)->whereYear('created_at', '=', $year)->where('is_deleted', 0);
        if (Auth::user()->is_admin != 1) {
            $select->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $select->count();
    }

    public function orderByDateMonth($date, $month, $year)
    {

        $day = $year . '-' . $month . '-' . $date;
        $select = $this->whereDate('created_at', '=', $day)->where('is_deleted', 0);
        if (Auth::user()->is_admin != 1) {
            $select->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $select->count();
    }

    public function _getList($filter = [])
    {
        $date = Carbon::now()->format('Y-m-d');

        $ds = $this
//            ->leftJoin('customers', 'customers.customer_id', '=', 'orders.customer_id')
//            ->leftJoin('branches', 'branches.branch_id', '=', 'orders.branch_id')
//            ->leftJoin('staffs', 'staffs.staff_id', '=', 'orders.created_by')
//            ->leftJoin('receipts', 'receipts.order_id', '=', 'orders.order_id')
            ->select('orders.order_id as order_id',
                'orders.order_code as order_code',
                'orders.total as total',
//                'staffs.full_name as staffs',
                'orders.discount as discount',
                'orders.amount as amount',
//                'branches.branch_name',
                'orders.process_status as process_status',
//                'customers.full_name as full_name',
                'orders.created_at as created_at',
//                'receipts.amount_paid as amount_paid',
                'orders.created_by',
                'orders.branch_id',
                'orders.customer_id',
                'orders.order_source_id'
            )
            ->where('orders.is_deleted', 0)
//            ->where('orders.process_status', 'new')
            ->whereDate('orders.created_at', $date);
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query
//                    ->where('customers.full_name', 'like', '%' . $search . '%')
                    ->where('order_code', 'like', '%' . strtoupper($search) . '%');
            });
        }
        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $ds->orderBy('orders.created_at', 'desc');
    }

    public function getOrderByObjectType($type, $date)
    {
        if (Auth::user()->is_admin != 1) {
            $q = $this->select(DB::raw("SUM(detail.quantity) as quantity"), DB::raw("SUM(detail.amount) as amount"), 'detail.object_type as type')
                ->join('order_details as detail', 'detail.order_id', '=', 'orders.order_id')
                ->where('orders.process_status', 'paysuccess')
                ->where('detail.object_type', $type)
                ->where('orders.branch_id', Auth::user()->branch_id)
                ->groupBy('detail.object_type')
                ->whereBetween('orders.created_at', $date)->get()->toArray();

            if (count($q) > 0) {
                $q[0]['type'] = $this->getType($type);
            } else {
                $q = [
                    [
                        'quantity' => 0,
                        'amount' => 0,
                        'type' => $this->getType($type)
                    ]
                ];
            }


            return $q;
        }else{
            $q = $this->select(DB::raw("SUM(detail.quantity) as quantity"), DB::raw("SUM(detail.amount) as amount"), 'detail.object_type as type')
                ->join('order_details as detail', 'detail.order_id', '=', 'orders.order_id')
                ->where('orders.process_status', 'paysuccess')
                ->where('detail.object_type', $type)
                ->groupBy('detail.object_type')
                ->whereBetween('orders.created_at', $date)->get()->toArray();

            if (count($q) > 0) {
                $q[0]['type'] = $this->getType($type);
            } else {
                $q = [
                    [
                        'quantity' => 0,
                        'amount' => 0,
                        'type' => $this->getType($type)
                    ]
                ];
            }


            return $q;
        }

    }

    public function getTopService($date)
    {
        $q = $this->select(DB::raw("SUM(detail.amount) as amount"), DB::raw("count(*) as used"), 'detail.object_name as name')
            ->join('order_details as detail', 'detail.order_id', '=', 'orders.order_id')
            ->where('orders.process_status', 'paysuccess')
            ->where('detail.object_type', 'service')
            ->groupBy('detail.object_id')
            ->orderBy('used', 'desc')
            ->whereBetween('orders.created_at', $date);
        if (Auth::user()->is_admin != 1) {
            $q->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $q->take(10)->get()->toArray();
    }

    private function getType($type)
    {
        switch ($type) {
            case 'service_card':
                return __("Thẻ dịch vụ");
                break;
            case 'product':
                return __("Sản phẩm");
                break;
            case 'service':
                return __("Dịch vụ");
                break;
            case 'member_card':
                return __("Thẻ thành viên");
                break;
            default:
                break;
        }
    }

}