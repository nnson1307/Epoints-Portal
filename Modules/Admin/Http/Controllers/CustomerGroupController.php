<?php
/**
 * Created by PhpStorm.
 * User: thach le viet
 * Date: 13/03/2018
 * Time: 1:21 CH
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\CustomerGroup\CustomerGroupRepositoryInterface;

class CustomerGroupController extends Controller
{
    /**
     * @var Service Package Repository Interface
     */
    protected $customGroup;

    public function __construct(CustomerGroupRepositoryInterface $customGroup)
    {
        $this->customGroup = $customGroup;
    }

    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $customGroupList = $this->customGroup->list();
        return view('admin::customer-group.index', [
            'LIST' => $customGroupList,
            'FILTER' => $this->filters(),
        ]);
    }

    // FUNCTION  FILTER LIST ITEM
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

    /**
     * Ajax list customer-group
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $customGroupList = $this->customGroup->list($filters);
        return view('admin::customer-group.list', [
                'LIST' => $customGroupList,
                'page' => $filters['page']
            ]
        );
    }

    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request)
    {
        $name = $request->group_name;
        $test = $this->customGroup->testGroupName($name);
        if ($this->customGroup->testIsDeleted($name) != null) {
            $this->customGroup->editByName($name);
            return response()->json(['status' => 1]);
        } else {
            if ($test == null) {
                $data = [
                    'group_name' => $name,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'slug'=>str_slug($name)
                ];
                //Insert customer group
                $groupId = $this->customGroup->add($data);
                //Update group_uuid
                $this->customGroup->edit([
                    'group_uuid' => 'CUSTOMER_GROUP_' . date('dmY') . sprintf("%02d", $groupId)
                ], $groupId);
                return response()->json(['status' => 1]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->idCustomerGroup;
            $item = $this->customGroup->getItem($id);
            $jsonString = [
                "customer_group_id" => $item->customer_group_id,
                "group_name" => $item->group_name,
                "is_actived" => $item->is_actived
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $name = $request->group_name;
            $isActive = $request->is_actived;
            $testIsDeleted = $this->customGroup->testIsDeleted($name);
            $testName = $this->customGroup->testName($name, $id);
            if ($request->parameter == 0) {
                if ($testIsDeleted != null) {
                    //Tồn tại tên nhóm khách hàng trong db. is_deleted = 1.
                    return response()->json(['status' => 2]);
                } else {
                    if ($testName == null) {
                        $data = [
                            'updated_by' => Auth::id(),
                            'group_name' => $name,
                            'is_actived' => $isActive,
                            'slug'=>str_slug($name)
                        ];
                        $this->customGroup->edit($data, $id);
                        return response()->json(['status' => 1]);
                    } else {
                        return response()->json(['status' => 0]);
                    }
                }
            } else {
                //Kích hoạt lại tên nhóm khách hàng.
                $this->customGroup->edit(['is_deleted' => 0], $testIsDeleted->customer_group_id);
                return response()->json(['status' => 3]);
            }
        }
    }

    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_actived'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->customGroup->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => __('Trạng thái đã được cập nhật')
        ]);
    }

    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->customGroup->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }
}