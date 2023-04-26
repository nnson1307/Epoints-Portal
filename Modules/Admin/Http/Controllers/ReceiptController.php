<?php

/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 5:36 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Http\Api\BookingApi;
use Modules\Admin\Libs\help\Help;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\PaymentMethodTable;
use Modules\Admin\Models\ReceiptDetailTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\WardTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\ConfigPrintBill\ConfigPrintBillRepositoryInterface;
use Modules\Admin\Repositories\Customer\CustomerRepositoryInterface;
use Modules\Admin\Repositories\CustomerBranchMoney\CustomerBranchMoneyRepositoryInterface;
use Modules\Admin\Repositories\CustomerDebt\CustomerDebtRepositoryInterface;
use Modules\Admin\Repositories\OrderDetail\OrderDetailRepositoryInterface;
use Modules\Admin\Repositories\PrintBillLog\PrintBillLogRepositoryInterface;
use Modules\Admin\Repositories\Receipt\ReceiptRepositoryInterface;
use Modules\Admin\Repositories\ReceiptDetail\ReceiptDetailRepositoryInterface;
use Modules\Admin\Repositories\SpaInfo\SpaInfoRepositoryInterface;
use Modules\Admin\Repositories\Ward\WardRepositoryInterface;


class ReceiptController extends Controller
{
    protected $receipt;
    protected $order_detail;
    protected $receipt_detail;
    protected $customer_branch_money;
    protected $customer_debt;
    protected $branch;
    protected $help;
    protected $configPrintBill;
    protected $printBillLog;
    protected $spaInfo;
    protected $customer;

    public function __construct(
        ReceiptRepositoryInterface $receipt,
        OrderDetailRepositoryInterface $order_detail,
        ReceiptDetailRepositoryInterface $receipt_detail,
        CustomerBranchMoneyRepositoryInterface $customer_branch_money,
        CustomerDebtRepositoryInterface $customer_debt,
        BranchRepositoryInterface $branch,
        Help $help,
        ConfigPrintBillRepositoryInterface $configPrintBill,
        PrintBillLogRepositoryInterface $printBillLog,
        SpaInfoRepositoryInterface $spaInfo,
        CustomerRepositoryInterface $customer
    ) {
        $this->receipt = $receipt;
        $this->order_detail = $order_detail;
        $this->receipt_detail = $receipt_detail;
        $this->customer_branch_money = $customer_branch_money;
        $this->customer_debt = $customer_debt;
        $this->branch = $branch;
        $this->help = $help;
        $this->configPrintBill = $configPrintBill;
        $this->printBillLog = $printBillLog;
        $this->spaInfo = $spaInfo;
        $this->customer = $customer;
    }

