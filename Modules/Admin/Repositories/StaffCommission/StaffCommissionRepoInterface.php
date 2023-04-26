<?php

namespace Modules\Admin\Repositories\StaffCommission;

interface StaffCommissionRepoInterface
{
    public function getList(array $filters = []);
    public function store($input);
    public function dataViewCreate($input);
    public function dataViewEdit($id);
    public function update($input);
    public function delete($input);
}