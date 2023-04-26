<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/12/2018
 * Time: 10:28 AM
 */

namespace Modules\Admin\Repositories\ServiceCardGroup;


interface ServiceCardGroupRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function getItem($id);

    public function getOption();

    public function getAllName();

    public function checkName($id, $name);

    public function edit(array $data, $id);

    public function checkSlug($name, $id);

    public function remove($id);
}