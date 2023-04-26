<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 20/03/2018
 * Time: 10:15
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\OrderDeliveryType\OrderDeliveryTypeRepositoryInterface;

class OrderDeliveryTypeController extends Controller
{
    /**
     * @var Product Unit Repository Interface
     */
    protected $orderDeliveryType;

    public function __construct(OrderDeliveryTypeRepositoryInterface $orderDeliveryType)
    {
        $this->orderDeliveryType = $orderDeliveryType;
    }

    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $orderDeliveryTypeList = $this->orderDeliveryType->list();
        return view('admin::order-delivery-type.index', [
            'LIST' => $orderDeliveryTypeList,
            'FILTER' => $this->filters()
        ]);
    }

    // function  filter
    protected function filters()
    {
        return [
            'is_active' => [
                'text' => __('Trạng thái:'),
                'data' => [
                    '' => 'Tất cả',
                    1 => 'Đang hoạt động',
                    0 => 'Tạm ngưng'
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách customers source
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $orderDeliveryTypeList = $this->orderDeliveryType->list($filters);
        return view('admin::order-delivery-type.list', ['LIST' => $orderDeliveryTypeList]);
    }

    // FUNCTION RETURN VIEW ADD
    public function addAction()
    {
        return view('admin::order-delivery-type.add', [

            'TITLE' => 'Thêm người khách hàng',

        ]);
    }

    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request)
    {

        $data = $this->validate($request,
            [// validate value input
                'order_delivery_type_name' => 'required|unique:order_delivery_type',
                'order_delivery_type_code' => 'required|unique:order_delivery_type',


            ], [ // order info messages
                'order_delivery_type_name.required' => 'Tên hình thức giao hàng  bắt buộc',
                'order_delivery_type_name.unique' => 'Tên hình thức giao hàng  tồn tại',
                'order_delivery_type_code.required' => 'Mã hình thức giao hàng  bắt buộc',
                'order_delivery_type_code.unique' => 'Mã hình thức giao hàng đã tồn tại',

            ]);
        $data['order_delivery_type_description'] = $request->order_delivery_type_description;
        $data['is_active'] = $request->is_active;
        $oOrderDeliveryType = $this->orderDeliveryType->add($data);
        if ($oOrderDeliveryType) {
            // display  info  status update
            $request->session()->flash('status', 'Tạo trạng thái thành công!');
        }
        // redirect to view index
        return redirect()->route('order-delivery-type');

    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction($id)
    {
        $item = $this->orderDeliveryType->getItem($id);
        return view('admin::order-delivery-type.edit', [
            'TITLE' => 'Cập nhật trạng thái',
            'item' => $item
        ]);
    }

    // function submit update customers source
    public function submitEditAction(Request $request, $id)
    {
        $data = $this->validate($request, [
            //validate value input
            'order_delivery_type_name' => 'required',
            'order_delivery_type_code' => 'required|unique:order_delivery_type,order_delivery_type_id' . (($id) ? ",'$id' ,order_delivery_type_id" : ''),
        ], [// custom info messages
            'order_delivery_type_name.required' => 'Tên hình thức giao hàng bắt buộc',
            'order_delivery_type_code.unique' => 'Mã hình thức giao hàng đã tồn tại'
        ]);
        $data['order_delivery_type_description'] = $request->order_delivery_type_description;
        $data['is_active'] = $request->is_active;

        $oOrderDeliverytype = $this->orderDeliveryType->edit($data, $id);
        if ($oOrderDeliverytype) {
            // display  info  status update
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        } else {
            // display  info  status update
            Session::flash('messages', "Giá trị vừa nhập đã tồn tại");
            return redirect()->back();
        }
        // redirect to view index
        return redirect()->route('order-delivery-type');
    }

    // function change status
    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->orderDeliveryType->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => 'Trạng thái đã được cập nhật '
        ]);
    }

    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->orderDeliveryType->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

}