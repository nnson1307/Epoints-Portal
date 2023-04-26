<?php
/**
 * MemberLevelVerbController
 * @author ledangsinh
 * @since March 26, 2018
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\MemberLevel\MemberLevelRepositoryInterface;
use Modules\Admin\Repositories\MemberLevelVerb\MemberLevelVerbRepositoryInterface;

use Modules\Admin\Models\MemberLevelVerbTable;

class MemberLevelVerbController extends Controller
{
    protected $memberLevelVerb;
    protected $memberLevel;

    public function __construct(MemberLevelVerbRepositoryInterface $memberLevelVerb, MemberLevelRepositoryInterface $memberLevelRepository)
    {
        $this->memberLevelVerb = $memberLevelVerb;
        $this->memberLevel = $memberLevelRepository;
    }

    public function filters()
    {
        return [
            'mlv$is_active' => [
                'text' => __('Trạng thái'),
                'data' => [
                    '' => __('Tất cả'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    public function indexAction()
    {
        $memberLevelVerbList = $this->memberLevelVerb->list();
        return view('admin::member-level-verb.index', [
            'LIST' => $memberLevelVerbList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Ajax danh sách member level verb
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'mlv$is_active']);
        $memberLevelVerbList = $this->memberLevelVerb->list($filters);
        return view('admin::member-level-verb.list', ['LIST' => $memberLevelVerbList]);
    }

    //Function return view add
    public function addAction()
    {
        $optionMemberLevelName = $this->memberLevel->getOptionMemberLevel();
        return view('admin::member-level-verb.add', array(
            'optionMemberLevelName' => $optionMemberLevelName
        ));
    }

    //Function submit form add
    public function submitAddAction(Request $request)
    {
        $data = $this->validate($request, [
            'member_level_verb_name' => 'required',
            'is_active' => 'integer',
            'member_level_id' => 'required',
            'member_level_verb_point' => 'required',
            'order_price_min' => 'required',
            'order_price_max' => 'required',
            'product_number_min' => 'required',
            'product_number_max' => 'required',

        ], [
            'member_level_verb_name.required' => __('Vui lòng nhập tên hình thức quy đổi điểm'),
            'member_level_id.required' => __('Vui lòng nhập cấp độ hội viên'),
            'member_level_verb_point.required' => __('Vui lòng nhập số điểm nhận được'),
            'order_price_min.required' => __('Vui lòng nhập giá tối thiểu 1 đơn hàng'),
            'order_price_max.required' => __('Vui lòng nhập giá tối đa 1 đơn hang'),
            'product_number_min.required' => __('Vui lòng nhập số lượng sản phẩm tối thiểu'),
            'product_number_max.required' => __('Vui lòng nhập số lượng sản phẩm tối đa')
        ]);
        $oMemberLevelVerb = $this->memberLevelVerb->add($data);
        if ($oMemberLevelVerb) {
            $request->session()->flash('status', __('Tạo công thức quy đổi thành công!'));
        }
        // return to view index
        return redirect()->route('admin.member-level-verb');
    }

    // Function delete item
    public function removeAction($id)
    {
        $this->memberLevelVerb->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //Function return view edit
    public function editAction($id)
    {
        $item = $this->memberLevelVerb->getItem($id);
        $optionMemberLevelName = $this->memberLevel->getOptionMemberLevel();
        return view('admin::member-level-verb.edit',
            array('optionMemberLevelName' => $optionMemberLevelName),
            compact('item'));
    }

    //Function submit form edit
    public function submitEditAction(Request $request, $id)
    {
        $data = $this->validate($request, [
            'member_level_verb_name' => 'required',
            'is_active' => 'integer',
            'member_level_id' => 'required',
            'member_level_verb_point' => 'required',
            'order_price_min' => 'required',
            'order_price_max' => 'required',
            'product_number_min' => 'required',
            'product_number_max' => 'required',

        ], [
            'member_level_verb_name.required' => __('Vui lòng nhập tên hình thức quy đổi điểm'),
            'member_level_id.required' => __('Vui lòng nhập cấp độ hội viên'),
            'member_level_verb_point.required' => __('Vui lòng nhập số điểm nhận được'),
            'order_price_min.required' => __('Vui lòng nhập giá tối thiểu 1 đơn hàng'),
            'order_price_max.required' => __('Vui lòng nhập giá tối đa 1 đơn hang'),
            'product_number_min.required' => __('Vui lòng nhập số lượng sản phẩm tối thiểu'),
            'product_number_max.required' => __('Vui lòng nhập số lượng sản phẩm tối đa')
        ]);
        $oMemberLevelVerb = $this->memberLevelVerb->edit($data, $id);
        if ($oMemberLevelVerb) {
            $request->session()->flash('status', __('Cập nhật công thức quy đổi thành công'));
        }
        return redirect()->route('admin.member-level-verb');
    }

    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->memberLevelVerb->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => __('Trạng thái đã được cập nhật')
        ]);
    }

    public function exportExcelAction(Request $request)
    {
        $params = $request->except('_token');
        foreach ($params as $key => $value) {
            $oExplode = explode(",", $value);
            $column[] = $oExplode[0];
            $title[] = $oExplode[1];
        }
        $this->memberLevelVerb->exportExcel($column, $title);
    }
}