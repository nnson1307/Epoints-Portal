<?php


namespace Modules\ManagerProject\Repositories\ManageProjectComment;


interface ManageProjectCommentRepositoryInterface
{
    /**
     * Danh sách bình luận
     * @param $projectId
     * @return mixed
     */
    public function listComment($projectId);

    /**
     * Thêm bình luận
     * @param $projectId
     * @return mixed
     */
    public function addComment($data);
}