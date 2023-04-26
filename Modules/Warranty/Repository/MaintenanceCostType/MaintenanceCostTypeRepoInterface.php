<?php

namespace Modules\Warranty\Repository\MaintenanceCostType;

interface MaintenanceCostTypeRepoInterface
{
    public function getList(array $filters = []);
    public function store($input);
    public function dataViewEdit($warrantyPackageCode);
    public function update($input);
    public function delete($input);
}