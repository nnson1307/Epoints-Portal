<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/11/2021
 * Time: 18:19
 */

namespace Modules\Contract\Repositories\Browse;


interface BrowseRepoInterface
{
    /**
     * Danh sách HĐ cần phê duyệt
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Đồng ý duyệt
     *
     * @param $input
     * @return mixed
     */
    public function confirm($input);

    /**
     * Từ chối duyệt
     *
     * @param $input
     * @return mixed
     */
    public function refuse($input);
}