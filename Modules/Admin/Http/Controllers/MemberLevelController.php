<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/03/2018
 * Time: 2:33 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\MemberLevel\MemberLevelRepositoryInterface;

class MemberLevelController extends Controller
{
    protected $memberLevel;

    public function __construct(MemberLevelRepositoryInterface $memberLevel)
    {
        $this->memberLevel = $memberLevel;
    }

    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction(Request $request)
    {
        $memberLevelList = $this->memberLevel->list();
        return view('admin::member-level.index', [
            'LIST' => $memberLevelList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Khai báo filter
     *
     * @return array
     */
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
     * Ajax danh sách user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $memberLevelList = $this->memberLevel->list($filters);
        return view('admin::member-level.list', [
            'LIST' => $memberLevelList,
            'page' => $filters['page']
        ]);
    }


    /**
     * Xử lý thêm user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitaddAction(Request $request)
    {
        $name = $request->name;
        $test = $this->memberLevel->testName(str_slug($name), '0');
        if ($test['name'] == '') {
            $data = [
                'name' => $request->name,
                'slug' => str_slug($request->name),
                'point' => str_replace(',', '', $request->point),
                'is_actived' => $request->is_actived,
                'created_by' => Auth::id()
            ];
            $this->memberLevel->add($data);
            return response()->json(['status' => '', 'close' => $request->close]);
        } else {
            return response()->json(['status' => __('Cấp độ đã tồn tại')]);
        }
    }


    //function edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->memberLevel->getItem($id);

        $data = [
            'member_level_id' => $item->member_level_id,
            'name' => $item->name,
            'point' => $item->point,
            'discount' => $item->discount,
            'is_actived' => $item->is_actived,
            'description' => $item->description
        ];
        return response()->json($data);
    }

    public function submitEditAction(Request $request)
    {
        $id = intval($request->id);
        $test = $this->memberLevel->getItem($id);
        $point = strip_tags($request->point);
        if ($test != null) {
            $data = [
                'point' => str_replace(',', '', $point),
                'discount' => strip_tags($request->discount),
                'is_actived' => $request->is_actived,
                'description' => $request->description
            ];
            $data['updated_by'] = Auth::id();
            $this->memberLevel->edit($data, $id);
            return response()->json(['status' => '']);
        } else {
            return response()->json(['status' => __('Cấp độ đã tồn tại')]);
        }

    }

    //function thay doi trang thai
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->memberLevel->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    //function remove
    public function removeAction($id)
    {
        $this->memberLevel->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

}