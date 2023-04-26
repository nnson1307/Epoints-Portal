<?php

namespace Modules\Admin\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Repositories\Tax\TaxRepositoryInterface;
use phpDocumentor\Reflection\Types\Self_;
use Illuminate\Support\Facades\Input;
use Modules\Admin\Models\TaxTable;

/**
 * @author thanhlong
 * @since April 5, 2018
 */

class TaxController extends Controller
{
    protected $tax;

    public function __construct(TaxRepositoryInterface $tax)
    {
        $this->tax=$tax;
    }

    //return view index
    public function indexAction()
    {
        $taxList=$this->tax->list();
        return view('admin::tax.index',[
            'LIST'=>$taxList,
            'FILTER'=>$this->filters()
        ]);
    }
    //function filter
    protected function filters()
    {
        return[
            'is_active'=>[
                'text'=>__('Trạng thái'),
                'data'=>[
                    ''=>'Tất cả',
                    1=>'Đang hoạt động',
                    0=>'Tạm đóng'
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách Tax
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters  = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $taxList= $this->tax->list($filters);
        return view('admin::tax.list',['LIST' => $taxList]);
    }

    //return view add
    public function addAction()
    {
        return view('admin::tax.add');
    }

    //submit add
    public function submitAddAction(Request $request)
    {
        $data=$this->validate($request,[
           'name'=>'required|unique:tax',
            'is_active'   => 'integer',
            'value'=>'required|integer',
            'type'=>'required',
            'descripton'=>'max:255'
        ],[
            'name.required'=>'Tên thuế không được bỏ trống',
            'name.unique'=>'Tên loại hình thuế đã tồn tại',
            'value.required'=>'Vui lòng nhập hình thức thuế',
            'value.integer'=>'Giá trị hình thức thuế phải là kiểu số'
        ]);
        $this->tax->add($data);
        return redirect()->route('admin.tax');
    }

    //function remove
    public function removeAction($id)
    {
        $this->tax->remove(($id));

        return response()->json([
            'error'=>0,
            'message'=>'Remove success'
        ]);
    }

    //return view edit
    public function editAction($id)
    {
       $item=$this->tax->getItem($id);
        return view('admin::tax.edit',compact('item'));
    }

    //submit edit
    public function submitEditAction(Request $request,$id)
    {
        $data=$this->validate($request,[
            'name'=>'required|unique:tax,name,'.$id.",tax_id",
            'is_active'   => 'integer',
            'value'=>'required|integer',
            'type'=>'required',
            'descripton'=>'max:255'
        ],[
            'name.unique'=>'Tên loại hình thuế đã tồn tại',
            'name.required'=>'Tên thuế không được bỏ trống',
            'value.required'=>'Vui lòng nhập hình thức thuế',
            'value.integer'=>'Giá trị hình thức thuế phải là kiểu số'
        ]);

        $oTax=$this->tax->edit($data,$id);
        if($oTax)
        {
            $request->session()->flash('status','Cập nhật thành công');
        }
        else{
            Session::flash('messages','Cập nhật thất bại');
            return redirect()->back();
        }
        return redirect()->route('admin.tax');
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $params=$request->all();
        $data['is_active']=($params['action']=='unPublish') ? 1:0;
        $this->tax->edit($data,$params['id']);
        return response()->json([
            'status'=>0
        ]);
    }

    //export Excel
    public function exportAction(Request $request)
    {
        $params = $request->data;
        $oExplode=(explode(" ",$params));
        $this->tax->exportExcel($oExplode);
    }
    public function importAction()
    {
        return view('admin::tax.import-excel');
    }

    public function submitImportAction(Request $request)
    {
        return $this->tax->uploadExcel($request);
    }
}