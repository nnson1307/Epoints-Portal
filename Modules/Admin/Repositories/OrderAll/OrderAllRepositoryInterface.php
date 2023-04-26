<?php

/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/20/2018
 * Time: 10:20 PM
 */

namespace Modules\Admin\Repositories\OrderAll;


interface OrderAllRepositoryInterface
{
    /**
     * danh sach tat ca don hang
     * @param $screening
     * @return mixed
     */
    public function allOrder($screening = []);
    public function list($screening = []);
    public function exportList($params = []);

}