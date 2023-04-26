<?php
/**
 * ServiceTimeRepositoryInterface
 * User: Sinh
 * Date: 3/31/2018
 */

namespace Modules\Services\Repositories\ServiceTime;


interface ServiceTimeRepositoryInterface
{
    public function list(array $filters = []);

    public function getOptionServiceTime();
}