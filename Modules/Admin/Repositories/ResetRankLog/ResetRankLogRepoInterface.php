<?php


namespace Modules\Admin\Repositories\ResetRankLog;


interface ResetRankLogRepoInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);
}