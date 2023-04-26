<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 04/01/2022
 * Time: 14:02
 */

namespace Modules\Payment\Repositories\ReceiptOnline;


use App\Jobs\CheckMailJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderApp\OrderAppRepo;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;
use Modules\Admin\Repositories\SmsLog\SmsLogRepositoryInterface;
use Modules\Payment\Http\Api\BookingApi;
use Modules\Payment\Http\Api\SendNotificationApi;
use Modules\Payment\Models\CustomerServiceCardTable;
use Modules\Payment\Models\CustomerTable;
use Modules\Payment\Models\DeliveryHistoryLogTable;
use Modules\Payment\Models\DeliveryHistoryTable;
use Modules\Payment\Models\DeliveryTable;
use Modules\Payment\Models\OrderDetailTable;
use Modules\Payment\Models\OrderLogTable;
use Modules\Payment\Models\OrderTable;
use Modules\Payment\Models\ProductChildTable;
use Modules\Payment\Models\ProductTable;
use Modules\Payment\Models\PromotionDailyTimeTable;
use Modules\Payment\Models\PromotionDateTimeTable;
use Modules\Payment\Models\PromotionDetailTable;
use Modules\Payment\Models\PromotionLogTable;
use Modules\Payment\Models\PromotionMasterTable;
use Modules\Payment\Models\PromotionMonthlyTimeTable;
use Modules\Payment\Models\PromotionObjectApplyTable;
use Modules\Payment\Models\PromotionWeeklyTimeTable;
use Modules\Payment\Models\ReceiptDetailTable;
use Modules\Payment\Models\ReceiptOnlineTable;
use Modules\Payment\Models\ReceiptTable;
use Modules\Payment\Models\ServiceCardTable;
use Modules\Payment\Models\ServiceTable;
use Modules\Payment\Models\VoucherTable;
use Modules\Payment\Models\WarrantyCardTable;
use Modules\Payment\Models\WarrantyPackageDetailTable;
use Modules\Payment\Models\WarrantyPackageTable;

class ReceiptOnlineRepo implements ReceiptOnlineRepoInterface
{
    protected $receiptOnline;

    public function __construct(
        ReceiptOnlineTable $receiptOnline
    )
    {
        $this->receiptOnline = $receiptOnline;
    }

    const CANCEL = "cancel";
    const SUCCESS = "success";
    const PAY_HALF = "pay-half";
    const PAY_SUCCESS = "paysuccess";
    const LIVE = 1;

    /**
     * Danh sách giao dịch online
     *
     * @param array $filters
     * @return array|mixed
     */
    public function list(array $filters = [])
    {
        $list = $this->receiptOnline->getList($filters);

        return [
            "list" => $list,
        ];
    }

    /**
     * Huỷ thanh toán chuyển khoản
     *
     * @param $input
     * @return array|mixed
     */
    public function cancel($input)
    {
        try {
            //Cập nhật đợt giao thành sang trạng thái huỷ
            $this->receiptOnline->edit([
                'status' => self::CANCEL
            ], $input['receipt_online_id']);

            return [
                'error' => false,
                'message' => __('Huỷ thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Huỷ thất bại')
            ];
        }
    }

