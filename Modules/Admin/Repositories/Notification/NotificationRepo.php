<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 2:45 PM
 */

namespace Modules\Admin\Repositories\Notification;


use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\CustomerAppointmentTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\NotificationAutoConfigTable;
use Modules\Admin\Models\NotificationDetailTable;
use Modules\Admin\Models\NotificationLogTable;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\ServiceCard;
use Modules\Admin\Models\ServiceTable;

class NotificationRepo implements NotificationRepoInterface
{
    /**
     * Insert notification log
     *
     * @param $key
     * @param $objectId
     * @return mixed|null
     */
    public function insertLogNotification($key, $objectId)
    {
        $mNotiConfig = new NotificationAutoConfigTable();
        $mNofiDetail = new NotificationDetailTable();
        $mNotiLog = new NotificationLogTable();

        switch ($key) {
            case 'cancel_appointment':
            case 'new_appointment':
                $config = $mNotiConfig->getInfo($key);
                $mAppointment = new CustomerAppointmentTable();
                if ($config != null) {
                    //Thông tin lịch hẹn
                    $appointment = $mAppointment->getItemEdit($objectId);
                    //Thêm notification detail
                    $idDetail = $mNofiDetail->add([
                        'notification_auto_group' => $config['notification_auto_group_id'],
                        'background' => '',
                        'content' => $config['content'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);
                    //Build nội dung.
                    $content = $config['content'];
                    $gender = __('Anh');
                    if ($appointment['gender'] == 'female') {
                        $gender = __('Chị');
                    } elseif ($appointment['gender'] == 'other') {
                        $gender = __('Anh/Chị');
                    }
                    $message = str_replace(
                        [
                            '{CUSTOMER_NAME}',
                            '{CUSTOMER_FULL_NAME}',
                            '{CUSTOMER_GENDER}',
                            '{DATETIME_APPOINTMENT}',
                            '{CODE_APPOINTMENT}',
                            '{NAME_SPA}'
                        ],
                        [
                            substr($appointment['full_name_cus'], strrpos($appointment['full_name_cus'], ' ') + 1),
                            $appointment['full_name_cus'],
                            $gender,
                            $appointment['date_appointment'],
                            $appointment['customer_appointment_code'],
                            'PIOSPA'
                        ], $content);
                    //Insert notification log
                    $mNotiLog->add([
                        'notification_detail_id' => $idDetail,
                        'user_id' => $appointment['customer_id'],
                        'notification_title' => $config['name'],
                        'notification_message' => $message,
                    ]);
                }
                break;
            case 'new_customer':
                $config = $mNotiConfig->getInfo($key);
                $mCustomer = new CustomerTable();
                if ($config != null) {
                    //Thông tin khách hàng
                    $info = $mCustomer->getItem($objectId);
                    //Thêm notification detail
                    $idDetail = $mNofiDetail->add([
                        'notification_auto_group' => $config['notification_auto_group_id'],
                        'background' => '',
                        'content' => $config['content'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);
                    //Build nội dung.
                    $content = $config['content'];
                    $gender = __('Anh');
                    if ($info['gender'] == 'female') {
                        $gender = __('Chị');
                    } elseif ($info['gender'] == 'other') {
                        $gender = __('Anh/Chị');
                    }
                    $message = str_replace(
                        [
                            '{CUSTOMER_NAME}',
                            '{CUSTOMER_FULL_NAME}',
                            '{CUSTOMER_GENDER}',
                            '{NAME_SPA}'
                        ],
                        [
                            substr($info['full_name'], strrpos($info['full_name'], ' ') + 1),
                            $info['full_name'],
                            $gender,
                            'PIOSPA'
                        ], $content);
                    //Insert notification log
                    $mNotiLog->add([
                        'notification_detail_id' => $idDetail,
                        'user_id' => $info['customer_id'],
                        'notification_title' => $config['name'],
                        'notification_message' => $message,
                    ]);
                }
                break;
            case 'paysuccess':
            case 'new_order':
                $config = $mNotiConfig->getInfo($key);
                $mOrder = new OrderTable();
                if ($config != null) {
                    //Thông tin đơn hàng
                    $info = $mOrder->getItemDetail($objectId);
                    //Thêm notification detail
                    $idDetail = $mNofiDetail->add([
                        'notification_auto_group' => $config['notification_auto_group_id'],
                        'background' => '',
                        'content' => $config['content'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);
                    //Build nội dung.
                    $content = $config['content'];
                    $gender = __('Anh');
                    if ($info['gender'] == 'female') {
                        $gender = __('Chị');
                    } elseif ($info['gender'] == 'other') {
                        $gender = __('Anh/Chị');
                    }
                    $message = str_replace(
                        [
                            '{CUSTOMER_NAME}',
                            '{CUSTOMER_FULL_NAME}',
                            '{CUSTOMER_GENDER}',
                            '{NAME_SPA}'
                        ],
                        [
                            substr($info['full_name'], strrpos($info['full_name'], ' ') + 1),
                            $info['full_name'],
                            $gender,
                            'PIOSPA'
                        ], $content);
                    //Insert notification log
                    $mNotiLog->add([
                        'notification_detail_id' => $idDetail,
                        'user_id' => $info['customer_id'],
                        'notification_title' => $config['name'],
                        'notification_message' => $message,
                    ]);
                }
                break;
            case 'service_card_over_number_used':
                $config = $mNotiConfig->getInfo($key);
                $mServiceCard = new ServiceCard();
                if ($config != null) {
                    //Thông tin thẻ dịch vụ
                    $info = $mServiceCard->serviceCardOverNumberUseds($objectId);
                    //Thêm notification detail
                    $idDetail = $mNofiDetail->add([
                        'notification_auto_group' => $config['notification_auto_group_id'],
                        'background' => '',
                        'content' => $config['content'],
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ]);
                    //Build nội dung.
                    $content = $config['content'];
                    $gender = __('Anh');
                    if ($info['gender'] == 'female') {
                        $gender = __('Chị');
                    } elseif ($info['gender'] == 'other') {
                        $gender = __('Anh/Chị');
                    }
                    $message = str_replace(
                        [
                            '{CUSTOMER_NAME}',
                            '{CUSTOMER_FULL_NAME}',
                            '{CUSTOMER_GENDER}',
                            '{CODE_CARD}'
                        ],
                        [
                            substr($info['full_name'], strrpos($info['full_name'], ' ') + 1),
                            $info['full_name'],
                            $gender,
                            $info['card_code']
                        ], $content);
                    //Insert notification log
                    $mNotiLog->add([
                        'notification_detail_id' => $idDetail,
                        'user_id' => $info['customer_id'],
                        'notification_title' => $config['name'],
                        'notification_message' => $message,
                    ]);
                }
                break;
            default:
                return null;
        }
    }
}