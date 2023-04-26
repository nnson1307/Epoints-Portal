<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 10:18
 */

namespace Modules\Admin\Repositories\TimeWorking;


interface TimeWorkingRepositoryInterface
{
    public function list();

    public function edit(array $data, $id);
}