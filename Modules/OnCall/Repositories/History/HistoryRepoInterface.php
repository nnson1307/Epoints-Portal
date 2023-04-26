<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 29/07/2021
 * Time: 10:59
 */

namespace Modules\OnCall\Repositories\History;


interface HistoryRepoInterface
{
    /**
     * Lấy danh sách lịch sử cuộc gọi
     *
     * @param $input
     * @return mixed
     */
    public function list($input = []);

    /**
     * Lấy option trang danh sách
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Lấy dữ liệu view chi tiết cuộc gọi
     *
     * @param $historyId
     * @return mixed
     */
    public function dataViewDetail($historyId);
}