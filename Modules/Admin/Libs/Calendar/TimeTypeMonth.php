<?php
namespace Modules\Admin\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class TimeTypeMonth
 * @package Modules\Admin\Libs\Calendar
 * @since Mar, 2021
 * @author DaiDP
 */
class TimeTypeMonth extends CalendarPromoAbstract
{
    /**
     * Kiểm tra thời gian khuyến mãi hợp lệ không
     *
     * @param Carbon $date
     * @param $promotion
     * @return boolean
     */
    public function inPromotionDate(Carbon $date, $promotion)
    {
        //var_dump($date->format('Y-m-d'), $promotion->run_date);die;
        return $date->format('Y-m-d') == $promotion->run_date;
    }
}