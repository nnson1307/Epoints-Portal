<?php


namespace Modules\ManagerProject\Repositories\ManageProjectComment;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
//use Modules\Managerproject\Http\Api\SendNotificationApi;
use Modules\ManagerProject\Models\ManageProjectCommentTable;
use Modules\ManagerProject\Models\ManageProjectHistoryTable;

class ManageProjectCommentRepository implements ManageProjectCommentRepositoryInterface
{
    protected $mManageProjectComment;

    public function __construct(ManageProjectCommentTable $manageProjectComment)
    {
        $this->mManageProjectComment = $manageProjectComment;
    }

    /**
     * Danh sách bình luận
     * @param $projectId
     * @return mixed
     */
    public function listComment($projectId){
        $listComment = $this->mManageProjectComment->getListCommentWork($projectId);

        foreach ($listComment as $key => $item) {
            $listComment[$key]['child_comment'] = $this->mManageProjectComment->getListCommentProject($projectId, $item['manage_project_comment_id']);
        }
        return $listComment;
    }

    /**
     * Thêm bình luận
     * @param $data
     * @return mixed|void
     */
    public function addComment($data)
    {
        try {

            $mManageProjectHistory = app()->get(ManageProjectHistoryTable::class);
            $comment = [
                'message' => $data['description'],
                'manage_project_id' => $data['manage_project_id'],
                'parent_id' => isset($data['parent_id']) ? $data['parent_id'] : null,
                'staff_id' => Auth::id(),
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idComment = $this->mManageProjectComment->createdComment($comment);

            $detailComment = $this->mManageProjectComment->getDetail($idComment);


//            $sendNoti = new SendNotificationApi();

//            $dataNoti = [
//                'key' => 'comment_new',
//                'object_id' => $data['manage_work_id'],
//            ];
//            $sendNoti->sendStaffNotification($dataNoti);

            $view = view('manager-project::comment.append.append-message', ['detail' => $detailComment, 'data' => $data])->render();

            $dataHistory = [
                'manage_project_id' => $data['manage_project_id'],
                'staff_id' => Auth::id(),
                'message' => __(' đã thêm bình luận').' : '.strip_tags($data['description']),
                'action' => 'comment',
                'manage_project_comment_id' => $idComment,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $mManageProjectHistory->addHistory($dataHistory);

            return [
                'error' => false,
                'message' => __('Thêm bình luận thành công'),
                'view' => $view
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm bình luận thất bại')
            ];
        }
    }
}