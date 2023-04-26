<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/20/2019
 * Time: 5:04 PM
 */

namespace Modules\Admin\Repositories\RoleGroup;


interface RoleGroupRepositoryInterface
{
    public function list(array $filterts = []);

    public function getList2();

    public function add(array $data);

    public function edit(array $data, $id);

    public function checkName($name, $id);

    public function getItem($id);

    public function getOptionActive();
}