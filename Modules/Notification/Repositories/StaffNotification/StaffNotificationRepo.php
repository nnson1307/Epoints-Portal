<?php

namespace Modules\Notification\Repositories\StaffNotification;

use Carbon\Carbon;
use Modules\Notification\Models\OrderTable;
use Modules\Notification\Models\StaffNotificationTable;

class StaffNotificationRepo implements StaffNotificationRepoInterface
{
    protected $staffNotification;
    public function __construct(StaffNotificationTable $staffNotification)
    {
        $this->staffNotification = $staffNotification;
    }
    const IS_NEW = 0;
    const IS_OLD = 1;
    const IS_READ = 1;
    const NOT_READ = 0;
    /**
     * Lấy tất cả notify
     *
     * @param $filter
     * @return array|mixed
     */
    public function getAllNotification($filter)
    {
        $getAllNotification = $this->staffNotification->getList($filter)->items();

        // Tính thời gian
        foreach ($getAllNotification as $key => $item) {

//            if (in_array($item['action'],['my_work','manage_work_detail'])) {
//                $getAllNotification[$key]['notification_message'] = $item['notification_title'];
//            }

            if ($item['created_at'] != null) {
                $diff = Carbon::parse($item['created_at'])->diffForHumans();
                $item['time_ago'] = $diff;
            } else {
                $item['time_ago'] = '';
            }
        }
        return [
            'getAllNotification' => $getAllNotification
        ];
    }

    /**
     * Lấy thông báo new để push
     *
     * @return array
     */
    public function getNotificationNew()
    {
        $getNotificationNew = $this->staffNotification->getNotificationNew();
        return [
            'getNotificationNew' => $getNotificationNew
        ];
    }

    /**
     * Cập nhật trạng thái đã đọc
     *
     * @param $input
     * @return array|mixed
     */
    public function updateStatus($input)
    {
        try {
            $staffNotificationId = $input['staff_notification_id'];
            if (isset($staffNotificationId) && $staffNotificationId != null) {
                $data = [
                    'is_read' => self::IS_READ,
                    'is_new' => self::IS_OLD
                ];
                $this->staffNotification->edit($data, $staffNotificationId);
                // Kiểm tra xem có action với param không, có thì redirect
                // Lấy thông tin staff notification
                $getInfo = $this->staffNotification->getItem($staffNotificationId);

                if (isset($getInfo['action']) && $getInfo['action']) {
                    switch ($getInfo['action']) {
                        case 'order_detail' :
                            // lấy order id
                            $temp = json_decode($getInfo['action_params']);
                            //Lấy thông tin đơn hàng
                            $mOrderTable = app()->get(OrderTable::class);
                            $info = $mOrderTable->getInfo($temp->order_id);

                            $url = "";

                            if ($info['order_source_id'] == 1) {
                                //Đơn hàng trực tiệp
                                $url = route('admin.order.detail', $temp->order_id);
                            } else if ($info['order_source_id'] == 2) {
                                //Đơn hàng online
                                $url = route('admin.order-app.detail', $temp->order_id);
                            }

                            return [
                                'error' => false,
                                'object_id' => $temp->order_id,
                                'url' => $url,
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'appointment_detail':
                            //Chi tiết lịch hẹn

                            // parse json param
                            $temp = json_decode($getInfo['action_params']);

                            return [
                                'error' => false,
                                'object_id' => $temp->appointment_id,
                                'url' => route('admin.customer_appointment.detail-booking', $temp->appointment_id),
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'contract_detail':
                            //Chi tiết thông báo
                            // parse json param
                            $temp = json_decode($getInfo['action_params']);

                            return [
                                'error' => false,
                                'object_id' => $temp->contract_id,
                                'url' => route('contract.contract.show', $temp->contract_id),
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'ticket_detail':
                            //Chi tiết thông báo
                            // parse json param
                            $temp = json_decode($getInfo['action_params']);

                            return [
                                'error' => false,
                                'object_id' => $temp->ticket_id,
                                'url' => route('ticket.detail', $temp->ticket_id),
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'request_material_detail':
                            //Chi tiết thông báo
                            // parse json param
                            $temp = json_decode($getInfo['action_params']);

                            return [
                                'error' => false,
                                'object_id' => $temp->ticket_request_material_id,
                                'url' => route('ticket.material', ['ticket_request_material_id' => $temp->ticket_request_material_id]),
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'acceptance_detail':
                            //Chi tiết thông báo
                            // parse json param
                            $temp = json_decode($getInfo['action_params']);

                            return [
                                'error' => false,
                                'object_id' => $temp->ticket_acceptance_id,
                                'url' => route('ticket.acceptance.detail', $temp->ticket_acceptance_id),
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'manage_work_detail':
                            //Chi tiết thông báo
                            // parse json param
                            $temp = json_decode($getInfo['action_params']);

                            return [
                                'error' => false,
                                'object_id' => $temp->manage_work_id,
                                'url' => route('manager-work.detail', $temp->manage_work_id),
                                'message' => __('Cập nhật thành công')
                            ];
                        case 'my_work':
                            //Chi tiết thông báo
                            // parse json param

                            return [
                                'error' => false,
                                'object_id' => 1,
                                'url' => route('manager-work.report.my-work'),
                                'message' => __('Cập nhật thành công')
                            ];
                        default: break;
                    }
                }
            }

            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Lấy số lượng thông báo mới
     *
     * @return int|mixed
     */
    public function getNumberOfNotificationNew()
    {
        $res = $this->staffNotification->countNotificationNew();
        if ($res != null) {
            return $res['number_of_notification'];
        } else {
            return 0;
        }
    }

    /**
     * Clear những thông báo mới khi click vào chuông
     *
     * @return mixed|void
     */
    public function clearNotifyNew()
    {
        $this->staffNotification->clearNotifyNew();
    }
}