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
use Modules\Admin\Repositories\ServicePackage\ServicePackageRepositoryInterface;

//use Modules\Admin\Repositories\ServiceGroup\ServicePackageRepositoryInterface;
class ServicePackageController extends Controller
{
    /**
     * @var ServicePackageRepositoryInterface
     */
    protected $servicePackage;
    public function __construct(ServicePackageRepositoryInterface $servicePackage){
        $this->servicePackage = $servicePackage;
    }
    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $servicePackageList = $this->servicePackage->list();
        return view('admin::service-package.index', [
            'LIST'   => $servicePackageList,
            'FILTER' => $this->filters()
        ]);
    }
    // FUNCTION  FILTER LIST ITEM
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
     * Ajax danh sách service Package
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters            = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $servicePackageList   = $this->servicePackage->list($filters);
        return view('admin::service-package.list', ['LIST' => $servicePackageList]);
    }
    // FUNCTION RETURN VIEW ADD
    public function addAction(){
        return view('admin::service-package.add', [
            'TITLE'     =>'Thêm gói dich vụ',
        ]);
    }
    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request){

        $data = $this->validate($request,
        [// validate value input
            'service_package_name'            => 'required|unique:service_package',
        ], [ // custom info message
            'service_package_name.required'   => 'Tên gói dịch vụ  bắt buộc',
            'service_package_name.unique'     => 'Tên gói dịch đã tồn tại'
        ]);
        $data['created_at']                 = date('Y-m-d H:i:s') ;
        $data['is_active']    = $request->is_active;
        $oServicePackage      = $this->servicePackage->add($data);
        if($oServicePackage){
            $request->session()->flash('status', 'Tạo gói dịch vụ thành công!');
        }
        return redirect()->route('service-package');

    }
    // FUNCTION RETURN VIEW EDIT
    public function editAction($id){
        $OBJECT = $this->servicePackage->getItem($id);
        return view('admin::service-package.edit', [
            'TITLE'     =>'Cập nhật gói dịch vụ',
            'OBJECT'     =>$OBJECT
        ]);
    }
    // FUNCTION SUBMIT EDIT
    public function submitEditAction(Request $request, $id){

        $data = $this->validate($request,
        [// validate value input
            'service_package_name'            => 'required|unique:service_package,service_package_name,'.$id.',service_package_id',
        ],[ // custom info message
            'service_package_name.required'   => 'Tên nhóm dịch vụ  bắt buộc',
            'service_package_name.unique'     => 'Tên nhóm đã tồn tại'
        ]);
        $data['is_active']    = $request->is_active;
        $oServiceGroup      = $this->servicePackage->edit($data ,$id);
        if($oServiceGroup){
            // display info message
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        }else{
            // display info message
            Session::flash('messages', "Giá trị vừa nhập đã tồn tại");
            return redirect()->back();
        }
        // redirect to view index
        return redirect()->route('service-package');
    }

    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request){
        $params             = $request->all() ;
        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->servicePackage->edit($data, $params['id']);
        return response()->json([
            'status'=>0,
            'messages'=>'Trạng thái đã được cập nhật '
        ]);
    }
    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->servicePackage->remove($id);
        return response()->json([
            'error'   => 0,
            'message' => 'Remove success'
        ]);
    }
}