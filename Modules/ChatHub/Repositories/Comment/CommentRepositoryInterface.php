<?php

namespace Modules\ChatHub\Repositories\Comment;

interface CommentRepositoryInterface
{
   public function getList($filters);
}