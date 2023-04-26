<?php


namespace Modules\FNB\Repositories\Service;


interface ServiceRepositoryInterface
{
    /**
     * get item
     * @param array $data
     * @return $data
     */
    public function getItem($id);
}