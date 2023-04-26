<?php

namespace Modules\Api\Repositories\UserBrand;

interface UserBrandRepositoryInterface
{
    public function getItem($id);
    public function updatePass( array $data, $id);
    public function changeStatus(array $data, $id);
}