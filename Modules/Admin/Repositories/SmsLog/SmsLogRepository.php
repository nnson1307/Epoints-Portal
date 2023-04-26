<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 2/19/2019
 * Time: 3:40 PM
 */

namespace Modules\Admin\Repositories\SmsLog;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\CustomerAppointmentDetailTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\SmsConfigTable;
use Modules\Admin\Models\SmsLogTable;
use Modules\Admin\Models\SmsProviderTable;
use Modules\Admin\Repositories\Customer\CustomerRepository;
use Modules\Admin\Repositories\CustomerAppointment\CustomerAppointmentRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\SendSms\SendSmsRepository;
use Modules\Admin\Repositories\ServiceCard\ServiceCardRepositoryInterface;

class SmsLogRepository implements SmsLogRepositoryInterface
{
    protected $smsLog;
    protected $smsConfig;
    protected $smsSettingBrandName;
    protected $customer;
    protected $customerAppointment;
    protected $order;
    protected $serviceCard;
    protected $sendSms;

    public function __construct(
        SmsLogTable $smsLog,
        SmsConfigTable $smsConfig,
        SmsProviderTable $smsSettingBrandName,
        CustomerRepository $customer,
        CustomerAppointmentRepositoryInterface $customerAppointment,
        OrderRepositoryInterface $order,
        ServiceCardRepositoryInterface $serviceCard,
        SendSmsRepository $sendSms
    )
    {
        $this->smsLog = $smsLog;
        $this->smsConfig = $smsConfig;
        $this->smsSettingBrandName = $smsSettingBrandName;
        $this->customer = $customer;
        $this->customerAppointment = $customerAppointment;
        $this->order = $order;
        $this->serviceCard = $serviceCard;
        $this->sendSms = $sendSms;
    }

    /**
     * add customer Group
     */
    public function add(array $data)
    {
        return $this->smsLog->add($data);
    }

    public function getLogCampaign($id)
    {
        return $this->smsLog->getLogCampaign($id);
    }

    public function remove($id)
    {
        return $this->smsLog->remove($id);
    }

    //Cancel sms log
    public function cancelLog($type)
    {
        return $this->smsLog->cancelLog($type);
    }

    public function saveSmsLog($type, array $fields)
    {
        if (!empty($fields['phone'])) {
            $brandName = $this->smsSettingBrandName->getItem(1)->value;

            $data = [
                'brandname' => $brandName,
                'phone' => $fields['phone'],
                'customer_name' => $fields['customer_name'],
                'message' => $fields['message'],
                'sms_type' => $fields['sms_type'],
                'created_at' => $fields['created_at'],
                'updated_at' => $fields['updated_at'],
                'time_sent' => $fields['time_sent'],
                'created_by' => $fields['created_by'],
                'sms_status' => 'new',
                'object_id' => $fields['object_id'],
                'object_type' => $fields['object_type'],
            ];
            $idLog = $this->smsLog->add($data);
            //        if ($type == "new_customer" || $type == 'new_appointment' || $type == 'cancel_appointment' || $type == 'paysuccess' || $type == 'service_card_over_number_used') {
//            $this->sendSms->sendOneSms($idLog);
//        }
        }
    }

