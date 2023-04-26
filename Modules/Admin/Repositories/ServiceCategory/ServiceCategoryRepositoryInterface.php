<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/12/2018
 * Time: 10:18 AM
 */

namespace Modules\Admin\Repositories\ServiceCategory;


interface ServiceCategoryRepositoryInterface
{
    public function list(array $filters = []);
    public function add(array $data);
    public function remove($id);
    public function edit(array $data, $id);
    public function getItem($id);
    public function testName($name,$id);
    public function getOptionServiceCategory();
}