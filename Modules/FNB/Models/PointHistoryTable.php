<?php


namespace Modules\FNB\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class PointHistoryTable extends Model
{
    use ListTableTrait;
    protected $table = 'point_history';
    protected $primaryKey = 'point_history_id';
    protected $fillable = [
        'point_history_id',
        'customer_id',
        'order_id',
        'point',
        'type',
        'point_description',
        'is_deleted',
        'accepted_ranking',
        'created_at',
        'updated_at',
        'created_by'
    ];

    /**
     * DS tích luỹ điểm
     *
     * @param array $filter
     * @return mixed
     */
    public function _getList($filter = [])
    {
        $ds = $this
            ->select(
                "orders.order_code",
                "{$this->table}.point_history_id",
                "{$this->table}.order_id",
                "{$this->table}.object_id",
                "{$this->table}.point",
                "{$this->table}.type",
                "{$this->table}.point_description",
                "{$this->table}.accepted_ranking",
                "{$this->table}.updated_at as created_at",
                "orders.amount"
            )
            ->leftJoin("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where('point_history.is_deleted', 0)
            ->orderBy('point_history.created_at', 'desc');

        return $ds;
    }

    /**
     * @param $customer_id
     * @param $description
     * @return mixed
     */
    public function getHistoryByDescription($customer_id, $description)
    {
        $ds = $this
            ->select(
                'customer_id',
                'point',
                'type',
                'point_description'
            )
            ->where('customer_id', $customer_id)
            ->where('point_description', $description)
            ->where('created_at', date('Y-m-d'))
            ->first();
        return $ds;
    }

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
        $select = $this
            ->select(
                "orders.order_code",
                "{$this->table}.point_history_id",
                "{$this->table}.order_id",
                "{$this->table}.object_id",
                "{$this->table}.point",
                "{$this->table}.type",
                "{$this->table}.point_description",
                "{$this->table}.accepted_ranking",
                "{$this->table}.updated_at as created_at",
                "orders.amount"
            )
            ->leftJoin("orders", "orders.order_id", "=", "{$this->table}.order_id")
            ->where('point_history.customer_id', $customerId)
            ->where('point_history.is_deleted', 0)
            ->orderBy('point_history.created_at', 'desc')
            ->get();
        return $select;
    }

    /**
     * Hủy đơn hàng thì xóa history.
     * @param $orderId
     *
     * @return mixed
     */
    public function cancelOrder($orderId)
    {
        return $this->where('order_id', $orderId)->update(
            ['is_deleted' => 1]
        );
    }

    /**
     * Lấy điểm của order.
     * @param $orderId
     *
     * @return mixed
     */
    public function getPointOrder($orderId)
    {
        return $this->where('order_id', $orderId)->first();
    }

    /**
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function getPointGroupByCustomer($startTime, $endTime)
    {
        $ds = $this
            ->join('customers', 'customers.customer_id', '=', 'point_history.customer_id')
            ->select(
                'customers.customer_id',
                'customers.full_name',
                DB::raw('sum(point_history.point) as total')
            )
            ->where('point_history.type', 'plus')
            ->whereBetween('point_history.created_at', [$startTime, $endTime])
            ->groupBy('point_history.customer_id')->get();
        return $ds;
    }
}