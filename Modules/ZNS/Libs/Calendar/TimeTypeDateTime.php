<?php
namespace Modules\ZNS\Libs\Calendar;

use Carbon\Carbon;

/**
 * Class TimeTypeDateTime
 * @package Modules\ZNS\Libs\Calendar
 * @since Mar, 2021
 * @author DaiDP
 */
class TimeTypeDateTime extends CalendarPromoAbstract
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
        foreach ($promotion->cf_date_time as $item)
        {
            $start = Carbon::createFromFormat('Y-m-d', $item['form_date']);
            $end   = Carbon::createFromFormat('Y-m-d', $item['to_date']);

            // Không nằm trong range ngày
            if ( !($start->diffInDays($date, false) >= 0 && $date->diffInDays($end, false) >=0) ) {
                continue;
            }

            return true;
        }


        return false;
    }
}