<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/20/2018
 * Time: 10:20 PM
 */

namespace Modules\Booking\Repositories\Order;


use Modules\Booking\Models\OrderTable;

class OrderRepository implements OrderRepositoryInterface
{
    private $order;

    /**
     * OrderRepository constructor.
     * @param OrderTable $orders
     */
    public function __construct(OrderTable $orders)
    {
        $this->order = $orders;
    }


    /**
     * @param $id
     * @return mixed|void
     */
    public function getItemDetail($id)
    {
        return $this->order->getItemDetail($id);
    }

    /**
     * Detail customer
     * @param $id
     *
     * @return mixed
     */
    public function detailCustomer($id)
    {
        return $this->order->detailCustomer($id);
    }
}