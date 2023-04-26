<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 18/2/2019
 * Time: 10:30
 */

namespace Modules\Admin\Repositories\EmailProvider;


use Modules\Admin\Models\EmailProviderTable;

class EmailProviderRepository implements EmailProviderRepositoryInterface
{
    protected $email_provider;
    protected $timestamps = true;

    public function __construct(EmailProviderTable $email_provider)
    {
        $this->email_provider = $email_provider;
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->email_provider->getItem($id);
    }

    public function edit(array $data, $id)
    {
        return $this->email_provider->edit($data, $id);
    }
}