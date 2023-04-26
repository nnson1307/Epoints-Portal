<?php

namespace Modules\Admin\Repositories\Faq;

interface FaqRepositoryInterface
{
    /**
     * Lấy danh sách câu hỏi hỗ trợ có phân trang
     *
     * @param array $filters
     * @return array
     */
    public function getListNew(array $filters = []);

    /**
     * Lấy danh sách chính sách bảo mật và điều khoản sử dụng
     *
     * @param array $filters
     * @return mixed
     */
    public function getListPolicyTerms(array $filters = []);

    /**
     * Lấy danh sách câu hỏi hỗ trợ không phân trang
     *
     * @param array $filters
     * @return array
     */
    public function getListAll(array $filters = []);

    /**
     * Thêm câu hỏi hỗ trợ
     *
     * @param array $data
     * @param string $faqType
     * @return int
     */
    public function add(array $data, $faqType = 'faq');

    /**
     * Lấy chi tiết câu hỏi hỗ trợ
     *
     * @param int $id
     * @return mixed
     */
    public function detail($id);

    /**
     * Chỉnh sửa thông tin câu hỏi hỗ trợ
     *
     * @param array $data
     * @return bool
     */
    public function edit(array $data);

    /**
     * Đánh dấu xóa câu hỏi hỗ trợ
     *
     * @param int $id
     * @return bool
     */
    public function remove($id);

    /**
     * Kiểm tra chi tiết nội dung thuộc loại trang đã tồn tại chưa
     *
     * @param $faqType
     * @return boolean
     */
    public function checkFaqType($faqType);

    /**
     * Cập nhật trạng thái hiển thị
     *
     * @param int $status
     * @param int $id
     * @return bool
     */
    public function updateStatus($status, $id);
}
