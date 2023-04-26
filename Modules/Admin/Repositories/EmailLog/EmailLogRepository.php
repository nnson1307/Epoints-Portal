<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 19/2/2019
 * Time: 21:59
 */

namespace Modules\Admin\Repositories\EmailLog;


use Modules\Admin\Models\EmailLogTable;

class EmailLogRepository implements EmailLogRepositoryInterface
{
    protected $email_log;
    protected $timestamps = true;

    public function __construct(EmailLogTable $email_logs)
    {
        $this->email_log = $email_logs;
    }


    public function add(array $data)
    {
        return $this->email_log->add($data);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->email_log->getItem($id);
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->email_log->edit($data, $id);
    }

    public function remove($id)
    {
        return $this->email_log->remove($id);
    }

    public function groupStatus($id, $status)
    {
        // TODO: Implement groupStatus() method.
        return $this->email_log->groupStatus($id, $status);
    }

    public function getTypeLog($type)
    {
        // TODO: Implement getTypeLog() method.
        return $this->email_log->getTypeLog($type);
    }

    public function list($id, array $filters = [])
    {
        // TODO: Implement list() method.
        return $this->email_log->getList($id, $filters);
    }

    public function getLogIsTimeSent($time_now)
    {
        // TODO: Implement getLogIsTimeSent() method.
        return $this->email_log->getLogIsTimeSent($time_now);
    }

    public function getLogNotTimeSent($time_now)
    {
        // TODO: Implement getLogNotTimeSent() method.
        return $this->email_log->getLogNotTimeSent($time_now);
    }


}