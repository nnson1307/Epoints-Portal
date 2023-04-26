<?php
/**
 * OrderReasonCancelController
 * @author ledangsinh
 * @since March 20, 2018
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Models\OrderReasonCancelTable;
use Modules\Admin\Repositories\OrderReasonCancel\OrderReasonCancelRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;
use Box\Spout\Writer\WriterFactory;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Illuminate\Support\Facades\DB;
use Box\Spout\Reader\CSV\Reader;
use Box\Spout\Writer\ODS\Writer;
use Box\Spout\Writer\Style\StyleBuilder;

class OrderReasonCancelController extends Controller
{
    /**
     * @var OrderReasonCancelRepositoryInterface
     */
    protected $orderReasonCancel;

    public function __construct(OrderReasonCancelRepositoryInterface $orderReasonCancel)
    {
        $this->orderReasonCancel = $orderReasonCancel;
    }

    public function filters()
    {
        return [
            'is_active' => [
                'text' => __('Trạng thái'),
                'data' => [
                    '' => 'Tất cả',
                    1 => 'Hoạt động',
                    0 => 'Tạm ngưng'
                ]
            ]
        ];
    }

    /**
     * Trang chính
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */

    public function indexAction()
    {
        $orderReasonCancelList = $this->orderReasonCancel->list();
        return view('admin::order-reason-cancel.index', [
            'LIST' => $orderReasonCancelList,
            'FILTER' => $this->filters()
        ]);
    }

    /**
     * Ajax danh sách order reason cancel
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function listAction(Request $request)
    {
        $filters = $request->only('page', 'display', 'search_type', 'search_keyword', 'is_active');
        $orderReasonCancelList = $this->orderReasonCancel->list($filters);
        return view('admin::order-reason-cancel.list', ['LIST' => $orderReasonCancelList]);
    }

    //Function return view add
    public function addAction()
    {
        return view('admin::order-reason-cancel.add');
    }

    //Function submit form add
    public function submitAddAction(Request $request)
    {
        $data = $this->validate($request, [
            'order_reason_cancel_name' => 'required',
            'order_reason_cancel_description' => 'string',
            'is_active' => 'integer'
        ], [
            'order_reason_cancel_name.required' => 'Vui lòng nhập tên lý do hủy đơn hàng'
        ]);
        $oOrderReasonCancel = $this->orderReasonCancel->add($data);
        if ($oOrderReasonCancel) {
            $request->session()->flash('status', 'Tạo lý do hủy đơn hàng thành công');
        }
        //Return to view index
        return redirect()->route('admin.order-reason-cancel');
    }

    public function removeAction($id)
    {
        $this->orderReasonCancel->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //Function return view edit
    public function editAction($id)
    {
        $item = $this->orderReasonCancel->getItem($id);
        return view('admin::order-reason-cancel.edit', compact('item'));
    }

    //Function submit form edit
    public function submitEditAction(Request $request, $id)
    {
        $data = $this->validate($request,
            [
                'order_reason_cancel_name' => 'required',
                'order_reason_cancel_description' => 'string',
                'is_active' => 'integer'
            ], [
                'order_reason_cancel_name.required' => 'Vui lòng nhập tên lý do hủy đơn hàng'
            ]);
        $oOrderReasonCancel = $this->orderReasonCancel->edit($data, $id);
        if ($oOrderReasonCancel) {
            $request->session()->flash('status', 'Cập nhật thành công');
        }
        return redirect()->route('admin.order-reason-cancel');
    }

    public function changeStatusAction(Request $request)
    {
        $params = $request->all();
        $data['is_active'] = ($params['action'] == 'unPublish') ? 1 : 0;
        $this->orderReasonCancel->edit($data, $params['id']);
        return response()->json([
            'status' => 0,
            'messages' => 'Trạng thái đã được cập nhật '
        ]);
    }

    public function importExcelAction()
    {
        return view('admin::order-reason-cancel.import-excel');
    }

    public function submitImportExcelAction(Request $request)
    {
        $file = $request->file("file");
//        dd($file->getPathname());
        if (isset($file)) {
            $des_file = 'uploads/admin/order-reason-cancel/' . basename($file->getClientOriginalName());
            $excelFileType = $file->getClientOriginalExtension();
            $title = ['order_reason_cancel_name',
                'order_reason_cancel_description', 'is_active', 'created_at',
                'updated_at', 'created_by', 'updated_by'];
            if ($excelFileType != "xlsx") {
                return redirect()->back()->with("error", "File Excel không đúng");
            } else {
                move_uploaded_file($file->getPathname(), $des_file);
                $this->import_excel($des_file, $title);
            }
            return redirect()->route('admin.order-reason-cancel');
        }
    }

    public function import_excel($file_name, $title)
    {
        $reader = ReaderFactory::create(Type::XLSX);
        $reader->open($file_name);
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $key => $row) {
                if ($key == 1) {

                } elseif ($key != 1 && $row[0] != '') {
                    DB::table('order_reason_cancel')->insert([
                        "order_reason_cancel_name" => $row[0],
                        "order_reason_cancel_description" => $row[1],
                        "is_active" => $row[2],
                        "created_at" => $row[3],
                        "updated_at" => $row[4],
                        "created_by" => $row[5],
                        "updated_by" => $row[6]
                    ]);
                }

            }
        }
        $reader->close();
    }

    public function exportAction()
    {
        $piospa = DB::table("order_reason_cancel")->get();
        $table_title = ["order_reason_cancel_id", 'order_reason_cancel_name',
            'order_reason_cancel_description', 'is_active', 'created_at',
            'updated_at', 'created_by', 'updated_by'];
        $oExcel = WriterFactory::create(Type::XLSX);
        $oExcel->openToBrowser("piospa.xlsx");
        $oExcel->addRowWithStyle($table_title, (new StyleBuilder())->setFontBold()->setFontSize(14)->build());
        foreach ($piospa as $sheet) {
            $oExcel->addRow(get_object_vars($sheet));
        }
        $oExcel->close();
    }
}