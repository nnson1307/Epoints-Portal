<?php


namespace Modules\Admin\Http\Controllers;


use Carbon\Carbon;
use Modules\Admin\Repositories\Config\ConfigRepoInterface;
use Modules\Admin\Repositories\ConfigTimeResetRank\ConfigTimeResetRankRepoInterface;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\MemberLevel\MemberLevelRepositoryInterface;
use Modules\Admin\Repositories\PointHistory\PointHistoryRepoInterface;
use Modules\Admin\Repositories\ResetRankLog\ResetRankLogRepoInterface;

class ResetRankController extends Controller
{
    protected $config;
    protected $timeResetRank;
    protected $customer;
    protected $pointHistory;
    protected $resetRankLog;
    protected $memberLevel;

    public function __construct(
        ConfigRepoInterface $config,
        ConfigTimeResetRankRepoInterface $timeResetRank,
        CustomerRepositoryInterface $customer,
        PointHistoryRepoInterface $pointHistory,
        ResetRankLogRepoInterface $resetRankLog,
        MemberLevelRepositoryInterface $memberLevel
    ) {
        $this->config = $config;
        $this->timeResetRank = $timeResetRank;
        $this->customer = $customer;
        $this->pointHistory = $pointHistory;
        $this->resetRankLog = $resetRankLog;
        $this->memberLevel = $memberLevel;
    }

    public function resetRankAction()
    {
        $config_active = $this->config->getInfoByKey('actived_loyalty');
        if ($config_active['value'] == 1) {
            $config_time = $this->config->getInfoByKey('reset_member_ranking');
            if ($config_time['value'] > 0) {
                $type = '';
                if ($config_time['value'] == 1) {
                    $type = 'one_month';
                    $sub_start = Carbon::now()->subMonth(1);
                    $sub_end = Carbon::now()->subMonth(1);
                } else if ($config_time['value'] == 2) {
                    $type = 'two_month';
                    $sub_start = Carbon::now()->subMonth(2);
                    $sub_end = Carbon::now()->subMonth(1);
                } else if ($config_time['value'] == 3) {
                    $type = 'three_month';
                    $sub_start = Carbon::now()->subMonth(3);
                    $sub_end = Carbon::now()->subMonth(1);
                } else if ($config_time['value'] == 4) {
                    $type = 'four_month';
                    $sub_start = Carbon::now()->subMonth(4);
                    $sub_end = Carbon::now()->subMonth(1);
                } else if ($config_time['value'] == 6) {
                    $type = 'six_month';
                    $sub_start = Carbon::now()->subMonth(6);
                    $sub_end = Carbon::now()->subMonth(1);
                } else if ($config_time['value'] == 12) {
                    $type = 'one_year';
                    $sub_start = Carbon::now()->subMonth(12);
                    $sub_end = Carbon::now()->subMonth(1);
                }
                $time_reset_rank = $this->timeResetRank->getItemByType($type);
                if (strstr($time_reset_rank['value'], (string)Carbon::now()->month) == true) {
//                    $total_day_start = cal_days_in_month(CAL_GREGORIAN, $sub_start->month, $sub_end->year);
                    $total_day_end = cal_days_in_month(CAL_GREGORIAN, $sub_end->month, $sub_end->year);
                    $start_time = $sub_start->year . '-' . $sub_start->month . '-' . '01';
                    $end_time = $sub_end->year . '-' . $sub_end->month . '-' . $total_day_end;
                    //Danh sách khách hàng có tích lũy
                    $customer_point = $this->pointHistory->getPointGroupByCustomer($start_time, $end_time);
                    $arr_customer_point = [];
                    foreach ($customer_point as $item) {
                        $arr_customer_point[$item['customer_id']]  = $item['total'];
                    }
                    //Danh sách tất cã khách hàng
                    $list_customer = $this->customer->getAllCustomer()->toArray();
                    foreach ($list_customer as $item) {
                        $point = isset($arr_customer_point[$item['customer_id']]) ? $arr_customer_point[$item['customer_id']]: 0;
                        $rank = $this->memberLevel->rankByPoint($point)->toArray()[0];
                        //Cập nhật cấp độ khách hàng
                        $this->customer->edit([
                            'member_level_id' => $rank['member_level_id']
                        ], $item['customer_id']);
                        //Lưu log
                        $this->resetRankLog->add([
                            'customer_id' => $item['customer_id'],
                            'time_reset_rank_id' => $time_reset_rank['id'],
                            'month_reset' => Carbon::now()->month,
                            'member_level_id' => $rank['member_level_id'],
                            'member_level_old_id' => $item['member_level_id']
                        ]);
                    }
                    echo 'Cập nhật tích lũy thành công';
                } else {
                    echo 'Cập nhật tích lũy không thành công';
                }
            }
        }
    }
}