<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 22/06/2021
 * Time: 10:19
 */

namespace Modules\Admin\Repositories\ServiceBooking;


interface ServiceBookingRepoInterface
{
    /**
     * Lấy cấu hình đặt lịch
     *
     * @return mixed
     */
    public function getConfig();

    /**
     * Danh sách xe đã đặt
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters=[]);
}