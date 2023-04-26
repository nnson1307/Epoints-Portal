<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Ngoc Son
 * Date: 3/13/2018
 * Time: 2:07 PM
 */

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\ProductOrigin\ProductOriginRepositoryInterface;



class ProductOriginController extends Controller
{
    /**
     * @var ProductOriginRepositoryInterface
     */
    protected $productorigin;

    public function __construct(ProductOriginRepositoryInterface $productorigin)
    {
        $this->productorigin=$productorigin;
    }

    //return view index
    public function indexAction(){
        $originList=$this->productorigin->list();
        return view('admin::product-origin.index',[
            'LIST' =>  $originList,
            'FILTER'=> $this->filters()
        ]);
    }
    protected function filters()
    {
        return [
            'is_active'=>  [
                'text' => __('Trạng thái'),
                'data' =>[
                    ''=> 'Tất cả',
                    1 => 'Đang hoạt động',
                    0 =>'Tạm ngưng'
                ]
            ]
        ];
    }

    //  Thay đổi status
    public  function changeStatusAction(Request $request)
    {
        $params             = $request->all() ;

        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->productorigin->edit($data, $params['id']);
        return response()->json([
            'status'=>0

        ]);
    }

    /**
     * Ajax danh sách product origin
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters=$request->only(['page','display','search_type','search_keyword','is_active']);
        $originList= $this->productorigin->list($filters);
        return view('admin::product-origin.list',['LIST' => $originList]);

    }

    //Function xóa
    public function removeAction($id){

        $this->productorigin->remove($id);
        return response()->json([
            'status'=>0,
            'message'=>'Remove success'
        ]);
    }

    //Function thêm mới
    public function addAction(){
        return view('admin::product-origin.add');

    }

    //Function submit form add
    public function submitAddAction(Request $request){
        $data=$this->validate($request,[
            'product_origin_name' => 'required|unique:product_origin,product_origin_name',
            'product_origin_code' =>'required',
            'is_active' => 'integer'
        ],[
            'product_origin_name.required'=>'Tên quốc gia không được trống',
            'product_origin_name.unique'=>'Tên quốc gia đã tồn tại',
            'product_origin_code.required'=>'Mã quốc gia không được trống'
        ]);
        $data['product_origin_description']  = $request->input('product_origin_description');
        $data['created_at']                 = date('Y-m-d H:i:s') ;
        $oOrigin=$this->productorigin->add($data);
        if($oOrigin)
        {
            $request->session()->flash('status','Thêm xuất xứ thành công');
        }
        //return view index product origin
        return redirect()->route('admin.product-origin');
    }

    //Function edit
    public function editAction($id)
    {
        $item=$this->productorigin->getEdit($id);
        return view('admin::product-origin.edit',compact('item'));
    }

    //Function submit form edit
    public function submitEditAction(Request $request,$id){
        $data = $this->validate($request,[
            'product_origin_name' => 'required|unique:product_origin,product_origin_name,'.$id.",product_origin_id",
            'product_origin_code'=>'required|unique:product_origin,product_origin_code,'.$id.",product_origin_id",
            'is_active' => 'integer'
        ],[
            'product_origin_name.required'=>'Tên quốc gia không được trống',
            'product_origin_name.unique'=>'Tên quốc gia đã tồn tại',
            'product_origin_code.required'=>'Mã quốc gia không được trống',
            'product_origin_code.unique'=>'Mã quốc gia đã tồn tại'
        ]);
        $data['product_origin_description']  = $request->input('product_origin_description');
        $productOrigin= $this->productorigin->edit($data,$id);
        if($productOrigin){
            $request->session()->flash('status','Cập nhật thành công');
        }else{
            Session::flash('messages','Cập nhật thất bại');
            return redirect()->back();
        }
        return redirect()->route('admin.product-origin');
    }
}