<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerProject\Http\Controllers;

use Illuminate\Http\Request;
use Modules\ManagerProject\Repositories\ManagePhase\ManagePhaseInterfaceRepository;
use Modules\ManagerProject\Repositories\ManageProjectPhare\ManageProjectPhareRepositoryInterface;
use Modules\ManagerProject\Repositories\ManageProjectStaff\ManageProjectStaffRepositoryInterface;
use Modules\ManagerProject\Repositories\Project\ProjectRepositoryInterface;


class PhaseController extends Controller
{
    public function addAction($id, Request $request){
        $param = $request->all();
        $rManageProject = app()->get(ProjectRepositoryInterface::class);
        $rManageProjectStaff = app()->get(ManageProjectStaffRepositoryInterface::class);
        $rManagePhase = app()->get(ManagePhaseInterfaceRepository::class);
        $detailProject = $rManageProject->getItem($id);
        $listStaff = $rManageProjectStaff->getListStaff($id);

        $listPhase = [];

        if (isset($param['template'])){
            $listPhase = $rManagePhase->getListPhase($param['template']);
        }

        return view('manager-project::phase.add',[
            'detailProject' => $detailProject,
            'listStaff' => $listStaff,
            'listPhase' => $listPhase
        ]);
    }

    public function storeAction(Request $request){
        $rManageProjectPhase = app()->get(ManageProjectPhareRepositoryInterface::class);
        $param = $request->all();
        $data = $rManageProjectPhase->storePhase($param);
        return response()->json($data);
    }

    /**
     * Xóa phase
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function removeAction(Request $request){
        $rManageProjectPhase = app()->get(ManageProjectPhareRepositoryInterface::class);
        $param = $request->all();
        $data = $rManageProjectPhase->removeAction($param);
        return response()->json($data);
    }

    /**
     * Hiển thị popup cập nhật phase
     * @param Request $request
     */
    public function showPopup(Request $request){
        $rManageProjectPhase = app()->get(ManageProjectPhareRepositoryInterface::class);
        $param = $request->all();
        $data = $rManageProjectPhase->showPopup($param);
        return response()->json($data);
    }

    /**
     * Cập nhật phase
     * @param Request $request
     */
    public function updateAction(Request $request){
        $rManageProjectPhase = app()->get(ManageProjectPhareRepositoryInterface::class);
        $param = $request->all();
        $data = $rManageProjectPhase->updateAction($param);
        return response()->json($data);
    }

    /**
     * Tự động tạo phase cho tất cả dự án
     */
    public function autoCreatePhase(){
        $rManageProjectPhase = app()->get(ManageProjectPhareRepositoryInterface::class);
        $rManageProjectPhase->autoCreatePhase();
        return 'Hoàn thành';
    }

    /**
     * Giao diện Mẫu có sẵn
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function templateSample($id){
        $rManagePhase = app()->get(ManagePhaseInterfaceRepository::class);
        $listGroupPhase = $rManagePhase->getListGroupPhase();
        $listPhase = [];

        if (count($listGroupPhase) != 0){
            $listPhase = $rManagePhase->getListPhase($listGroupPhase[0]['manage_phase_group_code']);
        }

        return view('manager-project::phase.template-sample',[
            'listGroupPhase' => $listGroupPhase,
            'listPhase' => $listPhase,
            'manage_project_id' => $id
        ]);
    }

    /**
     * Thay đổi mẫu có sẵn
     * @param Request $request
     */
    public function changeSample(Request $request){
        $rManagePhase = app()->get(ManagePhaseInterfaceRepository::class);
        $param = $request->all();
        $data = $rManagePhase->changeSample($param);
        return response()->json($data);
    }

}