<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/20/2019
 * Time: 4:43 PM
 */

namespace Modules\Booking\Repositories\Loyalty;

use App\Jobs\FunctionSendNotify;
use App\Jobs\SaveLogZns;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\MemberLevelTable;
use Modules\Booking\Models\ConfigTable;
use Modules\Booking\Models\CustomerTable;
use Modules\Booking\Models\OrderDetailTable;
use Modules\Booking\Models\OrderTable;
use Modules\Booking\Models\PointHistoryDetailTable;
use Modules\Booking\Models\PointHistoryTable;
use Modules\Booking\Models\PointRewardRuleTable;
use Modules\Booking\Models\ReceiptTable;

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
        OrderDetailTable $orderDetail,
        OrderTable $order,
        PointHistoryTable $pointHistory,
        PointHistoryDetailTable $pointHistoryDetail,
        CustomerTable $customer,
        ConfigTable $config,
        MemberLevelTable $memberLevel
    )
    {
        $this->pointRewardRule = $pointRewardRule;
        $this->orderDetail = $orderDetail;
        $this->order = $order;
        $this->pointHistory = $pointHistory;
        $this->pointHistoryDetail = $pointHistoryDetail;
        $this->customer = $customer;
        $this->config = $config;
        $this->memberLevel = $memberLevel;
    }

    /**
     * Tính điểm.
     *
     * @param array $data
     *
     * @return int
     */
    public function scoreCalculation(array $data = [])
    {
        try {
            DB::beginTransaction();
            $result = 0;
            $pointPlus = 0;
            $arrayPointRewardRuleId = [];
            //Chi tiết hóa đơn.
            $orderId = intval($data['order_id']);
            $orderDetail = $this->orderDetail->getDetail($orderId);

            $arrayProduct = [];
            $arrayService = [];
            $arrayServiceCard = [];

            //Mảng cộng
            $arrayPlus = [];

            //Danh sách các rule nhân và giá trị.
            $multiplication = [];

            //Danh sách các danh mục đặc biệt.
            $specialCategory['product'] = [];
            $specialCategory['service'] = [];
            $specialCategory['serviceCard'] = [];

            $specialPlus = [];
            $specialMultiplication = [];

            //Cờ các sp/dv/tdv đặc biệt
            $flagProductSpecial = 0;
            $flagServiceSpecial = 0;
            $flagServiceCardSpecial = 0;
            $active = $this->config->getItem(2);

            $arrayRuleTempMultiplication = [];
            $arrayRuleTempPlus = [];
            if ($active['value'] == 1) {
                if (count($orderDetail) > 0) {
                    //Các quy tắc điểm thưởng.
                    $pointRewardRule = $this->pointRewardRule->getAllActive();
                    if (count($pointRewardRule) > 0) {
                        foreach ($orderDetail as $item) {
                            if ($item['object_type'] == 'product') {
                                $arrayProduct[] = [
                                    'object_id' => $item['object_id'],
                                    'object_name' => $item['object_name'],
                                    'amount' => $item['amount'],
                                ];
                            } elseif ($item['object_type'] == 'service') {
                                $arrayService[] = [
                                    'object_id' => $item['object_id'],
                                    'object_name' => $item['object_name'],
                                    'amount' => $item['amount'],
                                ];
                            } elseif ($item['object_type'] == 'service_card') {
                                $arrayServiceCard[] = [
                                    'object_id' => $item['object_id'],
                                    'object_name' => $item['object_name'],
                                    'amount' => $item['amount'],
                                ];
                            }
                        }
                        foreach ($pointRewardRule as $item) {
                            if ($item['rule_type'] == 'purchase') {
                                if (!in_array(
                                    $item['rule_code'],
                                    ['service_special', 'product_special',
                                        'service_card_special']
                                )
                                ) {
                                    if ($item['point_maths'] == '+') {
                                        $arrayPlus[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_type' => $item['rule_type'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                            'hagtag_id' => $item['hagtag_id'],
                                        ];

                                        $specialPlus[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_type' => $item['rule_type'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                            'hagtag_id' => explode(
                                                ',', $item['hagtag_id']
                                            ),
                                        ];
                                    } elseif ($item['point_maths'] == '*') {
                                        $multiplication[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                            'hagtag_id' => $item['hagtag_id'],
                                        ];
                                    }
                                } else {
                                    if ($item['point_maths'] == '+') {
                                        $specialPlus[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_type' => $item['rule_type'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                            'hagtag_id' => explode(
                                                ',', $item['hagtag_id']
                                            ),
                                        ];
                                    } elseif ($item['point_maths'] == '*') {
                                        $specialMultiplication[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                            'hagtag_id' => explode(
                                                ',', $item['hagtag_id']
                                            ),
                                        ];
                                    }
                                    if ($item['rule_code'] == 'service_special') {
                                        $specialCategory['service'] = explode(
                                            ',', $item['hagtag_id']
                                        );
                                    } elseif ($item['rule_code'] == 'product_special') {
                                        $specialCategory['product'] = explode(
                                            ',', $item['hagtag_id']
                                        );;
                                    } elseif ($item['rule_code']
                                        == 'service_card_special'
                                    ) {
                                        $specialCategory['serviceCard'] = explode(
                                            ',', $item['hagtag_id']
                                        );;
                                    }
                                }

                                //Danh sách rule khi tính điểm
                                if (!in_array(
                                    $item['rule_code'],
                                    [
                                        'payment_ratio',
                                        'product',
                                        'services',
                                        'service_card',
                                    ]
                                )) {
                                    if ($item['point_maths'] == '*') {
                                        $arrayRuleTempMultiplication[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_type' => $item['rule_type'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                        ];
                                    } else {
                                        $arrayRuleTempPlus[] = [
                                            'point_reward_rule_id' => $item['point_reward_rule_id'],
                                            'rule_type' => $item['rule_type'],
                                            'rule_code' => $item['rule_code'],
                                            'rule_name' => $item['rule_name'],
                                            'point_maths' => $item['point_maths'],
                                            'point_value' => $item['point_value'],
                                        ];
                                    }

                                }

                            }
                        }

                        ////Tính điểm.

                        //Xét trường hợp các sp/dv/tdv có được cộng điểm không.
                        $flagSpecialProductPlus = 0;
                        $flagSpecialServicePlus = 0;
                        $flagSpecialServiceCardPlus = 0;

                        foreach ($specialPlus as $s => $specialMu) {
                            if (isset($specialMu['rule_code'])
                                && $specialMu['rule_code'] == 'product_special'
                            ) {
                                foreach ($arrayProduct as $p => $product) {
                                    if (in_array($product['object_id'], $specialMu['hagtag_id'])) {
                                        $flagSpecialProductPlus = 1;
                                    }
                                };
                            }
                        }

                        foreach ($specialPlus as $s => $specialMu) {
                            if (isset($specialMu['rule_code'])
                                && $specialMu['rule_code'] == 'service_special'
                            ) {
                                foreach ($arrayService as $p => $product) {
                                    if (in_array($product['object_id'], $specialMu['hagtag_id'])) {
                                        $flagSpecialServicePlus = 1;
                                    }
                                };
                            }
                        }

                        foreach ($specialPlus as $s => $specialMu) {
                            if (isset($specialMu['rule_code'])
                                && $specialMu['rule_code'] == 'service_card_special'
                            ) {
                                foreach ($arrayServiceCard as $p => $product) {
                                    if (in_array($product['object_id'], $specialMu['hagtag_id'])) {
                                        $flagSpecialServiceCardPlus = 1;
                                    }
                                };
                            }
                        }

                        $pointProduct = 0;
                        $pointService = 0;
                        $pointServiceCard = 0;

                        //Tính điểm sản phẩm.
                        if (count($arrayProduct) > 0) {
                            foreach ($specialMultiplication as $s => $specialMu) {
                                if (isset($specialMu['rule_code'])
                                    && $specialMu['rule_code'] == 'product_special'
                                ) {
                                    foreach ($arrayProduct as $p => $product) {
                                        if (in_array($product['object_id'], $specialMu['hagtag_id'])) {
                                            $flagProductSpecial = 1;
                                        }
                                    };
                                }
                            }
                            if ($flagProductSpecial == 1) {
                                foreach ($specialMultiplication as $s => $specialMu) {
                                    if (isset($specialMu['rule_code']) && $specialMu['rule_code'] == 'product_special') {
                                        $multiplication[] = [
                                            'point_reward_rule_id' => $specialMu['point_reward_rule_id'],
                                            'rule_code' => $specialMu['rule_code'],
                                            'rule_name' => $specialMu['rule_name'],
                                            'point_maths' => $specialMu['point_maths'],
                                            'point_value' => $specialMu['point_value'],
                                            'hagtag_id' => $specialMu['hagtag_id'],
                                        ];
                                    }
                                }
                            }

                            foreach ($arrayProduct as $p => $product) {
                                $temp = 0;
                                foreach ($multiplication as $key => $value) {
                                    if ($value['rule_code'] == 'payment_ratio') {
                                        $arrayPointRewardRuleId[] = 1;
                                        $temp = $value['point_value'] * $product['amount'];

                                    } elseif ($value['rule_code'] == 'product') {
                                        $arrayPointRewardRuleId[] = 2;
                                        $temp = $temp * $value['point_value'];
                                    }
                                }
                                $pointProduct += $temp;
                            }
                        }

                        //Tính điểm dịch vụ.
                        if (count($arrayService) > 0) {
                            foreach ($specialMultiplication as $s => $specialMu) {
                                if (isset($specialMu['rule_code'])
                                    && $specialMu['rule_code'] == 'service_special'
                                ) {
                                    foreach ($arrayService as $p => $product) {
                                        if (in_array($product['object_id'], $specialMu['hagtag_id'])) {
                                            $flagServiceSpecial = 1;
                                        }
                                    };
                                }
                            }
                            if ($flagServiceSpecial == 1) {
                                foreach ($specialMultiplication as $s => $specialMu) {
                                    if (isset($specialMu['rule_code']) && $specialMu['rule_code'] == 'service_special') {
                                        $multiplication[] = [
                                            'point_reward_rule_id' => $specialMu['point_reward_rule_id'],
                                            'rule_code' => $specialMu['rule_code'],
                                            'rule_name' => $specialMu['rule_name'],
                                            'point_maths' => $specialMu['point_maths'],
                                            'point_value' => $specialMu['point_value'],
                                            'hagtag_id' => $specialMu['hagtag_id'],
                                        ];
                                    }
                                }
                            }
                            foreach ($arrayService as $p => $product) {
                                $temp = 0;
                                foreach ($multiplication as $key => $value) {
                                    if ($value['rule_code'] == 'payment_ratio') {
                                        $arrayPointRewardRuleId[] = 1;
                                        $temp = $value['point_value'] * $product['amount'];
                                    } elseif ($value['rule_code'] == 'services') {
                                        $arrayPointRewardRuleId[] = 3;
                                        $temp = $temp * $value['point_value'];
                                    }
                                }
                                $pointService += $temp;
                            }
                        }

                        //Tính điểm thẻ dịch vụ.
                        if (count($arrayServiceCard) > 0) {
                            foreach ($specialMultiplication as $s => $specialMu) {
                                if (isset($specialMu['rule_code'])
                                    && $specialMu['rule_code'] == 'service_card_special'
                                ) {
                                    foreach ($arrayServiceCard as $p => $product) {
                                        if (in_array($product['object_id'], $specialMu['hagtag_id'])) {
                                            $flagServiceCardSpecial = 1;
                                        }
                                    };
                                }
                            }
                            if ($flagServiceCardSpecial == 1) {
                                foreach ($specialMultiplication as $s => $specialMu) {
                                    if (isset($specialMu['rule_code']) && $specialMu['rule_code'] == 'service_card_special') {
                                        $multiplication[] = [
                                            'point_reward_rule_id' => $specialMu['point_reward_rule_id'],
                                            'rule_code' => $specialMu['rule_code'],
                                            'rule_name' => $specialMu['rule_name'],
                                            'point_maths' => $specialMu['point_maths'],
                                            'point_value' => $specialMu['point_value'],
                                            'hagtag_id' => $specialMu['hagtag_id'],
                                        ];
                                    }
                                }
                            }
                            foreach ($arrayServiceCard as $p => $product) {
                                $temp = 0;
                                foreach ($multiplication as $key => $value) {
                                    if ($value['rule_code'] == 'payment_ratio') {
                                        $arrayPointRewardRuleId[] = 1;
                                        $temp = $value['point_value'] * $product['amount'];
                                    } elseif ($value['rule_code'] == 'service_card') {
                                        $arrayPointRewardRuleId[] = 4;
                                        $temp = $temp * $value['point_value'];
                                    }
                                }
                                $pointServiceCard += $temp;
                            }
                        }

                        //Tổng điểm của tỉ lệ SP/DV/TDV
                        $result = $pointProduct + $pointService + $pointServiceCard;
                        //Thông tin KH.
                        $customer = $this->order->getDetail($orderId);

                        //Tính điểm theo hạng thành viên
                        if ($customer != null) {
                            if ($customer['code'] != null) {
                                $flag = 0;
                                foreach ($arrayRuleTempPlus as $item) {
                                    if ($item['rule_code'] == $customer['code']) {
                                        $pointPlus += ($item['point_value']);
                                        $flag = 1;
                                        $arrayPointRewardRuleId[] = $item['point_reward_rule_id'];
                                        break;
                                    }
                                }
                                if ($flag == 0) {
                                    foreach ($arrayRuleTempMultiplication as $item) {
                                        if ($item['rule_code'] == $customer['code']) {
                                            $result *= ($item['point_value']);
                                            $arrayPointRewardRuleId[] = $item['point_reward_rule_id'];
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if (count($arrayRuleTempPlus) > 0) {
                            foreach ($arrayRuleTempPlus as $item) {
                                //Nếu có thuộc rule SP đặc biệt.
                                if ($flagSpecialProductPlus == 1) {
                                    if ($item['rule_code'] == 'product_special') {
                                        $pointPlus += intval($item['point_value']);
                                        $arrayPointRewardRuleId[] = 10;
                                    }
                                }
                                //Nếu có thuộc rule DV đặc biệt.
                                if ($flagSpecialServicePlus == 1) {
                                    if ($item['rule_code'] == 'service_special') {
                                        $pointPlus += intval($item['point_value']);
                                        $arrayPointRewardRuleId[] = 9;
                                    }
                                }
                                //Nếu có thuộc rule TDV đặc biệt.
                                if ($flagSpecialServiceCardPlus == 1) {
                                    if ($item['rule_code'] == 'service_card_special') {
                                        $pointPlus += intval($item['point_value']);
                                        $arrayPointRewardRuleId[] = 11;
                                    }
                                }
                            }
                        }

                        $arrayPointRewardRuleId = array_unique($arrayPointRewardRuleId);

                        $result = floatval($result) + floatval($pointPlus);
                        $dataHistory = [
                            'customer_id' => $customer['customer_id'],
                            'order_id' => $orderId,
                            'point' => floatval($result),
                            'type' => 'plus',
                            'point_description' => 'plus',
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        $pointHistoryId = $this->pointHistory->add($dataHistory);
                        if (count(($arrayPointRewardRuleId)) > 0) {
                            foreach (($arrayPointRewardRuleId) as $key => $value) {
                                $dataHistoryDetail = [
                                    'point_history_id' => $pointHistoryId,
                                    'point_reward_rule_id' => $value,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ];
                                $this->pointHistoryDetail->add($dataHistoryDetail);
                            }
                        }

                        //Cập nhật điểm của khách hàng.
                        $pointCustomer = floatval($result) + floatval($customer['point']);
                        $pointBalance = floatval($result) + floatval($customer['point_balance']);
                        //////////
                        $dataUpdate = [
                            'point' => $pointCustomer,
                            'point_balance' => $pointBalance,
                        ];
                        $this->customer->edit($dataUpdate, $customer['customer_id']);
                        //Lưu log ZNS
                        FunctionSendNotify::dispatch([
                            'type' => SEND_ZNS_CUSTOMER,
                            'key' => 'bonus_points',
                            'customer_id' => $customer['customer_id'],
                            'object_id' => floatval($result),
                            'tenant_id' => session()->get('idTenant')
                        ]);

                        $customer = $this->order->getDetail($orderId);
                        $reset = $this->config->getItem(1);
                        $memberLevel = $this->memberLevel->getOptionMemberLevel();
                        $level = null;
                        if ($reset['value'] == 0) {

                            foreach ($memberLevel as $item) {
                                if (floatval($customer['point']) >= floatval($item['point'])) {
                                    $level = $item['member_level_id'];
                                }
                            }
                            $this->customer->edit(['member_level_id' => $level], $customer['customer_id']);
                        }
                        $a['customer_point'] = $customer['point'];
                        $a['member_level_id'] = $level;

                        DB::commit();
                        return $result;
                    } else {
                        DB::commit();
                        return 0;
                    }
                } else {
                    DB::commit();
                    return 0;
                }
            } else {
                return 0;
            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cộng điểm khi submit event
     *
     * @param array $data
     * @return array
     */
    public function plusPointEvent(array $data = [])
    {
        $active = $this->config->getItem(2);
        if ($active['value'] == 1) {
            $ruleCode = strip_tags($data['rule_code']);
            $pointRewardRule = $this->pointRewardRule->getItemByCode($ruleCode);
            //Lấy thông tin KH
            $customer = $this->customer->getItem($data['customer_id']);
            if ($pointRewardRule != null && $customer != null && $customer['customer_id'] != 1) {
                $point = $pointRewardRule['point_value'];
                //Cập nhật điểm của khách hàng.
                $pointCustomer = floatval($point) + $customer['point'];
                $pointBalance = floatval($point) + $customer['point_balance'];
                //////////
                $dataUpdate = [
                    'point' => $pointCustomer,
                    'point_balance' => $pointBalance,
                ];
                $this->customer->edit($dataUpdate, $customer['customer_id']);
                //Lưu log ZNS
                FunctionSendNotify::dispatch([
                    'type' => SEND_ZNS_CUSTOMER,
                    'key' => 'bonus_points',
                    'customer_id' => $customer['customer_id'],
                    'object_id' => floatval($point),
                    'tenant_id' => session()->get('idTenant')
                ]);
                //Lưu history
                $dataHistory = [
                    'customer_id' => intval($data['customer_id']),
                    'point' => floatval($point),
                    'type' => 'plus',
                    'point_description' => $ruleCode,
                    'object_id' => $data['object_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $pointHistoryId = $this->pointHistory->add($dataHistory);
                $dataHistoryDetail = [
                    'point_history_id' => $pointHistoryId,
                    'point_reward_rule_id' => $pointRewardRule['point_reward_rule_id'],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
                $this->pointHistoryDetail->add($dataHistoryDetail);

                $reset = $this->config->getItem(1);
                $memberLevel = $this->memberLevel->getOptionMemberLevel();
                $level = null;
                if ($reset['value'] == 0) {
                    //Lấy thông tin KH sau khi + điểm
                    $customer = $this->customer->getItem($data['customer_id']);

                    foreach ($memberLevel as $item) {
                        if (floatval($customer['point']) >= floatval($item['point'])) {
                            $level = $item['member_level_id'];
                        }
                    }
                    $this->customer->edit(['member_level_id' => $level], $customer['customer_id']);
                }

                return [
                    'error' => false,
                    'point' => $point,
                    'message' => 'Success',
                ];
            } else {
                return [
                    'error' => false,
                    'point' => 0,
                    'message' => 'Rule code error or not active',
                ];
            }
        } else {
            return [
                'error' => false,
                'point' => 0,
                'message' => 'Not active',
            ];
        }
    }

    /**
     * Cộng điểm khi thanh toán chưa đủ tiền
     *
     * @param $input
     * @return array|mixed
     */
    public function plusPointReceiptAction($input)
    {
        try {
            $active = $this->config->getItem(2);

            if ($active['value'] == 1) {
                $ruleCode = 'payment_ratio';
                $pointRewardRule = $this->pointRewardRule->getItemByCode($ruleCode);

                if ($pointRewardRule != null) {
                    //Lấy thông tin thanh toán
                    $mReceipt = new ReceiptTable();
                    $info = $mReceipt->getInfo($input['receipt_id']);

                    if ($info['customer_id'] != 1) {
                        //Lấy thông tin khách hàng
                        $customer = $this->customer->getItem($info['customer_id']);
                        //Tính điểm cộng vào
                        $point = ($info['amount_paid']) * $pointRewardRule['point_value'];
                        //Cập nhật điểm của khách hàng.
                        $pointCustomer = floatval($point) + $customer['point'];
                        $pointBalance = floatval($point) + $customer['point_balance'];
                        //////////
                        $dataUpdate = [
                            'point' => $pointCustomer,
                            'point_balance' => $pointBalance,
                        ];

                        $this->customer->edit($dataUpdate, $customer['customer_id']);
                        //Lưu log ZNS
                        FunctionSendNotify::dispatch([
                            'type' => SEND_ZNS_CUSTOMER,
                            'key' => 'bonus_points',
                            'customer_id' => $customer['customer_id'],
                            'object_id' => floatval($point),
                            'tenant_id' => session()->get('idTenant')
                        ]);
                        //Lưu history
                        $dataHistory = [
                            'customer_id' => $info['customer_id'],
                            'order_id' => $info['order_id'],
                            'point' => floatval($point),
                            'type' => 'plus',
                            'point_description' => 'plus',
                            'object_id' => $input['receipt_id'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        $pointHistoryId = $this->pointHistory->add($dataHistory);
                        $dataHistoryDetail = [
                            'point_history_id' => $pointHistoryId,
                            'point_reward_rule_id' => $pointRewardRule['point_reward_rule_id'],
                            'created_at' => date('Y-m-d H:i:s'),
                        ];
                        $this->pointHistoryDetail->add($dataHistoryDetail);

                        $reset = $this->config->getItem(1);
                        $memberLevel = $this->memberLevel->getOptionMemberLevel();
                        $level = null;
                        if ($reset['value'] == 0) {
                            //Lấy thông tin khách hàng sau khi đã + điểm
                            $customer = $this->customer->getItem($info['customer_id']);

                            foreach ($memberLevel as $item) {
                                if (floatval($customer['point']) >= floatval($item['point'])) {
                                    $level = $item['member_level_id'];
                                }
                            }
                            $this->customer->edit(['member_level_id' => $level], $customer['customer_id']);
                        }
                        return [
                            'point' => $point
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => 'Cộng điểm thất bại'
            ];
        }
    }

    /**
     * Cộng điểm khi thanh toán đủ tiền
     *
     * @param $input
     * @return array|mixed
     */
    public function plusPointReceiptFullAction($input)
    {
        try {
            DB::beginTransaction();
            //Kiểm tra cấu hình cộng điểm
            $active = $this->config->getItem(2);
            if ($active['value'] == 1) {
                $point = 0;
                $arrDetail = [];
                //Lấy thông tin thanh toán
                $mReceipt = new ReceiptTable();
                $info = $mReceipt->getInfo($input['receipt_id']);

                if ($info['customer_id'] != 1) {
                    //Lấy thông tin khách hàng
                    $customer = $this->customer->getItem($info['customer_id']);
                    //Cộng điểm thanh toán
                    $rulePayment = $this->pointRewardRule->getItemByCode('payment_ratio');
                    if ($rulePayment != null) {
                        $point = ($info['amount_paid']) * $rulePayment['point_value'];
                        $arrDetail[] = [
                            'point_reward_rule_id' => $rulePayment['point_reward_rule_id']
                        ];
                    }
                    //Cộng điểm tỉ lệ rank
                    $ruleRank = $this->pointRewardRule->getItemByCode($customer['member_code']);
                    if ($ruleRank != null) {
                        if ($ruleRank['point_maths'] == '+') {
                            $point = $point + intval($ruleRank['point_value']);
                        } else if ($ruleRank['point_maths'] == '*') {
                            $point = $point * intval($ruleRank['point_value']);
                        }
                        $arrDetail[] = [
                            'point_reward_rule_id' => $ruleRank['point_reward_rule_id']
                        ];
                    }
                    //Cộng điểm tỉ lệ sp, dv, tdv
                    $arrRuleCode = ['service_special', 'product_special', 'service_card_special'];
                    foreach ($arrRuleCode as $v) {
                        $rulePoint = $this->pointRewardRule->getItemByCode($v);
                        if ($rulePoint != null) {
                            $type = preg_replace('/(.+)' . '_special' . '/', '$1', $v);
                            $listDetail = $this->orderDetail->getDetailByType($info['order_id'], $type);
                            if (count($listDetail) > 0) {
                                foreach ($listDetail as $v1) {
                                    //Kiểm tra sp, dv, tdv đặc biệt có thì cộng điểm
                                    if (in_array($v1['object_id'], explode(',', $rulePoint['hagtag_id']))) {
                                        if ($rulePoint['point_maths'] == '+') {
                                            $point = $point + intval($rulePoint['point_value']);
                                        } else if ($rulePoint['point_maths'] == '*') {
                                            $point = $point * intval($rulePoint['point_value']);
                                        }
                                        $arrDetail[] = [
                                            'point_reward_rule_id' => $rulePoint['point_reward_rule_id']
                                        ];
                                    }
                                }
                            }
                        }
                    }
                    if ($point > 0) {
                        //Cập nhật điểm của khách hàng.
                        $pointCustomer = floatval($point) + $customer['point'];
                        $pointBalance = floatval($point) + $customer['point_balance'];
                        //////////
                        $dataUpdate = [
                            'point' => $pointCustomer,
                            'point_balance' => $pointBalance,
                        ];
                        $this->customer->edit($dataUpdate, $customer['customer_id']);
                        //Lưu history
                        $dataHistory = [
                            'customer_id' => $info['customer_id'],
                            'order_id' => $info['order_id'],
                            'point' => floatval($point),
                            'type' => 'plus',
                            'point_description' => 'plus',
                            'object_id' => $input['receipt_id'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        $pointHistoryId = $this->pointHistory->add($dataHistory);
                        //Lưu history detail
                        foreach (array_unique($arrDetail, SORT_REGULAR) as $v) {
                            $dataHistoryDetail = [
                                'point_history_id' => $pointHistoryId,
                                'point_reward_rule_id' => $v['point_reward_rule_id'],
                                'created_at' => date('Y-m-d H:i:s'),
                            ];
                            $this->pointHistoryDetail->add($dataHistoryDetail);
                        }
                        $reset = $this->config->getItem(1);
                        $memberLevel = $this->memberLevel->getOptionMemberLevel();
                        $level = null;

                        if ($reset['value'] == 0) {
                            //Lấy thông tin khách hàng sau khi đã + điểm
                            $customer = $this->customer->getItem($info['customer_id']);

                            foreach ($memberLevel as $item) {
                                if (floatval($customer['point']) >= floatval($item['point'])) {
                                    $level = $item['member_level_id'];
                                }
                            }
                            $this->customer->edit(['member_level_id' => $level], $customer['customer_id']);
                        }

                        DB::commit();

                        //Lưu log ZNS
                        FunctionSendNotify::dispatch([
                            'type' => SEND_ZNS_CUSTOMER,
                            'key' => 'bonus_points',
                            'customer_id' => $customer['customer_id'],
                            'object_id' => floatval($point),
                            'tenant_id' => session()->get('idTenant')
                        ]);

                        return [
                            'point' => $point
                        ];
                    }
                }
            }
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => 'Cộng điểm thất bại'
            ];
        }
    }
}
