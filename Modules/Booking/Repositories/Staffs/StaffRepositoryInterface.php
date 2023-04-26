<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 26/03/2018
 * Time: 2:24 CH
 */

namespace Modules\Booking\Repositories\Staffs;


interface StaffRepositoryInterface
{
    public function bookingGetTechnician(array $filters=[]);
}