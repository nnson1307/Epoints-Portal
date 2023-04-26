<?php


namespace Modules\ManagerProject\Repositories\ManageConfig;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\ManagerProject\Models\ManageProjectTable;
use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerProject\Models\ManageProjectStatusConfigMapTable;
use Modules\ManagerProject\Models\ManageProjectStatusConfigTable;
use Modules\ManagerProject\Models\ManageProjectStatusTable;

class ManageConfigRepo implements ManageConfigRepositoryInterface
{
    protected $mRoleGroup;
    protected $mManageProjectStatusConfig;
    protected $mManageProjectStatusConfigMap;
    protected $mManageProjectStatus;
    protected $mManageConfigNotification;

    public function __construct(
        ManageProjectStatusConfigTable $mManageProjectStatusConfig,
        ManageProjectStatusConfigMapTable $mManageProjectStatusConfigMap,
        ManageProjectStatusTable $mManageProjectStatus
    )
    {
        $this->mManageProjectStatusConfig = $mManageProjectStatusConfig;
        $this->mManageProjectStatusConfigMap = $mManageProjectStatusConfigMap;
        $this->mManageProjectStatus = $mManageProjectStatus;
    }

    /**
     * Lấy danh sách cấu hình trạng thái
     * @return mixed|void
     */
    public function getListStatus()
    {
        $list = $this->mManageProjectStatusConfig->getListStatusConfig();

        foreach ($list as $key => $item) {
            $list[$key]['list_status'] = $this->mManageProjectStatusConfigMap->getListMapStatus($item['manage_project_status_config_id']);
        }

        $list = collect($list)->groupBy('manage_project_status_group_config_title');

        return $list;
    }

    /**
     * Danh sách trạng thái để lựa chọn
     * @return mixed
     */
    public function getListStatusSelect()
    {
        return $this->mManageProjectStatus->getAll();
    }

    /**
     * Danh sách trạng thái đang hoạt động để lựa chọn
     * @return mixed
     */
    public function getListStatusSelectActive()
    {
        return $this->mManageProjectStatus->getAllActive();
    }

