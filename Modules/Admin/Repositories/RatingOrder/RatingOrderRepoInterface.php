<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/11/2021
 * Time: 14:08
 */

namespace Modules\Admin\Repositories\RatingOrder;


interface RatingOrderRepoInterface
{
    /**
     * Danh sách đánh giá
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    public function getOptionFilter();

    /**
     * Xem hình ảnh phóng to
     *
     * @param $input
     * @return mixed
     */
    public function viewImage($input);

    /**
     * Xem video
     *
     * @param $input
     * @return mixed
     */
    public function viewVideo($input);

    /**
     * Lấy data chi tiết đánh giá
     *
     * @param $id
     * @return mixed
     */
    public function getDataDetail($id);
}