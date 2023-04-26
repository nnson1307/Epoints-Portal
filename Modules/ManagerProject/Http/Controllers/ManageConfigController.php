<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerProject\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerProject\Repositories\ManageConfig\ManageConfigRepositoryInterface;


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
     * Trang cấu hình trạng thái
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexStatusAction(){

        $listStatus = $this->manageConfigRepo->getListStatus();
        $listStatusSelect = $this->manageConfigRepo->getListStatusSelect();

        return view('manager-project::config-status.index', [
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
        $listStatusSelect = $this->manageConfigRepo->getListStatusSelect();

        return view('manager-project::config-status.edit', [
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