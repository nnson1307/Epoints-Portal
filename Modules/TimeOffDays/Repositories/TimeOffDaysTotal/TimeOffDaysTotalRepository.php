<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysTotal;

use Modules\TimeOffDays\Models\TimeOffDaysTotalTable;

class TimeOffDaysTotalRepository implements TimeOffDaysTotalRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysTotalTable $repo)
    {
        $this->repo = $repo;
    }

    public function getLists($id){
        return $this->repo->getLists($id);
    }

    public function checkValidTotal($staffId, $typeId){

        return $this->repo->checkValidTotal($staffId, $typeId);
    }

    public function edit($data, $staffId, $typeOffDaysId){
        return $this->repo->edit($data, $staffId, $typeOffDaysId);
    }
}