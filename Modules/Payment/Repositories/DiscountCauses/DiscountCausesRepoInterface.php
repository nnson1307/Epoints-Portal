<?php

namespace Modules\Payment\Repositories\DiscountCauses;

interface DiscountCausesRepoInterface
{
    public function getList(array $filters = []);
    public function store($input);
    public function dataViewEdit($paymentPackageCode);
    public function update($input);
    public function delete($input);
}