<?php


namespace Modules\ManagerWork\Repositories\ManageConfig;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\ManagerWork\Models\ManagerConfigNotificationTable;
use Modules\ManagerWork\Models\ManageRoleTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\ManageStatusConfigMapTable;
use Modules\ManagerWork\Models\ManageStatusConfigTable;
use Modules\ManagerWork\Models\ManageStatusTable;
use Modules\ManagerWork\Models\RoleGroupTable;

class ManageConfigRepo implements ManageConfigRepositoryInterface
{
    protected $mRoleGroup;
    protected $mManageStatusConfig;
    protected $mManageStatusConfigMap;
    protected $mManageStatus;
    protected $mManageConfigNotification;

    public function __construct(
        RoleGroupTable $mRoleGroup,
        ManageStatusConfigTable $mManageStatusConfig,
        ManageStatusConfigMapTable $mManageStatusConfigMap,
        ManageStatusTable $mManageStatus,
        ManagerConfigNotificationTable $mManageConfigNotification
    )
    {
        $this->mRoleGroup = $mRoleGroup;
        $this->mManageStatusConfig = $mManageStatusConfig;
        $this->mManageStatusConfigMap = $mManageStatusConfigMap;
        $this->mManageStatus = $mManageStatus;
        $this->mManageConfigNotification = $mManageConfigNotification;
    }

    /**
     * Lấy danh sách quyền được cấu hình
     * @return mixed
     */
    public function getListRole()
    {
        return $this->mRoleGroup->getAll();
    }

    /**
     * Cập nhật cấu hình quyền
     * @param $data
     * @return mixed|void
     */
    public function updateAction($data)
    {
        try {

            $dataRole = [];

            $mManageRole = new ManageRoleTable();

            $mManageRole->deleteRole();

            $dataRole = [];
            foreach ($data['role'] as $item) {
                $dataRole[] = [
                    'role_group_id' => $item['id'],
                    'is_all' => isset($item['check']) && $item['check'] == 'is_all' ? 1 : 0,
                    'is_branch' => isset($item['check']) && $item['check'] == 'is_branch' ? 1 : 0,
                    'is_department' => isset($item['check']) && $item['check'] == 'is_department' ? 1 : 0,
                    'is_own' => isset($item['check']) && $item['check'] == 'is_own' ? 1 : 0,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                    'updated_at' => Carbon::now(),
                    'updated_by' => Auth::id()
                ];
            }

            if (count($dataRole) != 0) {
                $mManageRole->createdRole($dataRole);
            }

            return [
                'error' => false,
                'message' => __('Cập nhật cấu hình phân quyền thành công')
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => $e
            ];
        }
    }

    /**
     * Lấy danh sách cấu hình trạng thái
     * @return mixed|void
     */
    public function getListStatus()
    {
        $list = $this->mManageStatusConfig->getListStatusConfig();

        foreach ($list as $key => $item) {
            $list[$key]['list_status'] = $this->mManageStatusConfigMap->getListMapStatus($item['manage_status_config_id']);
        }

        $list = collect($list)->groupBy('manage_status_group_config_title');

        return $list;
    }

    /**
     * Danh sách trạng thái để lựa chọn
     * @return mixed
     */
    public function getListStatusSelect()
    {
        return $this->mManageStatus->getAll();
    }

    /**
     * Danh sách trạng thái đang hoạt động để lựa chọn
     * @return mixed
     */
    public function getListStatusSelectActive()
    {
        return $this->mManageStatus->getAllActive();
    }

    public function getAllConfig()
    {
        return $this->mManageStatus->getAllConfig();
    }

