<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2021
 * Time: 17:35
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class OrderTable extends Model
{
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
        'cashier_by',
        'cashier_date',
        'total_tax',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    const ORDER_CANCEL = "ordercancle";

    /**
     * Thêm đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->order_id;
    }

    /**
     * Chỉnh sửa đơn hàng
     *
     * @param array $data
     * @param $orderId
     * @return mixed
     */
    public function edit(array $data, $orderId)
    {
        return $this->where("order_id", $orderId)->update($data);
    }

    /**
     * Lấy thông tin đơn hàng
     *
     * @param $orderCode
     * @return mixed
     */
    public function getInfoByCode($orderCode)
    {
        return $this
            ->select(
                "{$this->table}.order_id",
                "{$this->table}.order_code",
                "{$this->table}.customer_id",
                "{$this->table}.total",
                "{$this->table}.discount",
                "{$this->table}.amount",
                "{$this->table}.tranport_charge",
                "{$this->table}.process_status",
                "customers.full_name",
                'customers.customer_type'
            )
            ->join("customers", "customers.customer_id", "=", "{$this->table}.customer_id")
            ->where("{$this->table}.order_code", $orderCode)
            ->where("{$this->table}.process_status", "<>", self::ORDER_CANCEL)
            ->first();
    }

    /**
     * Lấy thông tin đơn hàng bằng mã
     *
     * @param $customerId
     * @param $orderCode
     * @return mixed
     */
    public function getOrderByCode($customerId, $orderCode)
    {
        return $this
            ->select(
                "order_id",
                "order_code",
                "customer_id",
                "total",
                "discount",
                "amount",
                "tranport_charge",
                "process_status"
            )
            ->where("customer_id", $customerId)
            ->where("order_code", $orderCode)
            ->where("process_status", "<>", self::ORDER_CANCEL)
            ->first();
    }

    /**
     * Lấy thông tin đơn hàng theo mã deal
     *
     * @param $dealCode
     * @return mixed
     */
    public function getItemByDealCode($dealCode)
    {
        $select = $this->select(
            'order_id',
            'order_code',
            'customer_id',
            'total',
            'discount',
            'amount',
            'tranport_charge',
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
            'deal_code'
        )
            ->where('deal_code', $dealCode)
            ->where('is_deleted', 0);
        return $select->first();
    }
}