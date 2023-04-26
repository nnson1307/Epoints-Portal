<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Ngoc Son
 * Date: 3/20/2018
 * Time: 10:06 AM
 */

namespace Modules\Admin\Http\Controllers;

use Box\Spout\Writer\Style\StyleBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Repositories\OrderStatus\OrderStatusRepositoryInterface;
use Illuminate\Support\Facades\DB;

use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;



class OrderStatusController extends Controller
{
    protected $orderstatus;

    public function __construct(OrderStatusRepositoryInterface $orderstatus)
    {
        $this->orderstatus=$orderstatus;
    }

    //return view index
    public function indexAction()
    {
        $statusList=$this->orderstatus->list();
        return view('admin::order-status.index',[
            'LIST'=>$statusList,
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
     * Ajax danh sách Oder Status
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request){
        $filters=$request->only(['page','display','search_type','search_keyword','is_active']);
        $statusList= $this->orderstatus->list($filters);
        return view('admin::order-status.list',['LIST' => $statusList]);

    }

    //return view add
    public function addAction()
    {
        return view('admin::order-status.add');
    }

    //submit add
    public function submitAddAction(Request $request)
    {
        $data=$this->validate($request,[
            'order_status_name' => 'required',
            'order_status_description' =>'',
            'is_active'=>'integer'
        ],[
            'order_status_name' =>'Trạng thái đơn hàng không được bỏ trống'
        ]);
        $this->orderstatus->add($data);
        return redirect()->route('admin.order-status');
    }

    //function remove
    public function removeAction($id)
    {
        $this->orderstatus->remove($id);
        return response()->json([
           'status'=>0,
           'message'=>'Remove success'
        ]);
    }

    //return view edit
    public function editAction($id)
    {
        $item=$this->orderstatus->getEdit($id);
        return view('admin::order-status.edit',compact('item'));
    }

    //submit edit
    public function submitEditAction(Request $request,$id)
    {
        $data=$this->validate($request,[
            'order_status_name'=>'required',
            'order_status_description' =>'',
            'is_active'=>'integer'
        ],[
            'order_status_name.required'=>'Trạng thái đơn hàng không được bỏ trống'
        ]);
        $oderStatus=$this->orderstatus->edit($data,$id);
        if($oderStatus)
        {
            $request->session()->flash('status','Cập nhật thành công');
        }else{
            Session::flash('messages','Cập nhật thất bại');
            return redirect()->back();
        }
        return redirect()->route('admin.order-status');
    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $params=$request->all();
        $data['is_active']=($params['action']=='unPublish') ? 1:0;
        $this->orderstatus->edit($data,$params['id']);
        return response()->json([
           'status'=>0

        ]);
    }

    //export Excel
    public function exportAction(Request $request){
        $params = $request->except("_token");

        foreach ($params as $key=>$value)
        {
            $oExplode=explode(",",$value);
            $column[]= $oExplode[0];
            $title[]=$oExplode[1];
        }
        $this->orderstatus->exportExcel($column,$title);
    }

    public function importAction(Request $req)
    {
        $file = $req->file("Import");
        if(isset($file))
        {
            $des_file='uploads/' . basename($file->getClientOriginalName());
            $excelFileType=$file->getClientOriginalExtension();
            $title=["order_status_id",'order_status_name','order_status_description','created_at','updated_at','is_active','is_delete'];
            if($excelFileType !="xlsx"){
                return redirect()->back()->with("error","File Excel không đúng");
            }
            else{
                move_uploaded_file($file->getPathname(),$des_file);
                $this->import_excel($des_file,$title);
            }
            return redirect()->back();
        }
    }

    public function import_excel($file_name,$title){
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($file_name);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $key=>$row) {
                if($key==1)
                {

                }
                elseif($key!=1 && $row[0] != '')
                {
                    DB::table('order_status')->insert([
                        "order_status_id"=>$row[0],
                        "order_status_name"=>$row[1],
                        "order_status_description"=>$row[2],
                        "created_at"=>$row[3],
                        "updated_at"=>$row[4],
                        "is_active"=>$row[5],
                        "is_delete"=>$row[6],

                    ]);
                }

            }
        }
        $reader->close();
    }

}