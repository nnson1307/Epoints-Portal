<?php


namespace Modules\Admin\Repositories\Log;


interface LogRepositoryInterface
{
    public function list(array $filters = []);

    public function getDetail($id);

    /**
     * Show popup trả lời câu hỏi khách hàng
     *
     * @param $input
     * @return mixed
     */
    public function popupAnswer($input);

    /**
     * Lưu câu trả lời
     *
     * @param $input
     * @return mixed
     */
    public function saveAnswer($input);

    /**
     * Xoá câu trả lời
     *
     * @param $input
     * @return mixed
     */
    public function removeAnswer($input);

    /**
     * Show popup chỉnh sửa câu trả lời
     *
     * @param $input
     * @return mixed
     */
    public function popupEditAnswer($input);
}