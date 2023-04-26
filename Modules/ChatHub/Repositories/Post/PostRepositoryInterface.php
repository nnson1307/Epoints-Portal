<?php

namespace Modules\ChatHub\Repositories\Post;

interface PostRepositoryInterface
{
   public function getList($filters);
   public function getPost($id);
   public function updateKey($id, $brand, $sku, $sub_brand, $attribute);
   public function subcribe($id);
   public function unsubcribe($id);
}