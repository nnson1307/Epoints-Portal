<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Modules\Admin\Models\StoreTable;
use Modules\Admin\Repositories\BranchImage\BranchImageRepositoryInterface;
use Modules\Admin\Repositories\Province\ProvinceRepositoryInterface;
use Modules\Admin\Repositories\District\DistrictRepositoryInterface;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;


class BranchController extends Controller
{
    protected $branches;
    protected $province;
    protected $district;
    protected $branch_image;

    public function __construct(BranchRepositoryInterface $branch,
                                ProvinceRepositoryInterface $provinces,
                                DistrictRepositoryInterface $districts,
                                BranchImageRepositoryInterface $branch_images)
    {
        $this->branches = $branch;
        $this->province = $provinces;
        $this->district = $districts;
        $this->branch_image = $branch_images;
    }

    //View index
    public function indexAction()
    {

        $branch = $this->branches->list();
        return view('admin::branch.index', [
            'LIST' => $branch,
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

    /**
     * Ajax danh sách Branches
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword',
            'is_actived', 'search']);
        $branchList = $this->branches->list($filter);
        return view('admin::branch.list', [
            'LIST' => $branchList,
            'page' => $filter['page']
        ]);
    }

    public function addAction()
    {
        $optionProvince = $this->province->getOptionProvince();
        return view('admin::branch.add', [
            'optionProvince' => $optionProvince
        ]);
    }

    public function loadDistrictAction(Request $request)
    {
        $id_province = $request->id_province;
        $district = $this->district->getOptionDistrict($id_province);

        $data = [];
        foreach ($district as $key => $value) {
            $data[] = [
                'id' => $value['id'],
                'name' => $value['name'],
                'type' => $value['type']
            ];
        }

        return response()->json([
            'optionDistrict' => $data
        ]);
    }

    public function uploadDropzoneAction(Request $request)
    {
        $time = Carbon::now();
        $image = $request->file('file');
        $extension = $image->getClientOriginalExtension();
        $filename = $image->getClientOriginalName();
        //$filename = time() . str_random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . time() . "." . $extension;
        $upload_success = $image->storeAs(TEMP_PATH, $filename, 'public');
        if ($upload_success) {
            return response()->json($filename, 200);
        } else {
            return response()->json('error', 400);
        }
    }

    public function deleteImageAction(Request $request)
    {
        Storage::disk("public")->delete(TEMP_PATH . '/' . $request->input('filename'));
        return response()->json(['success' => '1']);
    }


    /**
     * Thêm chi nhánh
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAddAction(Request $request)
    {
        $data = $this->branches->add($request->all());

        return $data;
    }

    //Function xóa
    public function removeAction($id)
    {
        $this->branches->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //Load dữ liệu khi edit
    public function editAction($id)
    {
        $item = $this->branches->getItem($id);
        $list_img=$this->branch_image->getItem($id);
        $optionProvice = $this->province->getOptionProvince();
        return view('admin::branch.edit', [
            'branch' => $item,
            'optionProvince' => $optionProvice,
            'list_img'=>$list_img
        ]);
    }

    /**
     * Chỉnh sửa chi nhánh
     *
     * @param Request $request
     * @return mixed
     */
    public function submitEditAction(Request $request)
    {
        $data = $this->branches->edit($request->all());

        return $data;
    }

    //Thay đổi status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->branches->changeStatus($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }
}