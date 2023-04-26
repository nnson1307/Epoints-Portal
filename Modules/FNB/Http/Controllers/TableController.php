<?php

namespace Modules\FNB\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\FNB\Http\Requests\Table\AddTableRequest;
use Modules\FNB\Repositories\FNBAreas\FNBAreasRepositoryInterface;
use Modules\FNB\Repositories\FNBTable\FNBTableRepositoryInterface;
use Modules\FNB\Repositories\Staff\StaffRepositoryInterface;
use Modules\FNB\Repositories\Branch\BranchRepositoryInterface;
use Modules\FNB\Repositories\ConfigColumn\ConfigColumnRepositoryInterface;

class TableController extends Controller
{
    private $table;
    private $route = 'fnb.table';

    public function __construct(FNBTableRepositoryInterface $table)
    {
        $this->table= $table;
    }
    public function index(){
        $rConfigColumn = app()->get(ConfigColumnRepositoryInterface::class);
        $rBranch = app()->get(BranchRepositoryInterface::class);
        $rStaff = app()->get(StaffRepositoryInterface::class);
        $areas = app()->get(FNBAreasRepositoryInterface::class);
//        Lấy danh sách cấu hình hiển thị hoặc tạo mới nếu chưa có

        $listConfigStaff = $rConfigColumn->getAllConfigStaff(Auth::id(),$this->route);

        if (count($listConfigStaff) != 0){
            $listConfigStaff = collect($listConfigStaff)->groupBy('type');
        }

//        Lấy danh sách chi nhánh
        $getListBranch = $rBranch->getAllBranch();

//        Lấy danh sách khu vực
        $getListAreas = $areas->getAllAreas();


//        Lấy danh sách nhân viên
        $listStaff = $rStaff->getAll();
        $list = $this->table->getList();
        return view('fnb::table.index', [
            'listConfigStaff' => $listConfigStaff,
            'list' => $list,
            'getListBranch' => $getListBranch,
            'listAreas' => $getListAreas,
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

        $list = $this->table->getList($param);

        return view('fnb::table.list', [
            'listConfigStaff' => $listConfigStaff,
            'list' => $list
        ]);
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

        $data = $this->table->showPopup($param);

        return \response()->json($data);
    }
    public function createTable(AddTableRequest $request){
        $dataTable = $request->all();
        $rTable= app()->get(FNBTableRepositoryInterface::class);
        $data = $rTable->createTable($dataTable);
        return \response()->json($data);
    }
    public function editTable(Request $request){
        $dataEditTable = $request->all();
        $rTable = app()->get(FNBTableRepositoryInterface::class);
        $data = $rTable->editTable($dataEditTable);
        return \response()->json($data);
    }
    public function deleteTable(Request $request){
        $input = $request->all();
        $rTable = app()->get(FNBTableRepositoryInterface::class);
        $data = $rTable -> deleteTable($input);
        return \response()->json($data);
    }
    public function export(){
        $data['route'] = $this->route;
        return $this->table->export($data);
    }

}
