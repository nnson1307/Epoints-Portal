<?php


namespace Modules\Report\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\ChatbotBrandTable;
use Modules\Report\Models\ChatbotHistoryTable;
use Modules\Report\Models\ChatbotKeywordLogTable;
use Modules\Report\Models\CustomerTable;

class DashboardController extends Controller
{
    protected $chatbot_history;
    protected $customer;
    protected $chatbot_brand;

    public function __construct(
        ChatbotHistoryTable $chatbot_history,
        CustomerTable $customer,
        ChatbotBrandTable $chatbot_brand
    )
    {
        $this->chatbot_history = $chatbot_history;
        $this->customer = $customer;
        $this->chatbot_brand = $chatbot_brand;
    }

    /**
     * Page Dashboard Over View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction(Request $request)
    {
        $date_range = $request->date_range;
        //$totalMessageTarget = $this->chatbot_history->totalMessageCompletion($date_range);
        //dd($totalMessageTarget->count());
        $optionBrand = $this->chatbot_brand->getOptionBrand();
        return view('report::dashboard.over-view.index', [
            'optionBrand' => $optionBrand
        ]);
    }

    /**
     * Load Chart Over View
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAction(Request $request)
    {
        $date_range = $request->date_range;
        $total_message = $this->chatbot_history->totalMessage($date_range);

        $total_user = $this->customer->totalUser($date_range);
        //Chart total message brand
        $arr_total_message_brand = $this->totalMessageBrand($date_range);

        //Chart total message month
        $brand_name = $this->chatbot_brand->getBrandByEntities($request->brand);
        $arr_total_message_month = $this->totalMessageMonth($date_range, $request->brand);

        //Chart donut total message chia theo tỉ lệ'

        $totalMessageTarget = $this->chatbot_history->totalMessageCompletion($date_range, 1);


        $totalMessageConfusion = $this->chatbot_history->totalMessageConfusion($date_range,1);


        //Chart total message attribute
        $arr_total_message_attribute = $this->totalMessageChartCompletion($date_range);
        //Chart total confusion attribute chưa có response

        $arr_total_message_not_response = $this->totalMessageChartConfusion($date_range);

        //Chart key word
        $arrKeyword = $this->keyWord($date_range);
       // var_dump($arrKeyword);
        if ($request->brand == 'all') {
            $name = '';
        } else {
            if ($brand_name == null) {
                $name = 'Khác';
            } else {
                $name = $brand_name['brand_name'];
            }
        }
        return response()->json([
            'total_message' => count($total_message),
            'total_user' => count($total_user),
            'total_message_brand' => $arr_total_message_brand,
            'total_message_attribute' => $arr_total_message_attribute,
            'total_message_target' => $totalMessageTarget,
            'total_message_confusion' => $totalMessageConfusion,
            'total_message_not_response' => $arr_total_message_not_response,
            'total_message_month' => $arr_total_message_month,
            'brand_name' => $name,
            'key_word' => $arrKeyword
        ]);
    }

    /**
     * Chart keyword
     *
     * @param $date_range
     * @return array
     */
    public function keyWord($date_range)
    {
        $mKeyword = new ChatbotKeywordLogTable();

        $keyword = $mKeyword->chartKeyword($date_range);

        $arr = [];
        foreach ($keyword as $item) {
            $arr [] = [
                'name' => $item['keyword'],
                'weight' => $item['total'],
//                'weight' => $this->checkNumber($item['total']),
            ];
        }
        return $arr;
    }

    /**
     * Chart total message chia theo brand
     *
     * @param $date_range
     * @return array
     */
    public function totalMessageBrand($date_range = null)
    {
        $optionBrand = $this->chatbot_brand->getOptionBrand();
        $totalMessageBrand = $this->chatbot_history->totalMessageBrand($date_range);
        $arr_total = [];
        foreach ($totalMessageBrand as $item) {
            $arr_total[$item['brand_name']][] = $item['total'];
        }
        $arr_total_message_brand = [];
        foreach ($optionBrand as $item) {
            $number = isset($arr_total[$item['brand_name']][0]) ? $arr_total[$item['brand_name']][0] : 0;
            $arr_total_message_brand [] = [
                $item['brand_name'],
                $number,
                '',
                $number
            ];
        }
        return $arr_total_message_brand;
    }

