<?php


namespace Modules\FNB\Repositories\PaymentMethod;


use Modules\FNB\Models\PaymentMethodTable;

class PaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    private $paymentMethod;

    public function __contruct(PaymentMethodTable $paymentMethod){
        $this->paymentMethod = $paymentMethod;
    }

    public function getOption()
    {
        $paymentMethod = app()->get(PaymentMethodTable::class);
        return $paymentMethod->getOption();
    }
}