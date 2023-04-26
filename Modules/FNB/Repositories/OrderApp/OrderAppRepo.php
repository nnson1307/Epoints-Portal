<?php
namespace Modules\FNB\Repositories\OrderApp;

use http\Exception;
use Illuminate\Support\Carbon;
//use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceTable;
use Modules\FNB\Models\CustomerTable;
use Modules\FNB\Models\ProductChildTable;
use Modules\FNB\Models\PromotionDailyTimeTable;
use Modules\FNB\Models\PromotionDateTimeTable;
use Modules\FNB\Models\PromotionDetailTable;
use Modules\FNB\Models\PromotionLogTable;
use Modules\FNB\Models\PromotionMasterTable;
use Modules\FNB\Models\PromotionMonthlyTimeTable;
use Modules\FNB\Models\PromotionObjectApplyTable;
use Modules\FNB\Models\PromotionWeeklyTimeTable;

class OrderAppRepo implements OrderAppRepoInterface
{
    /**
     * Trừ quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $orderId
     * @return mixed|void
     */
    public function subtractQuotaUsePromotion($orderId)
    {
        $mPromotionLog = app()->get(PromotionLogTable::class);
        $mPromotionMaster = app()->get(PromotionMasterTable::class);

        $getQuotaPromotion = $mPromotionLog->getQuotaPromotion($orderId);

        if (count($getQuotaPromotion) > 0) {
            foreach ($getQuotaPromotion as $v) {
                $infoMaster = $mPromotionMaster->getInfo($v['promotion_code']);

                //Cập nhật quota_use của promotion
                $mPromotionMaster->edit([
                    'quota_use' => $infoMaster['quota_use'] - $v['quantity_gift']
                ], $v['promotion_code']);
            }
        }
    }

    /**
     * Group số lượng mua của các object, lấy ra CTKM áp dụng cho đơn hàng
     *
     * @param $arrObjectBuy
     * @return mixed|void
     */
    public function groupQuantityObjectBuy($arrObjectBuy)
    {
        $promotionLog = [];
        $arrQuota = [];

        $arrBuy = [];

        //Group số lượng mua của những sp trùng nhau
        if (count($arrObjectBuy) > 0) {
            foreach ($arrObjectBuy as $v) {
                $objectCode = $v['object_code'];
                if (!array_key_exists($objectCode, $arrBuy)) {
                    $arrBuy[$objectCode] = $v;
                } else {
                    $arrBuy[$objectCode]['quantity'] = $arrBuy[$objectCode]['quantity'] + $v['quantity'];
                }
            }
        }


        if (count($arrBuy) > 0) {
            foreach ($arrBuy as $v) {
                //Lấy thông tin CTKM áp dụng cho đơn hàng
                $getLog = $this->getPromotionLog(
                    $v['object_type'],
                    $v['object_code'],
                    $v['price'],
                    $v['quantity'],
                    $v['customer_id'],
                    $v['order_source'],
                    $v['object_id'],
                    $v['order_id'],
                    $v['order_code']
                );

                foreach ($getLog['promotion_log'] as $vLog) {
                    $promotionLog[] = $vLog;
                }

                if (count($getLog['promotion_quota']) > 0) {
                    $arrQuota[] = $getLog['promotion_quota'];
                }
            }
        }

        return [
            'promotion_log' => $promotionLog,
            'promotion_quota' => $arrQuota
        ];
    }

    /**
     * Lấy thông tin CTKM khi mua hàng
     *
     * @param $objectType
     * @param $objectCode
     * @param $price
     * @param $quantity
     * @param $customerId
     * @param $orderSource
     * @param $objectId
     * @param $orderId
     * @param $orderCode
     * @return array|null
     */
    public function getPromotionLog($objectType, $objectCode, $price, $quantity, $customerId, $orderSource, $objectId, $orderId, $orderCode)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $currentTime = Carbon::now()->format('H:i');

