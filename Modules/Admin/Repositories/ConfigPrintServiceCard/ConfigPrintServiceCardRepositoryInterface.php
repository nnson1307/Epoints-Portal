<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 31/3/2019
 * Time: 14:54
 */

namespace Modules\Admin\Repositories\ConfigPrintServiceCard;


interface ConfigPrintServiceCardRepositoryInterface
{
    public function list();

    public function edit(array $data, $id);

    public function getItem($id);
}