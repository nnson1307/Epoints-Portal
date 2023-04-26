<?php

namespace Modules\Customer\Repositories\CustomerInfoType;

interface CustomerInfoTypeRepoInterface
{
    public function getList(array $filters = []);
    public function store($input);
    public function dataViewCreate($input);
    public function dataViewEdit($id);
    public function update($input);
    public function delete($input);
    public function updateStatus($input);
}