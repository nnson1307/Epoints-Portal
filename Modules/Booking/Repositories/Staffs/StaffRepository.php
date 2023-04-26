<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Booking\Repositories\Staffs;

use Modules\Booking\Models\StaffsTable;


class StaffRepository implements StaffRepositoryInterface
{

    /**
     * @var staffTable
     */
    protected $staff;
    protected $timestamps = true;

    public function __construct(StaffsTable $staffs)
    {
        $this->staff = $staffs;
    }

    public function bookingGetTechnician(array $filters = [])
    {
        return $this->staff->bookingGetTechnician($filters);
    }
}