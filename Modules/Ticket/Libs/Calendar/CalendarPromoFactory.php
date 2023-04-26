<?php
namespace Modules\Ticket\Libs\Calendar;

/**
 * Class CalendarPromoFactory
 * @package Modules\Ticket\Libs\Calendar
 * @since Mar, 2021
 * @author DaiDP
 */
class CalendarPromoFactory
{
    const TIME_TYPE_WEEK = 'W';
    const TIME_TYPE_MONTH = 'M';
    const TIME_TYPE_DATE_TIME = 'R';

    /**
     * Lấy xử lý
     *
     * @param $timeType
     * @return CalendarPromoAbstract
     */
    public static function getProcessor($timeType)
    {
        switch ($timeType)
        {
            case self::TIME_TYPE_WEEK:
                return app()->get(TimeTypeWeek::class);

            case self::TIME_TYPE_MONTH:
                return app()->get(TimeTypeMonth::class);

            case self::TIME_TYPE_DATE_TIME:
                return app()->get(TimeTypeDateTime::class);

            default:
                return app()->get(TimeTypeDefault::class);
        }
    }
}