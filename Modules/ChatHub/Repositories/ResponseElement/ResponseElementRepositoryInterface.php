<?php

namespace Modules\ChatHub\Repositories\ResponseElement;

interface ResponseElementRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($response_element_id);
   public function getResponseElement($response_element_id);
   public function update($data);
   public function getActive();
}