    /**
     * Thanh toán chuyển khoản thành công
     *
     * @param $input
     * @return mixed|void
     */
    public function success($input)
    {
        DB::beginTransaction();
        try {
            //Lấy thông tin giao dịch online
            $infoReceiptOnline = $this->receiptOnline->getInfo($input['receipt_online_id']);

            if ($infoReceiptOnline['object_type'] != 'order_online' && $infoReceiptOnline['status'] != 'inprocess') {
                return [
                    'error' => true,
                    'message' => __('Thanh toán thất bại')
                ];
            }

            //Cập nhật trạng thái giao dịch online
            $this->receiptOnline->edit([
                'status' => self::SUCCESS
            ], $input['receipt_online_id']);

            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            //Lấy thông tin đơn hàng
            $orderInfo = $mOrder->getInfo($infoReceiptOnline['object_id']);

            if ($orderInfo == null) {
                return [
                    'error' => true,
                    'message' => __('Đơn hàng không tồn tại')
                ];
            }

            if (in_array($orderInfo['process_status'], [self::PAY_HALF, self::PAY_SUCCESS])) {
                return [
                    'error' => true,
                    'message' => __('Đơn hàng đã được thanh toán')
                ];
            }

            //Lấy chi tiết đơn hàng
            $orderDetailInfo = $mOrderDetail->getDetail($orderInfo['order_id']);

            $statusOrder = self::PAY_HALF;

            if ($infoReceiptOnline['amount_paid'] >= $orderInfo['amount']) {
                $statusOrder = self::PAY_SUCCESS;
            }
            //Cập nhật trạng thái đơn hàng
            $mOrder->edit([
                'process_status' => $statusOrder
            ], $orderInfo['order_id']);

            $arrObjectBuy = [];
            $arrRemindUse = [];

            $mNoti = new SendNotificationApi();
            $mVoucher = app()->get(VoucherTable::class);

            if (count($orderDetailInfo) > 0) {
                $mOrderRepo = app()->get(OrderRepositoryInterface::class);
                $mSmsLog = app()->get(SmsLogRepositoryInterface::class);

                foreach ($orderDetailInfo as $v) {
                    if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                        if ($v['is_check_promotion'] == 1) {
                            $arrObjectBuy [] = [
                                'object_type' => $v['object_type'],
                                'object_code' => $v['object_code'],
                                'object_id' => $v['object_id'],
                                'price' => $v['price'],
                                'quantity' => $v['quantity'],
                                'customer_id' => $orderInfo['customer_id'],
                                'order_source' => self::LIVE,
                                'order_id' => $v['order_id'],
                                'order_code' => $v['order_code']
                            ];
                        }

                        $arrRemindUse [] = [
                            'object_type' => $v['object_type'],
                            'object_code' => $v['object_code'],
                            'object_id' => $v['object_id'],
                            'object_name' => $v['object_name']
                        ];

                        $info = null;

                        switch ($v['object_type']) {
                            case 'product':
                                $mProductChild = app()->get(ProductChildTable::class);
                                //Lấy thông tin sản phẩm
                                $info = $mProductChild->getInfo($v['object_id']);
                                $info['staff_commission_value'] = $info['staff_commission_value'] != null ? $info['staff_commission_value'] : 0;
                                break;
                            case 'service':
                                $mService = app()->get(ServiceTable::class);
                                //Lấy thông tin dịch vụ
                                $info = $mService->getInfo($v['object_id']);
                                $info['staff_commission_value'] = $info['staff_commission_value'] != null ? $info['staff_commission_value'] : 0;
                                break;
                            case 'service_card':
                                $mServiceCard = app()->get(ServiceCardTable::class);
                                //Lấy thông tin thẻ dịch vụ
                                $info = $mServiceCard->getInfo($v['object_code']);
                                $info['staff_commission_value'] = $info['staff_commission_value'] != null ? $info['staff_commission_value'] : 0;
                                break;
                        }

                        // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                        $mOrderRepo->calculatedCommission(
                            $v['quantity'],
                            $v['refer_id'],
                            $info,
                            $v['order_detail_id'],
                            $v['object_id'],
                            null,
                            $v['amount'],
                            $v['staff_id']
                        );
                    }

                    if ($v['object_type'] == 'member_card') {
                        $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
                        //Lấy thông tin thẻ liệu trình
                        $info = $mCustomerServiceCard->getInfo($v['object_id']);
                        //Update số lần sử dụng thẻ liệu trình
                        $mCustomerServiceCard->edit([
                            'count_using' => $info['count_using'] + $v['quantity']
                        ], $v['object_id']);

                        // TODO: Xử lý hoa hồng cho list nhân viên phục vụ
                        $mOrderRepo->calculatedCommission(
                            $v['quantity'],
                            $v['refer_id'],
                            $info,
                            $v['order_detail_id'],
                            $v['object_id'],
                            null,
                            $v['amount'],
                            $v['staff_id'],
                            0,
                            0,
                            "member_card"
                        );

                        DB::commit();

                        CheckMailJob::dispatch('is_event', 'service_card_over_number_used', $v['object_id']);
                        $mSmsLog->getList('service_card_over_number_used', $v['object_id']);
                        //Send notification
                        $mNoti->sendNotification([
                            'key' => 'service_card_over_number_used',
                            'customer_id' => $orderInfo['customer_id'],
                            'object_id' => $v['object_id']
                        ]);
                    }


                    if ($v['voucher_code'] != null) {
                        //Lấy thông tin voucher
                        $infoVoucher = $mVoucher->getInfoByCode($v['voucher_code']);
                        //Trừ số lần sử dụng voucher nếu có
                        $mVoucher->editVoucherOrder([
                            'total_use' => ($infoVoucher['total_use'] + 1)
                        ], $v['voucher_code']);
                    }
                }
            }

