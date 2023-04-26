<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/11/2021
 * Time: 14:21
 */

namespace Modules\Config\Repositories\ConfigReview;


interface ConfigReviewRepoInterface
{
    /**
     * Lấy dữ liệu cấu hình đánh giá đơn hàng
     *
     * @return mixed
     */
    public function getDataConfigOrder();

    /**
     * Thêm cú pháp đánh giá
     *
     * @param $input
     * @return mixed
     */
    public function insertContentSuggest($input);

    /**
     * Cập nhật cấu hình đánh giá đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function updateConfigOrder($input);
}