<?php

namespace Modules\ChatHub\Repositories\ResponseDetail;

interface ResponseDetailRepositoryInterface
{
   public function create($data);
   public function getList($filters);
   public function delete($response_detail_id);
   public function getResponseDetail($response_detail_id);
   public function update($data);
   public function getActive();
   public function getDetail(&$filter, $id='all');
}