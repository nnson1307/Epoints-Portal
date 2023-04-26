<?php
namespace Modules\ZNS\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class TimeTypeDefault
 * @package Modules\ZNS\Libs\Calendar
 * @since Mar, 2021
 * @author DaiDP
 */
class TimeTypeDefault extends CalendarPromoAbstract
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
        return true;
    }
}