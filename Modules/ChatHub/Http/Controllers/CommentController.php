<?php

namespace Modules\ChatHub\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\ChatHub\Repositories\Comment\CommentRepositoryInterface;
use Auth;


class CommentController extends Controller
{
    protected $comment;

    public function __construct(
        CommentRepositoryInterface $comment
    ) {
        $this->comment = $comment;
    }
    public function indexAction(Request $request){
        try{
            $filters = request()->all();
            $comment=$this->comment->getList($filters);
            return view('chathub::comment.index',[
                'LIST' => $comment
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        
    }
    public function listAction(Request $request)
    {
        $filters = $request->all();
        $comment=$this->comment->getList($filters);
        return view('chathub::comment.list',
            [
                'LIST' => $comment,
                'page' => $filters['page']
            ]);
    }
}