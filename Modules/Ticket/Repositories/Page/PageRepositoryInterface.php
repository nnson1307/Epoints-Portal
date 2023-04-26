<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/8/2019
 * Time: 2:00 PM
 */

namespace Modules\Ticket\Repositories\Page;


interface PageRepositoryInterface
{
    public function add(array $data);

    public function getList();
}