            //Trừ quota_user khi đơn hàng có promotion quà tặng
            $this->subtractQuotaUsePromotion($orderInfo['order_id']);

            $mPromotionLog = app()->get(PromotionLogTable::class);
            //Remove promotion log
            $mPromotionLog->removeByOrder($orderInfo['order_id']);
            //Lấy thông tin CTKM dc áp dụng cho đơn hàng
            $getPromotionLog = $this->groupQuantityObjectBuy($arrObjectBuy);
            //Insert promotion log
            $arrPromotionLog = $getPromotionLog['promotion_log'];
            $mPromotionLog->insert($arrPromotionLog);
            //Cộng quota_use promotion quà tặng
            $arrQuota = $getPromotionLog['promotion_quota'];
            $this->plusQuotaUsePromotion($arrQuota);

            if ($orderInfo['voucher_code'] != null) {
                //Lấy thông tin voucher
                $infoVoucher = $mVoucher->getInfoByCode($orderInfo['voucher_code']);
                //Trừ số lần sử dụng voucher nếu có
                $mVoucher->editVoucherOrder([
                    'total_use' => ($infoVoucher['total_use'] + 1)
                ], $orderInfo['voucher_code']);
            }

            $mReceipt = app()->get(ReceiptTable::class);
            $mReceiptDetail = app()->get(ReceiptDetailTable::class);

            //Tạo phiếu thu
            $receiptId = $mReceipt->add([
                'customer_id' => $orderInfo['customer_id'],
                'staff_id' => Auth()->id(),
                'object_id' => $orderInfo['order_id'],
                'object_type' => 'order',
                'order_id' => $orderInfo['order_id'],
                'total_money' => $infoReceiptOnline['amount_paid'],
                'voucher_code' => $orderInfo['voucher_code'],
                'status' => 'paid',
                'is_discount' => 1,
                'amount' => $infoReceiptOnline['amount_paid'],
                'amount_paid' => $infoReceiptOnline['amount_paid'],
                'receipt_type_code' => 'RTC_ORDER',
                'object_accounting_type_code' => '', // order code
                'object_accounting_id' => $orderInfo['order_id'], // order id
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);
            //Cập nhật mã phiếu thu
            $mReceipt->edit([
                'receipt_code' => 'TT_' . date('dmY') . $receiptId
            ], $receiptId);

            if (count($orderDetailInfo) > 0) {
                foreach ($orderDetailInfo as $v) {
                    if ($v['object_type'] == 'member_card') {
                        //Insert chi tiết thanh toán nếu là thẻ liệu trình
                        $mReceiptDetail->add([
                            'receipt_id' => $receiptId,
                            'cashier_id' => Auth()->id(),
                            'payment_method_code' => 'MEMBER_CARD',
                            'card_code' => $v['object_code'],
                            'amount' => 0,
                            'created_by' => Auth()->id(),
                            'updated_by' => Auth()->id()
                        ]);
                    }
                }
            }

            //Insert chi tiết thanh toán
            $mReceiptDetail->add([
                'receipt_id' => $receiptId,
                'cashier_id' => Auth()->id(),
                'amount' => $infoReceiptOnline['amount_paid'],
                'payment_method_code' => $infoReceiptOnline['payment_method_code'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            if ($orderInfo['order_source_id'] == 2) {
                //Cập nhật trạng thái đơn hàng cần giao (nếu là đơn hàng tại quầy thì không active)
                if ($orderInfo['receive_at_counter'] == 0) {
                    $mDelivery = new DeliveryTable();

                    $mDelivery->edit([
                        'is_actived' => 1
                    ], $orderInfo['order_id']);
                }

                $mOrderLog = app()->get(OrderLogTable::class);
                //Insert order log đơn hàng đã xác nhận
                $checkConfirm = $mOrderLog->checkStatusLog($orderInfo['order_id'], 'confirmed');
                if ($checkConfirm == null) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $orderInfo['order_id'],
                            'created_type' => 'backend',
                            'status' => 'confirmed',
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đã xác nhận đơn hàng',
                            'note_en' => 'Order confirm'
                        ]
                    ]);
                }
                //Insert order log đơn hàng đang xử lý
                $checkPacking = $mOrderLog->checkStatusLog($orderInfo['order_id'], 'packing');

                if ($checkPacking == null) {
                    $mOrderLog->insert([
                        [
                            'order_id' => $orderInfo['order_id'],
                            'created_type' => 'backend',
                            'status' => 'packing',
                            'created_by' => Auth()->id(),
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'note_vi' => 'Đang xử lý',
                            'note_en' => 'Processing'
                        ]
                    ]);
                }
            }

