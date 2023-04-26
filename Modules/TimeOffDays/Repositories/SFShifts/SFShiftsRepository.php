<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\SFShifts;

use Modules\TimeOffDays\Models\SFShiftsTable;

class SFShiftsRepository implements SFShiftsRepositoryInterface
{
    protected $repo;

    public function __construct(SFShiftsTable $repo)
    {
        $this->repo = $repo;
    }

    public function getLists($data){
        return $this->repo->getLists($data);
    }

}