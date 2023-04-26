<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/08/2021
 * Time: 11:08
 */

namespace Modules\OnCall\Repositories\ReportStaff;


use Illuminate\Support\Carbon;
use Modules\OnCall\Models\HistoryTable;
use Modules\OnCall\Models\StaffTable;

class ReportStaffRepo implements ReportStaffRepoInterface
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
     * Load dữ liệu báo cáo
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

        //Lấy số liệu làm biểu đồ tròn
        $totalHistory = count($getHistory);

        //Data chart 1
        $dataChart1 = [
            'categories' => [],
            'series' => [
                [
                    'name' => __('Thành công'),
                    'data' => [],
                    'color' => '#2D85B6',
                ],
                [
                    'name' => __('Thất bại'),
                    'data' => [],
                    'color' => '#D86E6E',
                ],
            ]
        ];

        //Data chart 2
        $dataChart2 = [
            'categories' => [],
            'series' => []
        ];

        $isOneDay = 1;

        //Phân tách ngày or khung giờ
        if ($startTime == $endTime) {
            //Filter 1 ngày
            $dataChart2['categories'] = [
                '0h-3h',
                '3h-6h',
                '6h-9h',
                '9h-12h',
                '12h-15h',
                '15h-18h',
                '18h-21h',
                '21h-24h'
            ];
        } else {
            //Nhiều ngày (or 6 tháng)
            $dtStart = Carbon::parse($startTime);
            $dtEnd = Carbon::parse($endTime);
            $diffMonth = $dtEnd->diffInMonths($dtStart);

            if ($diffMonth >= 5) {
                //Filter 6 tháng
                $isOneDay = 3;

                for ($i = 0; $i <= $diffMonth; $i++) {
                    $dataChart2['categories'][] = Carbon::createFromFormat('Y-m-d', $startTime)->addMonths($i)->format('m/Y');
                }
            } else {
                //Filter nhiều ngày
                $isOneDay = 2;

                $dtStart = Carbon::parse($startTime);
                $dtEnd = Carbon::parse($endTime);
                $diffDay = $dtEnd->diffInDays($dtStart);

                for ($i = 0; $i <= $diffDay; $i++) {
                    $dataChart2['categories'][] = Carbon::createFromFormat('Y-m-d', $startTime)->addDays($i)->format('d/m');
                }
            }
        }

        //Group dữ liệu lịch sữ theo tên nhân viên
        $groupByStaffName = collect($getHistory)->groupBy('object_name_call');

        if (count($groupByStaffName) > 0) {
            foreach ($groupByStaffName as $k => $v) {
                $dataChart1['categories'][] = $k;

                $success = 0;
                $fail = 0;

                if (count($v) > 0) {
                    //Lấy số liệu thành công, thất bại (chart 1)
                    foreach ($v as $v1) {
                        if ($v1['status'] == 0) {
                            $fail++;
                        } else {
                            $success++;
                        }
                    }

                    //Lấy số liệu theo ngày or khung giờ (chart 2)
                    if ($isOneDay == 2) {
                        //Filter nhiều ngày
                        //Group data cuộc gọi của nhân viên (theo ngày)
                        $groupDataCreatedAt = collect($v)->groupBy('created_at_format');

                        $dataSeries2 = [];

                        foreach ($dataChart2['categories'] as $v1) {
                            $number = 0;

                            foreach ($groupDataCreatedAt as $k2 => $v2) {
                                if ($v1 == $k2) {
                                    $number = count($v2);
                                }
                            }

                            $dataSeries2 [] = $number;
                        }

                        $dataChart2['series'][] = [
                            'name' => $k,
                            'data' => $dataSeries2
                        ];
                    } else if ($isOneDay == 1) {
                        //Filter 1 ngày
                        $dataSeries2 = [];

                        //Group data cuộc gọi của nhân viên (theo ngày)
                        $groupDataTimeAt = collect($v)->groupBy('time_at_format');
                        foreach ($dataChart2['categories'] as $k1 => $v1) {
                            $number = 0;

                            foreach ($groupDataTimeAt as $k2 => $v2) {
                                //Số thứ tự mãng (trong mãng khung giờ)
                                $rangeTime = 0;

                                if (0 < $k2 && $k2 <= 3) {
                                    $rangeTime = 0;
                                } else if (3 < $k2 && $k2 <= 6) {
                                    $rangeTime = 1;
                                } else if (6 < $k2 && $k2 <= 9) {
                                    $rangeTime = 2;
                                } else if (9 < $k2 && $k2 <= 12) {
                                    $rangeTime = 3;
                                } else if (12 < $k2 && $k2 <= 15) {
                                    $rangeTime = 4;
                                } else if (15 < $k2 && $k2 <= 18) {
                                    $rangeTime = 5;
                                } else if (18 < $k2 && $k2 <= 21) {
                                    $rangeTime = 6;
                                } else if (21 < $k2 && $k2 <= 24) {
                                    $rangeTime = 7;
                                }

                                if ($k1 == $rangeTime) {
                                    $number = count($v2);
                                }
                            }

                            $dataSeries2 [] = $number;
                        }

                        $dataChart2['series'][] = [
                            'name' => $k,
                            'data' => $dataSeries2
                        ];
                    } else if ($isOneDay == 3) {
                        $dtStart = Carbon::parse($startTime);
                        $dtEnd = Carbon::parse($endTime);
                        $diffMonth = $dtEnd->diffInMonths($dtStart);

                        $dataChart2['series'][$k]['name'] =  $k;

                        for ($i = 0; $i <= $diffMonth; $i++) {
                            $dataChart2['series'][$k]['data'] =  array_fill(0, $diffMonth + 1, 0);
                        }


                        //Group data cuộc gọi của nhân viên (theo ngày)
                        $groupDataMonthAt = collect($v)->groupBy('month_at_format');

                        foreach ($groupDataMonthAt as $k1 => $v1) {
                            $getKeyArray = array_search($k1, $dataChart2['categories']);

                            $dataChart2['series'][$k]['data'] [$getKeyArray] = count($v1);
                        }
                    }
                }

                $dataChart1['series'][0]['data'] [] = $success;
                $dataChart1['series'][1]['data'] [] = $fail;
            }
        }
        //Xử lý hãng tuần tự chart 2
        $dataChart2['series'] = array_values($dataChart2['series']);

        //Lấy data list 2
        $dataList2 = $this->_handleDataList2($getHistory, $isOneDay);

        $htmlList2 = \View::make('on-call::report-staff.list2', [
            'dataList2' => $dataList2['dataList2'],
            'totalHistory' => $totalHistory,
            'totalSuccess' => $dataList2['totalSuccess'],
            'totalFail' => $dataList2['totalFail']
        ])->render();

        //Data chart 3
        $dataChart3 = [
            'series' => [
                'name' => __('Tỷ lệ'),
                'data' => [
                    [
                        'name' => __('Thành công'),
                        'y' => $totalHistory > 0 ? round((($dataList2['totalSuccess']/$totalHistory) * 100), 1) : 0,
                        'color' => '#2D85B6'
                    ],
                    [
                        'name' => __('Thất bại'),
                        'y' => $totalHistory > 0 ? round((($dataList2['totalFail']/$totalHistory) * 100), 1) : 0,
                        'color' => '#D86E6E'
                    ]
                ]
            ]
        ];

        //Lấy dữ liệu lịch sử cuộc gọi
        $getList = $mHistory->getList([
            'object_id_call' => $input['staff_id'],
            'status' => $input['status'],
            'created_at' => $input['created_at'],
            'history_type' => $input['history_type'],
            'page' => 1,
            'display' => 10
        ]);

        $htmlList1 = \View::make('on-call::report-staff.list1', [
            'LIST' => $getList,
            'page' => 1
        ])->render();

        return response()->json([
            'dataChart1' => $dataChart1,
            'dataChart2' => $dataChart2,
            'dataChart3' => $dataChart3,
            'htmlList1' => $htmlList1,
            'htmlList2' => $htmlList2,
        ]);
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

        return view('on-call::report-staff.list1', [
            'LIST' => $getList,
            'page' => $input['page']
        ]);
    }

    /**
     * Xử lý dữ liệu list 2
     *
     * @param $getHistory
     * @param $isOneDay
     * @return array
     */
    public function _handleDataList2($getHistory, $isOneDay)
    {
        $dataList2 = [];
        $totalSuccess = 0;
        $totalFail = 0;

        if ($isOneDay != 3) {
            //Filter 1 or nhiều ngày

            //Group dữ liệu theo ngày
            $groupByCreatedAt = collect($getHistory)->groupBy('created_at_format');

            if (count($groupByCreatedAt) > 0) {
                foreach ($groupByCreatedAt as $k => $v) {
                    $totalItem = count($v);
                    $totalItemSuccess = 0;
                    $totalItemFail = 0;

                    foreach ($v as $v1) {
                        if ($v1['status'] == 0) {
                            $totalItemFail++;
                            $totalFail++;
                        } else {
                            $totalItemSuccess++;
                            $totalSuccess++;
                        }
                    }

                    $dataList2 [] = [
                        'date' => $k,
                        'total' => $totalItem,
                        'success' => $totalItemSuccess,
                        'fail' => $totalItemFail
                    ];
                }
            }
        } else {
            //Filter >= 6 tháng

            //Group dữ liệu theo tháng
            $groupDataMonthAt = collect($getHistory)->groupBy('month_at_format');

            if (count($groupDataMonthAt) > 0) {
                foreach ($groupDataMonthAt as $k => $v) {
                    $totalItem = count($v);
                    $totalItemSuccess = 0;
                    $totalItemFail = 0;

                    foreach ($v as $v1) {
                        if ($v1['status'] == 0) {
                            $totalItemFail++;
                            $totalFail++;
                        } else {
                            $totalItemSuccess++;
                            $totalSuccess++;
                        }
                    }

                    $dataList2 [] = [
                        'date' => $k,
                        'total' => $totalItem,
                        'success' => $totalItemSuccess,
                        'fail' => $totalItemFail
                    ];
                }
            }
        }

        return [
            'dataList2' => $dataList2,
            'totalSuccess' => $totalSuccess,
            'totalFail' => $totalFail
        ];
    }
}