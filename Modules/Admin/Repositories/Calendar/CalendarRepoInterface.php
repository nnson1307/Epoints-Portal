<?php


namespace Modules\Admin\Repositories\Calendar;


interface CalendarRepoInterface
{
    /**
     * Show popup thêm lịch hẹn
     *
     * @param $input
     * @return mixed
     */
    public function showModalAdd($input);

    /**
     * Show popup chi tiết dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function showModalDetail($input);
}