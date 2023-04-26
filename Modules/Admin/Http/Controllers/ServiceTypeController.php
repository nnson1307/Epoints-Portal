<?php
/**
 * Created by PhpStorm.
 * User: thach le viet
 * Date: 13/03/2018
 * Time: 1:21 CH
 */
namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ServiceType\ServiceTypeRepositoryInterface;

//use Modules\Admin\Repositories\ServiceGroup\ServiceTypeRepositoryInterface;
class ServiceTypeController extends Controller
{
    /**
     * @var ServiceTypeRepositoryInterface
     */
    protected $serviceType;
    public function __construct(ServiceTypeRepositoryInterface $serviceType){
        $this->serviceType = $serviceType;
    }
    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $serviceTypeList = $this->serviceType->list();
        return view('admin::service-type.index', [
            'LIST'   => $serviceTypeList,
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
     * Ajax danh sách service Type
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters           = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $serviceTypeList   = $this->serviceType->list($filters);
        return view('admin::service-type.list', ['LIST' => $serviceTypeList]);
    }
    // FUNCTION RETURN VIEW ADD
    public function addAction(){
        return view('admin::service-type.add', [
            'TITLE'     =>'Thêm gói dich vụ',
        ]);
    }
    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request){

        $data = $this->validate($request,
        [// validate value input
            'service_type_name'            => 'required|unique:service_type',
        ], [ //
            'service_type_name.required'   => 'Tên loại dịch vụ  bắt buộc',
            'service_type_name.unique'     => 'Tên loại dịch đã tồn tại'
        ]);
        $data['created_at']                 = date('Y-m-d H:i:s') ;
        $data['is_active']                  = $request->is_active;
        $oServiceType      = $this->serviceType->add($data);
        if($oServiceType){
            // display  info  status update
            $request->session()->flash('status', 'Tạo loại dịch vụ thành công!');
        }
        // redirect to view index
        return redirect()->route('service-type');

    }
    // FUNCTION RETURN VIEW EDIT
    public function editAction($id){
        $OBJECT = $this->serviceType->getItem($id);
        return view('admin::service-type.edit', [
            'TITLE'     =>'Cập nhật gói dịch vụ',
            'OBJECT'     =>$OBJECT
        ]);
    }
    // FUNCTION SUBMIT EDIT
    public function submitEditAction(Request $request, $id){
        $data = $this->validate($request, [
            //validate value input
            'service_type_name'            => 'required|unique:service_type,service_type_name,'.$id.',service_type_id',
        ],[// custom info messages
            'service_type_name.required'   => 'Tên loại dịch vụ  bắt buộc',
            'service_type_name.unique'     => 'Tên loại dịch vụ đã tồn tại'
        ]);
        $data['is_active']                = $request->is_active;
        $oServiceType                     = $this->serviceType->edit($data ,$id);
        if($oServiceType){
            // display  info  status update
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        }else{
            // display  info  status update
            Session::flash('messages', "Giá trị vừa nhập đã tồn tại");
            return redirect()->back();
        }
        // redirect to view index
        return redirect()->route('service-type');
    }
    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request){
        $params             = $request->all() ;
        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->serviceType->edit($data, $params['id']);
        return response()->json([
            'status'=>0,
            'messages'=>'Trạng thái đã được cập nhật '
        ]);
    }
    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->serviceType->remove($id);
        return response()->json([
            'error'   => 0,
            'message' => 'Remove success'
        ]);
    }
}