        //Lấy chi tiết CTKM
        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, null, $currentDate);

        $promotionLog = [];
        $promotionQuota = [];
        $promotionPrice = [];
        $result = [];
        $resultPlusQuota = [];

        if (count($getDetail) > 0) {
            foreach ($getDetail as $v) {
                //Check thời gian diễn ra chương trình
                if ($currentDate < $v['start_date'] || $currentDate > $v['end_date']) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check chi nhánh áp dụng
                if (
                    $v['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $v['branch_apply']))
                ) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check KM theo time đặc biệt
                if ($v['is_time_campaign'] == 1) {
                    switch ($v['time_type']) {
                        case 'D':
                            $daily = $mDaily->getDailyByPromotion($v['promotion_code']);

                            if ($daily != null) {
                                $startTime = Carbon::createFromFormat('H:i:s', $daily['start_time'])->format('H:i');
                                $endTime = Carbon::createFromFormat('H:i:s', $daily['end_time'])->format('H:i');
                                //Kiểm tra giờ bắt đầu, giờ kết thúc
                                if ($currentTime < $startTime || $currentTime > $endTime) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                        case 'W':
                            $weekly = $mWeekly->getWeeklyByPromotion($v['promotion_code']);
                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['default_start_time'])->format('H:i');
                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['default_end_time'])->format('H:i');

                            switch (Carbon::createFromFormat('Y-m-d H:i:s', $currentDate)->format('l')) {
                                case 'Monday':
                                    if ($weekly['is_monday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Tuesday':
                                    if ($weekly['is_tuesday'] == 1) {
                                        if ($weekly['is_other_tuesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Wednesday':
                                    if ($weekly['is_wednesday'] == 1) {
                                        if ($weekly['is_other_wednesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Thursday':
                                    if ($weekly['is_thursday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Friday':
                                    if ($weekly['is_friday'] == 1) {
                                        if ($weekly['is_other_friday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Saturday':
                                    if ($weekly['is_saturday'] == 1) {
                                        if ($weekly['is_other_saturday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Sunday':
                                    if ($weekly['is_sunday'] == 1) {
                                        if ($weekly['is_other_sunday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                            }
                            //Kiểm tra giờ bắt đầu, giờ kết thúc
                            if ($currentTime < $startTime || $currentTime > $endTime) {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'M':
                            $monthly = $mMonthly->getMonthlyByPromotion($v['promotion_code']);

                            if (count($monthly) > 0) {
                                $next = false;

                                foreach ($monthly as $v1) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['start_time'])->format('Y-m-d H:i');
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $v1['run_date'] . ' ' . $v1['end_time'])->format('Y-m-d H:i');

                                    if ($currentDate > $startDate && $currentDate < $endDate) {
                                        $next = true;
                                    }
                                }

                                if ($next == false) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            } else {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'R':
                            $fromTo = $mFromTo->getDateTimeByPromotion($v['promotion_code']);

                            if ($fromTo != null) {
                                $startFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['form_date'] . ' ' . $fromTo['start_time'])->format('Y-m-d H:i');
                                $endFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['to_date'] . ' ' . $fromTo['end_time'])->format('Y-m-d H:i');

                                if ($currentDate < $startFrom || $currentDate > $endFrom) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                    }
                }

                //Check nguồn đơn hàng
                if ($v['order_source'] != 'all' && $v['order_source'] != $orderSource) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check đối tượng áp dụng
                if ($v['promotion_apply_to'] != 1 && $v['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc vòng for
                        continue;
                    }

                    if ($getCustomer['member_level_id'] == null) {
                        $getCustomer['member_level_id'] = 1;
                    }

                    $objectId = '';
                    if ($v['promotion_apply_to'] == 2) {
                        $objectId = $getCustomer['member_level_id'];
                    } else if ($v['promotion_apply_to'] == 3) {
                        $objectId = $getCustomer['customer_group_id'];
                    } else if ($v['promotion_apply_to'] == 4) {
                        $objectId = $v['customer_id'];
                    }

                    $getApply = $mPromotionApply->getApplyByObjectId($v['promotion_code'], $objectId);

                    if ($getApply == null) {
                        //Kết thúc vòng for
                        continue;
                    }
                }

                if ($v['promotion_type'] == 1) {
                    //Khuyến mãi giảm giá
                    $promotionPrice[] = $v;
                } else if ($v['promotion_type'] == 2) {
                    if ($quantity >= $v['quantity_buy']) {
                        $multiplication = intval($quantity / $v['quantity_buy']);
                        //Số quà được tặng
                        $totalGift = intval($v['quantity_gift'] * $multiplication);
                        //Lấy quota_use nếu tính áp dụng promotion này
                        $quotaUse = floatval($v['quota_use']) + $totalGift;
                        //Check số lượng cần mua để dc quà + quota_use
                        if ($v['quota'] == 0 || $v['quota'] == '' || $quotaUse <= floatval($v['quota'])) {
                            //Lấy giá trị quà tặng
                            $priceGift = $this->getPriceObject($v['gift_object_type'], $v['gift_object_code']);

                            $v['quantity_gift'] = $totalGift;
                            $v['quota'] = !empty($v['quota']) ? $v['quota'] : 0;
                            $v['quota_use'] = floatval($v['quota_use']);
                            $v['total_price_gift'] = $priceGift * $totalGift;

                            $promotionQuota[] = $v;
                        }
                    }
                }
            }
        }

        if (count($promotionPrice) > 0) {
            //Lấy CTKM có giá ưu đãi nhất
            $getPriceMostPreferential = $this->choosePriceMostPreferential($promotionPrice);

            $promotionLog[] = $getPriceMostPreferential;
        }

        if (count($promotionQuota) > 0) {
            //Lấy CTKM có quà tặng ưu đãi nhất
            $getGiftMostPreferential = $this->getGiftMostPreferential($promotionQuota);
            $promotionLog[] = $getGiftMostPreferential;
        }

        foreach ($promotionLog as $v) {
            $result[] = [
                'promotion_id' => $v['promotion_id'],
                'promotion_code' => $v['promotion_code'],
                'start_date' => $v['start_date'],
                'end_date' => $v['end_date'],
                'order_id' => $orderId,
                'order_code' => $orderCode,
                'object_type' => $objectType,
                'object_id' => $objectId,
                'object_code' => $objectCode,
                'quantity' => $quantity,
                'base_price' => $v['base_price'],
                'promotion_price' => $v['promotion_price'],
                'gift_object_type' => $v['gift_object_type'],
                'gift_object_id' => $v['gift_object_id'],
                'gift_object_code' => $v['gift_object_code'],
                'quantity_gift' => $v['quantity_gift'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            if ($v['promotion_type'] == 2) {
                $resultPlusQuota = [
                    'promotion_code' => $v['promotion_code'],
                    'quantity_gift' => $v['quantity_gift']
                ];
            }
        }

        return [
            'promotion_log' => $result,
            'promotion_quota' => $resultPlusQuota
        ];
    }

    /**
     * Lấy giá trị khuyến mãi sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return int
     */
    public function getPriceObject($objectType, $objectCode)
    {
        $price = 0;

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(ProductChildTable::class);
                //Lấy thông tin sp khuyến mãi
                $getProduct = $mProduct->getProductPromotion($objectCode);
                $price = $getProduct['new_price'];

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dv khuyến mãi
                $getService = $mService->getServicePromotion($objectCode);
                $price = $getService['new_price'];

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCard::class);
                //Lấy thông tin thẻ dv khuyến mãi
                $getServiceCard = $mServiceCard->getServiceCardPromotion($objectCode);
                $price = $getServiceCard['new_price'];

                break;
        }

        return floatval($price);
    }

    /**
     * Chọn CTKM giảm giá ưu đãi nhất
     *
     * @param $arrPrice
     * @return array
     */
    public function choosePriceMostPreferential($arrPrice)
    {
        //Lấy giá trị quà tặng có giá trị cao nhất
        $minPrice = array_column($arrPrice, 'promotion_price');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($minPrice, SORT_ASC, $arrPrice);
        //Lấy CTKM có giá ưu đãi nhất
        return [
            'promotion_id' => $arrPrice[0]['promotion_id'],
            'promotion_code' => $arrPrice[0]['promotion_code'],
            'promotion_type' => $arrPrice[0]['promotion_type'],
            'start_date' => $arrPrice[0]['start_date'],
            'end_date' => $arrPrice[0]['end_date'],
            'base_price' => $arrPrice[0]['base_price'],
            'promotion_price' => $arrPrice[0]['promotion_price'],
            'gift_object_type' => $arrPrice[0]['gift_object_type'],
            'gift_object_id' => $arrPrice[0]['gift_object_id'],
            'gift_object_code' => $arrPrice[0]['gift_object_code'],
            'quantity_gift' => $arrPrice[0]['quantity_gift'],
        ];
    }

    /**
     * Lấy quà tặng ưu đãi nhất
     *
     * @param $arrGift
     * @return array
     */
    public function getGiftMostPreferential($arrGift)
    {
        try {
            $result = [];

            if (count($arrGift) == 1) {
                //Có 1 CTKM quà tặng thì lấy chính nó
                $result[] = [
                    'promotion_type' => $arrGift[0]['promotion_type'],
                    'promotion_id' => $arrGift[0]['promotion_id'],
                    'promotion_code' => $arrGift[0]['promotion_code'],
                    'start_date' => $arrGift[0]['start_date'],
                    'end_date' => $arrGift[0]['end_date'],
                    'base_price' => $arrGift[0]['base_price'],
                    'promotion_price' => $arrGift[0]['promotion_price'],
                    'gift_object_type' => $arrGift[0]['gift_object_type'],
                    'gift_object_id' => $arrGift[0]['gift_object_id'],
                    'gift_object_code' => $arrGift[0]['gift_object_code'],
                    'quantity_gift' => $arrGift[0]['quantity_gift'],
                ];
            } else if (count($arrGift) > 1) {
                //Có nhiều CTKM quà tặng
                //Lấy quà tặng có giá trị cao nhất
                $giftPreferential = $this->chooseGiftPreferential($arrGift);

                $result = $giftPreferential;

                if (count($result) > 1) {
                    //Lấy quà tặng có số lượng mua thấp nhất
                    $giftMinBuy = $this->chooseGiftMinBuy($result);

                    $result = $giftMinBuy;
                }

                if (count($result) > 1) {
                    //Lấy quà tặng có quota - quota_use còn nhiều nhất (ưu tiên quota != 0 ko giới hạn)
                    $giftQuota = $this->chooseGiftQuota($result);

                    $result = $giftQuota;
                }
            }

            return $result[0];
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    public function chooseGiftPreferential($arrGift)
    {
        try {
            $result = [];
            //Lấy giá trị quà tặng có giá trị cao nhất
            $giftPrice = array_column($arrGift, 'total_price_gift');
            //Sắp xếp lại array có quà tặng giá trị cao nhất
            array_multisort($giftPrice, SORT_DESC, $arrGift);

            $result[] = [
                'promotion_id' => $arrGift[0]['promotion_id'],
                'promotion_code' => $arrGift[0]['promotion_code'],
                'promotion_type' => $arrGift[0]['promotion_type'],
                'start_date' => $arrGift[0]['start_date'],
                'end_date' => $arrGift[0]['end_date'],
                'base_price' => $arrGift[0]['base_price'],
                'promotion_price' => $arrGift[0]['promotion_price'],
                'gift_object_type' => $arrGift[0]['gift_object_type'],
                'gift_object_id' => $arrGift[0]['gift_object_id'],
                'gift_object_code' => $arrGift[0]['gift_object_code'],
                'quantity_gift' => $arrGift[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrGift[0]['quantity_buy'],
                'quota' => $arrGift[0]['quota'],
                'quota_use' => $arrGift[0]['quota_use'],
                'total_price_gift' => $arrGift[0]['total_price_gift']
            ];

            unset($arrGift[0]);

            foreach ($arrGift as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['total_price_gift'] >= $result[0]['total_price_gift']) {
                    $result[] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }

            return $result;
        }catch (Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    public function chooseGiftMinBuy($arrGift)
    {
        try {
            //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
            $result = [];
            //Lấy quà tặng có số lượng mua thấp nhất
            $quantityBuy = array_column($arrGift, 'quantity_buy');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityBuy, SORT_ASC, $arrGift);

            $result[] = [
                'promotion_id' => $arrGift[0]['promotion_id'],
                'promotion_code' => $arrGift[0]['promotion_code'],
                'promotion_type' => $arrGift[0]['promotion_type'],
                'start_date' => $arrGift[0]['start_date'],
                'end_date' => $arrGift[0]['end_date'],
                'base_price' => $arrGift[0]['base_price'],
                'promotion_price' => $arrGift[0]['promotion_price'],
                'gift_object_type' => $arrGift[0]['gift_object_type'],
                'gift_object_id' => $arrGift[0]['gift_object_id'],
                'gift_object_code' => $arrGift[0]['gift_object_code'],
                'quantity_gift' => $arrGift[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrGift[0]['quantity_buy'],
                'quota' => $arrGift[0]['quota'],
                'quota_use' => $arrGift[0]['quota_use'],
                'total_price_gift' => $arrGift[0]['total_price_gift']
            ];

            unset($arrGift[0]);

            foreach ($arrGift as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quantity_buy'] == $result[0]['quantity_buy']) {
                    $result[] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }

            return $result;
        }catch (Exception $e){
            dd($e->getMessage(),123);
        }
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    public function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited[] = $v;
            } else {
                $arrUnLimited[] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result[] = [
                'promotion_id' => $arrLimited[0]['promotion_id'],
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'promotion_type' => $arrLimited[0]['promotion_type'],
                'start_date' => $arrLimited[0]['start_date'],
                'end_date' => $arrLimited[0]['end_date'],
                'base_price' => $arrLimited[0]['base_price'],
                'promotion_price' => $arrLimited[0]['promotion_price'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_code' => $arrLimited[0]['gift_object_code'],
                'quantity_gift' => $arrLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrLimited[0]['quantity_buy'],
                'quota' => $arrLimited[0]['quota'],
                'quota_use' => $arrLimited[0]['quota_use'],
                'total_price_gift' => $arrLimited[0]['total_price_gift']
            ];

            unset($arrLimited[0]);

            foreach ($arrLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_balance'] == ($result[0]['quota'] - $result[0]['quota_use'])) {
                    $result[] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        if (count($result) == 0 && count($arrUnLimited) > 0) {
            //Lấy quà tặng có quota_use thấp nhất
            $quantityQuotaUse = array_column($arrUnLimited, 'quota_use');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuotaUse, SORT_ASC, $arrUnLimited);

            $result[] = [
                'promotion_id' => $arrUnLimited[0]['promotion_id'],
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'promotion_type' => $arrUnLimited[0]['promotion_type'],
                'start_date' => $arrUnLimited[0]['start_date'],
                'end_date' => $arrUnLimited[0]['end_date'],
                'base_price' => $arrUnLimited[0]['base_price'],
                'promotion_price' => $arrUnLimited[0]['promotion_price'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_code' => $arrUnLimited[0]['gift_object_code'],
                'quantity_gift' => $arrUnLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrUnLimited[0]['quantity_buy'],
                'quota' => $arrUnLimited[0]['quota'],
                'quota_use' => $arrUnLimited[0]['quota_use'],
                'total_price_gift' => $arrUnLimited[0]['total_price_gift']
            ];

            unset($arrUnLimited[0]);

            foreach ($arrUnLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_use'] <= $result[0]['quota_use']) {
                    $result[] = [
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        //        if (count($result) > 1) {
        //            $result = $result[0];
        //        }

        return $result;
    }

    /**
     * Cộng quota_use khi mua hàng có promotion là quà tặng
     *
     * @param $arrPromotionSubtract
     */
    public function plusQuotaUsePromotion($arrPromotionSubtract)
    {
        $mPromotionMaster = app()->get(PromotionMasterTable::class);

        if (count($arrPromotionSubtract) > 0) {
            foreach ($arrPromotionSubtract as $v) {

                $infoMaster = $mPromotionMaster->getInfo($v['promotion_code']);

                //Cập nhật quota_use của promotion
                $mPromotionMaster->edit([
                    'quota_use' => $infoMaster['quota_use'] + $v['quantity_gift']
                ], $v['promotion_code']);
            }
        }
    }
}