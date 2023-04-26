<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 4/1/2019
 * Time: 12:07 PM
 */

namespace Modules\Admin\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\BranchTable;
use Modules\Admin\Repositories\ConfigPrintBill\ConfigPrintBillRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;

class ConfigPrintBillController extends Controller
{
    protected $configPrintBill;
    protected $spaInfo;

    public function __construct(
        ConfigPrintBillRepositoryInterface $configPrintBill,
        SpaInfoRepositoryInterface $spaInfo
    ) {
        $this->configPrintBill = $configPrintBill;
        $this->spaInfo = $spaInfo;
    }

    public function indexAction()
    {
        $configPrintBill = $this->configPrintBill->getItem(1);

        return view(
            'admin::config-print-bill.index',
            [
                'configPrintBill' => $configPrintBill,
                'spaInfo' => $this->spaInfo->getInfoSpa(),
            ]
        );
    }

    public function submitEditAction(Request $request)
    {
        $params = $request->only([
            "template",
            "printed_sheet",
            "is_print_reply",
            "print_time",
            "is_show_logo",
            "is_show_unit",
            "is_show_address",
            "is_show_phone",
            "is_show_order_code",
            "is_show_cashier",
            "is_show_customer",
            "is_show_datetime",
            "is_show_footer",
            "symbol",
            "tax_code",
            "is_total_bill",
            "is_total_discount",
            "is_total_amount",
            "is_total_receipt",
            "is_amount_return",
            'is_qrcode_order',
            'is_payment_method',
            'is_customer_code',
            'is_order_code',
            'is_profile_code',
            'is_company_tax_code',
            'is_sign',
            'is_dept_customer',
            "note_footer"
        ]);

        if (isset($params['template'])) {
            $data['template'] = $params['template'];
        }
        if (isset($params['printed_sheet'])) {
            $data['printed_sheet'] = $params['printed_sheet'];
        }
        if (isset($params['print_time'])) {
            $data['print_time'] = $params['print_time'];
        } else {
            $data['print_time'] = null;
        }
        if (isset($params['is_print_reply'])) {
            $data['is_print_reply'] = $params['is_print_reply'] == 'on' ? 1 : 0;
        } else {
            $data['is_print_reply'] = 0;
            $data['print_time'] = 1;
        }
        if (isset($params['is_show_logo'])) {
            $data['is_show_logo'] = $params['is_show_logo'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_logo'] = 0;
        }
        if (isset($params['is_show_unit'])) {
            $data['is_show_unit'] = $params['is_show_unit'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_unit'] = 0;
        }
        if (isset($params['is_show_address'])) {
            $data['is_show_address'] = $params['is_show_address'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_address'] = 0;
        }
        if (isset($params['is_show_phone'])) {
            $data['is_show_phone'] = $params['is_show_phone'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_phone'] = 0;
        }
        if (isset($params['is_show_order_code'])) {
            $data['is_show_order_code'] = $params['is_show_order_code'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_order_code'] = 0;
        }
        if (isset($params['is_show_cashier'])) {
            $data['is_show_cashier'] = $params['is_show_cashier'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_cashier'] = 0;
        }
        if (isset($params['is_show_customer'])) {
            $data['is_show_customer'] = $params['is_show_customer'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_customer'] = 0;
        }
        if (isset($params['is_show_datetime'])) {
            $data['is_show_datetime'] = $params['is_show_datetime'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_datetime'] = 0;
        }
        if (isset($params['is_show_footer'])) {
            $data['is_show_footer'] = $params['is_show_footer'] == 'on' ? 1 : 0;
        } else {
            $data['is_show_footer'] = 0;
        }

        if (isset($params['is_total_bill'])) {
            $data['is_total_bill'] = $params['is_total_bill'] == 'on' ? 1 : 0;
        } else {
            $data['is_total_bill'] = 0;
        }

        if (isset($params['is_total_discount'])) {
            $data['is_total_discount'] = $params['is_total_discount'] == 'on' ? 1 : 0;
        } else {
            $data['is_total_discount'] = 0;
        }

        if (isset($params['is_total_amount'])) {
            $data['is_total_amount'] = $params['is_total_amount'] == 'on' ? 1 : 0;
        } else {
            $data['is_total_amount'] = 0;
        }

        if (isset($params['is_total_receipt'])) {
            $data['is_total_receipt'] = $params['is_total_receipt'] == 'on' ? 1 : 0;
        } else {
            $data['is_total_receipt'] = 0;
        }

        if (isset($params['is_amount_return'])) {
            $data['is_amount_return'] = $params['is_amount_return'] == 'on' ? 1 : 0;
        } else {
            $data['is_amount_return'] = 0;
        }
        if (isset($params['is_qrcode_order'])) {
            $data['is_qrcode_order'] = $params['is_qrcode_order'] == 'on' ? 1 : 0;
        } else {
            $data['is_qrcode_order'] = 0;
        }
        if (isset($params['is_payment_method'])) {
            $data['is_payment_method'] = $params['is_payment_method'] == 'on' ? 1 : 0;
        } else {
            $data['is_payment_method'] = 0;
        }
        if (isset($params['is_customer_code'])) {
            $data['is_customer_code'] = $params['is_customer_code'] == 'on' ? 1 : 0;
        } else {
            $data['is_customer_code'] = 0;
        }
        if (isset($params['is_profile_code'])) {
            $data['is_profile_code'] = $params['is_profile_code'] == 'on' ? 1 : 0;
        } else {
            $data['is_profile_code'] = 0;
        }
        if (isset($params['is_sign'])) {
            $data['is_sign'] = $params['is_sign'] == 'on' ? 1 : 0;
        } else {
            $data['is_sign'] = 0;
        }
        if (isset($params['is_dept_customer'])) {
            $data['is_dept_customer'] = $params['is_dept_customer'] == 'on' ? 1 : 0;
        } else {
            $data['is_dept_customer'] = 0;
        }
        if (isset($params['is_company_tax_code'])) {
            $data['is_company_tax_code'] = $params['is_company_tax_code'] == 'on' ? 1 : 0;
        } else {
            $data['is_company_tax_code'] = 0;
        }
        $data['updated_by'] = Auth::id();
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['symbol'] = $params['symbol'];
        $data['note_footer'] = $params['note_footer'];

        $this->configPrintBill->edit($data, 1);
        $this->spaInfo->edit(['tax_code' => $params['tax_code']], 1);
        return redirect()->route('admin.config-print-bill')->with('statusss', 'success');
    }

    public function getPrinterAction(Request $request)
    {
        //        dd($request->all());
        //        $configPrintBill = $this->configPrintBill->getItem(1);
        $printers = $this->configPrintBill->getPrinters($request->all());
        return view(
            'admin::config-print-bill.printer.printer',
            [
                'LIST' => $printers,
                'FILTER' => $this->filters(),
                'param' => $request->all()
            ]
        );
    }

    public function listAction(Request $request)
    {
        //        dd($request->all());
        //        $configPrintBill = $this->configPrintBill->getItem(1);
        $printers = $this->configPrintBill->getPrinters($request->all());
        return view(
            'admin::config-print-bill.printer.list',
            [
                'LIST' => $printers,
                'FILTER' => $this->filters(),
                'param' => $request->all()
            ]
        );
    }

    public function createPrinterAction(Request $request)
    {
        $mBranch = app()->get(BranchTable::class);

        //Lấy option chi nhánh
        $optionBranch = $mBranch->getBranchOption();
        $configPrintBill = $this->configPrintBill->getItem(1);
        $html = view('admin::config-print-bill.printer.popup-create', ['configPrintBill' => $configPrintBill, "optionBranch" => $optionBranch])->render();
        $data = [
            'html' => $html
        ];
        return response()->json($data);
    }

    public function storePrinterAction(Request $request)
    {
        try {
            $input = $request->all();
            $input['user_id'] = Auth::user()->staff_id ?? 0;
            $data = $this->configPrintBill->storePrinter($input);
            return response()->json([
                "error" => false,
                "message" => __("Tạo thành công"),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "error" => true,
                "message" => __("Tạo thất bại"),
            ]);
        }
    }

    /**
     * Render các option filter
     *
     * @return array
     */
    protected function filters()
    {

        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    '1' => __('Đang hoạt động'),
                    '0' => __('Đã tạm ngừng')
                ]
            ],
        ];
    }

    /**
     * Cập nhật trạng thái máy in
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatusPrinterAction(Request $request)
    {
        $this->configPrintBill->updatePrinterStatus($request->all());
        return response()->json([
            'error' => 0,
            'message' => 'Update success'
        ]);
    }

    /**
     * Xóa máy in
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPrinterAction(Request $request)
    {
        $this->configPrintBill->removePrinter($request->all());
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    /**
     * Lấy view cập nhật máy in
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Throwable
     */
    public function editPrinterAction(Request $request)
    {
        $input = $request->all();

        $printer = $this->configPrintBill->getPrinter($input['print_bill_device_id']);
        $mBranch = app()->get(BranchTable::class);
        //Lấy option chi nhánh
        $optionBranch = $mBranch->getBranchOption();

        if (empty($printer)) {
            return response()->json([
                'error' => 1,
                'message' => __("Máy in không tồn tại")
            ]);
        }

        $html = view('admin::config-print-bill.printer.popup-edit', ['printer' => $printer, "optionBranch" => $optionBranch])->render();
        return response()->json([
            'error' => 0,
            'message' => "",
            'html' => $html
        ]);
    }

    /**
     * Cập nhật thông tin máy in
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrinterAction(Request $request)
    {
        $this->configPrintBill->updatePrinter($request->all());
        return response()->json([
            'error' => 0,
            'message' => 'Update success'
        ]);
    }

    /**
     * Cập nhật printer mặc định
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePrinterDefaultAction(Request $request)
    {
        return $this->configPrintBill->updatePrinterDefault($request->all());
    }
}