<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 18:09 AM
 */

namespace Modules\Payment\Repositories\PaymentMethod;

interface PaymentMethodRepositoryInterface
{
    public function getPaymentMethodOption();
    public function getList(array &$filters = []);
    public function store($input);
    public function dataViewEdit($paymentMethodId);
    public function update($input);
    public function delete($input);
}