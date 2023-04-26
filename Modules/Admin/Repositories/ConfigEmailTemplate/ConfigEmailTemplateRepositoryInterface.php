<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 1/4/2019
 * Time: 12:09
 */

namespace Modules\Admin\Repositories\ConfigEmailTemplate;


interface ConfigEmailTemplateRepositoryInterface
{
    public function list();

    public function edit(array $data, $id);

    public function getItem($id);
}