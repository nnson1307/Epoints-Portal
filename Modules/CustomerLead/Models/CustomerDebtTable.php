<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDebtTable extends Model
{
    protected $table = 'customer_debt';
    protected $primaryKey = 'customer_debt_id';
    protected $fillable = [
        'customer_debt_id',
        'debt_code',
        'customer_id',
        'staff_id',
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
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_debt_id;
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
     * Lấy công nợ của KH
     *
     * @param $id_customer
     * @return mixed
     */
    public function getDebtByCustomer($id_customer)
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
}