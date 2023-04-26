<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 27/3/2019
 * Time: 10:32
 */

namespace Modules\Admin\Repositories\EmailTemplate;


use Modules\Admin\Models\EmailTemplateTable;

class EmailTemplateRepository implements EmailTemplateRepositoryInterface
{
    protected $email_template;
    protected $timestamps = true;

    public function __construct(EmailTemplateTable $email_templates)
    {
        $this->email_template = $email_templates;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
        return $this->email_template->getAll();
    }
}