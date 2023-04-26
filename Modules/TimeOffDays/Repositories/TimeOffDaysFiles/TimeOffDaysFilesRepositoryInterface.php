<?php
/**
 * Created by PhpStorm.
 * User: PHONGDT
 */
namespace Modules\TimeOffDays\Repositories\TimeOffDaysFiles;


interface TimeOffDaysFilesRepositoryInterface
{
    public function getLists($data);

    public function add($data);

    public function remove($id);
    
}