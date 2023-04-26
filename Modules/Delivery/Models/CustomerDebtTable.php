<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 4/21/2020
 * Time: 9:33 AM
 */

namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerDebtTable extends Model
{
    protected $table = "customer_debt";
    protected $primaryKey = "customer_debt_id";
    protected $fillable = [
        "customer_debt_id",
        "debt_code",
        "customer_id",
        "staff_id",
        "debt_type",
        "order_id",
        "status",
        "amount",
        "amount_paid",
        "note",
        "updated_by",
        "created_at",
        "updated_at",
        "created_by"
    ];

    /**
     * Lấy thông tin công nợ bằng order_id
     *
     * @param $orderId
     * @return mixed
     */
    public function getInfo($orderId)
    {
        return $this
            ->select(
                "customer_debt_id",
                "debt_code",
                "customer_id",
                "staff_id",
                "debt_type",
                "order_id",
                "status",
                "amount",
                "amount_paid",
                "note"
            )
            ->where("order_id", $orderId)
            ->first();
    }

    /**
     * Thêm công nợ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_debt_id;
    }

    /**
     * Chỉnh sửa công nợ
     *
     * @param array $data
     * @param $customerDebtId
     * @return mixed
     */
    public function edit(array $data, $customerDebtId)
    {
        return $this->where("customer_debt_id", $customerDebtId)->update($data);
    }
}