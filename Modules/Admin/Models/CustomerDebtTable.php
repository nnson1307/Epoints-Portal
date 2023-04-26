<?php


namespace Modules\Admin\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class CustomerDebtTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_debt';
    protected $primaryKey = 'customer_debt_id';
    protected $fillable = [
        'customer_debt_id',
        'debt_code',
        'customer_id',
        'staff_id',
        'branch_id',
        'debt_type',
        'order_id',
        'status',
        'amount',
        'amount_paid',
        'note',
        'updated_by',
        'created_by',
        'updated_at',
        'created_at',
    ];

    /**
     * @param array $filters
     * @return mixed
     */
    public function _getList(&$filters = [])
    {
        $select = $this
            ->select(
                'orders.order_code as order_code',
                'customers.full_name as customer_name',
                'staffs.full_name as staff_name',
                'customer_debt.created_at as created_at',
                'customer_debt.customer_debt_id',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.note',
                'customer_debt.status',
                'customer_debt.debt_type',
                'branches.branch_name',
                "customers.customer_code",
                "{$this->table}.customer_id",
                "{$this->table}.debt_code",
                "orders.order_id"
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->orderBy('customer_debt.created_at', 'desc');
        if (
            isset($filters['search_keyword'])
            && $filters['search_keyword'] != ''
        ) {
            $keyword = $filters['search_keyword'];
            $select->where(function ($query) use ($keyword) {
                $query->where(
                    'customers.full_name',
                    'like',
                    '%' . $keyword . '%'
                )
                    ->orWhere(
                        'orders.order_code',
                        'like',
                        '%' . $keyword . '%'
                    );
            });
            unset($filters['search_keyword']);
        }
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $select->whereBetween('customer_debt.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }

        if (Auth::user()->is_admin != 1) {
            $select->where('branches.branch_id', Auth::user()->branch_id);
        }
        return $select;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_debt_id;
    }

    public function cancleReceipt($id)
    {
        return $this->where("customer_debt_id", $id)->update([
            "status" => "cancel"
        ]);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('customer_debt_id', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCustomerDebt($id)
    {
        return $this
            ->select(
                'customer_debt.customer_debt_id',
                'customer_debt.debt_code',
                'customer_debt.customer_id',
                'customer_debt.order_id',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.created_at',
                'customer_debt.created_by',
                'staffs.full_name',
                'customer_debt.debt_type',
                'orders.order_code',
                'orders.order_source_id',
                'orders.order_id',
                'customers.full_name as customer_name',
                'customers.phone1 as customer_phone',
                'customer_debt.note',
                'customers.profile_code',
                'customers.customer_code',
                'branches.branch_name',
                'branches.branch_id'
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->where('customer_debt.customer_debt_id', $id)
            ->first();
    }

    /**
     * @param $id_customer
     * @return mixed
     */
    public function getItemDebt($id_customer)
    {
        return $this
            ->select(
                'orders.order_code as order_code',
                'orders.order_source_id',
                'orders.order_id',
                'customers.full_name as customer_name',
                'staffs.full_name as staff_name',
                'customer_debt.created_at as created_at',
                'customer_debt.customer_debt_id',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.note',
                'customer_debt.status',
                'customer_debt.debt_type',
                'staffs.full_name'
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->where('customer_debt.customer_id', $id_customer)
            ->where('customer_debt.status', '!=', 'cancel')
            ->orderBy('customer_debt.created_at', 'desc')
            ->get();
    }

    /**
     * @param $id_branch
     * @param $time
     * @return mixed
     */
    public function reportDebtAll($id_branch, $time)
    {
        $ds = $this
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.created_by')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->select(
                'branches.branch_name',
                'branches.branch_id',
                'customer_debt.amount',
                'customer_debt.status',
                'customer_debt.amount_paid'
            );
        if (isset($id_branch)) {
            $ds->where('branches.branch_id', $id_branch);
        }
        if (isset($time) && $time != "") {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('customer_debt.created_at', [$startTime . ' 00:00:00', $endTime . ' 23:59:59']);
        }
        return $ds->get();
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    /**
     * @param $order_id
     * @return mixed
     */
    public function getCustomerDebtByOrder($order_id)
    {
        $ds = $this->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->select(
                'customer_debt.customer_debt_id',
                'customer_debt.debt_code',
                'customer_debt.customer_id',
                'customer_debt.order_id',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.created_at',
                'customer_debt.created_by',
                'staffs.full_name',
                'customer_debt.debt_type',
                'orders.order_code',
                'customers.full_name as customer_name',
                'customer_debt.note'
            )
            ->where('customer_debt.order_id', $order_id)
            ->where('debt_type', 'order')
            ->first();
        return $ds;
    }

    /**
     * Lấy data export cho Sie
     *
     * @param $beforeDate
     * @return mixed
     */
    public function getDebtExportSie($beforeDate)
    {
        return $this
            ->select(
                'orders.order_code as order_code',
                'customers.full_name as customer_name',
                'staffs.full_name as staff_name',
                'customer_debt.created_at as created_at',
                'customer_debt.customer_debt_id',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.note',
                'customer_debt.status',
                'customer_debt.debt_type',
                'branches.branch_name',
                "customers.customer_code",
                "{$this->table}.customer_id",
                "{$this->table}.debt_code",
                "orders.order_id",
                "customers.phone1 as customer_phone"
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->where("{$this->table}.created_at", "<", $beforeDate)
            ->get();
    }

    /**
     * Lấy công nợ của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getDebtByCustomer($customerId)
    {
        return $this
            ->select(
                "orders.order_code as order_code",
                "orders.order_source_id",
                "orders.order_id",
                "customers.full_name as customer_name",
                "staffs.full_name as staff_name",
                "customer_debt.created_at as created_at",
                "customer_debt.customer_debt_id",
                "customer_debt.amount",
                "customer_debt.amount_paid",
                "customer_debt.note",
                "customer_debt.status",
                "customer_debt.debt_type",
                "staffs.full_name"
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->where("{$this->table}.customer_id", $customerId)
            // ->where("{$this->table}.status", '!=', 'cancel')
            ->whereNotIn("{$this->table}.status", ['cancel', 'paid'])
            ->where("{$this->table}.is_deleted", 0)
            ->get();
    }

    /**
     * Lấy công nợ thanh toán chưa hoàn thành của KH
     *
     * @param $customerId
     * @return mixed
     */
    public function getDebtNotFinishByCustomer($customerId)
    {
        return $this
            ->select(
                "orders.order_code as order_code",
                "orders.order_source_id",
                "orders.order_id",
                "customers.full_name as customer_name",
                "staffs.full_name as staff_name",
                "customer_debt.created_at as created_at",
                "customer_debt.customer_debt_id",
                "{$this->table}.debt_code",
                "customer_debt.amount",
                "customer_debt.amount_paid",
                "customer_debt.note",
                "customer_debt.status",
                "customer_debt.debt_type",
                "staffs.full_name"
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->where("{$this->table}.customer_id", $customerId)
            ->whereNotIn("{$this->table}.status", ['cancel', 'paid'])
            ->where("{$this->table}.is_deleted", 0)
            ->get();
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getListByCustomer(&$filters = [])
    {

        $select = $this
            ->select(
                'orders.order_code as order_code',
                'customers.full_name as customer_name',
                'staffs.full_name as staff_name',
                'customer_debt.created_at as created_at',
                'customer_debt.customer_debt_id',
                'customer_debt.amount',
                'customer_debt.amount_paid',
                'customer_debt.note',
                'customer_debt.status',
                'customer_debt.debt_type',
                'branches.branch_name',
                "customers.customer_code",
                "{$this->table}.customer_id",
                "{$this->table}.debt_code",
                "orders.order_id"
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->whereNotIn("{$this->table}.status", ['cancel', 'paid'])
            ->where("{$this->table}.customer_id", '=', $filters['customer_debt$customer_id'])
            ->orderBy('customer_debt.created_at', 'desc');

        if (Auth::user()->is_admin != 1) {
            $select->where('branches.branch_id', Auth::user()->branch_id);
        }
        $page    = (int) ($filters['page'] ?? 1);
        $display = (int) ($filters['perpage'] ?? 10);
        return $select->paginate($display, $columns = ['*'], $pageName = 'page', $page);
        return $select;
    }
}