    /**
     * Danh sách công nợ
     *
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function indexAction()
    {
        $un = $this->customer_debt->list();

        return view('admin::receipt.index', [
            'LIST' => $un,
            'FILTER' => $this->filters()
        ]);
    }

    protected function filters()
    {
        $optionBranch = $this->branch->getBranch();
        $groupCate = (['' => __('Chọn chi nhánh')]) + $optionBranch;

        return [
            'branches$branch_id' => [
                'data' => $groupCate
            ],
            'customer_debt$status' => [
                'data' => [
                    '' => __('Trạng thái'),
                    'paid' => __('Đã thanh toán'),
                    'part-paid' => __('Thanh toán một phần'),
                    'unpaid' => __('Chưa thanh toán'),
                    'cancel' => __('Đã hủy')
                ]
            ],
        ];
    }

    /**
     * Filter, paginate công nợ
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_keyword',
            'branches$branch_id',
            'created_at',
            'customer_debt$status',
            'customer_debt$customer_id'
        ]);
        $list = $this->customer_debt->list($filter);

        return view('admin::receipt.list', [
            'LIST' => $list,
            'page' => $filter['page'],
        ]);
    }

    /**
     * Filter, paginate công nợ
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function listDeptByCustomerAction(Request $request)
    {
        $filter = $request->only([
            'page',
            'display',
            'search_keyword',
            'branches$branch_id',
            'created_at',
            'customer_debt$status',
            'customer_debt$customer_id'
        ]);
        $list = $this->customer_debt->listCustomerDept($filter);

        return view('admin::receipt.list', [
            'LIST' => $list,
            'page' => $filter['page'],
        ]);
    }

    public function detailAction(Request $request)
    {
        $item_receipt = $this->customer_debt->getCustomerDebt($request->customer_debt_id);

        $arr_order_detail = [];
        if ($item_receipt['order_id'] != null) {
            $order_detail = $this->order_detail->getItem($item_receipt['order_id']);
            if (count($order_detail) > 0) {
                foreach ($order_detail as $key => $item) {
                    $arr_order_detail[] = [
                        'object_name' => $item['object_name'],
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'discount' => $item['discount'],
                        'voucher_code' => $item['voucher_code'],
                        'amount' => $item['amount']
                    ];
                }
            }
        }
        //Lấy dữ liệu từ bảng receipt
        $receipt = $this->receipt->getReceipt($request->customer_debt_id);
        $arr_receipt_detail = [];
        if (count($receipt) > 0) {
            foreach ($receipt as $item) {
                $arr_receipt_detail[] = [
                    'receipt_date' => $item['created_at'],
                    'full_name' => $item['full_name'],
                    'amount_paid' => $item['amount_paid'],
                    'note' => $item['note'],
                    'receipt_detail' => $this->receipt_detail->getItem($item['receipt_id'])
                ];
            }
        }

        $view = \View::make('admin::receipt.pop.detail', [
            'order_detail' => $arr_order_detail,
            'itemReceipt' => $item_receipt,
            'receipt' => $arr_receipt_detail,
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Form thanh toán
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function receiptAction(Request $request)
    {
        $mPaymentMethod = new PaymentMethodTable();
        $optionPaymentMethod = $mPaymentMethod->getOption();
        $item_receipt = $this->customer_debt->getCustomerDebt($request->customer_debt_id);
        //Check tài khoản thành viên theo chi nhánh
        $branch_money = $this->customer_branch_money->getPriceBranch($item_receipt['customer_id'], Auth::user()->branch_id);

        $view = \View::make('admin::receipt.pop.receipt', [
            'itemReceipt' => $item_receipt,
            'branchMoney' => $branch_money,
            'optionPaymentMethod' => $optionPaymentMethod
        ])->render();
        return response()->json([
            'url' => $view
        ]);
    }

    /**
     * Thanh toán công nợ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitReceiptAction(Request $request)
    {
        DB::beginTransaction();
        try {
            //Lấy thông tin công nợ
            $item_receipt = $this->customer_debt->getCustomerDebt($request->customer_debt_id);
            $amount_bill = str_replace(',', '', $request->amount_bill); // tiền phải thanh toán
            $amount_return = str_replace(',', '', $request->amount_return); // tiền trả lại khách
            $amount_all = str_replace(',', '', $request->amount_all); // tổng tiền các phương thức thanh toán
            if ($amount_all <= 0) { // tổng tiền trả
                return response()->json([
                    'error' => true,
                    'message' => __('Hãy nhập tiền thanh toán')
                ]);
            }

            //Check tài khoản thành viên
            $arrMethodWithMoney = $request->array_method;
            if (isset($arrMethodWithMoney) && $arrMethodWithMoney != null) {
                foreach ($arrMethodWithMoney as $methodCode => $money) {
                    if ($methodCode == 'MEMBER') {
                        $branch_money = $this->customer_branch_money->getPriceBranch($item_receipt['customer_id'], Auth::user()->branch_id);
                        if ($money > $branch_money['balance']) {
                            return response()->json([
                                'error' => true,
                                'message' => __('Tài khoản thành viên không hợp lệ')
                            ]);
                        }
                        //Check có xài tiền thành viên ko dc thanh toán dư
                        if ($money > $amount_bill) {
                            return response()->json([
                                'error' => true,
                                'message' => __('Tiền thanh toán không hợp lệ')
                            ]);
                        }
                    }
                }
            }

            if ($amount_all > $amount_bill) {
                $amount_receipt = $amount_bill;
            } else {
                $amount_receipt = $amount_all;
            }
            $data_debt = [
                'amount_paid' => $item_receipt['amount_paid'] + $amount_receipt,
                //                'note' => $request->note,
                'updated_by' => Auth::id()
            ];

            if ($amount_receipt > 0) {
                $data_debt['status'] = 'part-paid';
            }

            if ($item_receipt['amount_paid'] + $amount_receipt >= $item_receipt['amount']) {
                $data_debt['status'] = 'paid';

                //Update table order
                $mOrder = new OrderTable();
                $data_order = [
                    'process_status' => 'paysuccess',
                ];
                $mOrder->edit($data_order, $item_receipt['order_id']);
            }

            //Update customer debt
            $this->customer_debt->edit($data_debt, $request->customer_debt_id);

            if ($request->receipt_id == null) {
                $data_receipt = [
                    'customer_id' => $item_receipt['customer_id'],
                    'receipt_code' => 'code',
                    'staff_id' => Auth::id(),
                    'branch_id' => Auth::user()->branch_id,
                    'object_type' => 'debt',
                    'object_id' => $request->customer_debt_id,
                    'order_id' => $item_receipt['order_id'],
                    'total_money' => $amount_all,
                    'status' => 'paid',
                    'amount' => $amount_all,
                    'amount_paid' => $amount_receipt,
                    'amount_return' => $amount_return,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'note' => $request->note,
                    'receipt_type_code' => 'RTC_DEBT',
                    'object_accounting_type_code' => $item_receipt['debt_code'], // debt code
                    'object_accounting_id' => $item_receipt['customer_debt_id'], // debt id
                ];
                //insert receipt
                $id_receipt = $this->receipt->add($data_receipt);
                $day_code = date('dmY');
                $data_code = [
                    'receipt_code' => 'TT_' . $day_code . $id_receipt
                ];
                //updated receipt code
                $this->receipt->edit($data_code, $id_receipt);
            } else {
                //Chỉnh sửa phiếu thanh toán
                $id_receipt = $request->receipt_id;

                $this->receipt->edit([
                    'customer_id' => $item_receipt['customer_id'],
                    'staff_id' => Auth::id(),
                    'branch_id' => Auth::user()->branch_id,
                    'object_type' => 'debt',
                    'object_id' => $request->customer_debt_id,
                    'order_id' => $item_receipt['order_id'],
                    'total_money' => $amount_all,
                    'status' => 'paid',
                    'amount' => $amount_all,
                    'amount_paid' => $amount_receipt,
                    'amount_return' => $amount_return,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                    'note' => $request->note,
                    'receipt_type_code' => 'RTC_DEBT',
                    'object_accounting_type_code' => $item_receipt['debt_code'], // debt code
                    'object_accounting_id' => $item_receipt['customer_debt_id'], // debt id
                ], $request->receipt_id);

                $mReceiptDetail = app()->get(ReceiptDetailTable::class);
                //Xoá receipt_detail để ở dưới insert zo lại
                $mReceiptDetail->removeByReceipt($request->receipt_id);
            }

            //Insert receipt detail
            foreach ($arrMethodWithMoney as $methodCode => $money) {
                if ($money > 0) {
                    $dataReceiptDetail = [
                        'receipt_id' => $id_receipt,
                        'cashier_id' => Auth::id(),
                        'amount' => $money,
                        'payment_method_code' => $methodCode,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id()
                    ];

                    if ($methodCode == 'MEMBER_MONEY') {
                        // Check số tiền thành viên
                        if ($money <= $amount_bill) { // trừ tiên thành viên
                            if ($money < $request->member_money) {
                                $customerMoney = $this->customer->getItem($item_receipt['customer_id']);
                                $dataCusMoney = [
                                    'account_money' => $customerMoney['account_money'] - $money
                                ];
                                $this->receipt_detail->add($dataReceiptDetail);
                                $this->customer->edit($dataCusMoney, $item_receipt['customer_id']);
                                $customerBranch = $this->customer_branch_money->getPriceBranch($item_receipt['customer_id'], $customerMoney['branch_id']);

                                if ($customerBranch != null) {
                                    $dataCusBranchMoney = [
                                        'total_using' => $customerBranch['total_using'] + $money,
                                        'balance' => $customerBranch['total_money'] - ($customerBranch['total_using'] + $money)
                                    ];
                                    $this->customer_branch_money->edit($dataCusBranchMoney, $item_receipt['customer_id'], $customerMoney['branch_id']);
                                }
                            } else {
                                return response()->json([
                                    'error_account_money' => 1,
                                    'message' => __('Số tiền còn lại trong tài khoản không đủ'),
                                    'money' => $request->member_money
                                ]);
                            }
                        } else {
                            return response()->json([
                                'money_large_moneybill' => 1,
                                'message' => __('Tiền tài khoản lớn hơn tiền thanh toán')
                            ]);
                        }
                    } else {
                        $this->receipt_detail->add($dataReceiptDetail);
                    }
                }
            }

            DB::commit();

            //Tính điểm thưởng khi thanh toán
            $mBookingApi = new BookingApi();
            $mBookingApi->plusPointReceipt(['receipt_id' => $id_receipt]);

            return response()->json([
                'error' => false,
                'message' => __('Thanh toán công nợ thành công'),
                'receipt_id' => $id_receipt
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                '_message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Template print bill khi thanh toán
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed
     */
    public function printBillAction(Request $request)
    {
        $item_debt = $this->customer_debt->getCustomerDebt($request->customer_debt_id);
        $receipt = $this->receipt->getReceiptById($request->receipt_id);
        $list_receipt_detail = $this->receipt_detail->getItem($receipt['receipt_id']);
        $totalCustomerPaid = 0; // Tính tổng tiền KH trả
        foreach ($list_receipt_detail as $item) {
            $totalCustomerPaid += $item['amount'];
        }
        //Lấy cấu hình in bill
        $configPrintBill = $this->configPrintBill->getItem(1);
        //Lấy thông tin chi nhánh của đơn hàng
        if ($item_debt == null) {
            $item_debt = [
                "branch_id" => "",
                "debt_code" => "",
                "profile_code" => "",
                "customer_code" => "",
                "customer_id" => "",
                "customer_name" => "",
                "customer_phone" => "",
            ];
        }

        $branchInfo = $this->branch->getItem($item_debt['branch_id']);
        if ($branchInfo != null) {
            // cắt hot line thành mảng -> chuyển qua chuỗi dạng sdt1 - sdt2 - sdt3
            $arrPhoneBranch = explode(",", $branchInfo['hot_line']);
            $strPhone = '';
            $temp = 0;
            $countPhoneBranch = count($arrPhoneBranch);
            if ($countPhoneBranch > 0) {
                foreach ($arrPhoneBranch as $value) {
                    if ($temp < $countPhoneBranch - 1) {
                        $strPhone = $strPhone . str_replace(' ', '', $value) . ' - ';
                    } else {
                        $strPhone = $strPhone . str_replace(' ', '', $value);
                    }
                    $temp++;
                }
            }
            $branchInfo['hot_line'] = $strPhone;
        } else {
            $branchInfo = [
                "branch_name" => "",
                "address" => "",
                "district_type" => "",
                "district_name" => "",
                "province_name" => "",
                "hot_line" => "",
            ];
        }

        $template = 'admin::receipt.print.content-print';
        switch ($configPrintBill->template) {
            case 'k58':
                $template = 'admin::receipt.print.template-k58';
                break;
            case 'A5':
                $template = 'admin::receipt.print.template--a5';
                break;
            case 'A5-landscape':
                $template = 'admin::receipt.print.template--a5';
                break;
            case 'A4':
                $template = 'admin::receipt.print.template-a4';
                break;
        }
        //check log
        $checkPrintBill = $this->printBillLog->checkPrintBillDebt($item_debt['debt_code']);
        $printTime = count($checkPrintBill);
        $printReply = '';
        if ($printTime > 0) {
            $printReply = __('(In lại)');
        }
        $maxId = $this->printBillLog->getBiggestId();
        $convertNumberToWords = $this->help->convertNumberToWords($receipt['amount']);
        $spaInfo = $this->spaInfo->getInfoSpa();
        // Tách sdt theo dấu ,
        $arrPhoneSpa = explode(",", $spaInfo['phone']);
        $arrPhoneNew = [];
        if (count($arrPhoneSpa) > 0) {
            foreach ($arrPhoneSpa as $value) {
                $arrPhoneNew[] = str_replace(' ', '', $value);
            }
        }
        $spaInfo['phone'] = $arrPhoneNew;

        return view($template, [
            'debt' => $item_debt,
            'receipt' => $receipt,
            'receipt_detail' => $list_receipt_detail,
            'spaInfo' => $spaInfo,
            'totalCustomerPaid' => $totalCustomerPaid,
            'configPrintBill' => $configPrintBill,
            'id' => $request->customer_debt_id,
            'printTime' => $printReply,
            'STT' => $maxId != null ? $maxId['id'] : 1,
            'QrCode' => $item_debt['debt_code'],
            'convertNumberToWords' => $convertNumberToWords,
            'amount_bill' => $request->amount_bill,
            'amount_return' =>  str_replace(',', '', $request->amount_return_bill),
            'branchInfo' => $branchInfo,
            'text_total_amount_paid' => $this->convert_number_to_words($totalCustomerPaid)
        ]);
    }

