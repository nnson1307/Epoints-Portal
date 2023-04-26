<?php


namespace Modules\Report\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Report\Models\ChatbotBrandTable;
use Modules\Report\Models\ChatbotHistoryTable;
use Modules\Report\Models\CustomerChannelTagTable;
use Modules\Report\Models\CustomerTable;

class UserManagementController
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
     * Page User Management By Tag
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function indexAction()
    {
        $optionBrand = $this->chatbot_brand->getOptionBrand();
        return view('report::user-management.index', [
            'optionBrand' => $optionBrand
        ]);
    }

    /**
     * Load Chart Page User Management
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAction(Request $request)
    {
        $date_range = $request->date_range;
       // dd($date_range);
        //Chart total user follow
        $arr_user_follow = $this->totalUserFollow($date_range);
        //Chart total unique user
        $arr_total_unique_user = $this->totalUserByTime($date_range);
        //Total Unique User By Brand
        $arr_total_unique_user_brand = $this->totalUniqueUserByBrand($date_range);
        //Total Unique User Sku By Brand
        $arr_total_user_sku = $this->totalUniqueUserSkuByBrand($date_range, $request->brand_sku);
        //Total Unique User Attribute By Brand
        $arr_total_user_attr = $this->totalUniqueUserAttrByBrand($date_range, $request->brand_attr);
        return response()->json([
            'total_user_follow' => $arr_user_follow,
            'total_unique_user' => $arr_total_unique_user,
            'total_user_sku' => $arr_total_user_sku,
            'total_user_attr' => $arr_total_user_attr,
            'total_unique_user_brand' => $arr_total_unique_user_brand
        ]);
    }

    /**
     * Chart số người quan tâm
     *
     * @param $date_range
     * @return array
     */
    public function totalUserFollow($date_range)
    {
        //Total Brand
        $data_chart = [];
        $data_brand = [];
        $brand = $this->chatbot_brand->getOptionBrand();
        for ($i = 1; $i <= count($brand); $i++) {
            $data_brand[$i . ' brand'] = 0;
        }
        //Total User
        $total_unique_user_brand = $this->chatbot_history->totalUniqueUserByBrand($date_range)->toArray();
        $user = array_unique($total_unique_user_brand, SORT_REGULAR);
        $group_follow = $this->array_group_by($user, "name");
        $total_user_follow = [];
        foreach ($group_follow as $key => $item) {
            $total_user_follow[$key] = count($item);
        }

        foreach ($total_user_follow as $item) {
            $data_brand[$item . ' brand'] += 1;
        }
        foreach ($data_brand as $key => $item) {
            $data_chart [] = [
                $key,
                $item,
                '',
                $item
            ];
        }

        return $data_chart;
    }

    /**
     * Chart số lượng unique user by time
     *
     * @param $date_range
     * @return array
     */
    public function totalUserByTime($date_range)
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
        $arr_total_unique_user = [];
        if ($date_range != null) {
            $arr_filter = explode(" - ", $date_range);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0]);
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1]);
            if ($startTime->month == $endTime->month) {
                $total_unique_user = $this->customer->totalUserMonthRange(
                    $startTime->format('Y-m-d'),
                    $endTime->format('Y-m-d'));
                $arr_total_unique_user [] = [
                    $startTime->format('F'),
                    $total_unique_user[0]['total'],
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
                        $total_unique_user = $this->customer->totalUserMonthRange($startTime, $endTime);
                        //Push Array
                        $arr_total_unique_user [] = [
                            $value->shortLocaleMonth,
                            $total_unique_user[0]['total'],
                        ];

                    } else if ($key == count($arr_month) - 1) {
                        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $value->month, $value->year);
                        $startTime = $value->format('Y') . '-' . $value->format('m') . '-' . '01';
                        $endTime = $value->format('Y-m-d');
                        //Query
                        $total_unique_user = $this->customer->totalUserMonthRange($startTime, $endTime);
                        //Push Array
                        $arr_total_unique_user [] = [
                            $value->shortLocaleMonth,
                            $total_unique_user[0]['total'],
                        ];
                    } else {
                        $day_in_month = cal_days_in_month(CAL_GREGORIAN, $value->month, $value->year);
                        $startTime = $value->format('Y-m-d');
                        $endTime = $value->format('Y') . '-' . $value->format('m') . '-' . $day_in_month;
                        //Query
                        $total_unique_user = $this->customer->totalUserMonthRange($startTime, $endTime);
                        //Push Array
                        $arr_total_unique_user [] = [
                            $value->shortLocaleMonth,
                            $total_unique_user[0]['total'],
                        ];
                    }
                }
            }
        } else {
            foreach ($arr_month as $key => $item) {
                $total_unique_user = $this->customer->totalUserMonth($item);
                $arr_total_unique_user [] = [
                    $item->shortLocaleMonth,
                    $total_unique_user[0]['total']
                ];
            }
        }
        return $arr_total_unique_user;
    }

    /**
     * Chart số lượng unique user by brand
     *
     * @param $date_range
     * @return array
     */
    public function totalUniqueUserByBrand($date_range)
    {
        $optionBrand = $this->chatbot_brand->getOptionBrand();
        //Data Customer Channel Tag
        $mChannelTag = new CustomerChannelTagTable();
        $total_unique_user_brand = $mChannelTag->totalUniqueUserByBrand($date_range)->toArray();
        $user = array_unique($total_unique_user_brand, SORT_REGULAR);
        $group_user = $this->array_group_by($user, "keyword");
        $arr_total = [];
        foreach ($group_user as $key => $item) {
            $arr_total[$key][] = count($item);
        }
        $arr_total_unique_user_brand = [];
        foreach ($optionBrand as $key => $item) {
            $number = isset($arr_total[$item['entities']][0]) ? $arr_total[$item['entities']][0] : 0;
            $arr_total_unique_user_brand [] = [
                $item['brand_name'],
                $number,
                '',
                $number
            ];
        }
        return $arr_total_unique_user_brand;
    }

    /**
     * Chart unique users chia theo Sku by Brand
     *
     * @param $date_range
     * @param $brand
     * @return array
     */
    public function totalUniqueUserSkuByBrand($date_range, $brand)
    {
        $total_user_sku = $this->chatbot_history->totalUserSkuByBrand($date_range, $brand);
        $records = [];
        foreach ($total_user_sku as $item) {
            $records [] = [
                "sku_name" => $item['sku_name'],
                "name" => $item['name'],
                'brand_name' => $item['brand_name']
            ];
        }
        $grouped = $this->array_group_by($records, "sku_name");
        $arr_total_user_sku = [];
        foreach ($grouped as $key => $item) {
            $user = array_unique($item, SORT_REGULAR);
            $arr_total_user_sku [] = [
                $key,
                count($user),
                '',
                count($user)
            ];
        }
        return $arr_total_user_sku;
    }

    /**
     * Chart unique users chia theo Attribute by Brand
     *
     * @param $date_range
     * @param $brand
     * @return array
     */
    public function totalUniqueUserAttrByBrand($date_range, $brand)
    {
        $total_user_attr = $this->chatbot_history->totalUserAttributeByBrand($date_range, $brand)->toArray();
        $attr = [];
        foreach ($total_user_attr as $item) {
            $attr [] = [
                "attribute_name" => $item['attribute_name'],
                "name" => $item['name'],
                'brand_name' => $item['brand_name']
            ];
        }
        $group_attr = $this->array_group_by($attr, "attribute_name");
        $arr_total_user_attr = [];
        foreach ($group_attr as $key => $item) {
            $user = array_unique($item, SORT_REGULAR);
            $arr_total_user_attr [] = [
                $key,
                count($user),
                '',
                count($user)
            ];
        }
        return $arr_total_user_attr;
    }

    /**
     * Change Brand Sku Load Chart
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartSkuAction(Request $request)
    {
        $date_range = $request->date_range;
        //Total Unique User By Brand
        $total_user_sku = $this->chatbot_history->totalUserSkuByBrand($date_range, $request->brand_sku);
        $records = [];
        foreach ($total_user_sku as $item) {
            $records [] = [
                "sku_name" => $item['sku_name'],
                "name" => $item['name'],
            ];
        }
        $grouped = $this->array_group_by($records, "sku_name");
        $arr_total_user_sku = [];
        foreach ($grouped as $key => $item) {
            $user = array_unique($item, SORT_REGULAR);
            $arr_total_user_sku [] = [
                $key,
                count($user),
                '',
                count($user)
            ];
        }
        return response()->json([
            'total_user_sku' => $arr_total_user_sku
        ]);
    }

    /**
     * Change Brand Attr Load Chart
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function chartAttributeAction(Request $request)
    {
        $date_range = $request->date_range;
        //Total Unique User Attribute By Brand
        $total_user_attr = $this->chatbot_history->totalUserAttributeByBrand($date_range, $request->brand_attr)->toArray();
        $attr = [];
        foreach ($total_user_attr as $item) {
            $attr [] = [
                "attribute_name" => $item['attribute_name'],
                "name" => $item['name'],
                'brand_name' => $item['brand_name']
            ];
        }
        $group_attr = $this->array_group_by($attr, "attribute_name");
        $arr_total_user_attr = [];
        foreach ($group_attr as $key => $item) {
            $user = array_unique($item, SORT_REGULAR);
            $arr_total_user_attr [] = [
                $key,
                count($user),
                '',
                count($user)
            ];
        }
        return response()->json([
            'total_user_attr' => $arr_total_user_attr
        ]);
    }

    /**
     * Export User By Time
     *
     * @param Request $request
     */
    public function exportUserTimeAction(Request $request)
    {
        $params = $request->all();
        //All Brand
//        $brand = $this->chatbot_brand->getOptionBrand();
        $arr_brand = [];
//        foreach ($brand as $item) {
//            $arr_brand[$item['brand_name']] = '';
//        }

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
        $result = [];
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
            $data = [
                'STT' => $key + 1,
                'Họ và tên' => $item['name'],
                'Số điện thoại' => $item['phone'],
                'Email' => $item['email'],
                'Giới tính' => $gender,
                'Tag' => $item['tag_name'],
                'Ngày đầu tương tác' => $start_follow,
                'Ngày cuối tương tác' => $end_follow,

            ];
            $result [] = array_merge($data, $arr_brand);
        }
        Excel::create('users-by-time', function ($excel) use ($result) {
            $excel->sheet('SheetName', function ($sheet) use ($result) {
                $sheet->fromArray($result);
            });
        })->download('xlsx');
    }

    /**
     * Export Unique User By Brand
     *
     * @param Request $request
     */
    public function exportUserByBrand(Request $request)
    {
        $params = $request->all();
        //All Brand
        $brand = $this->chatbot_brand->getOptionBrand();
        $arr_brand = [];
        foreach ($brand as $item) {
            $arr_brand[$item['entities']] = 'Không';
        }
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
        //Total Unique User By Brand
        $mChannelTag = new CustomerChannelTagTable();
        $total_unique_user_brand = $mChannelTag->totalUniqueUserByBrand($params['date_range'])->toArray();
        $user = array_unique($total_unique_user_brand, SORT_REGULAR);
        $result = [];
        $stt = 0;
        $arrUser = [];
        foreach ($user as $item){
            $arrUser[$item['customer_channel_id']][] = $item;
        }

//        foreach ($user as $key => $item) {
        foreach ($arrUser as $key => $item) {
            $gender = '';
            if ($item[0]['gender'] == 'male') {
                $gender = 'Nam';
            } else if ($item[0]['gender'] == 'female') {
                $gender = 'Nữ';
            }
            $start_follow = '';
            $end_follow = '';
            if (isset($grouped[$item[0]['customer_id']]) && count($grouped[$item[0]['customer_id']]) > 0) {
                $start_follow = $grouped[$item[0]['customer_id']][0]['request_time'];
                $end_follow = $grouped[$item[0]['customer_id']][count($grouped[$item[0]['customer_id']]) - 1]['request_time'];
            }
            $data = [
                'STT' => $stt += 1,
                'Họ và tên' => $item[0]['name'],
                'Số điện thoại' => $item[0]['phone'],
                'Email' => $item[0]['email'],
                'Giới tính' => $gender,
                'Tag' => $item[0]['tag_name'],
                'Ngày đầu tương tác' => $start_follow,
                'Ngày cuối tương tác' => $end_follow,

            ];
            $tmpTag = '';
            $count = 0;
            foreach ($item as $oItem){
                $countTmpTag = 0;
                foreach ($brand as $itemBrand) {
                    if ($itemBrand['entities'] == $oItem['keyword']){
                        $countTmpTag = 1;
                    }
                }
                if ($countTmpTag == 1) {
                    if ($count == 0){
                        $tmpTag = $tmpTag.$oItem['tag_name'];
                        $count = 1;
                    } else {
                        $tmpTag = $tmpTag.','.$oItem['tag_name'];
                    }
                }
                if (isset($arr_brand[$oItem['keyword']])) {
                    $arr_brand[$oItem['keyword']] = 'Có';
                }
            }
            $data['Tag'] = $tmpTag;
            if ($data['Tag'] != null) {
                $result [] = array_merge($data, $arr_brand);
            }
            foreach ($brand as $itemBrand) {
                $arr_brand[$itemBrand['entities']] = 'Không';
            }
        }
        Excel::create('users-by-brand', function ($excel) use ($result) {
            $excel->sheet('SheetName', function ($sheet) use ($result) {
                $sheet->fromArray($result);
            });
        })->download('xlsx');
    }

    /**
     * Export User Unique Sku By Brand
     *
     * @param Request $request
     */
    public function exportUserSkuByBrand(Request $request)
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
        //Total Unique User Sku By Brand
        $total_user_sku = $this->chatbot_history->totalUserSkuByBrand($params['date_range'], $params['brand_sku'])->toArray();
        $group_brand = $this->array_group_by($total_user_sku, "brand_name");
        $data = [];
        $stt = 0;
        foreach ($group_brand as $key => $item) {
            $item = array_unique($item, SORT_REGULAR);
            foreach ($item as $k1 => $v1) {
                $gender = '';
                if ($v1['gender'] == 'male') {
                    $gender = 'Nam';
                } else if ($v1['gender'] == 'female') {
                    $gender = 'Nữ';
                }
                $start_follow = '';
                $end_follow = '';
                if (isset($grouped[$v1['customer_id']]) && count($grouped[$v1['customer_id']]) > 0) {
                    $start_follow = $grouped[$v1['customer_id']][0]['request_time'];
                    $end_follow = $grouped[$v1['customer_id']][count($grouped[$v1['customer_id']]) - 1]['request_time'];
                }
                $data [] = [
                    'STT' => $stt += 1,
                    'Họ và tên' => $v1['name'],
                    'Số điện thoại' => $v1['phone'],
                    'Email' => $v1['email'],
                    'Giới tính' => $gender,
                    'Tag' => $v1['tag_name'],
                    'Ngày đầu tương tác' => $start_follow,
                    'Ngày cuối tương tác' => $end_follow,
                    'SKU' => $v1['sku_name'],
                    'Brand Name' => $v1['brand_name']
                ];
            }
        }
        Excel::create('user-sku-by-brand', function ($excel) use ($data) {
            $excel->sheet('SheetName', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
    }

    /**
     * Export User Attribute By Brand
     *
     * @param Request $request
     */
    public function exportUserAttributeBrand(Request $request)
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
        //Total Unique User Attribute By Brand
        $total_user_attr = $this->chatbot_history->totalUserAttributeByBrand($params['date_range'], $params['brand_attr'])->toArray();
        $group_attr = $this->array_group_by($total_user_attr, "attribute_name");
        $data = [];
        $stt = 0;
        foreach ($group_attr as $key => $item) {
            $item = array_unique($item, SORT_REGULAR);
            foreach ($item as $k1 => $v1) {
                $gender = '';
                if ($v1['gender'] == 'male') {
                    $gender = 'Nam';
                } else if ($v1['gender'] == 'female') {
                    $gender = 'Nữ';
                }
                $start_follow = '';
                $end_follow = '';
                if (isset($grouped[$v1['customer_id']]) && count($grouped[$v1['customer_id']]) > 0) {
                    $start_follow = $grouped[$v1['customer_id']][0]['request_time'];
                    $end_follow = $grouped[$v1['customer_id']][count($grouped[$v1['customer_id']]) - 1]['request_time'];
                }
                $data [] = [
                    'STT' => $stt += 1,
                    'Họ và tên' => $v1['name'],
                    'Số điện thoại' => $v1['phone'],
                    'Email' => $v1['email'],
                    'Giới tính' => $gender,
                    'Tag' => $v1['tag_name'],
                    'Ngày đầu tương tác' => $start_follow,
                    'Ngày cuối tương tác' => $end_follow,
                    'Attribute' => $v1['attribute_name'],
                    'Brand Name' => $v1['brand_name']
                ];
            }
        }
        Excel::create('user-attr-by-brand', function ($excel) use ($data) {
            $excel->sheet('SheetName', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xlsx');
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