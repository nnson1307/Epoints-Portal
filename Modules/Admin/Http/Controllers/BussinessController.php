<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 20/3/2019
 * Time: 15:25
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Bussiness\BussinessRepositoryInterface;

class BussinessController extends Controller
{
    protected $bussiness;

    public function __construct(BussinessRepositoryInterface $bussiness)
    {
        $this->bussiness = $bussiness;
    }

    public function indexAction()
    {

        $list = $this->bussiness->list();
        return view('admin::bussiness.index', [
            'LIST' => $list,
            'FILTER' => $this->filters()
        ]);
    }

    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => 'Chọn trạng thái',
                    1 => 'Hoạt động',
                    0 => 'Tạm ngưng'
                ]
            ]

        ];
    }

    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'is_actived', 'search_bussiness']);
        $list = $this->bussiness->list($filter);
        return view('admin::bussiness.list', [
            'LIST' => $list,
            'page' => $filter['page']
        ]);
    }

    public function submitAddAction(Request $request)
    {
        $test = $this->bussiness->testName($request->name, '0');
        if($test == null)
        {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'is_actived' => 1,
                'created_by' => Auth::id()
            ];
            $this->bussiness->add($data);
            return response()->json([
                'success' => 1,
                'message' => 'Thêm ngành nghề thành công',
                'type' => $request->type
            ]);
        }else{
            return response()->json([
                'success' => 0,
                'message' => 'Thêm ngành nghề thất bại'
            ]);
        }

    }

    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->bussiness->getItem($id);
        return response()->json([
            'item' => $item
        ]);
    }
    public function submitEditAction(Request $request)
    {
        $test = $this->bussiness->testName($request->name, $request->id);
        if($test == null)
        {
            $data=[
                'name'=>$request->name,
                'description' => $request->description,
                'is_actived' => $request->is_actived,
                'updated_by'=>Auth::id()
            ];
            $this->bussiness->edit($data,$request->id);
            return response()->json([
                'success'=>1,
                'message'=>'Cập nhật ngành nghề kinh doanh thành công'
            ]);
        }else{
            return response()->json([
                'success' => 0,
                'message' => 'Cập nhật ngành nghề thất bại'
            ]);
        }

    }
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->bussiness->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function removeAction($id)
    {
        $this->bussiness->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
}