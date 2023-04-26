<?php

namespace Modules\ChatHub\Repositories\Attribute;

interface AttributeRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($attribute_id);
   public function getAttribute($attribute_id);
   public function update($data);
   public function getActive();
}