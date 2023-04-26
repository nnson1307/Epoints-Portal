<?php

namespace Modules\ChatHub\Repositories\ResponseContent;

interface ResponseContentRepositoryInterface
{
   public function getList($filters);
   public function remove($id);
   public function getDataViewEdit($id);
    public function saveUpdate($item,$id);
    public function insertData($item);
}
