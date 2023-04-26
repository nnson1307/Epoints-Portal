<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 4:43 PM
 */

namespace Modules\Admin\Repositories\Loyalty;

use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\PointHistoryDetailTable;
use Modules\Admin\Models\PointHistoryTable;
use Modules\Admin\Models\PointRewardRuleTable;

class LoyaltyRepository implements LoyaltyRepositoryInterface
{
    protected $pointRewardRule;
    protected $orderDetail;
    protected $order;
    protected $pointHistory;
    protected $pointHistoryDetail;
    protected $customer;
    protected $config;
    protected $memberLevel;

    public function __construct(
        PointRewardRuleTable $pointRewardRule,
        PointHistoryTable $pointHistory,
        PointHistoryDetailTable $pointHistoryDetail,
        CustomerTable $customer
    ) {
        $this->pointRewardRule = $pointRewardRule;
        $this->pointHistory = $pointHistory;
        $this->pointHistoryDetail = $pointHistoryDetail;
        $this->customer = $customer;
    }

    /**
     * Tính điểm event
     * @param array $data
     *
     * @return array
     */
    public function plusPointEvent(array $data = [])
    {
        try {
            DB::beginTransaction();

            $point = 0;
            $pointRewardRuleId = 0;
            $pointRewardRule = $this->pointRewardRule->getAllActive();
            foreach ($pointRewardRule as $item) {
                if ($item['rule_code'] == strip_tags($data['rule_code'])) {
                    $point = intval($item['point_value']);
                    $pointRewardRuleId = $item['point_reward_rule_id'];
                }
            }


            $customer = $this->customer->getItem($data['customer_id']);
            if ($customer != null) {
                //Cập nhật điểm của khách hàng.
                $pointCustomer = $point + $customer['point'];
                $this->customer->edit(['point' => $pointCustomer], $customer['customer_id']);

                //Lưu history
                $dataHistory = [
                    'customer_id' => $data['customer_id'],
                    'point' => $point,
                    'type' => 'plus',
                    'point_description' => $data['rule_code'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $pointHistoryId = $this->pointHistory->add($dataHistory);
                $dataHistoryDetail = [
                    'point_history_id' => $pointHistoryId,
                    'point_reward_rule_id' => $pointRewardRuleId,
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->pointHistoryDetail->add($dataHistoryDetail);
            } else {
                return [
                    'error'   => false,
                    'point' => 0,
                    'message' => 'Customer null',

                ];
            }


            DB::commit();
            return [
                'error'   => false,
                'point' => $point,
                'message' => 'Success',

            ];
    } catch (Exception $e) {
            DB::rollBack();
            return [
                'error'   => true,
                'point' => 0,
                'message' => $e->getMessage(),
            ];
        }
    }
}