    /**
     * Chart total message chia theo tháng
     *
     * @param $date_range
     * @param $brand
     * @return array
     */
    public function totalMessageMonth($date_range, $brand)
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
        $arr_total_message_month = [];
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startMonth = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
            $endMonth = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
            if ($startMonth->month == $endMonth->month) {
                $arr_month = [
                    $startMonth
                ];
            } else {
                $number_month = ($endMonth->month - $startMonth->month) + 12 * ($endMonth->year - $startMonth->year);
                $arr_month = [];
                if ($number_month > 1) {
                    for ($i = 0; $i <= $number_month; $i++) {
                        $arr_month [] = Carbon::parse($startMonth->year . '-' . $startMonth->month . '-' . '01')->addMonth($i);
                    }
                    $arr_month[0] = $startMonth;
                    $arr_month[count($arr_month) - 1] = $endMonth;
                } else {
                    $arr_month = [
                        $startMonth,
                        $endMonth
                    ];
                }
            }
            foreach ($arr_month as $key => $item) {
                $totalMessageMonth = $this->chatbot_history->totalMessageMonth($item);
                $totalMessageMonthBrand = $this->chatbot_history->totalMessageMonthBrand($brand, $item);
                if ($brand == 'other') {
                    $arr_total_message_month [] = [
                        $item->shortLocaleMonth,
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                } else if ($brand == 'all') {
                    $arr_total_message_month [] = [
                        $item->shortLocaleMonth,
                        $totalMessageMonth[0]['total'],
                    ];
                } else {
                    $arr_total_message_month [] = [
                        $item->shortLocaleMonth,
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                }
            }
        } else {
            foreach ($arr_month as $key => $item) {
                $totalMessageMonth = $this->chatbot_history->totalMessageMonth($item);
                $totalMessageMonthBrand = $this->chatbot_history->totalMessageMonthBrand($brand, $item);
                if ($brand == 'other') {
                    $arr_total_message_month [] = [
                        $item->shortLocaleMonth,
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                } else if ($brand == 'all') {
                    $arr_total_message_month [] = [
                        $item->shortLocaleMonth,
                        $totalMessageMonth[0]['total'],
                    ];
                } else {
                    $arr_total_message_month [] = [
                        $item->shortLocaleMonth,
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                }
            }
        }
        return $arr_total_message_month;
    }

    /**
     * Chart total message chia theo attribute có response
     *
     * @param $date_range
     * @return array
     */
    public function totalMessageChartCompletion($date_range)
    {
        $totalMessageAttribute = $this->chatbot_history->totalMessageChartCompletion($date_range);

        $arr_total_message_attribute = [];
        foreach ($totalMessageAttribute as $item) {
            $arr_total_message_attribute [] = [
                $item['attribute_name'],
                $item['total'],
                ''
            ];
        }
        return $arr_total_message_attribute;
    }

    /**
     * Chart total message chưa có response
     *
     * @param $date_range
     * @return array
     */
    public function totalMessageChartConfusion($date_range)
    {
        $totalMessageNotResponse = $this->chatbot_history->totalMessageChartConfusion($date_range);
        $arr_total_message_not_response = [];
        foreach ($totalMessageNotResponse as $item) {
            $arr_total_message_not_response [] = [
                $item['attribute_name'],
                $item['total'],
                ''
            ];
        }
        return $arr_total_message_not_response;
    }

    /**
     * Change Brand Load Chart Month
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartMonthAction(Request $request)
    {
        $date_range = $request->date_range;
        //Chart total message month
        $brand_name = $this->chatbot_brand->getBrandByEntities($request->brand);
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
        $arr_total_message_month = [];
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startMonth = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
            $endMonth = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
            if ($startMonth->month == $endMonth->month) {
                $arr_month = [
                    $startMonth
                ];
            } else {
                $number_month = ($endMonth->month - $startMonth->month) + 12 * ($endMonth->year - $startMonth->year);
                $arr_month = [];
                if ($number_month > 1) {
                    for ($i = 0; $i <= $number_month; $i++) {
                        $arr_month [] = Carbon::parse($startMonth->year . '-' . $startMonth->month . '-' . '01')->addMonth($i);
                    }
                    $arr_month[0] = $startMonth;
                    $arr_month[count($arr_month) - 1] = $endMonth;
                } else {
                    $arr_month = [
                        $startMonth,
                        $endMonth
                    ];
                }
            }
            foreach ($arr_month as $key => $item) {
                $totalMessageMonth = $this->chatbot_history->totalMessageMonth($item);
                $totalMessageMonthBrand = $this->chatbot_history->totalMessageMonthBrand($request->brand, $item);
                if ($request->brand == 'other') {
                    $arr_total_message_month [] = [
                        $item->format('F'),
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                } else if ($request->brand == 'all') {
                    $arr_total_message_month [] = [
                        $item->format('F'),
                        $totalMessageMonth[0]['total'],

                    ];
                } else {
                    $arr_total_message_month [] = [
                        $item->format('F'),
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                }
            }
        } else {
            foreach ($arr_month as $key => $item) {
                $totalMessageMonth = $this->chatbot_history->totalMessageMonth($item);
                $totalMessageMonthBrand = $this->chatbot_history->totalMessageMonthBrand($request->brand, $item);
                if ($request->brand == 'other') {
                    $arr_total_message_month [] = [
                        $item->format('F'),
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                } else if ($request->brand == 'all') {
                    $arr_total_message_month [] = [
                        $item->format('F'),
                        $totalMessageMonth[0]['total'],
                    ];
                } else {
                    $arr_total_message_month [] = [
                        $item->format('F'),
                        $totalMessageMonth[0]['total'],
                        $totalMessageMonthBrand[0]['total']
                    ];
                }
            }
        }
        $brand_name = $this->chatbot_brand->getBrandByEntities($request->brand);
        if ($request->brand == 'all') {
            $name = '';
        } else {
            if ($brand_name == null) {
                $name = 'Khác';
            } else {
                $name = $brand_name['brand_name'];
            }
        }
        return response()->json([
            'total_message_month' => $arr_total_message_month,
            'brand_name' => $name
        ]);
    }

    /**
     * Export Total Unique User
     *
     * @param Request $request
     */
    public function exportUserAction(Request $request)
    {
        $params = $request->all();
        //Total Message User
        $total_message = $this->chatbot_history->totalMessageAll('');
        $records = [];
        foreach ($total_message as $item) {
            $records [] = [
                "session_id" => $item['session_id'],
                "request_time" => $item['request_time'],
            ];
        }
        $grouped = $this->array_group_by($records, "session_id");
        //Total User
        $total_user = $this->customer->totalUser($params['date_range']);
        $data = [];
        foreach ($total_user as $key => $item) {
            $gender = '';
            if ($item['gender'] == 'male') {
                $gender = 'Nam';
            } else if ($item['gender'] == 'female') {
                $gender = 'Nữ';
            }
            $start_follow = '';
            $end_follow = '';
            if (isset($grouped[$item['customer_id']]) && count($grouped[$item['customer_id']]) > 0) {
                $start_follow = $grouped[$item['customer_id']][0]['request_time'];
                $end_follow = $grouped[$item['customer_id']][count($grouped[$item['customer_id']]) - 1]['request_time'];
            }
            $data [] = [
                'STT' => $key + 1,
                'Họ và tên' => $item['name'],
                'Số điện thoại' => $item['phone'],
                'Email' => $item['email'],
                'Giới tính' => $gender,
                'Tag' => $item['tag_name'],
                'Ngày đầu tương tác' => $start_follow,
                'Ngày cuối tương tác' => $end_follow
            ];
        }
        Excel::create('users', function ($excel) use ($data) {
            $excel->sheet('SheetName', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }

    public function exportTotalMessageAction(Request $request)
    {
        $params = $request->all();
        set_time_limit(300);
        $limit = 5000;
        $Date = date('Y-m-d');
        $this->download_send_headers('total-message' . date('Y-m-d') . '.csv');
        $df = fopen("php://output", 'w');
        fprintf($df, "\xEF\xBB\xBF"); // utf8
        fputcsv($df, ['STT', 'Nội dung Message', 'Nội dung Response', 'Thời gian', 'Brand Entities', 'SKU Entities', 'Attribute Entities', 'Keyword Brand','Keyword Sku', 'Keyword Attribute', 'Loại Message', 'Link Conversation', 'Type Inbox', 'Link post']);
        // $fields = ['Id','FbName','FbId', 'FbEmail', 'FullName', 'Email', 'CMND', 'Mobile', 'Birthday', 'Gender', 'Address', 'ward_name', 'district_name', 'city_name', 'user_created_at', 'user_updated_at', 'user_joined_at'];
        $stt = 1;

        $lastPage = true;
        $page = 1;
        // $total_message = $this->chatbot_history->totalMessage($params['date_range'], $limit, $page);
        while ($lastPage)
        {
            $total_message = $this->chatbot_history->totalMessage($params['date_range'], $limit, $page);  
            $lastPage = $total_message->currentPage() != $total_message->lastPage();
            $page++;
            foreach ($total_message as $item)
            {
                $type_mess = 'Khác';
                if ($item['type'] == null && $item['response_forward'] == 0) {
                    $type_mess = 'Message có response và config';
                } else if ($item['type'] == null && $item['response_forward'] == 1) {
                    $type_mess = 'Điều hướng message chồng chép';
                } else if ($item['type'] == 'config_on_bot' || $item['type'] == 'config_off_bot') {
                    $type_mess = 'Điều hướng menu';
                } else if ($item['attr_type'] == 'not_have_response') {
                    $type_mess = 'Message có nhận diện attribute nhưng chưa có response';
                } else if ($item['type'] == 'default' || $item['type'] == 'reply_after') {
                    $type_mess = 'Confusion Message';
                }

                $data = [
                    $stt++,
                    " ".($item['query']),
                    $item['response_content'],
                    $item['request_time'],
                    $item['brand'],
                    $item['sku'],
                    $item['attribute'],
                    $item['brand_name'],
                    $item['attribute_name'],
                    $item['sku_name'],
                    $type_mess,
                    route('admin.history.detailConversation', ['id' => $item['session_id'], 'conversation' => $item['conversation']]),
                    $item['ib_type']=='comment'? 'Inbox comment': 'Inbox message',
                    $item['post_id']?'https://www.facebook.com/'.$item['post_id']:''
                ];
            
                fputcsv($df, $data);
                $data = null;
            }
        }
        fclose($df);

        // $params = $request->all();
        // $total_message = $this->chatbot_history->totalMessage($params['date_range']);
        // $data = [];
        // foreach ($total_message as $key => $item) {
        //     $type_mess = 'Khác';
        //     if ($item['type'] == null && $item['response_forward'] == 0) {
        //         $type_mess = 'Message có response và config';
        //     } else if ($item['type'] == null && $item['response_forward'] == 1) {
        //         $type_mess = 'Điều hướng message chồng chép';
        //     } else if ($item['type'] == 'config_on_bot' || $item['type'] == 'config_off_bot') {
        //         $type_mess = 'Điều hướng menu';
        //     } else if ($item['attr_type'] == 'not_have_response') {
        //         $type_mess = 'Message có nhận diện attribute nhưng chưa có response';
        //     } else if ($item['type'] == 'default' || $item['type'] == 'reply_after') {
        //         $type_mess = 'Confusion Message';
        //     }

        //     $data [] = [
        //         'STT' => $key + 1,
        //         'Nội dung Message' => " ".($item['query']),
        //         'Nội dung Response' => $item['response_content'],
        //         'Thời gian' => $item['request_time'],
        //         'Brand Entities' => $item['brand'],
        //         'SKU Entities' => $item['sku'],
        //         'Attribute Entities' => $item['attribute'],
        //         'Keyword Brand' => $item['brand_name'],
        //         'Keyword Sku' => $item['attribute_name'],
        //         'Keyword Attribute' => $item['sku_name'],
        //         'Loại Message' => $type_mess,
        //         'Link Conversation' => route('admin.history.detailConversation', ['id' => $item['session_id'], 'conversation' => $item['conversation']]),
        //         'Type Inbox' => $item['ib_type']=='comment'? 'Inbox comment': 'Inbox message',
        //         'Link post' => $item['post_id']?'https://www.facebook.com/'.$item['post_id']:''
        //     ];
        // }
        // Excel::create('total-message', function ($excel) use ($data) {
        //     $excel->sheet('SheetName', function ($sheet) use ($data) {
        //         $sheet->fromArray($data);
        //     });
        // })->download('xlsx');
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

    /**
     * Check number chart keyword trả về font size text
     *
     * @param $number
     * @return int
     */
    private function checkNumber($number)
    {
        switch ($number) {
            case $number < 10:
                return 4;
                break;
            case $number > 10 && $number < 20  :
                return 5;
                break;
            case $number > 20 && $number < 30  :
                return 6;
                break;
            case $number > 30 && $number < 40  :
                return 7;
                break;
            case $number > 40 && $number < 50  :
                return 8;
                break;
            case $number > 50 && $number < 60  :
                return 9;
                break;
            case $number > 60:
                return 10;
                break;
        }
    }

    protected function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");
    
        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        // header('Content-Encoding: UTF-8');
        // header('Content-type: text/csv; charset=UTF-8');
    
        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }


}
