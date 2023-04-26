<?php
namespace Modules\ZNS\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class CalendarPromoAbstract
 * @package Modules\ZNS\Libs\Calendar
 * @since Mar, 2021
 * @author DaiDP
 */
abstract class CalendarPromoAbstract
{
    /**
     * Kiểm tra thời gian khuyến mãi hợp lệ không
     *
     * @param Carbon $date
     * @param $promotion
     * @return boolean
     */
    abstract public function inPromotionDate(Carbon $date, $promotion);
}