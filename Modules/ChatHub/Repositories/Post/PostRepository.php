<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 8/29/2019
 * Time: 11:43 AM
 */
namespace Modules\ChatHub\Repositories\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Models\ChatHubPostTable;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        ChatHubPostTable $post
    )
    {
        $this->post = $post;
    }
    public function getList($filters = null){
        return $this->post->getList($filters);
    }
    public function getPost($id){
        return $this->post->getPost($id);
    }

    public function updateKey($id, $brand, $sku, $sub_brand, $attribute){
        return $this->post->updateKey($id, $brand, $sku, $sub_brand, $attribute);
    }
    public function subcribe($id){
        return $this->post->subcribe($id);
    }
    public function unsubcribe($id){
        return $this->post->unsubcribe($id);
    }
}