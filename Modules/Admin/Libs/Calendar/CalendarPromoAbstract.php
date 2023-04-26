<?php
namespace Modules\Admin\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class CalendarPromoAbstract
 * @package Modules\Admin\Libs\Calendar
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