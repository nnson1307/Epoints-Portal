<?php
namespace Modules\ZNS\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class TimeTypeWeek
 * @package Modules\ZNS\Libs\Calendar
 * @since Mar, 2021
 * @author DaiDP
 */
class TimeTypeWeek extends CalendarPromoAbstract
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
        $weekMap = [
            0 => 'is_sunday',
            1 => 'is_monday',
            2 => 'is_tuesday',
            3 => 'is_wednesday',
            4 => 'is_thursday',
            5 => 'is_friday',
            6 => 'is_saturday',
        ];

        $field = $weekMap[$date->dayOfWeek];

        return $promotion->{$field};
    }
}