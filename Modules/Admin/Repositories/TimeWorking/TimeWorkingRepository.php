<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 10:18
 */

namespace Modules\Admin\Repositories\TimeWorking;


use Modules\Admin\Models\TimeWorkingTable;

class TimeWorkingRepository implements TimeWorkingRepositoryInterface
{
    protected $time_working;
    protected $timestamps = true;

    public function __construct(TimeWorkingTable $time_working)
    {
        $this->time_working = $time_working;
    }

    /**
     * @return mixed
     */
    public function list()
    {
        // TODO: Implement list() method.
        return $this->time_working->getList();
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->time_working->edit($data, $id);
    }
}