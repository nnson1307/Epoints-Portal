<?php

namespace Modules\FNB\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Http\Requests\Areas\AddAreasRequest;
use Modules\FNB\Http\Requests\Areas\EditAreasRequest;
use Modules\FNB\Repositories\Branch\BranchRepositoryInterface;
use Modules\FNB\Repositories\ConfigColumn\ConfigColumnRepositoryInterface;
use Modules\FNB\Repositories\FNBAreas\FNBAreasRepositoryInterface;
use Modules\FNB\Repositories\Staff\StaffRepositoryInterface;

class AreasController extends Controller
{
    private $areas;
    private $route = 'fnb.areas';

    public function __construct(FNBAreasRepositoryInterface $areas)
    {
        $this->areas = $areas;
    }

    public function index(){

        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $rBranch = app()->get(BranchRepositoryInterface::class);
        $rStaff = app()->get(StaffRepositoryInterface::class);

//        Lấy danh sách cấu hình hiển thị hoặc tạo mới nếu chưa có
        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$this->route);
        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }
//        Lấy danh sách chi nhánh
        $getListBranch = $rBranch->getAllBranch();
//        Lấy danh sách nhân viên
        $listStaff = $rStaff->getAll();

        $list = $this->areas->getList();
        return view('fnb::areas.index', [
            'listConfigStaff' => $listConfigStaff,
            'list' => $list,
            'getListBranch' => $getListBranch,
            'listStaff' => $listStaff
        ]);
    }

    public function list(Request $request){
        $param = $request->all();
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);

//        Lấy danh sách cấu hình hiển thị hoặc tạo mới nếu chưa có
        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$this->route);

        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }

        $list = $this->areas->getList($param);

        return view('fnb::areas.list', [
            'listConfigStaff' => $listConfigStaff,
            'list' => $list
        ]);
    }
    public function allAreas(Request $request){
        $param = $request->all();
        $listAll = $this->areas->getAllAreas();
        return  $listAll;
    }

    /**
     * Hiển thị popup cấu hình hiển thị
     */
    public function showPopupConfig(Request $request){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $param = $request->all();
        $param['route'] = $this->route;
        $data = $rConfigColumn->showColumn($param);
        return \response()->json($data);
    }

    /**
     * Lưu cấu hình
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function saveConfig(Request $request){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $param = $request->all();
        $param['route'] = $this->route;
        $data = $rConfigColumn->saveConfig($param);
        return \response()->json($data);
    }
    public function showPopup(Request $request){
        $param = $request->all();

        $data = $this->areas->showPopup($param);

        return \response()->json($data);
    }
    public function createAreas(AddAreasRequest $request){
        $input = $request->all();
        $areas = app()->get(FNBAreasRepositoryInterface::class);
        $rAreas = $areas->createAreas($input);
        return \response()->json($rAreas);
    }

    public function editAreas(EditAreasRequest $request){
        $input = $request->all();
        $areas = app()->get(FNBAreasRepositoryInterface::class);
        $rAreas = $areas->editAreas($input);
        return \response()->json($rAreas);
    }
    public function deleteAreas(Request $request)
    {
        $input = $request->all();
        $areas = app()->get(FNBAreasRepositoryInterface::class);
        $rDelAreas = $areas->deleteAreas($input);
        return \response()->json($rDelAreas);

    }
    public function export(){
        $data['route'] = $this->route;
        return $this->areas->export($data);

    }


}
