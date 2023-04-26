<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/18/2019
 * Time: 9:23 AM
 */

namespace Modules\Booking\Repositories\SmsProvider;

use Modules\Booking\Models\SmsProviderTable;

class SmsProviderRepository implements SmsProviderRepositoryInterface
{
    protected $smsProvider;
    protected $timestamps = true;

    public function __construct(SmsProviderTable $smsProvider)
    {
        $this->smsProvider = $smsProvider;
    }

    public function getItem($id)
    {
        return $this->smsProvider->getItem($id);
    }

    /*
     * edit sms provider
     */
    public function edit(array $data, $id)
    {
        return $this->smsProvider->edit($data, $id);
    }
}
//