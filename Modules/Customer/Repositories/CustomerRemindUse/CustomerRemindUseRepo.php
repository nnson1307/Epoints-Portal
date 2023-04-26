<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 14/06/2021
 * Time: 14:47
 */

namespace Modules\Customer\Repositories\CustomerRemindUse;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\ConfigNotifyTable;
use Modules\Customer\Models\CustomerRemindCareTable;
use Modules\Customer\Models\CustomerRemindUseTable;
use Modules\Customer\Models\EmailLogTable;
use Modules\Customer\Models\NotifyQueueTable;
use Modules\Customer\Models\SmsLogTable;

class CustomerRemindUseRepo implements CustomerRemindUseRepoInterface
{
    protected $remindUse;

    public function __construct(
        CustomerRemindUseTable $remindUse
    )
    {
        $this->remindUse = $remindUse;
    }

    /**
     * Danh sách dự kiến nhắc sử dụng
     *
     * @param array $filters
     * @return mixed|void
     */
    public function list(array $filters = [])
    {
        $list = $this->remindUse->getList($filters);

        return [
            "list" => $list,
        ];
    }

    /**
     * Dữ liệu view chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param $remindId
     * @return mixed|void
     */
    public function dataViewEdit($remindId)
    {
        $isSentNotify = 0;

        //Lấy thông tin dự kiến nhắc
        $info = $this->remindUse->getInfo($remindId);

        $mRemindCare = app()->get(CustomerRemindCareTable::class);
        //Lấy thông tin chăm sóc
        $getCare = $mRemindCare->getCare($remindId)->toArray();

        $dataCare = [];

        if (count($getCare) > 0) {
            foreach ($getCare as $v) {
                if ($v['sms_status'] == 'sent' || $v['email_status'] == 'sent' || $v['notify_is_send'] == 1) {
                    $isSentNotify = 1;
                }

                if ($v['type'] == "care") {
                    $dataCare [] = [
                        "type" => $v['type'],
                        "type_name" => $v['type_name'],
                        "content" => $v['content'],
                        "date" => Carbon::parse($v['created_at'])->format('d/m/Y'),
                        "time" => Carbon::parse($v['created_at'])->format('H:i'),
                        "staff_name" => $v['staff_name']
                    ];
                } else {
                    switch ($v['type']) {
                        case 'email':
                            if ($v['email_status'] == 'sent') {
                                $dataCare [] = [
                                    "type" => $v['type'],
                                    "type_name" => $v['type_name'],
                                    "content" => $v['content'],
                                    "date" => Carbon::parse($v['email_sent_at'])->format('d/m/Y'),
                                    "time" => Carbon::parse($v['email_sent_at'])->format('H:i'),
                                    "staff_name" => $v['staff_name']
                                ];
                            }
                            break;
                        case 'sms':
                            if ($v['sms_status'] == 'sent') {
                                $dataCare [] = [
                                    "type" => $v['type'],
                                    "type_name" => $v['type_name'],
                                    "content" => $v['content'],
                                    "date" => Carbon::parse($v['sms_sent_at'])->format('d/m/Y'),
                                    "time" => Carbon::parse($v['sms_sent_at'])->format('H:i'),
                                    "staff_name" => $v['staff_name']
                                ];
                            }
                            break;
                        case 'notify':
                            if ($v['notify_is_send'] == 1) {
                                $dataCare [] = [
                                    "type" => $v['type'],
                                    "type_name" => $v['type_name'],
                                    "content" => $v['content'],
                                    "date" => Carbon::parse($v['notify_sent_at'])->format('d/m/Y'),
                                    "time" => Carbon::parse($v['notify_sent_at'])->format('H:i'),
                                    "staff_name" => $v['staff_name']
                                ];
                            }
                            break;

                    }
                }
            }
        }

        return [
            'item' => $info,
            'isSentNotify' => $isSentNotify,
            'dataCare' => collect($dataCare)->groupBy('date')
        ];
    }

