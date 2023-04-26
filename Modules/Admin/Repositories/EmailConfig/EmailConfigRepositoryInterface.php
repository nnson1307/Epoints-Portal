<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 18/2/2019
 * Time: 14:51
 */

namespace Modules\Admin\Repositories\EmailConfig;


interface EmailConfigRepositoryInterface
{
    public function list(array $filters = []);

    public function getConfig();

    public function getItem($id);

    public function edit(array $data, $id);

    public function getSubject($key);
}