    /**
     * Lưu log hóa đơn công nợ
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function savePrintLogAction(Request $request)
    {
        $item_debt = $this->customer_debt->getCustomerDebt($request->customer_debt_id);

        $branch = Auth::user()->branch_id;
        $debt_code = $item_debt['debt_code'];
        $staffPrintBy = Auth::id();
        $created = date('Y-m-d H:i:s');

        $checkPrintBill = $this->printBillLog->checkPrintBillDebt($debt_code);
        $printTime = count($checkPrintBill);
        $configPrintBill = $this->configPrintBill->getItem(1);

        if ($configPrintBill['is_print_reply'] == 0) {
            if ($printTime > 0) {
                return response()->json(['error' => __('Chỉ được in 01 lần')]);
            } else {
                $data = [
                    'branch_id' => $branch,
                    'debt_code' => $debt_code,
                    'staff_print_reply_by' => '',
                    'staff_print_by' => $staffPrintBy,
                    'created_at' => $created,
                ];
                $this->printBillLog->add($data);
                return response()->json(['success' => 1]);
            }
        } else {
            if ($configPrintBill['print_time'] != null) {
                if ($printTime >= $configPrintBill['print_time']) {
                    return response()->json(['error' => __('Vượt quá số lần in cho phép')]);
                } else {
                    if ($printTime == 0) {
                        $data = [
                            'branch_id' => $branch,
                            'debt_code' => $debt_code,
                            'staff_print_reply_by' => '',
                            'staff_print_by' => $staffPrintBy,
                            'created_at' => $created,
                        ];
                    } else {
                        $data = [
                            'branch_id' => $branch,
                            'debt_code' => $debt_code,
                            'staff_print_reply_by' => $staffPrintBy,
                            'staff_print_by' => '',
                            'created_at' => $created,
                        ];
                    }
                    $this->printBillLog->add($data);
                    return response()->json(['success' => 1, 'error' => '']);
                }
            } else {
                if ($printTime == 0) {
                    $data = [
                        'branch_id' => $branch,
                        'debt_code' => $debt_code,
                        'staff_print_reply_by' => '',
                        'staff_print_by' => $staffPrintBy,
                        'created_at' => $created,
                    ];
                } else {
                    $data = [
                        'branch_id' => $branch,
                        'debt_code' => $debt_code,
                        'staff_print_reply_by' => $staffPrintBy,
                        'staff_print_by' => '',
                        'created_at' => $created,
                    ];
                }
                $this->printBillLog->add($data);
                return response()->json(['success' => 1, 'error' => '']);
            }
        }
    }

    /**
     * Import công nợ bằng tay
     *
     * @param Request $request
     * @return mixed
     */
    public function importExcelManual(Request $request)
    {
        return $this->receipt->importExcelManual($request->all());
    }

