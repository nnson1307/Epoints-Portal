<?php


namespace Modules\Admin\Repositories\ResetRankLog;


use Modules\Admin\Models\ResetRankLogTable;

class ResetRankLogRepo implements ResetRankLogRepoInterface
{
    protected $resetRankLog;
    protected $timestamps = true;

    public function __construct(
        ResetRankLogTable $resetRankLog
    ) {
        $this->resetRankLog = $resetRankLog;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->resetRankLog->add($data);
    }
}