<?php
namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class OrderAppTable extends Model
{
    use ListTableTrait;

    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        'order_id',
        'order_code',
        'customer_id',
        'total',
        'discount',
        'amount',
        'tranport_charge',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'process_status',
        'order_description',
        'customer_description',
        'payment_method_id',
        'order_source_id',
        'transport_id',
        'voucher_code',
        'is_deleted',
        'branch_id',
        'refer_id',
        'discount_member',
        'is_apply',
        'customer_contact_code',
        'shipping_address',
        'receive_at_counter',
    ];


    public function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                'orders.order_id as order_id',
                'orders.order_code as order_code',
                'orders.total as total',
                'orders.discount as discount',
                'orders.amount as amount',
                'orders.tranport_charge as tranport_charge',
                'orders.process_status as process_status',
                'customers.full_name as full_name_cus',
                'orders.created_at as created_at',
                'staffs.full_name as full_name',
                'branches.branch_name as branch_name',
                'branches.branch_id as branch_id',
                'orders.order_description',
//                'order_sources.order_source_name',
                'orders.order_source_id',
                'orders.is_apply',
//                'deliveries.delivery_status',
                'orders.tranport_charge',
                'orders.customer_id',
                'orders.receive_at_counter'
            )
            ->join('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'orders.created_by')
            ->leftJoin('branches', 'branches.branch_id', '=', 'orders.branch_id')
//            ->leftJoin('order_sources', 'order_sources.order_source_id', '=', 'orders.order_source_id')
//            ->join('deliveries', 'deliveries.order_id', '=', 'orders.order_id')
            ->where('orders.is_deleted', 0)
            ->orderBy('orders.created_at', 'desc');

        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('order_code', 'like', '%' . $search . '%');
            });
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('orders.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (isset($filter['receive_at_counter'])){
            $ds = $ds->where($this->table.'.receive_at_counter',$filter['receive_at_counter']);
            unset($filter['receive_at_counter']);
        }

        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        return $ds;
    }

    public function getAll(&$filter = [])
    {
        $ds = $this
            ->select(
                'orders.order_id as order_id',
                'orders.order_code as order_code',
                'orders.total as total',
                'orders.discount as discount',
                'orders.amount as amount',
                'orders.tranport_charge as tranport_charge',
                'orders.process_status as process_status',
                'customers.full_name as full_name_cus',
                'orders.created_at as created_at',
                'staffs.full_name as full_name',
                'branches.branch_name as branch_name',
                'branches.branch_id as branch_id',
                'orders.order_description',
//                'order_sources.order_source_name',
                'orders.order_source_id',
                'orders.is_apply',
//                'deliveries.delivery_status',
                'orders.tranport_charge'
            )
            ->join('customers', 'customers.customer_id', '=', 'orders.customer_id')
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'orders.created_by')
            ->leftJoin('branches', 'branches.branch_id', '=', 'orders.branch_id')
            ->where('orders.is_deleted', 0)
            ->orderBy('orders.created_at', 'desc');

        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('order_code', 'like', '%' . $search . '%');
            });
        }
        if (isset($filter["created_at"]) && $filter["created_at"] != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('orders.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        if (Auth::user()->is_admin != 1) {
            $ds->where('orders.branch_id', Auth::user()->branch_id);
        }
        // filter list
        foreach ($filter as $key => $val)
        {
            if (trim($val) == '') {
                continue;
            }

            $ds->where(str_replace('$', '.', $key), $val);
        }
        return $ds->paginate(1000000000, $columns = ['*'], $pageName = 'page', 1);
    }
}