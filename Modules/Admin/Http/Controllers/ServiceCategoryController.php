<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/12/2018
 * Time: 10:19 AM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\ServiceCategory\ServiceCategoryRepositoryInterface;

class ServiceCategoryController extends Controller
{
    protected $service_category;

    public function __construct(ServiceCategoryRepositoryInterface $service_categories)
    {
        $this->service_category = $service_categories;
    }

    public function indexAction()
    {
        $get = $this->service_category->list();

        return view('admin::service-category.index', [
            'LIST' => $get,
            'FILTER' => $this->filters(),

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

    //function view list
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $list = $this->service_category->list($filter);
        return view('admin::service-category.list', ['LIST' => $list, 'page' => $filter['page']]);
    }

    //function add
    public function addAction(Request $request)
    {
        $name = $request->name;
        $test = $this->service_category->testName(str_slug($name), 0);
        if ($test == null) {
            $data = [
                'name' => $name,
                'slug' => str_slug($name),
                'description' => $request->description,
                'is_actived' => 1,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
            $this->service_category->add($data);
            $option = $this->service_category->getOptionServiceCategory();
            return response()->json([
                'status' => 1,
                'close' => $request->close,
                'optionCategory' => $option
            ]);
        } else {
            return response()->json(['status' => 0]);
        }

    }

    //Thay đổi status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->service_category->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    //Function xóa
    public function removeAction($id)
    {
        $this->service_category->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function get edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->service_category->getItem($id);
        $data = [
            'service_category_id' => $item->service_category_id,
            'name' => $item->name,
            'description' => $item->description,
            'is_actived' => $item->is_actived
        ];
        return response()->json($data);
    }

    //function submit edit
    public function submitEditAction(Request $request)
    {
        $id = $request->id;
        $name = $request->name;
        $test = $this->service_category->testName(str_slug($name), $id);
        if (!isset($test['name'])) {
            $data = [
                'name' => $name,
                'slug'=>str_slug($name),
                'description' => $request->description,
                'is_actived' => $request->is_actived,
                'updated_by' => Auth::id(),
            ];
            $this->service_category->edit($data, $id);
            return response()->json([
                'status' => 1
            ]);
        } else {
            return response()->json([
                'status' => 0
            ]);
        }
    }
}