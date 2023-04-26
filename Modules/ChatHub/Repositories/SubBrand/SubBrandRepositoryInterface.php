<?php

namespace Modules\ChatHub\Repositories\SubBrand;

interface SubBrandRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($sub_brand_id);
   public function getSubBrand($sub_brand_id);
   public function update($data);
   public function getActive();
}