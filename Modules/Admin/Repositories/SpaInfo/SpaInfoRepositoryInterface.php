<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 21/3/2019
 * Time: 09:31
 */

namespace Modules\Admin\Repositories\SpaInfo;


interface SpaInfoRepositoryInterface
{
    public function list(array $filters = []);

    public function add(array $data);

    public function getItem();

    public function edit(array $data, $id);

    public function remove($id);

    public function testName($name, $id);

    public function getInfoSpa();

    public function getIntroduction();

    public function updateIntroduction(array $data);
}