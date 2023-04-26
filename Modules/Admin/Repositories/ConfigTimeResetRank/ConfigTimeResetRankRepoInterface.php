<?php


namespace Modules\Admin\Repositories\ConfigTimeResetRank;


interface ConfigTimeResetRankRepoInterface
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters=[]);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * @param array $data
     * @return mixed
     */
    public function edit(array $data);

    /**
     * @param $type
     * @return mixed
     */
    public function getItemByType($type);
}