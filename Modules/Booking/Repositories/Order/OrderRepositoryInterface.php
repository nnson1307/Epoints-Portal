<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/20/2018
 * Time: 10:20 PM
 */

namespace Modules\Booking\Repositories\Order;


interface OrderRepositoryInterface
{
    public function getItemDetail($id);

//    public function detailDayCustomer($id);

    public function detailCustomer($id);
}