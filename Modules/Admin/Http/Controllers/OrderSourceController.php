<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 10:48 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\OrderSource\OrderSourceRepositoryInterface;

class OrderSourceController extends Controller
{
    protected $orderSource;

    public function __construct(OrderSourceRepositoryInterface $orderSource)
    {
        $this->orderSource = $orderSource;
    }

    public function indexAction()
    {
        $orderSourceList = $this->orderSource->list();
        return view('admin::order-source.index', [
            'LIST' => $orderSourceList,
            'FILTER' => $this->filters()
        ]);
    }

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

    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $orderSourceList = $this->orderSource->list($filters);
        return view('admin::order-source.list', ['LIST' => $orderSourceList]);
    }

    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->orderSourceName;
            $test = $this->orderSource->check($name);
            if ($this->orderSource->testIsDeleted($name) != null) {
                $this->orderSource->editByName($name);
                return response()->json(['status' => 1]);
            } else {
                if ($test == null) {
                    $data = [
                        'order_source_name' => $request->orderSourceName,
                        'is_actived' => $request->isActived,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'slug'=>str_slug($request->orderSourceName)
                    ];
                    $this->orderSource->add($data);
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 0]);
                }
            }
        }

    }

    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->orderSourceId;
            $item = $this->orderSource->getItem($id);
            $jsonString = [
                "order_source_name" => $item->order_source_name,
                "is_actived" => $item->is_actived,
                "id" => $id,
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $name = $request->orderSourceName;
        $testIsDeleted = $this->orderSource->testIsDeleted($name);
        $test = $this->orderSource->checkEdit($id, $name);

        if ($request->parameter == 0) {
            if ($testIsDeleted != null) {
                //Tồn tại tên nguồn đơn hàng trong db. is_deleted = 1.
                return response()->json(['status' => 2]);
            } else {
                if ($test == null) {
                    $data = [
                        'updated_by' => Auth::id(),
                        'order_source_name' => $name,
                        'is_actived' => $request->isActive,
                        'slug'=>str_slug($name)
                    ];
                    $this->orderSource->edit($data, $id);
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 0]);
                }
            }
        } else {
            //Kích hoạt lại nguồn đơn hàng.
            $this->orderSource->edit(['is_deleted' => 0], $testIsDeleted->order_source_id);
            return response()->json(['status' => 3]);
        }
    }

    public function removeAction($id)
    {
        $this->orderSource->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->orderSource->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}