    /**
     * Thêm view trạng thái
     * @param $data
     * @return mixed|void
     */
    public function addStatusConfig($data)
    {
        try {

            $mManageProjectStatusConfig = new ManageProjectStatusConfigTable();

            $positionStatus = $mManageProjectStatusConfig->getPosition($data['groupId']);

            $checkName = $this->mManageProjectStatus->getItemByName(strip_tags($data['status_name']));

            if (count($checkName) != 0) {
                return [
                    'error' => true,
                    'message' => __('Tên trạng thái đã được sử dụng')
                ];
            }

            $randomColor = $this->randColor();
            $status = [
                'manage_project_status_name' => strip_tags($data['status_name']),
                'manage_project_status_color' => $randomColor,
                'is_default' => 0,
                'is_active' => 1,
                'created_by' => Auth::id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idStatus = $this->mManageProjectStatus->add($status);

            $this->mManageProjectStatus->edit(['manage_project_status_value' => $idStatus], $idStatus);


            $detailStatus = $this->mManageProjectStatus->getItem($idStatus);

            $dateStatusConfig = [
                'manage_project_status_group_config_id' => $data['groupId'],
                'manage_project_status_config_title' => strip_tags($data['status_name']),
                'manage_project_color_code' => $randomColor,
                'position' => $positionStatus['position'] + 1,
                'manage_project_status_id' => $idStatus,
                'is_default' => 0,
                'is_edit' => 1,
                'is_deleted' => 1,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ];

            $idConfig = $mManageProjectStatusConfig->createdConfig($dateStatusConfig);

            $detail = $this->mManageProjectStatusConfig->getDetailStatusConfig($idConfig);
//            $listStatusSelect = $this->getListStatusSelect();
            $listStatusSelect = $this->getListStatusSelectActive();
            $view = view('manager-project::config-status.append.group-status', [
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
            $mManageProjectStatusConfig = new ManageProjectStatusConfigTable();
            $mManageProjectStatusConfigMap = new ManageProjectStatusConfigMapTable();

            $message = '';
            $nGroup = 0;
            foreach ($data['group'] as $key => $item) {
                $nGroup++;
                $nChild = 0;
                foreach ($item as $value) {
                    $nChild++;
                    if (strlen($value['manage_project_status_config_title']) == 0) {
                        $message = $message . __('Nhóm :group trạng thái :child vui lòng nhập tên trạng thái', ['group' => $nGroup, 'child' => $nChild]) . '<br>';
                    }

                    if (strlen($value['manage_project_status_config_title']) > 255) {
                        $message = $message . __('Nhóm :group trạng thái :child tên trạng thái vượt quá 255 ký tự', ['group' => $nGroup, 'child' => $nChild]) . '<br>';
                    }

//                    if (!isset($value['manage_project_status_config_map'])){
//                        $message = $message.__('Nhóm :group trạng thái :child vui lòng chọn trạng thái',['group' => $nGroup,'child' => $nChild]).'<br>';
//                    }
                }

                foreach ($item as $value){
                    if (!in_array($key,[3,4])) {
                        if (!isset($value['manage_project_status_config_map'])) {
                            return [
                                'error' => true,
                                'message' => __('Vui lòng chọn trạng thái kế tiếp cho trạng thái ') . $value['manage_project_status_config_title']
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

            $mManageProjectStatusConfig->deleteConfig();
            $mManageProjectStatusConfigMap->deleteConfigMap();

            $nGroup = 0;

            foreach ($data['group'] as $key => $item) {
                $nGroup++;
                $nChild = 0;
                foreach ($item as $value) {
                    $dateStatusConfig = [];
                    $nChild++;

                    $dateStatusConfig = [
                        'manage_project_status_group_config_id' => $key,
                        'manage_project_status_config_title' => strip_tags($value['manage_project_status_config_title']),
                        'manage_project_color_code' => $value['manage_project_color_code'],
                        'manage_project_status_id' => $value['manage_project_status_id'],
                        'position' => $nChild,
                        'is_default' => isset($value['is_default']) && $value['is_default'] == 1 ? 1 : 0,
                        'is_edit' => isset($value['is_edit']) ? 1 : 0,
                        'is_deleted' => isset($value['is_deleted']) ? 1 : 0,
                        'is_active' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'updated_at' => Carbon::now(),
                        'updated_by' => Auth::id()
                    ];

                    $this->mManageProjectStatus->edit(['manage_project_status_name' => strip_tags($value['manage_project_status_config_title']), 'manage_project_status_color' => $value['manage_project_color_code']], $value['manage_project_status_id']);
                    $dateStatusConfig['is_active'] = isset($value['is_active']) ? 1 : 0;
                    $idConfig = $mManageProjectStatusConfig->createdConfig($dateStatusConfig);
                    $dateStatusConfigMap = [];
                    if (!in_array($key,[3,4])) {
                        foreach ($value['manage_project_status_config_map'] as $itemMap) {
                            $dateStatusConfigMap[] = [
                                'manage_project_status_config_id' => $idConfig,
                                'manage_project_status_id' => $itemMap,
                                'created_at' => Carbon::now(),
                                'created_by' => Auth::id(),
                                'updated_at' => Carbon::now(),
                                'updated_by' => Auth::id()
                            ];
                        }
                    } else {
                        if (isset($value['manage_project_status_config_map'])) {
                            foreach ($value['manage_project_status_config_map'] as $itemMap) {
                                $dateStatusConfigMap[] = [
                                    'manage_project_status_config_id' => $idConfig,
                                    'manage_project_status_id' => $itemMap,
                                    'created_at' => Carbon::now(),
                                    'created_by' => Auth::id(),
                                    'updated_at' => Carbon::now(),
                                    'updated_by' => Auth::id()
                                ];
                            }
                        }
                    }

                    if (count($dateStatusConfigMap) != 0) {
                        $mManageProjectStatusConfigMap->createdConfigMap($dateStatusConfigMap);
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

    public function removeStatusConfig($data)
    {
        try {

            $mManageProject = new ManageProjectTable();
            $checkWork = $mManageProject->checkProjectByStatus($data['manage_project_status_id']);

            if (count($checkWork) != 0){
                return [
                    'error' => true,
                    'message' => __('Trạng thái đang được sử dụng cho dự án không thể xoá'),
                ];
            }
            
//            Xoá trạng thái
            $this->mManageProjectStatus->remove($data['manage_project_status_id']);

            $listConfig = $this->mManageProjectStatusConfig->getConfigByStatusId($data['manage_project_status_id']);

            $this->mManageProjectStatusConfig->deleteConfigByStatusId($data['manage_project_status_id']);
            $this->mManageProjectStatusConfigMap->deleteStatusByIdStatus($listConfig);

            if (count($listConfig) != 0) {
                $listConfig = collect($listConfig)->pluck('manage_project_status_group_config_id')->toArray();
                $this->mManageProjectStatusConfigMap->deleteConfigMapByConfig($listConfig);
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

            $mManageProjectStatusConfig = app()->get(ManageProjectStatusConfigTable::class);

            //            Check trạng thái hiện tại có phải là trạng thái next step
            $checkStatusConfig = $mManageProjectStatusConfig->checkStatusNextStep($data['idConfig']);

            if (count($checkStatusConfig) != 0){
                return [
                    'error' => true,
                    'message' => __('Trạng thái này đã được sử dụng. Bạn không thể thực hiện thao tác này')
                ];
            }

            $mManageProjectStatusConfig->updateConfig(['is_active' => $data['is_active']],$data['idConfig']);

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
