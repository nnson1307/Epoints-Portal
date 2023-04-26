<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 1/4/2019
 * Time: 12:10
 */

namespace Modules\Admin\Repositories\ConfigEmailTemplate;


use Modules\Admin\Models\ConfigEmailTemplateTable;

class ConfigEmailTemplateRepository implements ConfigEmailTemplateRepositoryInterface
{
    protected $config_email_template;
    protected $timestamps = true;

    public function __construct(ConfigEmailTemplateTable $config_email_template)
    {
        $this->config_email_template = $config_email_template;
    }

    public function list()
    {
        // TODO: Implement list() method.
        return $this->config_email_template->getList();
    }

    public function edit(array $data, $id)
    {
        // TODO: Implement edit() method.
        return $this->config_email_template->edit($data, $id);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->config_email_template->getItem($id);
    }
}