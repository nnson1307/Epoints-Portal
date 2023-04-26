<?php

namespace Modules\Estimate\Libs\help;

use Carbon\Carbon;

/**
 * Class Help
 * @author HaoNMN
 * @since May 2022
 */
class Help
{
    public static function getStartEndDateOfWeek($weekOfYear, $year = null){
        $date = Carbon::now();
        if(!$year){
            $year = $date->year;
        }
        
        $date = Carbon::now();
        $date->setISODate($year, $weekOfYear);
        
        return [
            $date->startOfWeek()->format('d/m/Y'), 
            $date->endOfWeek()->format('d/m/Y')
        ];
    }
}