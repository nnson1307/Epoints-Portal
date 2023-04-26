<?php


namespace Modules\Notification\Repositories\Config;


use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Contract\Models\ContractNotifyConfigMethodMapTable;
use Modules\Contract\Models\ContractNotifyConfigTable;
use Modules\Notification\Models\ConfigNotificationGroupTable;
use Modules\Notification\Models\ConfigNotificationTable;
use Modules\Notification\Models\NotificationTemplateAutoTable;

class ConfigRepo implements ConfigRepoInterface
{
    protected $config;

    protected $s3Disk;
    public function __construct(
        ConfigNotificationTable $config,S3UploadsRedirect $s3
    ) {
        $this->config = $config;
        $this->s3Disk = $s3;
    }

    /**
     * Data view index
     *
     * @return array|mixed
     */
    public function dataIndex()
    {
        $mGroup = new ConfigNotificationGroupTable();
        $getGroup = $mGroup->getGroup();
        $getConfig = $this->config->getConfig();

        if (count($getConfig) > 0) {
            $getConfig = $this->array_group_by($getConfig->toArray(), 'config_notification_group_id');
        }
        $mContractNotify = new ContractNotifyConfigTable();
        $lstNotifyContract = $mContractNotify->getAllConfig();
        foreach ($lstNotifyContract as $key => $value) {
            $lstNotifyContract[$key]['email'] = str_contains($lstNotifyContract[$key]['notify_method'] ?? '', 'email') ? 1 : 0;
            $lstNotifyContract[$key]['notify'] = str_contains($lstNotifyContract[$key]['notify_method'] ?? '', 'notify') ? 1 : 0;
        }
        return [
            'dataGroup' => $getGroup,
            'dataConfig' => $getConfig,
            'lstNotifyContract' => $lstNotifyContract
        ];
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

    /**
     * Lấy thông tin cấu hình thông báo
     *
     * @param $key
     * @return array|mixed
     */
    public function getInfo($key)
    {
        $getInfo = $this->config->getInfo($key);

        $typeAvatar = '';
        $sizeAvatar = '';
        $widthAvatar = '';
        $heightAvatar = '';

        $avatar = $getInfo['avatar'];

        if (Storage::disk('public')->exists(parse_url($avatar, PHP_URL_PATH))) {
            $getImageSize = getimagesizefromstring(Storage::disk('public')->get(parse_url($avatar, PHP_URL_PATH)));
            $typeAvatar = strtoupper(substr($avatar, strrpos($avatar, '.') + 1));
            $widthAvatar = $getImageSize[0];
            $heightAvatar = $getImageSize[1];
//            $sizeAvatar = (int)round(Storage::disk('public')->size($avatar) / 1024);
        }

        $typeBackground = '';
        $sizeBackground = '';
        $widthBackground = '';
        $heightBackground = '';

        $background = $getInfo['detail_background'];
        if (Storage::disk('public')->exists(parse_url($background, PHP_URL_PATH))) {
            $getImageSize = getimagesizefromstring(Storage::disk('public')->get(parse_url($background, PHP_URL_PATH)));
            $typeBackground = strtoupper(substr($background, strrpos($background, '.') + 1));
            $widthBackground = $getImageSize[0];
            $heightBackground = $getImageSize[1];
//            $sizeBackground = (int)round(Storage::disk('public')->size($background) / 1024);
        }

        return [
            'item' => $getInfo,
            'sizeAvatar' => $sizeAvatar,
            'widthAvatar' => $widthAvatar,
            'heightAvatar' => $heightAvatar,
            'sizeBackground' => $sizeBackground,
            'heightBackground' => $heightBackground,
            'widthBackground' => $widthBackground
        ];
    }

    /**
     * Chỉnh sửa cấu hình thông báo
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        try {
            DB::beginTransaction();

            $mTemplateAuto = new NotificationTemplateAutoTable();

            $dataConfig = [
                'send_type' => $input['send_type'],
                'schedule_unit' => $input['schedule_unit'],
                'value' => $input['value'],
                'updated_by' => Auth()->id()
            ];
            //Update config notification
            $this->config->edit($dataConfig, $input['key']);

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

            $this->config->edit($input, $input['key']);

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

    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed|string
     */
    public function uploadImage($input)
    {
        $upload = $this->uploadImageTemp($input['file'], $input['link']);

        return $upload;
    }

    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageTemp($file, $link)
    {
        $time = Carbon::now();
//        $idTenant = "ed5fdecf0930c60d4dc30c103d826071";
        $idTenant = session()->get('idTenant');

        $to = $idTenant . '/' . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put( $to . $file_name, file_get_contents($file), 'public');

        //Lấy real path trên s3
        $realPath = $this->s3Disk->getRealPath($to. $file_name);

        return [
            'success' => 1,
            'file' => $realPath
        ];
    }
    /**
     * Move ảnh từ folder temp sang folder chính
     *
     * @param $filename
     * @param $PATH
     * @return mixed|string
     */
    public function moveImage($filename, $PATH)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = $PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory($PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    /**
     * submit contract notify config
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function submitNotifyContract($data)
    {
        try {
            $mContractNotifyConfig = new ContractNotifyConfigTable();
            $mMethodMap = new ContractNotifyConfigMethodMapTable();
            if (isset($data['listConfig'])) {
                if(count($data['listConfig']) > 0){
                    foreach ($data['listConfig'] as $item) {
                        $id = $item['contract_notify_config_id'];
                        $mMethodMap->deleteMap($id);
                        if($item['email'] == 1){
                            $dataEmail = [
                                'contract_notify_config_id' => $id,
                                'notify_method'=> 'email'
                            ];
                            $mMethodMap->createData($dataEmail);
                        }
                        if($item['notify'] == 1){
                            $dataNotify = [
                                'contract_notify_config_id' => $id,
                                'notify_method'=> 'notify'
                            ];
                            $mMethodMap->createData($dataNotify);
                        }
                        $dataUpdate = [
                            'contract_notify_config_content' => $item['contract_notify_config_content'],
                            'is_created_by' => $item['is_created_by'],
                            'is_performer_by' => $item['is_performer_by'],
                            'is_signer_by' => $item['is_signer_by'],
                            'is_follow_by' => $item['is_follow_by'],
                        ];
                        $mContractNotifyConfig->updateData($dataUpdate, $id);
                    }
                }
            }
            return response()->json([
                'error' => false,
                'message' => __('Cấu hình thông báo hợp đồng thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Cấu hình thông báo hợp đồng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }
}