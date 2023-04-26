<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 16/05/2022
 * Time: 14:43
 */

namespace Modules\Admin\Repositories\ExportData;

use App\Exports\ExportFile;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\CustomerDebtTable;
use Modules\Admin\Models\CustomerServiceCardTable;
use Modules\Admin\Models\CustomerTable;
use Modules\Admin\Models\OrderDetailTable;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Models\StaffTable;
use Modules\Admin\Models\SupplierTable;


class ExportDataRepo implements ExportDataRepoInterface
{
    const NEW = 'new';
    const PAYSUCCESS = 'paysuccess';
    const PAY_HALF = 'pay-half';
    const ORDER_CANCEL = 'ordercancle';
    const CONFIRMED = 'confirmed';

    /**
     * Xuất dữ liệu excel cho Sie
     *
     * @param $input
     * @return mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function exportExcelSie($input)
    {
        switch ($input['type']) {
            case 'customer':
                return $this->_exportCustomer($input);
                break;
            case 'order':
                return $this->_exportOrder($input);
                break;
            case 'receipt':
                return $this->_exportReceipt($input);
                break;
            case 'debt':
                return $this->_exportDebt($input);
                break;
        }
    }

    /**
     * Export data KH
     *
     * @param $input
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function _exportCustomer($input)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);
        $mCustomerServiceCard = app()->get(CustomerServiceCardTable::class);

        $heading = [
            __('STT'),
            __('Họ & Tên'),
            __('Nhóm khách hàng'),
            __('Số điện thoại'),
            __('Giới tính'),
            __('Ngày sinh'),
            __('Email'),
            __('Địa chỉ'),
            __('Ngày tạo'),
            __('Hạng thành viên'),
            __('Điểm tích luỹ'),
            __('Điểm được sử dụng'),
            __('Thẻ dịch vụ'),
            __('Sản phẩm'),
            __('Dịch vụ'),
            __('Ghi chú')
        ];

        //Danh sách khách hàng
        $getCustomer = $mCustomer->getCustomerExportSie($input['before_date']);

        $data = [];

        foreach ($getCustomer as $key => $item) {
            $gender = '';
            if ($item['gender'] == 'other') {
                $gender = 'Khác';
            } elseif ($item['gender'] == 'male') {
                $gender = 'Nam';
            } elseif ($item['gender'] == 'female') {
                $gender = 'Nữ';
            }

            //LẤy danh sách sản phẩm, dịch vụ đã mua
            $listProduct = $mOrderDetail->getObjectByCustomer($item['customer_id'], 'product');
            $listService = $mOrderDetail->getObjectByCustomer($item['customer_id'], 'service');
            $listCard = $mCustomerServiceCard->getCustomerCardAll($item['customer_id']);

            //Lấy số tiền nợ

            $nameProduct = '';
            $nameService = '';
            $nameCard = '';

            if (count($listProduct) > 0) {
                foreach ($listProduct as $product) {
                    $nameProduct = $nameProduct . $product['object_name'] . ';' . chr(13);
                }
            }
            if (count($listService) > 0) {
                foreach ($listService as $service) {
                    $nameService = $nameService . $service['object_name'] . ';' . chr(13);
                }
            }

            if (count($listCard) > 0) {
                foreach ($listCard as $card) {
                    $nameCard = $nameCard . $card['card_name'] . chr(13);
                }
            }

            $data [] = [
                $key + 1,
                $item['full_name'],
                $item['group_name'],
                $item['phone1'],
                $gender,
                $item['birthday'] != null ? Carbon::parse($item['birthday'])->format('d/m/Y') : '',
                $item['email'],
                $item['address'],
                $item['created_at'] != null ? Carbon::parse($item['created_at'])->format('d/m/Y H:i') : '',
                $item['member_level_name'],
                $item['point'] > 0 ? intval($item['point']) : "0",
                $item['point_balance'] > 0 ? intval($item['point_balance']) : "0",
                $nameCard,
                $nameProduct,
                $nameService,
                $item['note']
            ];
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'file-customer.xlsx');
    }

    /**
     * Export data đơn hàng
     *
     * @param $input
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function _exportOrder($input)
    {
        $mOrder = app()->get(OrderTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        $heading = [
            __('STT'),
            __('Chi nhánh'),
            __('Mã đơn hàng'),
            __('Họ & Tên'),
            __('Số điện thoại'),
            __('SP/DV/Thẻ DV'),
            __('Tổng tiền'),
            __('Giảm giá'),
            __('Phí vận chuyển'),
            __('Thành tiền'),
            __('Trạng thái'),
            __('Tiền đã trả'),
            __('Người tạo'),
            __('Ngày tạo'),
            __('Ghi chú')
        ];

        //Lấy thông tin đơn hàng
        $getOrder = $mOrder->getOrderExportSie($input['before_date']);

        $data = [];

        foreach ($getOrder as $k => $v) {
            $productName = "";

            //Lấy chi tiết đơn hàng
            $orderDetail = $mOrderDetail->getItem($v['order_id']);

            foreach ($orderDetail as $v1) {
                $productName = $productName . $v1['object_name'] . ';' . chr(13) ;
            }

            switch ($v['process_status']) {
                case self::PAY_HALF:
                    $status = __('Thanh toán còn thiếu');
                    break;
                case self::NEW:
                    $status = __('Mới');
                    break;
                case self::ORDER_CANCEL:
                    $status = __('Đã hủy');
                    break;
                case self::CONFIRMED:
                    $status = __('Đã xác nhận');
                    break;
                default:
                    $status = __('Đã thanh toán');
            }

            $totalReceipt = 0;

            //Lấy tiền đã thanh toán của đơn hàng
            $getReceipt = $mReceipt->getReceiptByOrder($v['order_id']);

            foreach ($getReceipt as $v2) {
                $totalReceipt += $v2['amount_paid'];
            }

            $data [] = [
                $k + 1,
                $v['branch_name'],
                $v['order_code'],
                $v['customer_name'],
                $v['customer_phone'],
                $productName,
                number_format($v['total'], 0),
                number_format($v['discount'], 0),
                number_format($v['tranport_charge'], 0),
                number_format($v['amount'], 0),
                $status,
                number_format($totalReceipt, 0),
                $v['staff_name'],
                $v['created_at'] != null ? Carbon::parse($v['created_at'])->format('d/m/Y H:i') : '',
                $v['order_description']
            ];
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'file-order.xlsx');
    }

    /**
     * Export data phiếu thu
     *
     * @param $input
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function _exportReceipt($input)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mSupplier = app()->get(SupplierTable::class);
        $mStaff = app()->get(StaffTable::class);
        $mReceipt = app()->get(ReceiptTable::class);

        $heading = [
            __('STT'),
            __('MÃ PHIẾU'),
            __('LOẠI PHIẾU'),
            __('ĐỐI TƯỢNG'),
            __('TÊN ĐỐI TƯỢNG'),
            __('TRẠNG THÁI'),
            __('SỐ TIỀN THU'),
            __('NGƯỜI TẠO'),
            __('NGÀY GHI NHẬN'),
            __('NGÀY THANH TOÁN')
        ];

        $data = [];

        //Lấy thông tin phiếu thu
        $getReceipt = $mReceipt->getReceiptExportSie($input['before_date']);

        foreach ($getReceipt as $k => $v) {
            $object_accounting_name = null;

            switch ($v['object_accounting_type_code']) {
                case 'OAT_CUSTOMER':
                    //Khách hàng
                    $info = $mCustomer->getInfoById($v['object_accounting_id']);

                    $v['object_accounting_name'] = $info['full_name'];
                    break;
                case 'OAT_SUPPLIER':
                    //Nhà cung cấp
                    $info = $mSupplier->getInfo($v['object_accounting_id']);

                    $v['object_accounting_name'] = $info['supplier_name'];
                    break;
                case 'OAT_EMPLOYEE':
                    //Nhân viên
                    $info = $mStaff->getInfo($v['object_accounting_id']);

                    $v['object_accounting_name'] = $info['full_name'];
                    break;
            }

            if ($v['object_type'] != 'debt' && $v['order_id'] === 0) {
                $objectType = $v['object_accounting_type_name'];
                $objectName = $v['object_accounting_name'];
            } else if ($v['object_type'] == 'debt') {
                $objectType = __('Công nợ');
                $objectName = $v['customer_name_debt'];
            } else {
                $objectType = __('Khách hàng');
                $objectName = $v['customer_name'];
            }

            $status = "";

            switch ($v['status']) {
                case 'unpaid':
                    $status = __('Chưa thanh toán');
                    break;
                case 'part-paid':
                    $status = __('Thanh toán một phần');
                    break;
                case 'paid':
                    $status = __('Đã thanh toán');
                    break;
                case 'cancel':
                    $status = __('Hủy');
                    break;
                case 'fail':
                    $status = __('Lỗi');
                    break;
            }

            $datePayment = "";

            if ($v['status'] == 'paid') {
                $datePayment = Carbon::parse($v['updated_at'])->format('d/m/Y H:i');
            }

            $data [] = [
                $k+1,
                $v['receipt_code'],
                $v['receipt_type_name'],
                $objectType,
                $objectName,
                $status,
                number_format($v['amount'], 0),
                $v['staff_name'],
                Carbon::parse($v['created_at'])->format('d/m/Y H:i'),
                $datePayment
            ];
        }

        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'file-receipt.xlsx');
    }

    /**
     * Export data công nợ
     *
     * @param $input
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function _exportDebt($input)
    {
        $mCustomerDebt = app()->get(CustomerDebtTable::class);

        $heading = [
            __('STT'),
            __('Mã công nợ'),
            __('Chi nhánh'),
            __('Mã đơn hàng'),
            __('Khách hàng'),
            __('Số điện thoại'),
            __('Số tiền nợ'),
            __('Đã trả'),
            __('Còn nợ'),
            __('Người tạo'),
            __('Ngày tạo'),
            __('Ghi chú')

        ];

        $data = [];

        //Lấy thông tin công nợ
        $getDebt = $mCustomerDebt->getDebtExportSie($input['before_date']);

        foreach ($getDebt as $k => $v) {
            $data [] = [
                $k + 1,
                $v['debt_code'],
                $v['branch_name'],
                $v['order_code'],
                $v['customer_name'],
                $v['customer_phone'],
                number_format($v['amount'], 0),
                number_format($v['amount_paid'], 0),
                number_format($v['amount'] - $v['amount_paid'], 0),
                $v['staff_name'],
                Carbon::parse($v['created_at'])->format('d/m/Y H:i'),
                $v['note']
            ];
        }


        if (ob_get_level() > 0) {
            ob_end_clean();
        }

        return Excel::download(new ExportFile($heading, $data), 'file-debt.xlsx');
    }
}