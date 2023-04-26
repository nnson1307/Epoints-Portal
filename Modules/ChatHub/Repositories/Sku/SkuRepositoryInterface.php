<?php

namespace Modules\ChatHub\Repositories\Sku;

interface SkuRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($sku_id);
   public function getSku($sku_id);
   public function update($data);
   public function getActive();
}