            //Xóa tất cả phiếu giao hàng  của đơn hàng
            $this->removeDeliveryHistory($orderInfo['order_id']);

            $mCustomer = app()->get(CustomerTable::class);
            // Thêm phiếu bảo hành điện tử
            $customer = $mCustomer->getItem($orderInfo['customer_id']);
            $this->addWarrantyCard($customer['customer_code'], $orderInfo['order_id'], $orderInfo['order_code'], $orderDetailInfo, null);


            $mOrder = app()->get(OrderRepositoryInterface::class);
            //Lưu log dự kiến nhắc sử dụng lại
            $mOrder->insertRemindUse($orderInfo['order_id'], $orderInfo['customer_id'], $arrRemindUse);

            DB::commit();

            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();

            if ($infoReceiptOnline['amount_paid'] >= $orderInfo['amount']) {
                $mBookingApi->plusPointReceiptFull(['receipt_id' => $receiptId]);
            } else {
                $mBookingApi->plusPointReceipt(['receipt_id' => $receiptId]);
            }

            return [
                'error' => false,
                'message' => __('Thanh toán thành công')
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'error' => true,
                'message' => __('Thanh toán thất bại'),
                '_message' => $e->getMessage() . $e->getLine()
            ];
        }
    }

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
                    $promotionLog [] = $vLog;
                }

                if (count($getLog['promotion_quota']) > 0) {
                    $arrQuota [] = $getLog['promotion_quota'];
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
                if ($v['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $v['branch_apply']))) {
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
                    $getCustomer = $mCustomer->getInfoById($customerId);

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
                    $promotionPrice [] = $v;
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

                            $promotionQuota [] = $v;
                        }
                    }

                }

            }
        }

        if (count($promotionPrice) > 0) {
            //Lấy CTKM có giá ưu đãi nhất
            $getPriceMostPreferential = $this->choosePriceMostPreferential($promotionPrice);

            $promotionLog [] = $getPriceMostPreferential;
        }

        if (count($promotionQuota) > 0) {
            //Lấy CTKM có quà tặng ưu đãi nhất
            $getGiftMostPreferential = $this->getGiftMostPreferential($promotionQuota);
            $promotionLog [] = $getGiftMostPreferential;
        }

        foreach ($promotionLog as $v) {
            $result [] = [
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
    private function getPriceObject($objectType, $objectCode)
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
                $mServiceCard = app()->get(ServiceCardTable::class);
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
    private function choosePriceMostPreferential($arrPrice)
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
    private function getGiftMostPreferential($arrGift)
    {
        $result = [];

        if (count($arrGift) == 1) {
            //Có 1 CTKM quà tặng thì lấy chính nó
            $result [] = [
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
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftPreferential($arrGift)
    {
        $result = [];
        //Lấy giá trị quà tặng có giá trị cao nhất
        $giftPrice = array_column($arrGift, 'total_price_gift');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($giftPrice, SORT_DESC, $arrGift);

        $result [] = [
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
                $result [] = [
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
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftMinBuy($arrGift)
    {
        //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
        $result = [];
        //Lấy quà tặng có số lượng mua thấp nhất
        $quantityBuy = array_column($arrGift, 'quantity_buy');
        //Sắp xếp lại array có số lượng cần mua thấp nhất
        array_multisort($quantityBuy, SORT_ASC, $arrGift);

        $result [] = [
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
                $result [] = [
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
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited [] = $v;
            } else {
                $arrUnLimited [] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result [] = [
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
                    $result [] = [
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

            $result [] = [
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
                    $result [] = [
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

    /**
     * Cancel tất cả phiếu giao hàng của đơn hàng
     *
     * @param $orderId
     */
    public function removeDeliveryHistory($orderId)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        $mDeliveryHistoryLog = new DeliveryHistoryLogTable();

        $mOrder = app()->get(OrderTable::class);
        //Kiểm tra trạng thái đơn hàng
        $infoOrder = $mOrder->getInfo($orderId);

        if ($infoOrder['process_status']) {
            //Kiểm tra đơn hàng đó có phiếu giao hàng chưa
            $getDeliveryHistory = $mDeliveryHistory->getHistoryByOrder($orderId);
            if (count($getDeliveryHistory) > 0) {
                foreach ($getDeliveryHistory as $item) {
                    //Xóa phiếu giao hàng
                    $mDeliveryHistory->edit([
                        'status' => 'cancel'
                    ], $item['delivery_history_id']);
                    //Lưu log xóa phiếu giao hàng
                    $mDeliveryHistoryLog->add([
                        "delivery_history_id" => $item['delivery_history_id'],
                        "status" => "cancel",
                        "created_by" => Auth()->id(),
                        "created_type" => "backend"
                    ]);
                }
            }
        }
    }

    /**
     * Thêm phiếu bảo hành điện tử
     *
     * @param $customerCode
     * @param $orderId
     * @param $orderCode
     * @param $dataTableAdd
     * @param $dataTableEdit
     */
    protected function addWarrantyCard($customerCode, $orderId, $orderCode, $dataTableAdd, $dataTableEdit = null)
    {
        $mWarrantyDetail = new WarrantyPackageDetailTable();
        $mWarranty = new WarrantyPackageTable();
        $mWarrantyCard = new WarrantyCardTable();

        // get array object
        if (count($dataTableAdd) > 0) {
            foreach ($dataTableAdd as $item) {
                // value item
                $objectId = isset($item['object_id']) ? $item['object_id'] : 0;
                $objectType = isset($item['object_type']) ? $item['object_type'] : null;
                $objectCode = isset($item['object_code']) ? $item['object_code'] : null;
                $objectPrice = isset($item['price']) ? $item['price'] : 0;
                $objectQuantity = isset($item['quantity']) ? $item['quantity'] : 1;

                if ($objectType == 'product' || $objectType == 'service' || $objectType == 'service_card') {
                    // get object code -> get packed_code -> get info warranty package
                    $warrantyDetail = $mWarrantyDetail->getDetailByObjectCode($objectCode, $objectType);
                    if ($warrantyDetail != null) {
                        $warranty = $mWarranty->getInfoByCode($warrantyDetail['warranty_packed_code']);
                        $dataInsert = [
                            'customer_code' => $customerCode,
                            'warranty_packed_code' => $warrantyDetail['warranty_packed_code'],
                            'quota' => $warranty['quota'],
                            'warranty_percent' => $warranty['percent'],
                            'warranty_value' => $warranty['required_price'],
                            'status' => 'new',
                            'object_type' => $objectType,
                            'object_type_id' => $objectId,
                            'object_code' => $objectCode,
                            'object_price' => $objectPrice,
                            'created_by' => Auth()->id(),
                            'order_code' => $orderCode,
                            'description' => $warranty['detail_description']
                        ];
                        if ($objectQuantity > 1) {
                            for ($i = 0; $i < $objectQuantity; $i++) {
                                $warrantyCardId = $mWarrantyCard->add($dataInsert);
                                // card code
                                $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                                $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                            }
                        } else {
                            $warrantyCardId = $mWarrantyCard->add($dataInsert);
                            // card code
                            $warrantyCardCode = 'WRC_' . date('dmY') . sprintf("%02d", $warrantyCardId);
                            $mWarrantyCard->edit(['warranty_card_code' => $warrantyCardCode], $warrantyCardId);
                        }
                    }
                }
            }
        }
    }
}