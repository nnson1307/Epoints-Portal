<?php

namespace Modules\Ticket\Repositories\Action;

interface ActionRepositoryInterface
{
    public function add(array $data);

    public function getList();
}