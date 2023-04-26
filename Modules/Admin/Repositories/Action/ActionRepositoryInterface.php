<?php

namespace Modules\Admin\Repositories\Action;

interface ActionRepositoryInterface
{
    public function add(array $data);

    public function getList();
}