    /**
     * Tạo qr code thanh toán online
     *
     * @param Request $request
     * @return mixed
     */
    public function genQrCodeAction(Request $request)
    {
        return $this->receipt->genQrCode($request->all());
    }

    /**
     * Tạo qr code thanh toán online
     *
     * @param Request $request
     * @return mixed
     */
    public function cancleReceipt(Request $request)
    {
        $params['customer_debt_id'] = $request->customer_debt_id;
        $item = $this->customer_debt->getItem($params['customer_debt_id']);
        if ($item->debt_type == "first") {
            if ($this->customer_debt->cancleReceipt($params)) {
                return [
                    "error" => false,
                    "message" => __("Hủy thành công")
                ];
            }
        }
        return [
            "error" => true,
            "message" => __("Hủy không thành công")
        ];
    }

    /**
     * Function đọc tiền tiếng việt
     *
     * @param $number
     * @return string
     */
    function convert_number_to_words($number)
    {
        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = __('âm') . ' ';
        $decimal     = ' ' . __('phẩy') . ' ';
        $dictionary  = array(
            0                   => __('không'),
            1                   => __('một'),
            2                   => __('hai'),
            3                   => __('ba'),
            4                   => __('bốn'),
            5                   => __('năm'),
            6                   => __('sáu'),
            7                   => __('bảy'),
            8                   => __('tám'),
            9                   => __('chín'),
            10                  => __('mười'),
            11                  => __('mười một'),
            12                  => __('mười hai'),
            13                  => __('mười ba'),
            14                  => __('mười bốn'),
            15                  => __('mười năm'),
            16                  => __('mười sáu'),
            17                  => __('mười bảy'),
            18                  => __('mười tám'),
            19                  => __('mười chín'),
            20                  => __('hai mươi'),
            30                  => __('ba mươi'),
            40                  => __('bốn mươi'),
            50                  => __('năm mươi'),
            60                  => __('sáu mươi'),
            70                  => __('bảy mươi'),
            80                  => __('tám mươi'),
            90                  => __('chín mươi'),
            100                 => __('trăm'),
            1000                => __('nghìn'),
            1000000             => __('triệu'),
            1000000000          => __('tỷ'),
            1000000000000       => __('nghìn tỷ'),
            1000000000000000    => __('nghìn triệu triệu'),
            1000000000000000000 => __('tỷ tỷ')
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }
}
