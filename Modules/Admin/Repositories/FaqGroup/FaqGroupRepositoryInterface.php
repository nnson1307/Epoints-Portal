<?php

namespace Modules\Admin\Repositories\FaqGroup;

interface FaqGroupRepositoryInterface
{
    /**
     * Lấy danh sách faq group có phân trang
     *
     * @param array $filters
     * @return mixed
     */
    public function getListNew(array $filters = []);

    /**
     * Lấy toàn bộ danh sách faq group không phân trang
     *
     * @param array $filters
     * @return array
     */
    public function getListAll(array $filters = []);

    /**
     * Lấy thông tin chi tiết faq group
     *
     * @param int $faq_group_id
     * @return mixed
     */
    public function detail($faq_group_id);

    /**
     * Thêm faq group
     *
     * @param array $data
     * @return int
     */
    public function add(array $data);

    /**
     * Chỉnh sửa nhóm nội dung
     *
     * @param array $data
     * @param int $id
     * @return mixed
     */
    public function edit(array $data, $id);

    /**
     * Cập nhật trạng thái hiển thị
     *
     * @param int $status
     * @param int $id
     * @return mixed
     */
    public function updateStatus($status, $id);

    /**
     * Đánh dấu xóa nhóm nội dung
     *
     * @param int $id
     * @return mixed
     */
    public function remove($id);
}