    public function birthday(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('birthday');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}'],
                [$parameter['name'] . ' ', $parameter['full_name'] . ' ', $gender . ' '], $content);

            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'birthday',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => date('Y-m-d') . ' ' . $smsConfig->time_sent,
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'customer',
            ];

            $mSmsLog = new SmsLogTable();
            //Check đã insert vào bảng log  chưa
            $checkLog = $mSmsLog->checkLogExist("birthday", "customer", $parameter['object_id']);

            if ($checkLog == null) {
                $this->saveSmsLog('birthday', $data);
            }
        }
    }

    public function newAppointment(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('new_appointment');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }

            $message = str_replace(
                ['{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{DATETIME_APPOINTMENT}', '{CODE_APPOINTMENT}', '{NAME_SPA}'],
                [$parameter['name'] . ' ', $parameter['full_name'] . ' ', $gender . ' ', $parameter['datetime_appointment'] . ' ', $parameter['code_appointment'] . ' ', 'PIOSPA' . ' '], $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'new_appointment',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => null,
                'created_by' => Auth::id() ? Auth::id() : 0,
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'customer_appointment',
            ];

            $this->saveSmsLog('new_appointment', $data);
        }
    }

    public function cancelAppointment(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('cancel_appointment');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }

            $message = str_replace(
                [
                    '{CUSTOMER_NAME}',
                    '{CUSTOMER_FULL_NAME}',
                    '{CUSTOMER_GENDER}',
                    '{CODE_APPOINTMENT}',
                    '{NAME_SPA}',
                    '{PRODUCT_NAME}',
                    '{DATETIME}'
                ],
                [
                    $parameter['name'] . ' ',
                    $parameter['full_name'] . ' ',
                    $gender . ' ',
                    $parameter['code_appointment'] . ' ',
                    'PIOSPA' . ' ',
                    $parameter['product_name'],
                    $parameter['datetime'] . ' '
                ],
                $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'cancel_appointment',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => null,
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'customer_appointment',
            ];
            $this->saveSmsLog('cancel_appointment', $data);
        }
    }

    public function remindAppointment(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('remind_appointment');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(
                [
                    '{CUSTOMER_NAME}',
                    '{CUSTOMER_FULL_NAME}',
                    '{CUSTOMER_GENDER}',
                    '{DATETIME}',
                    '{NAME_SPA}',
                    '{PRODUCT_NAME}'
                ],
                [
                    $parameter['name'] . ' ',
                    $parameter['full_name'] . ' ',
                    $gender . ' ',
                    $parameter['datetime'] . ' ',
                    'PIOSPA',
                    $parameter['product_name']
                ], $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'remind_appointment',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => $parameter['day'] . ' ' . date('H:i', strtotime('-' . ($smsConfig->value / 60) . ' hour', strtotime($parameter['time']))),
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'customer_appointment',
            ];
            $mSmsLog = new SmsLogTable();
            //Check đã insert vào bảng log  chưa
            $checkLog = $mSmsLog->checkLogExist("remind_appointment", "customer_appointment", $parameter['object_id']);

            if ($checkLog == null) {
                $this->saveSmsLog('remind_appointment', $data);
            }

        }
    }

    public function paysuccess(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('paysuccess');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace([
                '{CUSTOMER_NAME}',
                '{CUSTOMER_FULL_NAME}',
                '{CUSTOMER_GENDER}',
                '{NAME_SPA}',
                '{PRODUCT_NAME}'
            ],
                [
                    $parameter['name'] . ' ',
                    $parameter['full_name'] . ' ',
                    $gender . ' ',
                    'PIOSPA',
                    $parameter['product_name']
                ], $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'paysuccess',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => null,
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'order',
            ];
            $this->saveSmsLog('paysuccess', $data);
        }
    }

    public function newCustomer(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('new_customer');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{NAME_SPA}'],
                [$parameter['name'] . ' ', $parameter['full_name'] . ' ', $gender . ' ', 'PIOSPA'], $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'new_customer',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => null,
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'customer',
            ];
            $this->saveSmsLog('new_customer', $data);
        }
    }

    public function serviceCardNearlyExpired(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('service_card_nearly_expired');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{CODE_CARD}', '{DATETIME}'],
                [$parameter['name'] . ' ', $parameter['full_name'] . ' ', $gender . ' ', $parameter['card_code'] . ' ', $parameter['datetime'] . ' '], $content);

            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'service_card_nearly_expired',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => date('Y-m-d') . ' 08:00:00',
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'service_card',
            ];

            $this->saveSmsLog('service_card_nearly_expired', $data);
        }
    }

    public function serviceCardOverNumberUsed(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('service_card_over_number_used');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{CODE_CARD}'],
                [$parameter['name'] . ' ', $parameter['full_name'] . ' ', $gender . ' ', $parameter['card_code'] . ' '], $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'service_card_over_number_used',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => null,
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'service_card',
            ];

            $this->saveSmsLog('service_card_over_number_used', $data);
        }
    }

    /**
     * Lưu sms log khi đặt hàng thành công
     *
     * @param array $parameter
     */
    public function orderSuccess(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('order_success');
        $brandName = $this->smsSettingBrandName->getItem(1)->value;

        if ($smsConfig != null && $smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(
                [
                    '{CUSTOMER_GENDER}',
                    '{CUSTOMER_FULL_NAME}',
                    '{ORDER_CODE}',
                    '{PRODUCT_NAME}',
                    '{DATETIME}'
                ],
                [
                    $gender,
                    $parameter['full_name'],
                    $parameter['object_code'],
                    $parameter['product_name'],
                    Carbon::createFromFormat('Y-m-d H:i:s', $parameter['created_at'])->format('d/m/Y H:i')
                ], $content);

            $dataSmsLog = [
                'brandname' => $brandName,
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'order_success',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => Auth()->id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'order',
            ];
            $idLog = $this->smsLog->add($dataSmsLog);
        }
    }

    //Thẻ dịch vụ hết hạn
    public function serviceCardExpires(array $parameter)
    {
        $smsConfig = $this->smsConfig->getItemByType('service_card_expires');
        if ($smsConfig->is_active == 1) {
            $content = $smsConfig->content;
            //Build nội dung.
            $gender = __('Anh');
            if ($parameter['gender'] == 'female') {
                $gender = __('Chị');
            } elseif ($parameter['gender'] == 'other') {
                $gender = __('Anh/Chị');
            }
            $message = str_replace(['{CUSTOMER_NAME}', '{CUSTOMER_FULL_NAME}', '{CUSTOMER_GENDER}', '{CODE_CARD}'],
                [$parameter['name'] . ' ', $parameter['full_name'] . ' ', $gender . ' ', $parameter['card_code']], $content);
            $data = [
                'phone' => $parameter['phone'],
                'customer_name' => $parameter['full_name'],
                'message' => $message,
                'sms_type' => 'service_card_expires',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => '',
                'time_sent' => date('Y-m-d') . ' 08:00:00',
                'created_by' => Auth::id(),
                'sms_status' => 'new',
                'object_id' => $parameter['object_id'],
                'object_type' => 'service_card',
            ];
            $this->saveSmsLog('service_card_expires', $data);
        }
    }

    public function getList($type, $id = null)
    {
        switch ($type) {
            case 'birthday':
                $listData = $this->customer->getBirthdays();

                foreach ($listData as $item) {
                    $phone = '';
                    if ($item['phone1'] != null) {
                        $phone = $item['phone1'];
                    } else {
                        $phone = $item['phone2'];
                    }
                    $parameter = [
                        'phone' => $phone,
                        'full_name' => $item['full_name'],
                        'name' => substr($item['full_name'], strrpos($item['full_name'], ' ') + 1),
                        'birthday' => Carbon::createFromFormat('Y-m-d H:i:s', $item['birthday'])->format('Y-m-d'),
                        'gender' => $item['gender'],
                        'object_id' => $item['customer_id']
                    ];
                    $this->birthday($parameter);
                }
                break;
            case 'new_appointment':
                $listData = $this->customerAppointment->getItemDetail($id);
                foreach ($listData as $item) {
                    $phone = '';
                    if ($item['phone1'] != null) {
                        $phone = $item['phone1'];
                    } else {
                        $phone = $item['phone2'];
                    }
                    $parameter = [
                        'phone' => $phone,
                        'full_name' => $item['full_name_cus'],
                        'name' => substr($item['full_name_cus'], strrpos($item['full_name_cus'], ' ') + 1),
//                        'time_sent' => Carbon::createFromFormat('Y-m-d H:i:s', $item['created_at'])->format('Y-m-d H:i:s'),
                        'time_sent' => null,
                        'gender' => $item['gender'],
                        'datetime_appointment' => $item['time'] . ' ' . Carbon::createFromFormat('Y-m-d', $item['date_appointment'])->format('d/m/Y'),
                        'code_appointment' => $item['customer_appointment_code'],
                        'object_id' => $id
                    ];

                    $this->newAppointment($parameter);
                }
                break;
            case 'cancel_appointment':
                //Lấy thông tin lịch hẹn
                $listData = $this->customerAppointment->getItemDetail($id);

                $mAppointmentDetail = new CustomerAppointmentDetailTable();

                foreach ($listData as $item) {
                    //Lấy chi tiết lịch hẹn
                    $getDetail = $mAppointmentDetail->getDetail($item['customer_appointment_id']);

                    $productName = '';
                    if (count($getDetail) > 0) {
                        foreach ($getDetail as $k => $v) {
                            if (in_array($v['object_type'], ['service', 'member_card'])) {
                                $comma = $k + 1 < count($getDetail) ? ';' : '';
                                $productName .= $v['object_name'] . $comma;
                            }
                        }
                    }

                    if (strlen($productName) > 50) {
                        $productName = substr($productName, 0, 47) . '...';
                    }

                    $phone = '';
                    if ($item['phone1'] != null) {
                        $phone = $item['phone1'];
                    } else {
                        $phone = $item['phone2'];
                    }
                    $parameter = [
                        'phone' => $phone,
                        'full_name' => $item['full_name_cus'],
                        'name' => substr($item['full_name_cus'], strrpos($item['full_name_cus'], ' ') + 1),
                        'time_sent' => date('Y-m-d H:i:s'),
                        'gender' => $item['gender'],
                        'code_appointment' => $item['customer_appointment_code'],
                        'object_id' => $id,
                        'product_name' => $productName,
                        'day' => $item['date'],
                        'datetime' => Carbon::createFromFormat('Y-m-d', $item['date'])->format('d/m/Y') . ' ' . $item['time'],
                        'time' => $item['time'],
                    ];
                    $this->cancelAppointment($parameter);
                }
                break;
            case 'remind_appointment':
                $listData = $this->customerAppointment->getCustomerAppointmentTodays();

                $mAppointmentDetail = new CustomerAppointmentDetailTable();
                foreach ($listData as $item) {
                    //Lấy chi tiết lịch hẹn
                    $getDetail = $mAppointmentDetail->getDetail($item['customer_appointment_id']);

                    $productName = '';
                    if (count($getDetail) > 0) {
                        foreach ($getDetail as $k => $v) {
                            if (in_array($v['object_type'], ['service', 'member_card'])) {
                                $comma = $k + 1 < count($getDetail) ? ';' : '';
                                $productName .= $v['object_name'] . $comma;
                            }
                        }
                    }

                    if (strlen($productName) > 50) {
                        $productName = substr($productName, 0, 47) . '...';
                    }

                    $phone = '';
                    if ($item['phone1'] != null) {
                        $phone = $item['phone1'];
                    } else {
                        $phone = $item['phone2'];
                    }
                    $parameter = [
                        'phone' => $phone,
                        'full_name' => $item['full_name_cus'],
                        'name' => substr($item['full_name_cus'], strrpos($item['full_name_cus'], ' ') + 1),
                        'gender' => $item['gender'],
                        'code_appointment' => $item['customer_appointment_code'],
                        'day' => $item['date'],
                        'datetime' => Carbon::createFromFormat('Y-m-d', $item['date'])->format('d/m/Y') . ' ' . $item['time'],
                        'time' => $item['time'],
                        'object_id' => $item['customer_appointment_id'],
                        'product_name' => $productName
                    ];
                    $this->remindAppointment($parameter);
                }
                break;
            case 'paysuccess':
                $listData = $this->order->getItemDetail($id);

                $mOrderDetail = new OrderDetailTable();
                //Lấy chi tiết đơn hàng
                $getDetail = $mOrderDetail->getItem($listData['order_id']);
                $productName = '';
                if (count($getDetail) > 0) {
                    foreach ($getDetail as $k => $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                            $comma = $k + 1 < count($getDetail) ? ';' : '';
                            $productName .= $v['object_name'] . $comma;
                        }
                    }
                }

                if (strlen($productName) > 50) {
                    $productName = substr($productName, 0, 47) . '...';
                }

                $parameter = [
                    'phone' => $listData['phone'],
                    'full_name' => $listData['full_name'],
                    'name' => substr($listData['full_name'], strrpos($listData['full_name'], ' ') + 1),
                    'gender' => $listData['gender'],
                    'object_id' => $id,
                    'product_name' => $productName
                ];

                $this->paysuccess($parameter);
                break;
            case 'new_customer':

                $listData = $this->customer->getItem($id);

                $parameter = [
                    'phone' => $listData['phone1'],
                    'full_name' => $listData['full_name'],
                    'name' => substr($listData['full_name'], strrpos($listData['full_name'], ' ') + 1),
                    'gender' => $listData['gender'],
                    'object_id' => $id
                ];
                $this->newCustomer($parameter);
                break;
            case 'service_card_nearly_expired':
                $smsConfig = $this->smsConfig->getItemByType('service_card_nearly_expired');
                $cenvertedTime = date('Y-m-d', strtotime('+' . $smsConfig->value . ' day', strtotime(date('Y-m-d'))));

                $listData = $this->serviceCard->serviceCardNearlyExpireds($cenvertedTime);
                foreach ($listData as $item) {
                    $parameter = [
                        'phone' => $item['phone1'],
                        'full_name' => $item['full_name'],
                        'name' => substr($item['full_name'], strrpos($item['full_name'], ' ') + 1),
                        'gender' => $item['gender'],
                        'card_code' => $item['card_code'],
                        'datetime' => Carbon::createFromFormat('Y-m-d H:i:s', $item['datetime'])->format('d/m/Y'),
                        'object_id' => $item['customer_service_card_id']
                    ];
                    $this->serviceCardNearlyExpired($parameter);
                }


                break;
            case 'service_card_over_number_used':
                $listData = $this->serviceCard->serviceCardOverNumberUseds($id);
                if ($listData != null) {
                    $parameter = [
                        'phone' => $listData['phone'],
                        'full_name' => $listData['full_name'],
                        'name' => substr($listData['full_name'], strrpos($listData['full_name'], ' ') + 1),
                        'gender' => $listData['gender'],
                        'card_code' => $listData['card_code'],
                        'object_id' => $id
                    ];
                    $this->serviceCardOverNumberUsed($parameter);
                }
                break;
            case 'service_card_expires':
                $listData = $this->serviceCard->serviceCardExpireds();
                foreach ($listData as $item) {
                    $parameter = [
                        'phone' => $item['phone'],
                        'full_name' => $item['full_name'],
                        'name' => substr($item['full_name'], strrpos($item['full_name'], ' ') + 1),
                        'gender' => $item['gender'],
                        'card_code' => $item['card_code'],
                        'object_id' => $item['customer_service_card_id']
                    ];
                    $this->serviceCardExpires($parameter);
                }
                break;
            case 'order_success':
                //Lấy thông tin đơn hàng
                $listData = $this->order->getItemDetail($id);

                $mOrderDetail = new OrderDetailTable();
                //Lấy chi tiết đơn hàng
                $getDetail = $mOrderDetail->getItem($id);
                $productName = '';
                if (count($getDetail) > 0) {
                    foreach ($getDetail as $k => $v) {
                        if (in_array($v['object_type'], ['product', 'service', 'service_card'])) {
                            $comma = $k + 1 < count($getDetail) ? ';' : '';
                            $productName .= $v['object_name'] . $comma;
                        }
                    }
                }

                if (strlen($productName) > 50) {
                    $productName = substr($productName, 0, 47) . '...';
                }

                $parameter = [
                    'phone' => $listData['phone'],
                    'full_name' => $listData['full_name'],
                    'name' => substr($listData['full_name'], strrpos($listData['full_name'], ' ') + 1),
                    'gender' => $listData['gender'],
                    'object_id' => $id,
                    'object_code' => $listData['order_code'],
                    'product_name' => $productName,
                    'created_at' => $listData['created_at']
                ];
                $this->orderSuccess($parameter);
                break;
        }
    }

    public function getAll()
    {
        return $this->smsLog->getAll();
    }

    public function getAllLogNew($timeSent)
    {
        return $this->smsLog->getAllLogNew($timeSent);
    }

    public function getAllLogNewNoTimeSend($timeSent)
    {
        return $this->smsLog->getAllLogNewNoTimeSend($timeSent);
    }

    public function edit(array $data, $id)
    {
        return $this->smsLog->edit($data, $id);
    }

    public function getItem($id)
    {
        return $this->smsLog->getItem($id);
    }

    public function getLogDetailCampaign($id, array $filter = [])
    {
        return $this->smsLog->getLogDetailCampaign($id, $filter);
    }

    public function cancelLogCampaign($id)
    {
        return $this->smsLog->cancelLogCampaign($id);
    }

    public function getSmsSend($timeSend)
    {
        return $this->smsLog->getSmsSend($timeSend);
    }
}