<?php

namespace Modules\ChatHub\Repositories\Response;

interface ResponseRepositoryInterface
{
   public function getList($filters);
   public function getDataCreateAction();
   public function storeAction($arrParams);
   public function getDateEditAction($id);
   public function updateAction($arrParams,$id);
}
