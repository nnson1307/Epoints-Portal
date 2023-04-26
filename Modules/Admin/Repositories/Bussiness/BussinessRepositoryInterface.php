<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 20/3/2019
 * Time: 15:28
 */

namespace Modules\Admin\Repositories\Bussiness;


interface BussinessRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function getItem($id);

    public function edit(array $data, $id);

    public function remove($id);

    public function testName($name, $id);

    public function getBussinessOption();
}