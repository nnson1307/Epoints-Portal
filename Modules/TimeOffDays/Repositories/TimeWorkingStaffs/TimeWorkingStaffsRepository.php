<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeWorkingStaffs;

use Modules\TimeOffDays\Models\TimeWorkingStaffsTable;

class TimeWorkingStaffsRepository implements TimeWorkingStaffsRepositoryInterface
{
    protected $repo;

    public function __construct(TimeWorkingStaffsTable $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(){
        return $this->repo->getAll();
    }


    public function getDetail($id){
        return $this->repo->getDetail($id);
    }
    
    public function edit($data, $id){
        return $this->repo->edit($data, $id);
    }
    public function removeTimeOffDay($id){
        return $this->repo->removeTimeOffDay($id);
    }
}