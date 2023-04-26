<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */

namespace Modules\TimeOffDays\Repositories\TimeOffDaysFiles;

use Modules\TimeOffDays\Models\TimeOffDaysFilesTable;

class TimeOffDaysFilesRepository implements TimeOffDaysFilesRepositoryInterface
{
    protected $repo;

    public function __construct(TimeOffDaysFilesTable $repo)
    {
        $this->repo = $repo;
    }

    public function getLists($data){
        return $this->repo->getLists($data);
    }

    public function add($data){
        return $this->repo->add($data);
    }

    public function remove($id){
        return $this->repo->remove($id);
    }
}