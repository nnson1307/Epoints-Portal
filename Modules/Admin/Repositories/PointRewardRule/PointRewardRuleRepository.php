<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 10:22 AM
 */

namespace Modules\Admin\Repositories\PointRewardRule;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\ConfigTimeResetRankTable;
use Modules\Admin\Models\PointRewardRuleTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\TimeResetRankTable;

class PointRewardRuleRepository implements PointRewardRuleRepositoryInterface
{
    protected $pointRewardRule;
    protected $config;
    protected $timeInsertRank;
    protected $configTimeResetRank;

    public function __construct(
        PointRewardRuleTable $pointRewardRule,
        ConfigTable $config,
        TimeResetRankTable $timeInsertRank,
        ConfigTimeResetRankTable $configTimeResetRank
    )
    {
        $this->pointRewardRule = $pointRewardRule;
        $this->config = $config;
        $this->timeInsertRank = $timeInsertRank;
        $this->configTimeResetRank = $configTimeResetRank;
    }

    public function getAll()
    {
        return $this->pointRewardRule->getAll();
    }

    public function edit(array $data = [])
    {
        try {
            DB::beginTransaction();
            //Lưu db.
            foreach ($data as $item) {
                $temp = [];

                if ($item['point_reward_rule_id'] == 1) {
                    $temp = [
                        'point_value' => 1 / str_replace(',', '', strip_tags($item['point_value'])),
                    ];
                } else {
                    $temp = [
                        'point_value' => str_replace(',', '', strip_tags($item['point_value'])),
                    ];
                }
                if ($item['point_reward_rule_id'] == 1) {
                    $temp['point_maths'] = '*';
                } else {
                    $temp['point_maths'] = $item['point_maths'];
                }
                if (in_array($item['point_reward_rule_id'], [9, 10, 11])) {
                    $temp['point_maths'] = $item['point_maths'];
                    $hagtagId = '';
                    if (isset($item['hagtag_id'])) {
                        if (count($item['hagtag_id']) > 0) {
                            foreach ($item['hagtag_id'] as $key => $value) {
                                $hagtagId .= $value . ',';
                            }
                        }
                        $temp['hagtag_id'] = substr($hagtagId, 0, -1);
                    } else {
                        $temp['hagtag_id'] = '0';
                    }
                } else {
                    $temp['hagtag_id'] = '0';
                }
//                $temp['point_reward_rule_id'] = $item['point_reward_rule_id'];
                $temp['modified_by'] = Auth::id();
                $temp['modified_at'] = date('Y-m-d H:i:s');
//                $temp['updated_at'] = date('Y-m-d H:i:s');
                $temp['is_actived'] = $item['is_actived'];
                //Edit model.
                $this->pointRewardRule->edit($temp, $item['point_reward_rule_id']);
            }

            DB::commit();
            return [
                'error' => false,
                'message' => ''
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getConfig()
    {
        return $this->config->getAll();
    }

    public function updateConfig(array $data = [])
    {
        try {
            DB::beginTransaction();
            $dataResetMemberRanking = [
                'value' => intval($data['reset_member_ranking'])
            ];
            $dataActivedLoyalty = [
                'value' => intval($data['actived_loyalty'])
            ];
            $this->config->edit($dataResetMemberRanking, 1);
            $this->config->edit($dataActivedLoyalty, 2);

            if (intval($data['reset_member_ranking'] > 0)) {
                //Remove All Config Time Rank
                $this->configTimeResetRank->removeAll();
                $type = '';
                $name = '';
                if (intval($data['reset_member_ranking'] == 1)) {
                    $type = 'one_month';
                    $name = 'Một tháng reset một lần';
                } else if (intval($data['reset_member_ranking'] == 2)) {
                    $type = 'two_month';
                    $name = 'Hai tháng reset một lần';
                } else if (intval($data['reset_member_ranking'] == 3)) {
                    $type = 'three_month';
                    $name = 'Ba tháng reset một lần';
                } else if (intval($data['reset_member_ranking'] == 4)) {
                    $type = 'four_month';
                    $name = 'Bốn tháng reset một lần';
                } else if (intval($data['reset_member_ranking'] == 6)) {
                    $type = 'six_month';
                    $name = 'Sáu tháng reset một lần';
                } else if (intval($data['reset_member_ranking'] == 12)) {
                    $type = 'one_year';
                    $name = 'Một năm reset một lần';
                }
                //Time Insert
                $value_insert = $this->timeInsertRank->getTimeByType($type);
                //Insert Config Time Reset Rank
                $this->configTimeResetRank->add([
                    'name' => $name,
                    'value' => $value_insert['value'],
                    'type' => $type,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id()
                ]);
            }
            DB::commit();
            return [
                'error' => false,
                'message' => ''
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateEvent(array $data = [])
    {
        try {
            DB::beginTransaction();
            $id = 11;
            foreach ($data['data'] as $item) {
                $id++;
                $temp = [
                    'point_value' => strip_tags($item['point_value']),
                    'is_actived' => intval($item['is_actived']),
                ];
                $temp['modified_by'] = Auth::id();
                $temp['modified_at'] = date('Y-m-d H:i:s');
//                $temp['updated_at'] = date('Y-m-d H:i:s');
                //Edit model.
                $this->pointRewardRule->edit($temp, $id);
            }

            DB::commit();
            return [
                'error' => false,
                'message' => ''
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * @param $rule_code
     * @return mixed
     */
    public function getRuleByCode($rule_code)
    {
        return $this->pointRewardRule->getRuleByCode($rule_code);
    }
}