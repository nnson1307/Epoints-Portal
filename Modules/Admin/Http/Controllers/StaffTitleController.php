<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Ngoc Son
 * Date: 3/17/2018
 * Time: 1:19 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\CodeGenerator\CodeGeneratorRepositoryInterface;
use Modules\Admin\Repositories\StaffTitle\StaffTitleRepositoryInterface;

class StaffTitleController extends Controller
{
    protected $stafftitle;
    protected $code;
    protected $action;

    public function __construct(
        StaffTitleRepositoryInterface $stafftitle,
        CodeGeneratorRepositoryInterface $code
    )
    {
        $this->stafftitle = $stafftitle;
        $this->code = $code;
    }

//    return view index
    public function indexAction()
    {
        $titleList = $this->stafftitle->list();
        return view('admin::staff-title.index', [
            'LIST' => $titleList,
            'FILTER' => $this->filters()
        ]);
    }

    //Function filters
    protected function filters()
    {
        return [
            'is_active' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách staff title
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $staffTitleList = $this->stafftitle->list($filters);
        return view('admin::staff-title.list', ['LIST' => $staffTitleList]);

    }

    //Function add
    public function addAction()
    {
        return view('admin::staff-title.add');
    }

    //Function submit form add
    public function submitAddAction(Request $request)
    {

        $test = $this->stafftitle->testName($request->staffTitleName);
        if ($this->stafftitle->testIsDeleted($request->staffTitleName) != null) {
            $this->stafftitle->editByName($request->staffTitleName);
            return response()->json(['status' => 1]);
        } else {
            if ($test == null) {
                $data = [
                    'staff_title_name' => $request->staffTitleName,
                    'staff_title_description' => $request->staffTitleDescription,
                    'slug'=>str_slug($request->staffTitleName)
                ];
                $id = $this->stafftitle->add($data);
                $code = '';
                if ($id < 10) ;
                {
                    $code = '0' . $id;
                }
                $this->stafftitle->edit(['staff_title_code' => $this->code->codeDMY('CV', $code)], $id);
                $option = $this->stafftitle->getStaffTitleOption();
                return response()->json([
                    'status' => 1,
                    'optionStaffTitle' => $option
                ]);
            } else {
                return response()->json(['status' => 0]);
            }
        }
    }

    //Function remove
    public function removeAction($id)
    {

        $this->stafftitle->remove($id);
        return response()->json([
            'status' => 0,
            'message' => 'Remove success'
        ]);
    }

    //Function edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->stafftitle->getEdit($id);
        return response()->json([
            'staff_title_name' => $item['staff_title_name'],
            'staff_title_description' => $item['staff_title_description'],
            'is_active' => $item['is_active'],
            'id' => $item['staff_title_id']
        ]);
    }

    //Function submit form edit
    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $staffTitleName = $request->staffTitleName;
        $staffTitleDescription = $request->staffTitleDescription;
        $isActive = $request->is_actived;

        $testIsDeleted = $this->stafftitle->testIsDeleted($staffTitleName);
        $testName = $this->stafftitle->testNameId($staffTitleName, $id);

        if ($request->parameter == 0) {
            if ($testIsDeleted != null) {
                //Tồn tại tên chức vụ trong db. is_deleted = 1.
                return response()->json(['status' => 2]);
            } else {
                if ($testName == null) {
                    $data = [
                        'staff_title_name' => $staffTitleName,
                        'staff_title_description' => $staffTitleDescription,
                        'is_active' => $isActive,
                        'slug'=>str_slug($staffTitleName)
                    ];
                    $this->stafftitle->edit($data, $id);
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 0]);
                }
            }
        } else {
            //Kích hoạt lại tên chức vụ.
            $this->stafftitle->edit(['is_delete' => 0], $testIsDeleted->staff_title_id);
            return response()->json(['status' => 3]);
        }
    }

    //Function thay đổi status
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->stafftitle->edit($data, $params['id']);
        return response()->json([
            'status' => 0,

        ]);
    }


}