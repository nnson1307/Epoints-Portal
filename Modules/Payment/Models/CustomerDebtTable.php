<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 10/05/2022
 * Time: 18:34
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerDebtTable extends Model
{
    protected $table = 'customer_debt';
    protected $primaryKey = 'customer_debt_id';

    const NOT_DELETED = 0;
    const STATUS_CANCEL = "cancel";

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
                'customers.full_name as customer_name',
                'customers.phone1 as customer_phone',
                'customer_debt.note',
                'customers.profile_code',
                'customers.customer_code',
                'branches.branch_name',
                'branches.branch_id',
                "{$this->table}.status"
            )
            ->leftJoin('staffs', 'staffs.staff_id', '=', 'customer_debt.staff_id')
            ->leftJoin('orders', 'orders.order_id', '=', 'customer_debt.order_id')
            ->leftJoin('customers', 'customers.customer_id', '=', 'customer_debt.customer_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'staffs.branch_id')
            ->where('customer_debt.customer_debt_id', $id)
            ->first();
    }
    
    public function removeOrderCustomerDept($data, $orderId)
    {
        return $this->where('order_id', $orderId)->update($data);
    }

    /**
     * Cập nhật công nợ
     *
     * @param array $data
     * @param $debtId
     * @return mixed
     */
    public function edit(array $data, $debtId)
    {
        return $this->where("customer_debt_id", $debtId)->update($data);
    }

    /**
     * Lấy công nợ của đơn hàng
     *
     * @param $orderId
     * @return mixed
     */
    public function getDebtByOrder($orderId)
    {
        return $this
            ->where("order_id", $orderId)
            ->where("is_deleted", self::NOT_DELETED)
            ->where("status", "<>", self::STATUS_CANCEL)
            ->first();
    }
}