<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 4:04 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Unit\UnitRepositoryInterface;

class UnitController extends Controller
{
    protected $unit;

    public function __construct(UnitRepositoryInterface $units)
    {
        $this->unit = $units;
    }

    //View index
    public function indexAction()
    {
        $un = $this->unit->list();
        return view('admin::unit.index', [
            'LIST' => $un,
            'FILTER' => $this->filters()
        ]);
    }

    //Filter
    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    //function view list
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $unitList = $this->unit->list($filter);
        return view('admin::unit.list', [
            'LIST' => $unitList,
            'page'=>$filter['page']
        ]);
    }

    //function add
    public function submitAddAction(Request $request)
    {
        $name = $request->name;
        $test = $this->unit->testName(str_slug($name), 0);
        if ($test == '') {
            $data=[
                'name'=>$request->name,
                'slug'=>str_slug($request->name),
                'is_standard'=>$request->is_standard,
                'is_actived'=>$request->is_actived,
                'created_by'=>Auth::id()
            ];
            $id = $this->unit->add($data);
            $unitOption = $this->unit->getAll();
            return response()->json(
                [
                    'status'     => '',
                    'close'      => $request->close,
                    'unitOption' => $unitOption,
                    'id'         => $id,
                ]
            );
        } else {
            return response()->json(['status' => __('Đơn vị tính đã tồn tại')]);
        }
    }

    //function edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->unit->getItem($id);
        $data = [
            'name' => $item->name,
            'unit_id' => $item->unit_id,
            'is_standard' => $item->is_standard,
            'is_actived' => $item->is_actived
        ];
        return response()->json($data);
    }

    //function submit edit
    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $test = $this->unit->testName(str_slug($name), $id);
        if ($test == null) {
            $data = [
                'name' => $request->name,
                'slug'=>str_slug($request->name),
                'is_standard' => $request->is_standard,
                'is_actived' => $request->is_actived
            ];
            $data['updated_by'] = Auth::id();
            $this->unit->edit($data, $id);

            return response()->json(['status' => '']);
        } else {
            return response()->json(['status' => 'Đơn vị tính đã tồn tại']);
        }
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->unit->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    //function remove
    public function removeAction($id)
    {
        $this->unit->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
}