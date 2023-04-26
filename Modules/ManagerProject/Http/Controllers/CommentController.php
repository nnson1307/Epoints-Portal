<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerProject\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\ManagerProject\Repositories\ManageProjectComment\ManageProjectCommentRepositoryInterface;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;


class CommentController extends Controller
{
    public function index($id){
        $rProject = app()->get(ProjectRepositoryInterface::class);
        $rManageProjectComment = app()->get(ManageProjectCommentRepositoryInterface::class);
        $listComment = $rManageProjectComment->listComment($id);
        $info = $rProject->projectInfoWork($id);

        return view('manager-project::comment.index',[
            'info' => $info,
            'listComment' => $listComment
        ]);
    }

    /**
     * Thêm bình luận
     * @param Request $request
     */
    public function addComment(Request $request)
    {
        $rManageProjectComment = app()->get(ManageProjectCommentRepositoryInterface::class);
        $param = $request->all();
        $data = $rManageProjectComment->addComment($param);
        return response()->json($data);
    }
}
