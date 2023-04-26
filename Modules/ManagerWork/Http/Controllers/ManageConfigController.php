<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerWork\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerWork\Repositories\ManageConfig\ManageConfigRepositoryInterface;


class ManageConfigController extends Controller
{
    protected $manageConfigRepo;

    public function __construct(
        ManageConfigRepositoryInterface $manageConfigRepo
    )
    {
         $this->manageConfigRepo = $manageConfigRepo;
    }

    /**
     * Trang thông tin cấu hình quyền
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexRoleAction()
    {
        $listRole = $this->manageConfigRepo->getListRole();

        return view('manager-work::config-role.index', [
            'listRole' => $listRole
        ]);
    }

    /**
     * Trang thông tin cấu hình quyền
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexRoleEditAction()
    {
        $listRole = $this->manageConfigRepo->getListRole();

        return view('manager-work::config-role.edit', [
            'listRole' => $listRole
        ]);
    }

    /**
     * Truyền thông tin cập nhật quyền chỉnh sửa
     * @param Request $request
     */
    public function updateRoleAction(Request $request){
        $param = $request->all();

        $updateAction = $this->manageConfigRepo->updateAction($param);

        return response()->json($updateAction);
    }

    /**
     * Trang cấu hình trạng thái
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexStatusAction(){

        $listStatus = $this->manageConfigRepo->getListStatus();

        $listStatusSelect = $this->manageConfigRepo->getAllConfig();

        return view('manager-work::config-status.index', [
            'listStatus' => $listStatus,
            'listStatusSelect' => $listStatusSelect
        ]);
    }

    /**
     * Trang cấu hình trạng thái chỉnh sửa
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexStatusEditAction(){

        $listStatus = $this->manageConfigRepo->getListStatus();
//        $listStatusSelect = $this->manageConfigRepo->getListStatusSelect();
        $listStatusSelect = $this->manageConfigRepo->getAllConfig();

        return view('manager-work::config-status.edit', [
            'listStatus' => $listStatus,
            'listStatusSelect' => $listStatusSelect
        ]);
    }

    /**
     * Thêm cấu hình trạng thái
     */
    public function addStatusConfig(Request $request){
        $param = $request->all();
        $addStatusConfig = $this->manageConfigRepo->addStatusConfig($param);
        return response()->json($addStatusConfig);
    }

    /**
     * Cập nhật cấu hình trạng thái
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateConfigStatus(Request $request){
        $param = $request->all();
        $updateConfigStatus = $this->manageConfigRepo->updateConfigStatus($param);
        return response()->json($updateConfigStatus);
    }

    /**
     * Giao diện cấu hình thông báo quản lý công việc
     */
    public function indexNotificationAction(){
        $listNotiConfig = $this->manageConfigRepo->getListNotiConfig();
        return view('manager-work::config-noti.index', [
            'listNotiConfig' => $listNotiConfig
        ]);
    }

    /**
     * Giao diện chỉnh sửa cấu hình thông báo quản lý công việc
     */
    public function editNotificationAction(){
        $listNotiConfig = $this->manageConfigRepo->getListNotiConfig();
        return view('manager-work::config-noti.edit', [
            'listNotiConfig' => $listNotiConfig
        ]);
    }

    /**
     * Hiển thị popup cập nhật nội dung thông báo
     * @param Request $request
     */
    public function showPopup(Request $request){
        $param = $request->all();
        $showPopup = $this->manageConfigRepo->showPopup($param);
        return response()->json($showPopup);
    }

    /**
     * Cập nhật cấu hình noti
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateNotification(Request $request){
        $param = $request->all();
        $updateNotification = $this->manageConfigRepo->updateNotification($param);
        return response()->json($updateNotification);
    }

    /**
     * Xoá cấu hình trạng thái
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeStatusConfig(Request $request){
        $param = $request->all();
        $removeStatusConfig = $this->manageConfigRepo->removeStatusConfig($param);
        return response()->json($removeStatusConfig);
    }

    /**
     * Cập nhật trạng thái hoạt động theo cấu hình
     * @param Request $request
     */
    public function updateActive(Request $request){
        $param = $request->all();
        $removeStatusConfig = $this->manageConfigRepo->updateActive($param);
        return response()->json($removeStatusConfig);
    }

}