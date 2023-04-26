<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 15:10
 */

namespace Modules\Admin\Repositories\RuleBooking;


interface RuleBookingRepositoryInterface
{
    public function list();

    public function edit(array $data, $id);
}