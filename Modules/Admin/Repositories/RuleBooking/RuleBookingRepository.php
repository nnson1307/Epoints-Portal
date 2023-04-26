<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 15:10
 */

namespace Modules\Admin\Repositories\RuleBooking;


use Modules\Admin\Models\RuleBookingTable;

class RuleBookingRepository implements RuleBookingRepositoryInterface
{
    protected $rule_booking;
    protected $timestamps = true;

    public function __construct(RuleBookingTable $rule_booking)
    {
        $this->rule_booking = $rule_booking;
    }

    public function list()
    {
        // TODO: Implement list() method.
        return $this->rule_booking->getList();
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->rule_booking->edit($data, $id);
    }
}