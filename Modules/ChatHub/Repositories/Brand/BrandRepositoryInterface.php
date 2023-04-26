<?php

namespace Modules\ChatHub\Repositories\Brand;

interface BrandRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($brand_id);
   public function getBrand($brand_id);
   public function update($data);
   public function getActive();
}