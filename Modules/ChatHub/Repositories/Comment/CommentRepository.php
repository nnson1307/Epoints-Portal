<?php
namespace Modules\ChatHub\Repositories\Comment;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubCommentTable;

class CommentRepository implements CommentRepositoryInterface
{
    public function __construct(
        ChatHubCommentTable $comment
    )
    {
        $this->comment = $comment;
    }
    public function getList($filters = null){
        return $this->comment->getList($filters);
    }
}
