<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/10/2021
 * Time: 17:39
 */

namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;

class ContractMapOrderTable extends Model
{
    protected $table = "contract_map_order";
    protected $primaryKey = "contract_map_order_id";
    protected $fillable = [
        "contract_map_order_id",
        "contract_code",
        "order_code",
        "source",
        "created_at",
        "updated_at"
    ];

    const PAY_SUCCESS = 'paysuccess';
    const ORDER_CANCEL = 'ordercancle';

    /**
     * Thêm dữ liệu map data giữa hợp đồng và đơn hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy thông tin đơn hàng gần nhất map với hợp đồng
     *
     * @param $contractCode
     * @return mixed
     */
    public function getOrderMap($contractCode)
    {
        return $this
            ->select(
                "{$this->table}.contract_map_order_id",
                "orders.order_id",
                "orders.order_code",
                "orders.total",
                "orders.discount",
                "orders.amount",
                "orders.tranport_charge",
                "orders.process_status"
            )
            ->join("orders", "orders.order_code", "=", "{$this->table}.order_code")
            ->where("orders.process_status", "<>", self::ORDER_CANCEL)
            ->where("{$this->table}.contract_code", $contractCode)
            ->orderBy("{$this->table}.contract_map_order_id", "desc")
            ->first();
    }

    /**
     * Lấy thông tin hợp đồng map với đơn hàng
     *
     * @param $contractCode
     * @param $orderCode
     * @return mixed
     */
    public function getOrderMapByContract($contractCode, $orderCode)
    {
        return $this
            ->select(
                "{$this->table}.contract_map_order_id",
                "orders.order_id",
                "orders.order_code",
                "orders.total",
                "orders.discount",
                "orders.amount",
                "orders.tranport_charge",
                "orders.process_status"
            )
            ->join("orders", "orders.order_code", "=", "{$this->table}.order_code")
            ->where("orders.process_status", "<>", self::PAY_SUCCESS)
            ->where("{$this->table}.contract_code", $contractCode)
            ->where("{$this->table}.order_code", $orderCode)
            ->first();
    }

    /**
     * Lấy thông tin hợp đồng map với đơn hàng
     *
     * @param $orderCode
     * @return mixed
     */
    public function getContractMapOrder($orderCode)
    {
        return $this
            ->select(
                "{$this->table}.contract_map_order_id",
                "{$this->table}.contract_code",
                "contracts.contract_id"
            )
            ->join("contracts", "contracts.contract_code", "=", "{$this->table}.contract_code")
            ->where("{$this->table}.order_code", $orderCode)
            ->first();
    }
}