    /**
     * Thêm view trạng thái
     * @param $data
     * @return mixed|void
     */
    public function addStatusConfig($data)
    {
        try {

            $mManageStatusConfig = new ManageStatusConfigTable();

            $positionStatus = $mManageStatusConfig->getPosition($data['groupId']);

            $checkName = $this->mManageStatus->getItemByName(strip_tags($data['status_name']));

            if (count($checkName) != 0) {
                return [
                    'error' => true,
                    'message' => __('Tên trạng thái đã được sử dụng')
                ];
            }

            $randomColor = $this->randColor();
            $status = [
                'manage_status_name' => strip_tags($data['status_name']),
                'manage_status_color' => $randomColor,
                'is_default' => 0,
                'is_active' => 1,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idStatus = $this->mManageStatus->add($status);

            $this->mManageStatus->edit(['manage_status_value' => $idStatus], $idStatus);


            $detailStatus = $this->mManageStatus->getItem($idStatus);

            $dateStatusConfig = [
                'manage_status_group_config_id' => $data['groupId'],
                'manage_status_config_title' => strip_tags($data['status_name']),
                'manage_color_code' => $randomColor,
                'position' => $positionStatus['position'] + 1,
                'manage_status_id' => $idStatus,
                'is_default' => 0,
                'is_edit' => 0,
                'is_deleted' => 0,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idConfig = $mManageStatusConfig->createdConfig($dateStatusConfig);

            $detail = $this->mManageStatusConfig->getDetailStatusConfig($idConfig);
//            $listStatusSelect = $this->getListStatusSelect();
            $listStatusSelect = $this->getListStatusSelectActive();
            $view = view('manager-work::config-status.append.group-status', [
                'listStatusSelect' => $listStatusSelect,
//                'groupId' => $data['groupId'],
                'count' => $data['count'],
                'value' => $detail,
                'detailStatus' => $detailStatus

            ])->render();

            return [
                'error' => false,
                'view' => $view,
                'detailStatus' => $detailStatus
            ];

        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Thêm cấu hình trạng thái thất bại')
            ];
        }
    }

    /**
     * Cập nhật cấu hình trạng thái
     * @param $data
     * @return mixed|void
     */
    public function updateConfigStatus($data)
    {
        DB::beginTransaction();
        try {

            $mManageStatusConfig = new ManageStatusConfigTable();
            $mManageStatusConfigMap = new ManageStatusConfigMapTable();

            $message = '';
            $nGroup = 0;
            foreach ($data['group'] as $key => $item) {
                $nGroup++;
                $nChild = 0;
                foreach ($item as $value) {
                    $nChild++;
                    if (strlen($value['manage_status_config_title']) == 0) {
                        $message = $message . __('Nhóm :group trạng thái :child vui lòng nhập tên trạng thái', ['group' => $nGroup, 'child' => $nChild]) . '<br>';
                    }

                    if (strlen($value['manage_status_config_title']) > 255) {
                        $message = $message . __('Nhóm :group trạng thái :child tên trạng thái vượt quá 255 ký tự', ['group' => $nGroup, 'child' => $nChild]) . '<br>';
                    }

//                    if (!isset($value['manage_status_config_map'])){
//                        $message = $message.__('Nhóm :group trạng thái :child vui lòng chọn trạng thái',['group' => $nGroup,'child' => $nChild]).'<br>';
//                    }
                }

                foreach ($item as $value){
                    if (!in_array($key,[3,4])) {
                        if (!isset($value['manage_status_config_map'])) {
                            return [
                                'error' => true,
                                'message' => __('Vui lòng chọn trạng thái kế tiếp cho trạng thái ') . $value['manage_status_config_title']
                            ];
                        }
                    }
                }
            }

            if ($message != null) {
                return [
                    'error' => true,
                    'message' => $message
                ];
            }

            $mManageStatusConfig->deleteConfig();
            $mManageStatusConfigMap->deleteConfigMap();

            $nGroup = 0;

            foreach ($data['group'] as $key => $item) {
                $nGroup++;
                $nChild = 0;
                foreach ($item as $value) {
                    $dateStatusConfig = [];
                    $nChild++;

                    $dateStatusConfig = [
                        'manage_status_group_config_id' => $key,
                        'manage_status_config_title' => strip_tags($value['manage_status_config_title']),
                        'manage_color_code' => $value['manage_color_code'],
                        'manage_status_id' => $value['manage_status_id'],
                        'position' => $nChild,
                        'is_default' => isset($value['is_default']) && $value['is_default'] == 1 ? 1 : 0,
                        'is_edit' => isset($value['is_edit']) ? 1 : 0,
                        'is_deleted' => isset($value['is_deleted']) ? 1 : 0,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];

                    $this->mManageStatus->edit(['manage_status_name' => strip_tags($value['manage_status_config_title']), 'manage_status_color' => $value['manage_color_code']], $value['manage_status_id']);
                    $dateStatusConfig['is_active'] = isset($value['is_active']) ? 1 : 0;

                    $idConfig = $mManageStatusConfig->createdConfig($dateStatusConfig);
                    $dateStatusConfigMap = [];
                    if (!in_array($key,[3,4])) {
                        foreach ($value['manage_status_config_map'] as $itemMap) {
                            $dateStatusConfigMap[] = [
                                'manage_status_config_id' => $idConfig,
                                'manage_status_id' => $itemMap,
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                        }
                    } else {
                        if (isset($value['manage_status_config_map'])) {
                            foreach ($value['manage_status_config_map'] as $itemMap) {
                                $dateStatusConfigMap[] = [
                                    'manage_status_config_id' => $idConfig,
                                    'manage_status_id' => $itemMap,
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id()
                                ];
                            }
                        }
                    }

                    if (count($dateStatusConfigMap) != 0) {
                        $mManageStatusConfigMap->createdConfigMap($dateStatusConfigMap);
                    }
                }
            }

            DB::commit();

            return [
                'error' => false,
                'message' => __('Lưu cấu hình trạng thái thành công')
            ];
        } catch (\Exception $e) {

            DB::rollBack();

            return [
                'error' => true,
                'message' => __('Lưu cấu hình trạng thái thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách noti cấu hình
     * @return mixed|void
     */
    public function getListNotiConfig()
    {
        return $this->mManageConfigNotification->getAll();
    }

    /**
     * Hiển thị popup thay đổi nội dung
     * @param $data
     * @return mixed|void
     */
    public function showPopup($data)
    {
        try {

            $detail = $this->mManageConfigNotification->getDetail($data['manage_config_notification_id']);

            $view = view('manager-work::config-noti.popup.edit-message', ['detail' => $detail])->render();
            return [
                'error' => false,
                'view' => $view
            ];

        } catch (\Exception $e) {
            return [
                'error' => false,
                'message' => __('Hiển thị popup thay đổi thông tin thất bại')
            ];
        }
    }

    /**
     * Cập nhật cấu hình noti công việc
     * @param $data
     * @return mixed|void
     */
    public function updateNotification($data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['noti'] as $key => $item) {
                $dataNoti = [];
                $dataNoti = [
                    'manage_config_notification_title' => $item['manage_config_notification_title'],
                    'manage_config_notification_message' => $item['manage_config_notification_message'],
                    'is_mail' => isset($item['is_mail']) ? 1 : 0,
                    'is_noti' => isset($item['is_noti']) ? 1 : 0,
                    'is_created' => isset($item['is_created']) ? 1 : 0,
                    'is_processor' => isset($item['is_processor']) ? 1 : 0,
                    'is_support' => isset($item['is_support']) ? 1 : 0,
                    'is_approve' => isset($item['is_approve']) ? 1 : 0,
                ];

                $this->mManageConfigNotification->editNoti($dataNoti, $key);
            }

            DB::commit();

            return [
                'error' => false,
                'message' => __('Cập nhật cấu hình thông báo thành công')
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => __('Cập nhật cấu hình thông báo thất bại')
            ];

        }
    }

    public function removeStatusConfig($data)
    {
        try {

            $mManageWork = new ManagerWorkTable();
            $checkWork = $mManageWork->checkWorkByStatus($data['manage_status_id']);

            if (count($checkWork) != 0){
                return [
                    'error' => true,
                    'message' => __('Trạng thái đang được sử dụng cho công việc không thể xoá'),
                ];
            }
            
//            Xoá trạng thái
            $this->mManageStatus->remove($data['manage_status_id']);

            $listConfig = $this->mManageStatusConfig->getConfigByStatusId($data['manage_status_id']);

            $this->mManageStatusConfig->deleteConfigByStatusId($data['manage_status_id']);
            $this->mManageStatusConfigMap->deleteStatusByIdStatus($listConfig);

            if (count($listConfig) != 0) {
                $listConfig = collect($listConfig)->pluck('manage_status_group_config_id')->toArray();
                $this->mManageStatusConfigMap->deleteConfigMapByConfig($listConfig);
            }

            return [
                'error' => false,
                'message' => __('Xoá trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá trạng thái thất bại')
            ];
        }
    }

    /**
     * Random mã màu
     * @return string
     */
    public function randColor()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Cập nhật trạng thái hoạt động theo cấu hình
     * @param $data
     * @return mixed|void
     */
    public function updateActive($data)
    {
        try {

            $mManageStatusConfig = app()->get(ManageStatusConfigTable::class);

//            Check trạng thái hiện tại có phải là trạng thái next step
            $checkStatusConfig = $mManageStatusConfig->checkStatusNextStep($data['idConfig']);

            if (count($checkStatusConfig) != 0){
                return [
                    'error' => true,
                    'message' => __('Trạng thái này đã được sử dụng. Bạn không thể thực hiện thao tác này')
                ];
            }

            $mManageStatusConfig->updateConfig(['is_active' => $data['is_active']],$data['idConfig']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái hoạt động thành công')
            ];
        }catch (Exception $e){
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái hoạt động thất bại')
            ];
        }
    }
}