    /**
     * Chỉnh sửa dự kiến nhắc sử dụng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $date = Carbon::createFromFormat('d/m/Y', $input['sent_date'])->format('Y-m-d');
            $time = $input['sent_time'];

            $dataEdit = [
                'sent_at' => $date . ' ' . $time,
                'note' => $input['note'],
                'is_finish' => $input['is_finish']
            ];
            //Chỉnh sửa dự kiến nhắc lịch
            $this->remindUse->edit($dataEdit, $input['customer_remind_use_id']);
            //Kiểm tra đã gửi thông báo chưa (chưa thì update lại ngày giờ gửi của log email, sms, notify)
            if ($input['is_sent_notify'] == 0) {
                $mRemindUseCare = app()->get(CustomerRemindCareTable::class);
                //Lấy thông tin chăm sóc
                $getCare = $mRemindUseCare->getCare($input['customer_remind_use_id']);

                if (count($getCare) > 0) {
                    foreach ($getCare as $v) {
                        switch ($v['type']) {
                            case 'email':
                                $mEmailLog = app()->get(EmailLogTable::class);
                                //Cập nhật ngày gửi email_log
                                $mEmailLog->edit([
                                    "time_sent" => $date . ' ' . $time
                                ], $v['type_id']);
                                break;
                            case 'sms':
                                $mSmsLog = app()->get(SmsLogTable::class);
                                //Cập nhật ngày gửi sms_log
                                $mSmsLog->edit([
                                    "time_sent" => $date . ' ' . $time
                                ], $v['type_id']);
                                break;
                            case 'notify':
                                $mConfigNotify = app()->get(ConfigNotifyTable::class);
                                //Lấy cấu hinh thông báo
                                $config = $mConfigNotify->getInfo('is_remind_use');

                                if ($config['is_active'] == 1) {
                                    //Tính thời gian gửi
                                    $timeSent = Carbon::createFromFormat("Y-m-d H:i", $date . ' ' . $time)->format('Y-m-d H:i');

                                    if ($config['send_type'] == "immediately") {
                                        //Gủi ngay thì ngày giờ gửi = giờ gửi của info
                                        $timeSent = Carbon::createFromFormat("Y-m-d H:i", $date . ' ' . $time)->format('Y-m-d H:i');
                                    } else if ($config['send_type'] == "in_time") {
                                        //Trong khoảng thời gian (lấy ngày gửi nối với thời gian cấu hình)
                                        $time = Carbon::createFromFormat("Y-m-d H:i", $date . ' ' . $time)->format('Y-m-d');
                                        $timeSent = $time . ' '. $config['value'];
                                    } else if ($config['send_type'] == "before") {
                                        //Lấy số phút để trừ (gửi trước) - cộng (gửi sau)
                                        $minute = $this->getMinute($config['schedule_unit'], $config['value']);
                                        //Gửi trước
                                        $timeSent = Carbon::createFromFormat("Y-m-d H:i", $date . ' ' . $time)->subMinutes($minute)->format('Y-m-d H:i');
                                    } else if ($config['send_type'] == "after") {
                                        //Lấy số phút để trừ (gửi trước) - cộng (gửi sau)
                                        $minute = $this->getMinute($config['schedule_unit'], $config['value']);
                                        //Gửi sau
                                        $timeSent = Carbon::createFromFormat("Y-m-d H:i", $date . ' ' . $time)->addMinutes($minute)->format('Y-m-d H:i');
                                    }

                                    $mNotifyQueue = app()->get(NotifyQueueTable::class);
                                    //Cập nhật ngày gửi notify_log
                                    $mNotifyQueue->edit([
                                        "send_at" => $timeSent
                                    ], $v['type_id']);
                                }

                                break;

                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Chăm sóc khách hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function submitCare($input)
    {
        try {
            $mRemindCare = app()->get(CustomerRemindCareTable::class);
            //Lưu thông tin chăm sóc
            $input['type'] = 'care';
            $input['created_by'] = Auth()->id();
            $input['updated_by'] = Auth()->id();

            $mRemindCare->add($input);

            return response()->json([
                'error' => false,
                'message' => __('Chăm sóc khách hàng thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chăm sóc khách hàng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    const HOUR = 'hour';
    const MINUTE = 'minute';
    const DAY = 'day';

    /**
     * Lấy số phút
     *
     * @param $type
     * @param $value
     * @return float|int
     */
    private function getMinute($type, $value)
    {
        switch ($type) {
            case self::DAY:
                return $value * 1440;
            case self::HOUR:
                return $value * 60;
            case self::MINUTE:
                return $value * 1;
            default:
                return $value * 1;
        }
    }
}