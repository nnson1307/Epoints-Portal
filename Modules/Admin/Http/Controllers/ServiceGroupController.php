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
use Modules\Admin\Repositories\ServiceGroup\ServiceGroupRepositoryInterface;

//use Modules\Admin\Repositories\ServicePackage\ServiceGroupRepositoryInterface;


class ServiceGroupController extends Controller
{
    /**
     * @var ServiceGroupRepositoryInterface
     */
    protected $serviceGroup;
    public function __construct(ServiceGroupRepositoryInterface $serviceGroup){
        $this->serviceGroup = $serviceGroup;
    }
    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $serviceGroupList = $this->serviceGroup->list();
        return view('admin::service-group.index', [
            'LIST'   => $serviceGroupList,
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
 * Ajax danh sách service Group
 *
 * @param Request $request
 * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
 */
    public function listAction(Request $request){
        $filters            = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $serviceGroupList   = $this->serviceGroup->list($filters);
        return view('admin::service-group.list', ['LIST' => $serviceGroupList]);
    }
    // FUNCTION RETURN  VIEW ADD
    public function addAction(){
        return view('admin::service-group.add', [

            'TITLE'     =>'Thêm nhóm dich vụ',

        ]);
    }
    // FUNCTION SUBMIT FORM  ADD
    public function submitAddAction(Request $request){

        $data = $this->validate($request,
        [   // check value input
            'service_group_name'            => 'required|unique:service_group',
        ],[ // custom info  messages
            'service_group_name.required'   => 'Tên nhóm dịch vụ  bắt buộc',
            'service_group_name.unique'     => 'Tên nhóm đã tồn tại']);
        $data['created_at']                 = date('Y-m-d H:i:s') ;
        $data['is_active']                  = $request->is_active;
        $oServiceGroup      = $this->serviceGroup->add($data);
        if($oServiceGroup){
            $request->session()->flash('status', 'Tạo nhóm dịch vụ thành công!');
        }
        // return to view index
        return redirect()->route('service-group');

    }
    // FUNCTION RETURN  VIEW EDIT
    public function editAction($id){
        $OBJECT = $this->serviceGroup->getItem($id);
        return view('admin::service-group.edit', [
            'TITLE'     =>'Cập nhật nhóm dịch vụ',
            'OBJECT'     =>$OBJECT
        ]);
    }
    // FUNCTION SUBMIT FORM  EDIT
    public function submitEditAction(Request $request, $id){

        $data = $this->validate($request, [
            // // validate value input
            'service_group_name'            => 'required|unique:service_group,service_group_name,'.$id.',service_group_id',
        ], [
            // custom info messages
            'service_group_name.required'   => 'Tên nhóm dịch vụ  bắt buộc',
            'service_group_name.unique'     => 'Tên nhóm đã tồn tại'
        ]);
        $data['is_active']      = $request->is_active;
        $oServiceGroup          = $this->serviceGroup->edit($data ,$id);
        if($oServiceGroup){
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        }else{
            Session::flash('messages', "Giá trị vừa nhập đã tồn tại");
            return redirect()->back();
        }
        // return to view index
        return redirect()->route('service-group');
    }

    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request){
        $params             = $request->all() ;
        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->serviceGroup->edit($data, $params['id']);
        return response()->json([
            'status'=>0,
            'messages'=>'Trạng thái đã được cập nhật '
        ]);
    }
    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->serviceGroup->remove($id);
        return response()->json([
            'error'   => 0,
            'message' => 'Remove success'
        ]);
    }
}