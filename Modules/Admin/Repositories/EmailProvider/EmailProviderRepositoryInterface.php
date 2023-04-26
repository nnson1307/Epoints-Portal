<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 18/2/2019
 * Time: 10:30
 */

namespace Modules\Admin\Repositories\EmailProvider;


interface EmailProviderRepositoryInterface
{
    public function getItem($id);

    public function edit(array $data, $id);
}