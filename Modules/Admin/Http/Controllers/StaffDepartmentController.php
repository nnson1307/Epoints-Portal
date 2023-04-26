<?php
/**
 * Created by PhpStorm.
 * User: Sinh
 * Date: 3/17/2018
 * Time: 2:01 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\StaffDepartment\StaffDepartmentRepositoryInterface;

class StaffDepartmentController extends Controller
{
    /**
     * @var StaffDepartmentRepositoryInterface
     */

    protected $staffDepartment;

    public function __construct(StaffDepartmentRepositoryInterface $staffDepartment)
    {
        $this->staffDepartment = $staffDepartment;
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'is_active' => [
                'text' => __('Trạng thái'),
                'data' => [
                    '' => 'Tất cả',
                    1 => 'Hoạt động',
                    0 => 'Tạm ngưng'
                ]
            ]
        ];
    }

    public function indexAction(Request $request)
    {
        $staffDepartmentList = $this->staffDepartment->list();
        return view('admin::staff-department.index', [
            'LIST' => $staffDepartmentList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     *
     *
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $staffDepartmentList = $this->staffDepartment->list($filters);
        return view('admin::staff-department.list',
            [
                'LIST' => $staffDepartmentList,
                'FILTER' => $this->filters()
            ]);
    }

    /**
     * Add staff department
     */
    public function addAction()
    {
        return view('admin::staff-department.add');
    }

    public function submitAddAction(Request $request)
    {
        $data = $this->validate($request, [
            'staff_department_name' => 'required|unique:staff_department',
            'staff_department_code' => 'required|unique:staff_department',
            'is_active' => 'integer'
        ], [
            'staff_department_name.required' => 'Vui lòng nhập tên phòng ban',
            'staff_department_code.required' => 'Vui lòng nhập mã phòng ban',
            'staff_department_name.unique' => 'Tên phòng ban đã tồn tại',
            'staff_department_code.unique' => 'Mã phòng ban đã tồn tại',
        ]);
        $oStaffDepartment = $this->staffDepartment->add($data);
        if ($oStaffDepartment) {
            $request->session()->flash('status', 'Tạo phòng ban thành công');
        }

        //Return view index.
        return redirect()->route('admin.staff-department');
    }

    /**
     * Edit staff department
     */
    public function editAction($id)
    {
        $item = $this->staffDepartment->getItem($id);
        return view('admin::staff-department.edit', [
            'TITLE' => 'Cập nhật phòng ban',
            'item' => $item
        ]);
    }

    public function submitEditAction(Request $request, $id)
    {


        $data = $this->validate($request, [
            'staff_department_name' => 'required|unique:staff_department,staff_department_name,' . $id . ",staff_department_id",
            'staff_department_code' => 'required|unique:staff_department,staff_department_code,' . $id . ",staff_department_id",
            'is_active' => 'integer'
        ], [
            // custom info messages
            'staff_department_name.required' => 'Vui lòng nhập phòng ban',
            'staff_department_code.required' => 'Vui lòng nhập mã phòng ban',
            'staff_department_code.unique' => 'Mã phòng ban đã tồn tại',
            'staff_department_name.unique' => 'Tên phòng ban đã tồn tại'
        ]);

        $oStaffDepartment = $this->staffDepartment->edit($data, $id);
        if ($oStaffDepartment) {
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        } else {
            Session::flash('messages', "Giá trị vừa nhập đã tồn tại");
            return redirect()->back();
        }
        // return to view index
        return redirect()->route('admin.staff-department');
    }

    /**
     * Delete staff department
     */
    public function removeAction($id)
    {

        $this->staffDepartment->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->staffDepartment->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => 'Trạng thái đã được cập nhật '
        ]);
    }
}