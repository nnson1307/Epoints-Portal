<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/18/2019
 * Time: 9:22 AM
 */

namespace Modules\Booking\Repositories\SmsProvider;


interface SmsProviderRepositoryInterface
{
    /*
     * get item
     */
    public function getItem($id);

    /**
     * Update sms_provider
     * @param array $data
     * @return number
     */
    public function edit(array $data, $id);
}
//