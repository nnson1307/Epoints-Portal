<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 19/03/2018
 * Time: 10:04 CH
 */

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Excel;
use Modules\Admin\Repositories\OrderPaymentType\OrderPaymentTypeRepositoryInterface;


class OrderPaymentTypeController extends Controller
{

    /**
     * @var  OrderPaymentType
     */
    protected $orderPaymentType;
    public function __construct(OrderPaymentTypeRepositoryInterface $orderPaymentType){
        $this->orderPaymentType = $orderPaymentType;
    }

    /**
     * Page Index
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $orderPaymentTypeList = $this->orderPaymentType->list();
        return view('admin::order-payment-type.index', [
            'LIST'   => $orderPaymentTypeList,
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
                    1  => 'Active',
                    0  => 'Deactive'
                ]
            ]
        ];
    }

    /**
     * Ajax list order Payment Type
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters           = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $orderPaymentTypeList   = $this->orderPaymentType->list($filters);
        
        return view('admin::order-payment-type.list', ['LIST' => $orderPaymentTypeList]);
    }

    // FUNCTION RETURN VIEW ADD
    public function addAction(){
        return view('admin::order-payment-type.add', [
            'TITLE'     =>'Thêm hình thức thanh toán',
        ]);
    }
    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request){

        $data = $this->validate($request,
            [// validate value input
                'order_payment_type_name'               => 'required|unique:order_payment_type',
            ], [ //
                'order_payment_type_name.required'      => 'Tên hình thức thanh toán',
                'order_payment_type_name.unique'        => 'Tên hình thức thanh toán đã tồn tại',
            ]);
        $data['order_payment_type_description']    = $request->order_payment_type_description;
        $data['created_at']    = date('Y-m-d H:i:s') ;
        $data['is_active']     = $request->is_active;
        $oOrderPaymentType     = $this->orderPaymentType->add($data);
        if($oOrderPaymentType){
            // display  info  status update
            $request->session()->flash('status', 'Tạo hình thức thanh toán thành công!');
        }
        // redirect to view index
        return redirect()->route('order-payment-type');

    }

    // FUNCTION RETURN VIEW EDIT
    public function editAction($id){
        $OBJECT = $this->orderPaymentType->getItem($id);
        return view('admin::order-payment-type.edit', [
            'TITLE'     =>'Cập nhật hình thức thanh toán',
            'item'     =>$OBJECT
        ]);
    }
    // FUNCTION SUBMIT SUBMIT ADD
    public function submitEditAction(Request $request, $id){

        $data = $this->validate($request,
            [  //validate value input
                'order_payment_type_name'            => 'required|unique:order_payment_type,order_payment_type_name,'.$id.',order_payment_type_id',
            ], ['order_payment_type_name.required'   => 'Tên hình thức thanh toán',
                'order_payment_type_name.unique'     => 'Tên hình thức thanh toán đã tồn tại',
            ]);
        $data['order_payment_type_description']    = $request->order_payment_type_description;
        $data['is_active']     = $request->is_active;
        $oOrderPaymentType     = $this->orderPaymentType->edit($data, $id);
        if($oOrderPaymentType){
            // display  info  status update
            $request->session()->flash('status', 'Tạo hình thức thanh toán thành công!');
        }
        // redirect to view index
        return redirect()->route('order-payment-type');
    }
    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request){
        $params             = $request->all() ;
        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->orderPaymentType->edit($data, $params['id']);
        return response()->json([
            'status'=>0,
            'messages'=>'Trạng thái đã được cập nhật '
        ]);
    }
    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->orderPaymentType->remove($id);
        return response()->json([
            'error'   => 0,
            'message' => 'Remove success'
        ]);
    }
}