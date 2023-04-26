<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ZNS\Repositories\Config;

use Carbon\Carbon;
use Modules\ZNS\Models\TriggerConfigTable;
use Modules\ZNS\Models\TriggerParamsTable;
use Modules\ZNS\Models\TemplateTable;
use Modules\ZNS\Models\ListParramsTable;
use Modules\ZNS\Models\CustomerTable;
use Modules\ZNS\Models\ResetRankLogTable;
use Modules\ZNS\Models\OrderTable;
use Modules\ZNS\Models\CustomerAppointmentTable;
use Modules\ZNS\Models\CustomerServiceCardTable;
use Modules\ZNS\Models\WarrantyCardTable;
use Modules\ZNS\Models\CustomerRemindUseTable;
use Modules\ZNS\Models\LogTable;

use Modules\ZNS\Http\Api\SendNotificationApi;


class ConfigRepository implements ConfigRepositoryInterface
{
    /**
     * @var TriggerConfigTable
     */
    protected $config;
    protected $timestamps = true;

    public function __construct(TriggerConfigTable $config)
    {
        $this->config = $config;
    }

    /**
     *get list Config
     */
    public function list(array $filters = [])
    {
        $mTriggerParamsTable = new TriggerParamsTable;
        return [
            'list' => $this->config->getList($filters),
            'params' => $filters,
            'status_config' => $this->status_config(),
            'mTriggerParamsTable' => $mTriggerParamsTable
        ];
    }

    /**
     * delete Config
     */
    public function remove($id)
    {
        $this->config->remove($id);
    }

    /**
     * add Config
     */
    public function add(array $data)
    {

        return $this->config->add($data);
    }

    /*
     * edit Config
     */
    public function edit(array $data, $id)
    {
        return $this->config->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->config->getItem($id);
    }

    /*
     *  get edit View
     */
    public function editView($params)
    {
        $mTriggerParamsTable = new TriggerParamsTable;
        $mTemplateTable = new TemplateTable;
        $item = $this->config->getItem($params['id']);
        $param_trigger = $mTriggerParamsTable->getParamsByTriggerConfig($params['id']);
        $option = $mTemplateTable->getName(1);
        if (isset($params['id'])) {
            return [
                'status' => 1,
                'html' => view('zns::config.edit', [
                    'item' => $item,
                    'param_trigger' => $param_trigger,
                    'option' => $option,
                ])->render()
            ];
        }
        return [
            'status' => 0,
            'html' => ''
        ];
    }

    public function editSubmit($params, $id)
    {
        return [
            'status' => $this->config->edit($params, $id)
        ];
    }

    public function status_config()
    {
        return [
            0 => __('Không hoạt động'),
            1 => __('Đang hoạt động')
        ];
    }

    public function changeStatusAction($change)
    {
        $data['is_active'] = ($change['action'] == 0) ? 1 : 0;
        if ($this->config->edit($data, $change['id'])) {
            return response()->json([
                'status' => 1
            ]);
        };
        return response()->json([
            'status' => 0
        ]);
    }

