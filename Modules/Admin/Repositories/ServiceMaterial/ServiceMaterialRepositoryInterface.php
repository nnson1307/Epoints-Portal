<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/16/2018
 * Time: 3:53 PM
 */

namespace Modules\Admin\Repositories\ServiceMaterial;


interface ServiceMaterialRepositoryInterface
{
    public function list(array $filters = []);
    public function add(array $data);
    public function remove($id);
    public function edit(array $data, $id);
    public function getItem($id);
    public function getSelect($id);
    public function deleteWhenEdit(array $data,$id);
    public function getListServiceDetail($id,array $filters=[]);
}