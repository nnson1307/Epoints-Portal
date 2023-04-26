<?php


namespace Modules\Shift\Repositories\ConfigNoti;


use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Shift\Models\SfTimekeepingNotificationTable;

class ConfigNotiRepository implements ConfigNotiRepositoryInterface
{
    /**
     * Lấy danh sách thông báo chấm công
     * @return mixed|void
     */
    public function getListNoti()
    {
        $mSfTimeKeepingNoti = app()->get(SfTimekeepingNotificationTable::class);
        return $mSfTimeKeepingNoti->getAll();
    }

    /**
     * Hiển thị popup
     * @param $data
     * @return mixed|void
     */
    public function showPopup($data)
    {
        try {
            $mSfTimeKeepingNoti = app()->get(SfTimekeepingNotificationTable::class);

            $detail = $mSfTimeKeepingNoti->getDetail($data['sf_timekeeping_notification_id']);

            $view = view('shift::config-shift.popup.edit-message', ['detail' => $detail])->render();
            return [
                'error' => false,
                'view' => $view
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Hiển thị popup thay đổi thông tin thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật nội dung thông báo
     * @param $data
     * @return mixed|void
     */
    public function updateMessage($data)
    {
        try {
            $mSfTimeKeepingNoti = app()->get(SfTimekeepingNotificationTable::class);
            $sf_timekeeping_notification_id = $data['sf_timekeeping_notification_id'];
            unset($data['sf_timekeeping_notification_id']);
            $data['time_send'] = isset($data['time_send']) ? str_replace(',', '', $data['time_send']) : 0;
            $mSfTimeKeepingNoti->updateNoti($data,$sf_timekeeping_notification_id);
            return [
                'error' => false,
                'message' => __('Cập nhật thông báo thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Cập nhật thông báo thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cập nhật cấu hình
     * @param $data
     * @return mixed|void
     */
    public function updateNoti($data)
    {
        try {
            $mSfTimeKeepingNoti = app()->get(SfTimekeepingNotificationTable::class);
            if (isset($data['noti']) && count($data['noti']) != 0){
                foreach ($data['noti'] as $item){
                    $tmp = [
                        'is_noti' => isset($item['is_noti']) ? 1 : 0,
                        'is_email' => isset($item['is_email']) ? 1 : 0,
                        'is_active' => isset($item['is_active']) ? 1 : 0,
                    ];
                    $mSfTimeKeepingNoti->updateNoti($tmp,$item['sf_timekeeping_notification_id']);
                }
            }

            return [
                'error' => false,
                'message' => __('Cập nhật cấu hình thành công'),
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Cập nhật cấu hình thất bại'),
                '__message' => $e->getMessage()
            ];
        }
    }
}