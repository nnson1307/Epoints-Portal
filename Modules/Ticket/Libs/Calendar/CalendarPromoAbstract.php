<?php
namespace Modules\Ticket\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class CalendarPromoAbstract
 * @package Modules\Ticket\Libs\Calendar
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