<?php

namespace Modules\Notification\Repositories\ConfigStaff;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Notification\Models\ConfigStaffNotificationGroupTable;
use Modules\Notification\Models\ConfigStaffNotificationTable;
use Modules\Notification\Models\RoleGroupTable;
use Modules\Notification\Models\StaffNotificationReceiverTable;
use Modules\Notification\Models\StaffNotificationTemplateAutoTable;

class ConfigStaffRepo implements ConfigStaffRepoInterface
{
    protected $configStaff;
    public function __construct(ConfigStaffNotificationTable $configStaff)
    {
        $this->configStaff = $configStaff;
    }

    /**
     * data view danh sách
     *
     * @return array|mixed
     */
    public function dataIndex()
    {
        $mGroup = new ConfigStaffNotificationGroupTable();
        $getGroup = $mGroup->getGroup();
        $getConfig = $this->configStaff->getConfig();

        if (count($getConfig) > 0) {
            $getConfig = $this->array_group_by($getConfig->toArray(), 'config_notification_group_id');
        }

        return [
            'dataGroup' => $getGroup,
            'dataConfig' => $getConfig
        ];
    }

    /**
     * Data view edit
     *
     * @param $key
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dataEdit($key)
    {
        $mReceiver = app()->get(StaffNotificationReceiverTable::class);
        $mRoleGroup = app()->get(RoleGroupTable::class);

        //Lấy thông tin cấu hình thông báo
        $getInfo = $this->configStaff->getInfo($key);
        //Lấy option nhóm quyền
        $optionRoleGroup = $mRoleGroup->getRoleGroup();
        //Lấy thông tin người nhân
        $getReceiver = $mReceiver->getReceiverByKey($key);

        $arrayReceiver = [];

        if (count($getReceiver) > 0) {
            foreach ($getReceiver as $v) {
                $arrayReceiver [] = $v['role_group_id'];
            }
        }

        return [
            'item' => $getInfo,
            'optionRoleGroup' => $optionRoleGroup,
            'arrayReceiver' => $arrayReceiver
        ];
    }

    /**
     * Cập nhật template
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            DB::beginTransaction();

            $mTemplateAuto = new StaffNotificationTemplateAutoTable();
            $mReceiver = app()->get(StaffNotificationReceiverTable::class);

            $dataConfig = [
                'send_type' => $input['send_type'],
                'schedule_unit' => $input['schedule_unit'],
                'value' => $input['value'],
                'updated_by' => Auth()->id()
            ];
            //Update config notification
            $this->configStaff->edit($dataConfig, $input['key']);
            $dataTemplateAuto = [
                'title' => $input['title'],
                'message' => $input['message'],
                'detail_content' => $input['detail_content']
            ];
            if ($input['avatar'] != null) {
                $dataTemplateAuto['avatar'] =  $input['avatar'];
            } else {
                $dataTemplateAuto['avatar'] = $input['avatar_old'];
            }
            if ($input['detail_background'] != null) {
                $dataTemplateAuto['detail_background'] = $input['detail_background'];
            } else {
                $dataTemplateAuto['detail_background'] = $input['detail_background_old'];
            }
            //Update notification template auto
            $mTemplateAuto->edit($dataTemplateAuto, $input['key']);

            //Xoá người nhận
            $mReceiver->removeReceiverByKey($input['key']);

            $arrayReceiver = [];

            if (isset($input['role_group_id']) && count($input['role_group_id']) > 0) {
                foreach ($input['role_group_id'] as $v) {
                    $arrayReceiver [] = [
                        'staff_notification_key' => $input['key'],
                        'role_group_id' => $v,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ];
                }
            }

            //Insert người nhận
            $mReceiver->insert($arrayReceiver);

            DB::commit();

            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa cấu hình thông báo thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa cấu hình thông báo thất bại')
            ]);
        }
    }

    /**
     * Thay đổi trạng thái
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function changeStatus($input)
    {
        try {
            if ($input['is_active'] == 1) {
                $input['updated_by'] = Auth::id();
            }
            $this->configStaff->edit($input, $input['key']);

            return response()->json([
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ]);
        }
    }

    public function uploadImage($input)
    {
        // TODO: Implement uploadImage() method.
    }

    /**
     * Function group by
     *
     * @param array $array
     * @param $key
     * @return array|null
     */
    private function array_group_by(array $array, $key)
    {
        if (!is_string($key) && !is_int($key) && !is_float($key) && !is_callable($key)) {
            trigger_error('array_group_by(): The key should be a string, an integer, or a callback', E_USER_ERROR);
            return null;
        }
        $func = (!is_string($key) && is_callable($key) ? $key : null);
        $_key = $key;
        // Load the new array, splitting by the target key
        $grouped = [];
        foreach ($array as $value) {
            $key = null;
            if (is_callable($func)) {
                $key = call_user_func($func, $value);
            } elseif (is_object($value) && property_exists($value, $_key)) {
                $key = $value->{$_key};
            } elseif (isset($value[$_key])) {
                $key = $value[$_key];
            }
            if ($key === null) {
                continue;
            }
            $grouped[$key][] = $value;
        }
        // Recursively build a nested grouping if more parameters are supplied
        // Each grouped array value is grouped according to the next sequential key
        if (func_num_args() > 2) {
            $args = func_get_args();
            foreach ($grouped as $key => $value) {
                $params = array_merge([$value], array_slice($args, 2, func_num_args()));
                $grouped[$key] = call_user_func_array('array_group_by', $params);
            }
        }
        return $grouped;
    }
}