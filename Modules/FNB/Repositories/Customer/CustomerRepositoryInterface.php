<?php


namespace Modules\FNB\Repositories\Customer;


interface CustomerRepositoryInterface
{
    public function getItem($customerId);

    public function getCustomerOption();

    public function edit($data,$id);
}