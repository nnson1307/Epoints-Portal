<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 05/08/2021
 * Time: 09:34
 */

namespace Modules\OnCall\Repositories\ReportOverview;


use Carbon\Carbon;
use Modules\OnCall\Models\HistoryTable;
use Modules\OnCall\Models\StaffTable;

class ReportOverviewRepo implements ReportOverviewRepoInterface
{
    /**
     * Lấy option view index
     *
     * @return array|mixed
     */
    public function getOption()
    {
        $mStaff = app()->get(StaffTable::class);

        //Lấy option nhân viên
        $optionStaff = $mStaff->getStaff();

        return [
            'optionStaff' => $optionStaff
        ];
    }

    /**
     * Load chart báo cáo tổng quan
     *
     * @param $input
     * @return mixed|void
     */
    public function loadChart($input)
    {
        $mHistory = app()->get(HistoryTable::class);

        $startTime = null;
        $endTime = null;

        //Lưu session filter để load list 1
        session()->put('filter_history', $input);

        if (isset($input["created_at"])) {
            $arr_filter = explode(" - ", $input["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        }

        //Lấy lịch sử cuộc gọi
        $getHistory = $mHistory->getHistoryReportStaff(
            $startTime,
            $endTime,
            $input['status'],
            $input['history_type'],
            $input['staff_id']
        )->toArray();

        if ($startTime == $endTime) {
            $isColumn = 1;
            //Xử lý dữ liệu filter 1 ngày
            $dataChart = $this->_handleOneDay($getHistory);
        } else {
            //Nhiều ngày (or 6 tháng)
            $dtStart = Carbon::parse($startTime);
            $dtEnd = Carbon::parse($endTime);
            $diffMonth = $dtEnd->diffInMonths($dtStart);

            if ($diffMonth >= 5) {
                $isColumn = 0;
                //Filter 6 tháng
                $dataChart = $this->_handleSixMonth($getHistory, $startTime, $endTime);
            } else {
                $isColumn = 1;
                //Filter nhiều ngày
                $dataChart = $this->_handleMoreDay($getHistory, $startTime, $endTime);
            }
        }

        //Lấy dữ liệu lịch sử cuộc gọi
        $getList = $mHistory->getList([
            'object_id_call' => $input['staff_id'],
            'status' => $input['status'],
            'created_at' => $input['created_at'],
            'history_type' => $input['history_type'],
            'page' => 1,
            'display' => 10
        ]);

        $htmlList1 = \View::make('on-call::report-overview.list1', [
            'LIST' => $getList,
            'page' => 1
        ])->render();

        return response()->json([
            'isColumn' => $isColumn,
            'dataChart' => $dataChart['dataChart'],
            'success' => $dataChart['success'],
            'fail' => $dataChart['fail'],
            'total' => count($getHistory),
            'htmlList1' => $htmlList1
        ]);
    }

    /**
     * Xử lý dữ liệu filter 1 ngày
     *
     * @param $getHistory
     * @return array
     */
    private function _handleOneDay($getHistory)
    {
        $success = 0;
        $fail = 0;

        //Filter 1 ngày
        $dataChart = [
            'categories' => [
                '0h-3h',
                '3h-6h',
                '6h-9h',
                '9h-12h',
                '12h-15h',
                '15h-18h',
                '18h-21h',
                '21h-24h'
            ],
            'series' => [
                [
                    'name' => __('Thành công'),
                    'data' => [0, 0, 0, 0, 0, 0, 0, 0],
                    'color' => '#2D85B6'
                ],
                [
                    'name' => __('Thất bại'),
                    'data' => [0, 0, 0, 0, 0, 0, 0, 0],
                    'color' => '#D86E6E'
                ]
            ],
            'textTotal' => __('Tổng')
        ];

        foreach ($getHistory as $v) {
            //Số thứ tự mãng (trong mãng khung giờ)
            $rangeTime = 0;

            if (0 <= $v['time_at_format'] && $v['time_at_format'] < 3) {
                $rangeTime = 0;
            } else if (3 <= $v['time_at_format'] && $v['time_at_format'] < 6) {
                $rangeTime = 1;
            } else if (6 <= $v['time_at_format'] && $v['time_at_format'] < 9) {
                $rangeTime = 2;
            } else if (9 <= $v['time_at_format'] && $v['time_at_format'] < 12) {
                $rangeTime = 3;
            } else if (12 <= $v['time_at_format'] && $v['time_at_format'] < 15) {
                $rangeTime = 4;
            } else if (15 <= $v['time_at_format'] && $v['time_at_format'] < 18) {
                $rangeTime = 5;
            } else if (18 <= $v['time_at_format'] && $v['time_at_format'] < 21) {
                $rangeTime = 6;
            } else if (21 <= $v['time_at_format'] && $v['time_at_format'] < 24) {
                $rangeTime = 7;
            }

            if ($v['status'] == 0) {
                //Thất bại
                $dataChart['series'][1]['data'][$rangeTime]++;
                $fail++;
            } else {
                //Thành công
                $dataChart['series'][0]['data'][$rangeTime]++;
                $success++;
            }
        }

        return [
            'dataChart' => $dataChart,
            'success' => $success,
            'fail' => $fail
        ];
    }

    /**
     * Xử lý dữ liệu nhiều ngày
     *
     * @param $getHistory
     * @param $startTime
     * @param $endTime
     * @return array
     */
    private function _handleMoreDay($getHistory, $startTime, $endTime)
    {
        $success = 0;
        $fail = 0;

        $dataChart = [
            'categories' => [],
            'series' => [
                [
                    'name' => __('Thành công'),
                    'data' => [],
                    'color' => '#2D85B6'
                ],
                [
                    'name' => __('Thất bại'),
                    'data' => [],
                    'color' => '#D86E6E'
                ]
            ],
            'textTotal' => __('Tổng')
        ];

        $dtStart = Carbon::parse($startTime);
        $dtEnd = Carbon::parse($endTime);
        $diffDay = $dtEnd->diffInDays($dtStart);

        for ($i = 0; $i <= $diffDay; $i++) {
            $dataChart['categories'][] = Carbon::createFromFormat('Y-m-d', $startTime)->addDays($i)->format('d/m');
            $dataChart['series'][0]['data'] [] = 0;
            $dataChart['series'][1]['data'] [] = 0;
        }

        foreach ($getHistory as $v) {
            $getKeyArray = array_search($v['created_at_format'], $dataChart['categories']);

            if ($v['status'] == 0) {
                //Thất bại
                $dataChart['series'][1]['data'] [$getKeyArray] ++;
                $fail++;
            } else {
                //Thành công
                $dataChart['series'][0]['data'] [$getKeyArray] ++;
                $success++;
            }
        }

        return [
            'dataChart' => $dataChart,
            'success' => $success,
            'fail' => $fail
        ];
    }

    /**
     * Xử lý dữ liệu 6 tháng
     *
     * @param $getHistory
     * @param $startTime
     * @param $endTime
     * @return array
     */
    private function _handleSixMonth($getHistory, $startTime, $endTime)
    {
        $success = 0;
        $fail = 0;

        $dataChart = [
            'categories' => [],
            'series' => [
                [
                    'name' => __('Thành công'),
                    'data' => [],
                    'color' => '#2D85B6'
                ],
                [
                    'name' => __('Thất bại'),
                    'data' => [],
                    'color' => '#D86E6E'
                ]
            ],
        ];

        $dtStart = Carbon::parse($startTime);
        $dtEnd = Carbon::parse($endTime);
        $diffMonth = $dtEnd->diffInMonths($dtStart);

        for ($i = 0; $i <= $diffMonth; $i++) {
            $dataChart['categories'][] = Carbon::createFromFormat('Y-m-d', $startTime)->addMonths($i)->format('m/Y');
            $dataChart['series'][0]['data'] =  array_fill(0, $diffMonth + 1, 0);
            $dataChart['series'][1]['data'] =  array_fill(0, $diffMonth + 1, 0);
        }

        foreach ($getHistory as $v) {
            $getKeyArray = array_search($v['month_at_format'], $dataChart['categories']);

            if ($v['status'] == 0) {
                //Thất bại
                $dataChart['series'][1]['data'] [$getKeyArray] ++;
                $fail++;
            } else {
                //Thành công
                $dataChart['series'][0]['data'] [$getKeyArray] ++;
                $success++;
            }
        }

        return [
            'dataChart' => $dataChart,
            'success' => $success,
            'fail' => $fail
        ];
    }

    /**
     * Load dữ liệu list 1
     *
     * @param $input
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function loadList1($input)
    {
        $mHistory = app()->get(HistoryTable::class);

        //Lấy session filter
        $filter = session()->get('filter_history');

        $input['object_id_call'] = $filter['staff_id'];
        $input['status'] = $filter['status'];
        $input['created_at'] = $filter['created_at'];
        $input['history_type'] = $filter['history_type'];


        //Lấy dữ liệu lịch sử cuộc gọi
        $getList = $mHistory->getList($input);

        return view('on-call::report-overview.list1', [
            'LIST' => $getList,
            'page' => $input['page']
        ]);
    }
}