    /**
     * Lưu log CSKH ZNS
     *
     * @param $key
     * @param $userId
     * @param $objectId
     * @return string
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function sendNotification($key, $userId, $objectId)
    {
        $mListParamsTable = new ListParramsTable();

        //Lấy thông tin cấu hình
        $configItem = $this->config->getInfoByKey($key);

        if ($configItem == null || $configItem['is_active'] == 0) {
            return '';
        }

        //Lấy thông tin template
        $templateId = $configItem->template_id;
        //Lấy param từ template
        $usable_params['param'] = $mListParamsTable->getItemByTemplateId($templateId);

        $data = [];

        // try {
        switch ($key) {
            // thành viên mới
            case 'new_customer' :
                $data = $this->insertDataNewCustomer($usable_params, $userId);
                break;
            //thanh toán thành công
            case 'order_success' :
                $data = $this->insertDataOrderSuccess($usable_params, $userId, $objectId);
            // đơn hàng đang giao
            case 'order_waiting':
                $data = $this->insertDataOrderSuccess($usable_params, $userId, $objectId);
                break;
            // cảm ơn đã đạt hàng
            case 'order_thanks' :
                $data = $this->insertDataThanksOrder($usable_params, $userId, $objectId);
                break;
            // hạng thành viên
            case 'membership' :
                $data = $this->insertDataNotiMemberRank($usable_params, $userId);
                break;
            // hủy đơn hàng
            case 'order_cancle' :
                $data = $this->insertDataCancleOrder($usable_params, $userId, $objectId);
                break;
            // tích lũy điểm
            case 'bonus_points' :
                $data = $this->insertDataNewAppointment($usable_params, $userId, $objectId);
                break;
            // sử dụng điểm
            case 'use_points' :
                $data = $this->insertDataRemindAppointment($usable_params, $userId, $objectId);
                break;
            // otp
            case 'otp' :
                // coupon quá hạn
//                    $data = $this->insertDataCoupon($usable_params,$userId,$objectId);
                break;
            // sinh nhật {bỏ}
            case 'birthday' :

                break;
            case 'new_appointment' :
                $data = $this->insertDataNewAppointment($usable_params, $userId, $objectId);
                break;
            // hủy lịch hẹn
            case 'cancel_appointment':
                $data = $this->insertDataRemindAppointment($usable_params, $userId, $objectId);
                break;
            // nhắc nhở lịch hẹn
            case 'remind_appointment' :
                $data = $this->insertDataRemindAppointment($usable_params, $userId, $objectId);
                break;
            // thẻ dịch vụ sắp hết hạn
            case 'service_card_nearly_expired' :
                $data = $this->insertDataNearExpirationAppointment($usable_params, $userId, $objectId);
                break;
            // thẻ dịch vụ hết số lần sử dụng
            case 'service_card_over_number_used' :
                $data = $this->insertDataExpirationAppointmentNumberUse($usable_params, $userId, $objectId);
                break;
            // thẻ dịch vụ hết hạn
            case 'service_card_expires' :
                $data = $this->insertDataExpirationAppointment($usable_params, $userId, $objectId);
                break;
            // tạo phiếu giao hàng thành công
            case 'delivery_note' :
                $data = $this->insertDataOrderDone($usable_params, $userId, $objectId);
                break;
            case 'confirm_deliveried' :
                $data = $this->insertDataOrderDone($usable_params, $userId, $objectId);
                break;
            // kích hoạt thẻ bảo hành thành công
            case 'active_warranty_card' :
                $data = $this->insertDataActiveWarrantyCard($usable_params, $userId, $objectId);
                break;
            // nhắc sử dụng lại dịch vụ
            case 'is_remind_use' :
        }
        // kiểm tra nếu ko có params thì để rỗng
        if (count($usable_params['param']) > 0){
            foreach ($usable_params['param'] as $k => $v) {
                if(!isset($data[$v['value']])){
                    $data[$v['value']] = "";
                }
            }
        }

        $mCustomerTable = app()->get(CustomerTable::class);
        $mTemplateTable = app()->get(TemplateTable::class);
        //Lấy thông tin khách hàng
        $user_info = $mCustomerTable->getItem($userId);
        //Lấy thông tin template
        $template_info = $mTemplateTable->getItemByTemplateId($templateId);

        $mLogTable = app()->get(LogTable::class);
        //Lưu log zns
        $mLogTable->add([
            'user_id' => $userId,
            'phone' => $user_info->phone1,
            'status' => "new",
            'message' => $template_info->preview,
            'template_id' => $templateId,
            'params' => count($data)?json_encode($data):null
        ]);
    }

    /**
     * Chào mừng thành viên mới
     *
     * @param $config
     * @param $userId
     * @return array
     */
    public function insertDataNewCustomer($config, $userId)
    {
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = $user_info->point;
                    break;
            }
        }

        return $data;
    }

    /*
    * case 2 + case 3
    ** Chúc mừng đặt hàng thành công + Thông báo đơn hàng đang vận chuyển	
    */
    public function insertDataOrderSuccess($config, $userId, $orderId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mOrderTable = app()->get(OrderTable::class);
        $order_info = $mOrderTable->orderItem($orderId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'order_number':
                    $data['order_number'] = $order_info->order_code;
                    break;
                case 'order_value':
                    $data['order_value'] = number_format($order_info->total, 0, '', '.');
                    break;
                case 'order_date':
                    $data['order_date'] = Carbon::createFromFormat("Y-m-d H:i:s", $order_info->order_date)->format("d/m/Y");
                    break;
                case 'payment_status':
                    if ($order_info->process_status == 'new') {
                        $data['payment_status'] = __('Mới');
                    } elseif ($order_info->process_status == 'confirmed') {
                        $data['payment_status'] = __('Đã xác nhận');
                    } elseif ($order_info->process_status == 'ordercancle') {
                        $data['payment_status'] = __('Đơn hàng đã hủy');
                    } elseif ($order_info->process_status == 'paysuccess') {
                        $data['payment_status'] = __('Thanh toán thành công');
                    } elseif ($order_info->process_status == 'payfail') {
                        $data['payment_status'] = __('Thanh toán thất bại');
                    } elseif ($order_info->process_status == 'pay-half') {
                        $data['payment_status'] = __('Trả một nửa');
                    } else {
                        $data['payment_status'] = "";
                    }
                    break;
                case 'order_note':
                    $data['order_note'] = $order_info->order_description;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'shipping_name':
                    $data['shipping_name'] = $order_info->contact_name;
                    break;
                case 'shipping_phone':
                    $data['shipping_phone'] = $order_info->contact_phone;
                    break;
                case 'shipping_method':
                    $data['shipping_method'] = $order_info->payment_method_name;
                    break;
                case 'payment_method':
                    $data['payment_method'] = ($order_info->receive_at_counter == 0) ? __('Giao hàng') : __('Nhận hàng tại quầy');
                    break;
            }
        }

        return $data;
    }

    /*
    * case 4
    * Cảm ơn sau khi mua hàng	
    */

    public function insertDataThanksOrder($config, $userId, $orderId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mOrderTable = app()->get(OrderTable::class);
        $order_info = $mOrderTable->orderItem($orderId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'added_point':
                    $data['added_point'] = $user_info->point_balance;
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = $user_info->point;
                    break;
                case 'order_number':
                    $data['order_number'] = $order_info->order_code;
                    break;
                case 'order_value':
                    $data['order_value'] = number_format($order_info->total, 0, '', '.');
                    break;
            }
        }

        return $data;
    }

    /*
    * case 5
    * Thông báo hạng thành viên
    */

    public function insertDataNotiMemberRank($config, $userId)
    {
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'expirydate_level':
                    $data['expirydate_level'] = '';
                    break;
                case 'spent_levelnext':
                    $data['spent_levelnext'] = '';
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = $user_info->point;
                    break;
            }
        }

        return $data;
    }

    /*
    * case 6
    * Thông báo hủy đơn hàng	
    */

    public function insertDataCancleOrder($config, $userId, $orderId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mOrderTable = app()->get(OrderTable::class);
        $order_info = $mOrderTable->orderItem($orderId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'order_number':
                    $data['order_number'] = $order_info->order_code;
                    break;
                case 'order_value':
                    $data['order_value'] = number_format($order_info->total, 0, '', '.');
                    break;
                case 'order_date':
                    $data['order_date'] = Carbon::createFromFormat("Y-m-d H:i:s", $order_info->order_date)->format("d/m/Y");
                    break;
                case 'payment_status':
                    if ($order_info->process_status == 'new') {
                        $data['payment_status'] = __('Mới');
                    } elseif ($order_info->process_status == 'confirmed') {
                        $data['payment_status'] = __('Đã xác nhận');
                    } elseif ($order_info->process_status == 'ordercancle') {
                        $data['payment_status'] = __('Đơn hàng đã hủy');
                    } elseif ($order_info->process_status == 'paysuccess') {
                        $data['payment_status'] = __('Thanh toán thành công');
                    } elseif ($order_info->process_status == 'payfail') {
                        $data['payment_status'] = __('Thanh toán thất bại');
                    } elseif ($order_info->process_status == 'pay-half') {
                        $data['payment_status'] = __('Trả một nửa');
                    } else {
                        $data['payment_status'] = "";
                    }
                    break;
                case 'order_note':
                    $data['order_note'] = $order_info->order_description;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'shipping_name':
                    $data['shipping_name'] = $order_info->contact_name;
                    break;
                case 'shipping_phone':
                    $data['shipping_phone'] = $order_info->contact_phone;
                    break;
                case 'shipping_method':
                    $data['shipping_method'] = $order_info->payment_method_name;
                    break;
                case 'payment_method':
                    $data['payment_method'] = ($order_info->receive_at_counter == 0) ? __('Giao hàng') : __('Nhận hàng tại quầy');
                    break;
                case 'order_reason':
                    $data['order_reason'] = "";
                    break;
            }
        }

        return $data;
    }

    /*
    * case 7
    * Thông báo Tích lũy điểm thưởng
    */

    public function insertDataRewardPoints($config, $userId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'added_point':
                    $data['added_point'] = '';
                    break;
                case 'reason_point':
                    $data['reason_point'] = '';
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = $user_info->point;
                    break;
            }
        }

        return $data;
    }

    /*
    * case 8
    * Thông báo sử dụng điểm thưởng	
    */

    public function insertDataNotiUsePoints($config, $userId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'expirydate_level':
                    $data['expirydate_level'] = '';
                    break;
                case 'burn_point':
                    $data['burn_point'] = '';
                    break;
                case 'burn_reason':
                    $data['burn_reason'] = '';
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = $user_info->point;
                    break;
            }
        }

        return $data;
    }

    /*
    * case 10
    * Thông báo coupon sắp hết hạn	
    */

    public function insertDataCoupon($config, $userId, $orderId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mOrderTable = app()->get(OrderTable::class);
        $order_info = $mOrderTable->orderItem($orderId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'expirydate_level':
                    $data['expirydate_level'] = '';
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = '';
                    break;
                case 'current_reward_point':
                    $data['current_reward_point'] = $user_info->point;
                    break;
                case 'number':
                    $data['number'] = '';
                    break;
                case 'date':
                    $data['date'] = '';
                    break;
            }
        }

        return $data;
    }

    /*
    * case 12 + 13
    * Lịch hện mới + hủy lịch
    */

    public function insertDataNewAppointment($config, $userId, $appointmentId)
    {
        //Lấy thông tin 
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mCustomerAppointmentTable = app()->get(CustomerAppointmentTable::class);
        $appointment_info = $mCustomerAppointmentTable->getInfo($appointmentId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'datetime_appointment':
                    $data['datetime_appointment'] = Carbon::createFromFormat("Y-m-d", $appointment_info->date)->format("d/m/Y") . ' ' . Carbon::createFromFormat("H:i:s", $appointment_info->time)->format("H:i");
                    break;
                case 'code_appointment':
                    $data['code_appointment'] = $appointment_info->customer_appointment_code;
                    break;
            }
        }

        return $data;
    }

    /*
    * case 14
    * Nhắc lịch
    */

    public function insertDataRemindAppointment($config, $userId, $appointmentId)
    {
        //Lấy thông tin 
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mCustomerAppointmentTable = app()->get(CustomerAppointmentTable::class);
        $appointment_info = $mCustomerAppointmentTable->getInfo($appointmentId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'datetime_appointment':
                    $data['datetime_appointment'] = Carbon::createFromFormat("Y-m-d", $appointment_info->date)->format("d/m/Y") . ' ' . Carbon::createFromFormat("H:i:s", $appointment_info->time)->format("H:i");
                    break;
                case 'name_spa':
                    $data['name_spa'] = $appointment_info->address . ', ' . $appointment_info->district_name . ', ' . $appointment_info->province_name;
                    break;
            }
        }

        return $data;
    }

    /*
    * case 15
    * Thẻ dịch vụ sắp hết hạn
    */

    public function insertDataNearExpirationAppointment($config, $userId, $cardId)
    {
        //Lấy thông tin 
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
        $service_card_info = $mCustomerServiceCard->getInfo($cardId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'code_card':
                    $data['code_card'] = $service_card_info->card_code;
                    break;
                case 'date':
                    $data['date'] = Carbon::createFromFormat("Y-m-d H:m:s", $service_card_info->expired_date)->format("d/m/Y");
                    break;
            }
        }

        return $data;
    }

    /*
    * case 16
    * Thẻ dịch vụ hết số lần sử dụng	
    */

    public function insertDataExpirationAppointmentNumberUse($config, $userId, $cardId)
    {
        //Lấy thông tin 
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
        $service_card_info = $mCustomerServiceCard->getInfo($cardId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'code_card':
                    $data['code_card'] = $service_card_info->card_code;
                    break;
                case 'date':
                    $data['date'] = Carbon::createFromFormat("Y-m-d H:m:s", $service_card_info->expired_date)->format("d/m/Y");
                    break;
            }
        }

        return $data;
    }

    /*
    * case 17
    * Thẻ dịch vụ hết hạn	
    */

    public function insertDataExpirationAppointment($config, $userId, $cardId)
    {
        //Lấy thông tin 
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);
        $service_card_info = $mCustomerServiceCard->getInfo($cardId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'member_number':
                    $data['member_number'] = $user_info->phone1;
                    break;
                case 'member_level':
                    $data['member_level'] = $user_info->member_level_name;
                    break;
                case 'code_card':
                    $data['code_card'] = $service_card_info->card_code;
                    break;
                case 'date':
                    $data['date'] = Carbon::createFromFormat("Y-m-d H:m:s", $service_card_info->expired_date)->format("d/m/Y");
                    break;
            }
        }

        return $data;
    }

    /*
    * case 19
    * Xác nhận giao hàng hoàn tất	
    */

    public function insertDataOrderDone($config, $userId, $orderId)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mOrderTable = app()->get(OrderTable::class);
        $order_info = $mOrderTable->orderItem($orderId);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'order_number':
                    $data['order_number'] = $order_info->order_code;
                    break;
                case 'order_value':
                    $data['order_value'] = number_format($order_info->total, 0, '', '.');
                    break;
                case 'order_date':
                    $data['order_date'] = Carbon::createFromFormat("Y-m-d H:i:s", $order_info->order_date)->format("d/m/Y");
                    break;
                case 'payment_status':
                    if ($order_info->process_status == 'new') {
                        $data['payment_status'] = __('Mới');
                    } elseif ($order_info->process_status == 'confirmed') {
                        $data['payment_status'] = __('Đã xác nhận');
                    } elseif ($order_info->process_status == 'ordercancle') {
                        $data['payment_status'] = __('Đơn hàng đã hủy');
                    } elseif ($order_info->process_status == 'paysuccess') {
                        $data['payment_status'] = __('Thanh toán thành công');
                    } elseif ($order_info->process_status == 'payfail') {
                        $data['payment_status'] = __('Thanh toán thất bại');
                    } elseif ($order_info->process_status == 'pay-half') {
                        $data['payment_status'] = __('Trả một nửa');
                    } else {
                        $data['payment_status'] = "";
                    }
                    break;
                case 'order_note':
                    $data['order_note'] = $order_info->order_description;
                    break;
                case 'customer_full_name':
                    $data['customer_full_name'] = $user_info->full_name;
                    break;
                case 'shipping_name':
                    $data['shipping_name'] = $order_info->contact_name;
                    break;
                case 'shipping_phone':
                    $data['shipping_phone'] = $order_info->contact_phone;
                    break;
                case 'shipping_method':
                    $data['shipping_method'] = $order_info->payment_method_name;
                    break;
                case 'payment_method':
                    $data['payment_method'] = ($order_info->receive_at_counter == 0) ? __('Giao hàng') : __('Nhận hàng tại quầy');
                    break;
            }
        }

        return $data;
    }

    /*
    * case 20
    * Kích hoạt thẻ bảo hành thành công
    */

    public function insertDataActiveWarrantyCard($config, $userId, $warranty_id)
    {
        //Lấy thông tin KH (query)
        $mCustomerTable = app()->get(CustomerTable::class);
        $user_info = $mCustomerTable->getItem($userId);
        $mWarrantyCardTable = app()->get(WarrantyCardTable::class);
        $warranty_card_info = $mWarrantyCardTable->getInfo($warranty_id);

        $data = [];

        foreach ($config['param'] as $v) {
            switch ($v->value) {
                case 'customer_name':
                    $pieces = explode(' ', $user_info->full_name);
                    $last_name = array_pop($pieces);
                    $data['customer_name'] = $last_name;
                    break;
                case 'customer_gender':
                    if ($user_info->gender == 'male') {
                        $data['customer_gender'] = __('Nam');
                    } elseif ($user_info->gender == 'female') {
                        $data['customer_gender'] = __('Nữ');
                    } else {
                        $data['customer_gender'] = __('Khác');
                    }
                    break;
                case 'warranty_card_code':
                    $data['warranty_card_code'] = $warranty_card_info->warranty_card_code;
                    break;
            }
        }

        return $data;
    }

}