<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/03/2018
 * Time: 2:33 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Admin\Models\OrderDeliveryStatusTable;
use Modules\Admin\Repositories\OrderDeliveryStatus\OrderDeliveryStatusRepositoryInterface;
use Box\Spout\Writer\Style\StyleBuilder;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;

use Illuminate\Support\Facades\DB;


class OrderDeliveryStatusController extends Controller
{
    protected $orderDeliveryStatus;


    public function __construct(OrderDeliveryStatusRepositoryInterface $orderDeliveryStatus)
//        $nhan->add($data)
    {
        $this->orderDeliveryStatus = $orderDeliveryStatus;
    }


    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function indexAction(Request $request)
    {
        $orderDeliveryStatusList = $this->orderDeliveryStatus->list();

        return view('admin::order-delivery-status.index', [
            'LIST'   => $orderDeliveryStatusList,
            'FILTER' => $this->filters()
        ]);
    }

    public function getProductListAction()
    {

    }

    /**
     * Khai báo filter
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'is_active' => [
                'text' => __('Trạng thái:'),
                'data' => [
                    '' => 'Tất cả',
                    1  => 'Đang hoạt động',
                    0  => 'Tạm ngưng'
                ]
            ]
        ];
    }

    /**
     * Ajax danh sách user
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters  = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_active']);
        $orderDeliveryStatusList = $this->orderDeliveryStatus->list($filters);

        #$b = new \Illuminate\Pagination\LengthAwarePaginator();
        #$b->nextPageUrl()
        #$b->toArray();

        return view('admin::order-delivery-status.list', ['LIST' => $orderDeliveryStatusList]);
    }


    /**
     * Xóa user
     *
     * @param number $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeAction($id)
    {
        $this->orderDeliveryStatus->remove($id);

        return response()->json([
            'error'   => 0,
            'message' => 'Remove success'
        ]);
    }


    /**
     * Form thêm user
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function addAction()
    {

        return view('admin::order-delivery-status.add');
    }

    /**
     * Xử lý thêm user
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitaddAction(Request $request)
    {
        $data = $this->validate($request, [
            'order_delivery_status_name' => 'required',
            'order_delivery_status_description' => 'required',

            'is_active'  =>'required',
        ]);



        $oDerDeliveryStatus  = $this->orderDeliveryStatus->add($data);

        if($oDerDeliveryStatus){

            $request->session()->flash('status', 'Tạo trạng thái giao hàng thành công!');
        }


        return redirect()->route('order-delivery-status');
    }



    public function editAction($id)
    {
        $item = $this->orderDeliveryStatus->getItem($id);
        return view('admin::order-delivery-status.edit',compact('item'));
    }

    public function  submitEditAction(Request $request,$id)
    {

        $data = $this->validate($request, [
            'order_delivery_status_name' => 'required',
            'order_delivery_status_description' => 'required',

//            'is_active'  =>'required',
        ]);


//
        $data['is_active'] = (int) $request->is_active;



        $orderDeliveryStatus =  $this->orderDeliveryStatus->edit($data, $id);

        if($orderDeliveryStatus){
            // display  info  status update
            $request->session()->flash('status', 'Cập nhât dữ liệu thành công!');
        }

        return redirect()->route('order-delivery-status')->with('success','Item updated successfully');



    }


    public  function changeStatusAction(Request $request)
    {
        $params             = $request->all() ;
        $data['is_active']  = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->orderDeliveryStatus->edit($data, $params['id']);
        return response()->json([
            'status'=>0,
            'messages'=>'Trạng thái đã được cập nhật '
        ]);
    }

//    public function getExport(){
//        $piospa=DB::table("order_delivery_status")->select('order_delivery_status_id','order_delivery_status_name','order_delivery_status_description','created_at','is_active')->get();
//        $table_title=["order_delivery_status_id",'order_delivery_status_name','order_delivery_status_description','created_at','is_active'];
//        $oExcel= WriterFactory::create(Type::XLSX);
//        $oExcel->openToBrowser("piospa.xlsx");
//        $oExcel->addRowWithStyle($table_title,(new StyleBuilder())->setFontBold()->setFontSize(14)->build());
//        foreach ($piospa as $sheet)
//        {
//            $oExcel->addRow(get_object_vars($sheet));
//        }
//        $oExcel->close();
//    }


    public function getExport(Request $req)
    {
        $param = $req->except('_token');
        foreach ($param as $value)
        {
            $devideArray = explode(',',$value);
            $column[]=$devideArray[0];
            $title[]=$devideArray[1];
        }


        $piospa=DB::table("order_delivery_status")->select($column)->get();
//        $table_title=[$title]; ci viwn $title o tren la mang roi nen ko can tao mang $table_title lam j nua
        $oExcel= WriterFactory::create(Type::XLSX);
        $oExcel->openToBrowser("piospa.xlsx");
        $oExcel->addRowWithStyle($title,(new StyleBuilder())->setFontBold()->setFontSize(14)->build());
        foreach ($piospa as $sheet)
        {


            if (!empty($sheet->created_at)) {
                $sheet->created_at = Carbon::parse($sheet->created_at)->format('d-m-Y');
            }

            if(!empty($sheet->is_active))
            {
                $sheet->is_active=($sheet->is_active == 0? "Ngung hoat dong":"Dang hoat dong");
            }

            $oExcel->addRow(get_object_vars($sheet));
        }



        $oExcel->close();
    }


    public function getImport()
    {
        return view('admin::order-delivery-status.importExcel');
    }
    public function submitImport(Request $req)
    {
        $file = $req->fileMuonImport;
        if(isset($file))
        {
            $des_file='uploads/' . basename($file->getClientOriginalName());
            $excelFileType=$file->getClientOriginalExtension();
            $title=["order_delivery_status_id",'order_delivery_status_name','order_delivery_status_description','created_at','is_active'];
            if($excelFileType !="xlsx"){
                return redirect()->back()->with("error","File Excel không đúng");
            }
            else{
                move_uploaded_file($file->getPathname(),$des_file);
                $this->import_excel($des_file,$title);
            }
            return redirect()->route('order-delivery-status');
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
                    DB::table('order_delivery_status')->insert([
                        "order_delivery_status_id"=>$row[0],
                        "order_delivery_status_name"=>$row[1],
                        "order_delivery_status_description"=>$row[2],
                        "created_at"=>$row[3],
                        "is_active"=>$row[4],


                    ]);
                }

            }
        }
        $reader->close();
    }

    public function deleteAllAction()
    {
        DB::table('order_delivery_status')->delete();
        return back();
    }

}