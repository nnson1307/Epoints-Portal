<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 3:14 PM
 */

namespace Modules\Booking\Repositories\TimeWork;


use Modules\Booking\Models\TimeWorkTable;

class TimeWorkRepository implements TimeWorkRepositoryInterface
{
    protected $timeWork;

    public function __construct(TimeWorkTable $timeWork)
    {
        $this->timeWork = $timeWork;
    }

    public function getTimeWork()
    {
        return $this->timeWork->getTimeWork();
    }
}