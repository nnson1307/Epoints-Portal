<?php
/**
 * Created by PhpStorm.
 * User: Như
 * Date: 13/03/2018
 * Time: 1:21 CH
 */
namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ProductUnit\ProductUnitRepositoryInterface;

//use Modules\Admin\Repositories\ServiceGroup\ServiceTypeRepositoryInterface;
class ProductUnitController extends Controller
{
    /**
     * @var Product Unit Repository Interface
     */
    protected $productUnit;
    public function __construct(ProductUnitRepositoryInterface $productUnit){
        $this->productUnit = $productUnit;
    }
    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction()
    {
        $productUnitList = $this->productUnit->list();
        return view('admin::product-unit.index', [
            'LIST'   => $productUnitList,
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
     * Ajax danh sách product unit
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters            = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $productUnitList   = $this->productUnit->list($filters);
        return view('admin::product-unit.list', ['LIST' => $productUnitList]);
    }
    // FUNCTION RETURN VIEW ADD
    public function addAction(){
        return view('admin::product-unit.add', [

            'TITLE'     =>'Thêm gói đơn vị sản phẩm',

        ]);
    }
    // FUNCTION SUBMIT SUBMIT ADD
    public function submitAddAction(Request $request){

        $data = $this->validate($request,
            [// validate value input
                'product_unit_name'            => 'required|unique:product_unit',
            ], [ // custom info messages
                'product_unit_name.required'   => 'Tên đơn vị sản phẩm  bắt buộc',
                'product_unit_name.unique'     => 'Tên đơn vị sản phẩm đã tồn tại'
            ]);
        $data['created_at']                   = date('Y-m-d H:i:s') ;
        $data['product_unit_description']    = $request->product_unit_description ;
        $data['is_active']  = $request->is_active;
        $oProductUnit       = $this->productUnit->add($data);
        if($oProductUnit){
            // display  info  status update
            $request->session()->flash('status', 'Tạo đơn vị sản phẩm thành công!');
        }
        // redirect to view index
        return redirect()->route('product-unit');

    }
    // FUNCTION RETURN VIEW EDIT
    public function editAction($id){
        $item = $this->productUnit->getItem($id);
        return view('admin::product-unit.edit', [
            'TITLE'     =>'Cập nhật đơn vị sản phẩm',
            'item'     =>$item
        ]);
    }
    // FUNCTION SUBMIT EDIT
    public function submitEditAction(Request $request, $id){
        $data = $this->validate($request, [
            //validate value input
            'product_unit_name'            => 'required|unique:product_unit,product_unit_name,'. $id . ",product_unit_id",
        ],[// custom info messages
            'product_unit_name.required'   => 'Tên đơn vị sản phẩm  bắt buộc',
            'product_unit_name.unique'     => 'Tên đơn vị sản phẩm đã tồn tại'
        ]);
        $data['is_active']                = $request->is_active;
        $data['product_unit_description']    = $request->product_unit_description ;
        $oProductUnit                     = $this->productUnit->edit($data ,$id);
        if($oProductUnit){
            // display  info  status update
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        }else{
            // display  info  status update
            Session::flash('messages', "Giá trị vừa nhập đã tồn tại");
            return redirect()->back();
        }
        // redirect to view index
        return redirect()->route('product-unit');
    }
    // FUNCTION CHANGE STATUS
    public function changeStatusAction(Request $request){
        $params             = $request->all() ;
        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->productUnit->edit($data, $params['id']);
        return response()->json([
            'status'=>0,
            'messages'=>'Trạng thái đã được cập nhật '
        ]);
    }
    // FUNCTION DELETE ITEM
    public function removeAction($id)
    {
        $this->productUnit->remove($id);
        return response()->json([
            'error'   => 0,
            'message' => 'Remove success'
        ]);
    }
}
