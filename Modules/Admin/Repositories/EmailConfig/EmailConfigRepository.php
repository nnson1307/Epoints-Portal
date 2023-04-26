<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 18/2/2019
 * Time: 14:51
 */

namespace Modules\Admin\Repositories\EmailConfig;


use Modules\Admin\Models\EmailConfigTable;

class EmailConfigRepository implements EmailConfigRepositoryInterface
{
    protected $email_config;
    protected $timestamps = true;

    public function __construct(EmailConfigTable $email_configs)
    {
        $this->email_config = $email_configs;
    }

    public function list(array $filters = [])
    {
        return $this->email_config->getList($filters);
    }

    public function getConfig()
    {
        // TODO: Implement getOption() method.
        return $this->email_config->getConfig();
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->email_config->getItem($id);
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->email_config->edit($data, $id);
    }

    public function getSubject($key)
    {
        // TODO: Implement getSubject() method.
        return $this->email_config->getSubject($key);
    }
}
