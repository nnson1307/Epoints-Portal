<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 17:19
 */

namespace Modules\Admin\Repositories\RuleSettingOther;


interface RuleSettingOtherRepositoryInterface
{
    public function list();

    public function edit(array $data, $id);
}