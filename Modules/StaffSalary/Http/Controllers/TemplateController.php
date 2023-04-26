<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 12/10/2022
 * Time: 14:22
 */

namespace Modules\StaffSalary\Http\Controllers;


use Illuminate\Http\Request;
use Modules\StaffSalary\Http\Requests\Template\StoreRequest;
use Modules\StaffSalary\Http\Requests\Template\AjaxCreateRequest;
use Modules\StaffSalary\Http\Requests\Template\UpdateRequest;
use Modules\StaffSalary\Repositories\Template\TemplateRepoInterface;

class TemplateController extends Controller
{
    protected $template;

    public function __construct(
        TemplateRepoInterface $template
    ) {
        $this->template = $template;
    }

    /**
     * Danh sách mẫu lương
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //Lấy data ds mẫu lương
        $data = $this->template->getList();

        return view('staff-salary::template.index', [
            'LIST' => $data['list'],
            'FILTER' => $this->filters(),
        ]);
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {

        return [];
    }

    /**
     * Ajax filter, phân trang mẫu lương
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search'
        ]);

        //Lấy data ds mẫu lương
        $data = $this->template->getList($filter);

        return view('staff-salary::template.list', [
            'LIST' => $data['list'],
            'page' => $filter['page']
        ]);
    }

    /**
     * View thêm mẫu lương
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        //Lấy data view tạo
        $data = $this->template->getDataCreate();

        return view('staff-salary::template.create', $data);
    }

    /**
     * View thêm mẫu lương
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function popupCreate()
    {
    
        //Lấy data view tạo
        $data = $this->template->getDataCreate();

        return [
            'action' => ['appendOrReplace','modal'],
            'appendOrReplace' => [
                '.popup-create' => view('staff-salary::template.popup-create', $data)->render(),
            ],
            'modal' => [
                '.popup-create' => 'show',
            ],
        ];

    }

    /**
     * Show pop thêm phụ cấp
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showPopCreateAllowanceAction()
    {
        $data = $this->template->getDataPopCreateAllowance();

        $html = \View::make('staff-salary::template.pop.create-allowance', $data)->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm mẫu lương
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $data = $this->template->store($request->all());

        return response()->json($data);
    }

    /**
     * Thêm mẫu lương
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxCreate(AjaxCreateRequest $request)
    {
        $data = $this->template->ajaxCreate($request->all());

        return response()->json($data);
    }

    /**
     * View chỉnh sửa mẫu lương
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        //Lấy data view chỉnh sửa
        $data = $this->template->getDataEdit($id);

        return view('staff-salary::template.edit', $data);
    }

    /**
     * Chỉnh sửa mẫu lương
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $data = $this->template->update($request->all());

        return response()->json($data);
    }

    /**
     * Cập nhật trạng thái
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusAction(Request $request)
    {
        $data = $this->template->updateStatus($request->all());

        return response()->json($data);
    }

    /**
     * Xoá mẫu lương
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $data = $this->template->destroy($request->all());

        return response()->json($data);
    }

    public function showModalTemplate(Request $request){
        $data = $this->template->getDataCreate();

        // return [
        //     'action' => ['appendOrReplace','modal'],
        //     'appendOrReplace' => [
        //         '.popup-create' => view('staff-salary::template.popup-create', $data)->render(),
        //     ],
        //     'modal' => [
        //         '.popup-create' => 'show',
        //     ],
        // ];
        $html = \View::make('staff-salary::template.popup-create',$data)->render();

        return response()->json([
            'html' => $html
        ]);
    }
}