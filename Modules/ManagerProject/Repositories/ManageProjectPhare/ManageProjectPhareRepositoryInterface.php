<?php


namespace Modules\ManagerProject\Repositories\ManageProjectPhare;


interface ManageProjectPhareRepositoryInterface
{
    public function getAllPhareByProject($projectId);

    /**
     * Tạo giai đoạn
     * @param $data
     * @return mixed
     */
    public function storePhase($data);

    /**
     * Lấy danh sách
     * @param $data
     * @return mixed
     */
    public function removeAction($data);

    /**
     * Hiển thị popup cập nhật Phase
     * @param $data
     * @return mixed
     */
    public function showPopup($data);

    /**
     * Cập nhật Phase
     * @param $data
     * @return mixed
     */
    public function updateAction($data);

    /**
     * Tự động tạo phase cho tất cả dự án
     * @return mixed
     */
    public function autoCreatePhase();
}