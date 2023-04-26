<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/22/2019
 * Time: 1:47 PM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\RoleGroup\RoleGroupRepositoryInterface;

class RoleGroupController extends Controller
{
    protected $roleGroup;

    public function __construct(RoleGroupRepositoryInterface $roleGroup)
    {
        $this->roleGroup = $roleGroup;
    }

    //View index
    public function indexAction()
    {

        $roleGroup = $this->roleGroup->list();

        return view('admin::role-group.index', [
            'LIST' => $roleGroup,
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

    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'is_actived', 'search']);

        $roleGroup = $this->roleGroup->list($filter);
        return view('admin::role-group.list', [
            'LIST' => $roleGroup,
            'page' => $filter['page']
        ]);
    }

    public function submitAddAction(Request $request)
    {
        $name = $request->name;
        $checkName = $this->roleGroup->checkName(str_slug($name), 0);

        if ($checkName == null) {
            $data = [
                'name' => $name,
                'slug' => str_slug($name),
                'is_actived' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->roleGroup->add($data);
            return response()->json(['status' => 1]);
        } else {
            return response()->json(['status' => 0]);
        }

    }

    //Thay đổi status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();

        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->roleGroup->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    public function editAction(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->id;
            $item = $this->roleGroup->getItem($id);

            $jsonString = [
                "name" => $item['name'],
                "is_actived" => $item['is_actived']
            ];
            return response()->json($jsonString);
        }
    }

    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $isActived = $request->is_actived;
        $checkName = $this->roleGroup->checkName(str_slug($name), $id);

        if ($checkName != null) {
            return response()->json(['status' => 0]);
        } else {
            $data = [
                'name' => $name,
                'slug' => str_slug($name),
                'is_actived' => $isActived,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            $this->roleGroup->edit($data,$id);
            return response()->json(['status' => 1]);
        }
    }
}