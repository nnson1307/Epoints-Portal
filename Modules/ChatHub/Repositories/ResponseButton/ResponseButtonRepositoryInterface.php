<?php

namespace Modules\ChatHub\Repositories\ResponseButton;

interface ResponseButtonRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($response_button_id);
   public function getResponseButton($response_button_id);
   public function update($data);
   public function getActive();
}