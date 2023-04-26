<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/4/2020
 * Time: 2:59 PM
 */

namespace Modules\Admin\Repositories\Rating;


interface RatingRepoInterface
{
    /**
     * Danh sách đánh giá
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Thay đổi hiễn thị đánh giá KH
     *
     * @param $input
     * @return mixed
     */
    public function changeShow($input);
}