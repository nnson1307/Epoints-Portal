<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 19/2/2019
 * Time: 22:00
 */

namespace Modules\Admin\Repositories\EmailLog;


interface EmailLogRepositoryInterface
{
    public function add(array $data);

    public function getItem($id);

    public function edit(array $data, $id);

    public function remove($id);

    public function groupStatus($id, $status);

    public function getTypeLog($type);

    public function list($id, array $filters = []);

    public function getLogNotTimeSent($time_now);

    public function getLogIsTimeSent($time_now);
}