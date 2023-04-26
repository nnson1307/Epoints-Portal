<?php
/**
 * Created by PhpStorm.
 * User: Sinh
 * Date: 3/17/2018
 * Time: 2:01 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Http\Requests\Department\StoreRequest;
use Modules\Admin\Http\Requests\Department\UpdateRequest;
use Modules\Admin\Repositories\Department\DepartmentRepositoryInterface;

class DepartmentController extends Controller
{
    /**
     * @var DepartmentRepositoryInterface
     */

    protected $department;

    public function __construct(DepartmentRepositoryInterface $department)
    {
        $this->department = $department;
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'is_inactive' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    public function indexAction(Request $request)
    {
        $departmentList = $this->department->list();
        return view('admin::department.index', [
            'LIST' => $departmentList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     *
     *
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_inactive']);
        $departmentList = $this->department->list($filters);
        return view('admin::department.list',
            [
                'LIST' => $departmentList,
                'FILTER' => $this->filters(),
                'page' => $filters['page']
            ]);
    }

    /**
     * Show modal thêm phòng ban
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public  function showPopupAddAction()
    {
        //Lấy data view thêm
        $data = $this->department->getDataCreate();

        $html = \View::make('admin::department.add', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Add staff department
     */
    public function addAction(Request $request)
    {
        if ($request->ajax()) {
            $name = $request->departmentName;
            $check = $this->department->check($name);
            if ($this->department->testIsDeleted($name) != null) {
                $this->department->editByName($name);
                return response()->json(['status' => 1]);
            } else {
                if ($check == null) {
                    $data = [
                        'department_name' => $name,
                        'is_inactive' => $request->isInActive,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'slug'=>str_slug($name)
                    ];
                    $this->department->add($data);
                    $option = $this->department->getStaffDepartmentOption();
                    return response()->json([
                        'status' => 1,
                        'optionDepartment' => $option
                    ]);
                } else {
                    return [
                        'error' => false,
                        'message' => 'Tạo Thành công'
                    ];
                }
            }
        }
    }



    public function add(StoreRequest $request)
    {
        $param = $request->all();
        $add = $this->department->add($param);
        return $add;
    }

    /**
     * Edit staff department
     */
    /**
     * Edit staff department
     */
    public function editAction(Request $request)
    {
        //Lấy data view thêm
        $data = $this->department->getDataEdit($request->id);

        $html = \View::make('admin::department.edit', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Chỉnh sửa phòng ban
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitEditAction(UpdateRequest $request)
    {
        $data = $this->department->update($request->all());

        return response()->json($data);
    }

    /**
     * Delete staff department
     */
    public function removeAction($id)
    {

        $this->department->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_inactive'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->department->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => __('Trạng thái đã được cập nhật')
        ]);
    }
}