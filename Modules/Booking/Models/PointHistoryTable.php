<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 5:21 PM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistoryTable extends Model
{
    public $timestamps = false;
    protected $table = "point_history";
    protected $primaryKey = "point_history_id";
    protected $fillable
        = [
            'point_history_id', 'customer_id', 'order_id', 'point', 'type',
            'point_description', 'created_at', 'updated_at', 'created_by',
            'is_deleted', 'accepted_ranking', 'object_id'
        ];

    /**
     * Add
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $oCustom = $this->create($data);
        return $oCustom->point_history_id;
    }

    /**
     * Lịch sử tích điểm.
     *
     * @param $customerId
     *
     * @return mixed
     */
    public function getHistory($customerId)
    {
        $select = $this->select(
            'branches.branch_name',
            'orders.order_code',
            'orders.amount',
            'point_history.point',
            'point_history.created_at',
            'point_history.type',
            'orders.order_id',
            'customers.full_name'
        )
            ->join('orders', 'orders.order_id', 'point_history.order_id')
            ->join('customers', 'customers.customer_id', 'point_history.customer_id')
            ->join('branches', 'branches.branch_id', 'orders.branch_id')
            ->where('point_history.customer_id', $customerId)
            ->where('point_history.is_deleted', 0)
            ->orderBy('point_history.created_at', 'desc')->get();
        return $select;
    }

    public function cancelOrder($orderId)
    {
        return $this->where('order_id', $orderId)->update(
            ['is_deleted' => 1]
        );
    }

    public function getPointOrder($orderId) {
        return $this->where('order_id', $orderId)->first();
    }
}