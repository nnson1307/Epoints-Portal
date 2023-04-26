<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 17:21
 */

namespace Modules\Admin\Repositories\BookingExtra;


interface BookingExtraRepositoryInterface
{
    public function list();

    public function edit(array $data, $id);
}