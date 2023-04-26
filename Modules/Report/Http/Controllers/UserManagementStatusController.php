<?php


namespace Modules\Report\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Report\Models\ChatbotHistoryTable;
use Modules\Report\Models\LinkLogTable;

class UserManagementStatusController extends Controller
{
    protected $history;
    protected $linkLog;

    public function __construct(
        ChatbotHistoryTable $history,
        LinkLogTable $linkLog
    ) {
        $this->history = $history;
        $this->linkLog = $linkLog;
    }

    public function indexAction()
    {

        return view('report::user-management-status.index');
    }

    /**
     * Load tất cả biểu đồ page User Management - Status
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAction(Request $request)
    {

        $data_on_off_bot = $this->chartOnBotOffBot($request->date_range);
        //Chart total user click link
        $data_click_link = $this->chartTotalClickLink($request->date_range);
        //Chart total unique user click link
        $data_unique_user_click = $this->chartTotalUniqueUserClickLink($request->date_range);
        return response()->json([
            'total_on_off_bot' => $data_on_off_bot,
            'total_user_click_link' => $data_click_link,
            'total_unique_user_click_link' => $data_unique_user_click
        ]);
    }

    /**
     * Chart On Bot và Off Bot
     *
     * @param $date_range
     * @return array
     */
    public function chartOnBotOffBot($date_range)
    {
        $arr_month = [
            Carbon::now()->subMonth(11),
            Carbon::now()->subMonth(10),
            Carbon::now()->subMonth(9),
            Carbon::now()->subMonth(8),
            Carbon::now()->subMonth(7),
            Carbon::now()->subMonth(6),
            Carbon::now()->subMonth(5),
            Carbon::now()->subMonth(4),
            Carbon::now()->subMonth(3),
            Carbon::now()->subMonth(2),
            Carbon::now()->subMonth(1),
            Carbon::now()
        ];

        $arr_data = [];
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
            if ($startTime->month == $endTime->month) {
                $data = $this->history->totalMessageStatusRange(
                    $startTime->format('Y-m-d'),
                    $endTime->format('Y-m-d'))
                    ->toArray();
                $arr_data [] = [
                    $startTime->format('F'),
                    isset($data[0]['total']) ? $data[0]['total'] : 0,
                    isset($data[1]['total']) ? $data[1]['total'] : 0
                ];
            } else {
                $number_month = ($endTime->month - $startTime->month) + 12 * ($endTime->year - $startTime->year);
                $arr_month = [];
                if ($number_month > 1) {
                    for ($i = 0; $i <= $number_month; $i++) {
                        $arr_month [] = Carbon::parse($startTime->year . '-' . $startTime->month . '-' . '01')->addMonth($i);
                    }
                    $arr_month[0] = $startTime;
                    $arr_month[count($arr_month) - 1] = $endTime;
                } else {
                    $arr_month = [
                        $startTime,
                        $endTime
                    ];
                }
                foreach ($arr_month as $key => $value) {
                    if ($key == 0) {
                        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $value->month, $value->year);
                        $startTime = $value->format('Y-m-d');
                        $endTime = $value->format('Y') . '-' . $value->format('m') . '-' . $day_in_month;
                        //Query
                        $data = $this->history->totalMessageStatusRange($startTime, $endTime)->toArray();
                        //Push Array
                        $arr_data [] = [
                            $value->shortLocaleMonth,
                            isset($data[0]['total']) ? $data[0]['total'] : 0,
                            isset($data[1]['total']) ? $data[1]['total'] : 0
                        ];

                    } else if ($key == count($arr_month) - 1) {
                        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $value->month, $value->year);
                        $startTime = $value->format('Y') . '-' . $value->format('m') . '-' . '01';
                        $endTime = $value->format('Y-m-d');
                        //Query
                        $data = $this->history->totalMessageStatusRange($startTime, $endTime)->toArray();
                        //Push Array
                        $arr_data [] = [
                            $value->shortLocaleMonth,
                            isset($data[0]['total']) ? $data[0]['total'] : 0,
                            isset($data[1]['total']) ? $data[1]['total'] : 0
                        ];
                    } else {
                        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $value->month, $value->year);
                        $startTime = $value->format('Y-m-d');
                        $endTime = $value->format('Y') . '-' . $value->format('m') . '-' . $day_in_month;
                        //Query
                        $data = $this->history->totalMessageStatusRange($startTime, $endTime)->toArray();
                        //Push Array
                        $arr_data [] = [
                            $value->shortLocaleMonth,
                            isset($data[0]['total']) ? $data[0]['total'] : 0,
                            isset($data[1]['total']) ? $data[1]['total'] : 0
                        ];
                    }
                }
            }
        } else {
            foreach ($arr_month as $key => $item) {
                $data = $this->history->totalMessageStatus($item)->toArray();
                $arr_data [] = [
                    $item->shortLocaleMonth,
                    isset($data[0]['total']) ? $data[0]['total'] : 0,
                    isset($data[1]['total']) ? $data[1]['total'] : 0
                ];
            }
        }
        return $arr_data;
    }

    /**
     * Chart Số Lần User Click Vào Link
     *
     * @param $date_range
     * @return array
     */
    public function chartTotalClickLink($date_range)
    {
        $log = $this->linkLog->totalUserClickLink($date_range)->toArray();
        $data = [];
        foreach ($log as $item) {
            $data [] = [
                $item['source'],
                $item['total'],
                ''
            ];
        }
        return $data;
    }

    public function chartTotalUniqueUserClickLink($date_range)
    {
        $log = $this->linkLog->totalUniqueUserClickLink($date_range)->toArray();
        $log_unique = array_unique($log, SORT_REGULAR);
        $grouped = $this->array_group_by($log_unique, "source");
        $data = [];
        foreach ($grouped as $key => $item) {
            $data [] = [
                $key,
                count($item),
                ''
            ];
        }
        return $data;
    }

    /**
     * Function group by
     *
     * @param array $array
     * @param $key
     * @return array|null
     */
    private function array_group_by(array $array, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
            return null;
        }
        $func = (!is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($array as $value) {
            $key = null;
            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }
            if ($key === null) {
                continue;
            }
            $grouped[$key][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }
        return $grouped;
    }


}