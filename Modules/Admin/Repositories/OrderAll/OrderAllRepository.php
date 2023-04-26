<?php

/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 11/20/2018
 * Time: 10:20 PM
 */

namespace Modules\Admin\Repositories\OrderAll;


use App\Exports\ExportFile;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Admin\Models\OrderTable;
use Modules\Admin\Models\ReceiptTable;
use Modules\Admin\Repositories\Branch\BranchRepositoryInterface;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Admin\Repositories\OrderApp\OrderAppRepoInterface;

class OrderAllRepository implements OrderAllRepositoryInterface
{
    protected $orders;
    protected $orderApp;
    protected $branch;

    const NEW = 'new';
    const PAYSUCCESS = 'paysuccess';
    const PAY_HALF = 'pay-half';
    const ORDER_CANCEL = 'ordercancle';
    const CONFIRMED = 'confirmed';

    public function __construct(
        OrderRepositoryInterface $orders,
        OrderAppRepoInterface $orderApp,
        BranchRepositoryInterface $branch

    )
    {
        $this->orders = $orders;
        $this->orderApp = $orderApp;
        $this->branch = $branch;
    }

    const LIVE = 1;
    public function allOrder($screening =[]){

        $directlyOrder = $this->orders->list();
        return $directlyOrder;
    }

    public function list($screening = [])
    {
        // TODO: Implement list() method.
    }
    /**
     * Danh sách đơn hàng
     *
     * @param array $filters
     * @return mixed
     */
    public function listAll(array $filters = [])
    {
        $mOrdersTable = app()->get(OrderTable::class);
        $list = $mOrdersTable ->getOrderAll($filters);

        //Data Receipt
        $mReceipt = new ReceiptTable();
        $listReceipt = $mReceipt->getAllReceipt();

        $arrReceipt = [];
        foreach ($listReceipt as $item) {
            $arrReceipt[$item['order_id']] = [
                'order_id' => $item['order_id'],
                'amount_paid' => $item['amount_paid'],
                'note' => $item['note']
            ];
        }
        return [
            'list' => $list,
            'receipt' => $arrReceipt,
        ];
    }
    /**
     * Export danh sách đơn hàng
     * @param array $params
     * @return mixed
     */
    public function exportList($params = [])
    {
        $data = $this->listAll($params);
        $list = $data['list'];
        $receipt = $data['receipt'];
        //Data export
        $arr_data = [];
        foreach ($list as $key => $item) {
            $amount = '0';
            $temp = 0;
            if (isset(config()->get('config.decimal_number')->value)) {
                $temp = config()->get('config.decimal_number')->value;
            }
            $amount = number_format($item['amount'], $temp);
            $rec = '0';
            if (isset($receipt[$item['order_id']])) {
                $rec = number_format($receipt[$item['order_id']]['amount_paid'], $temp);
            }
            $status = __('Đã thanh toán');
            switch ($item['process_status']) {
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
            $note = $item['order_description'];
            if ($item['process_status'] == self::NEW) {
                $note = $item['order_description'];
            } elseif ($item['process_status'] == self::PAYSUCCESS) {
                if (isset($receipt[$item['order_id']])) {
                    $note = $receipt[$item['order_id']]['note'];
                }
            } elseif ($item['process_status'] == self::ORDER_CANCEL) {
                $note = $item['order_description'];
            }
            $arr_data[] = [
                $key + 1,
                $item['order_code'],
                $item['order_source_name'],
                $item['full_name_cus'],
                $item['full_name'],
                $amount,
                $rec,
                $item['order_source_name'],
                $item['branch_name'],
                $status,
                $note,
                date("d/m/Y", strtotime($item['created_at']))
            ];
        }
        $heading = [
            __('STT'),
            __('MÃ ĐƠN HÀNG'),
            __('LOẠI ĐƠN HÀNG'),
            __('KHÁCH HÀNG'),
            __('NGƯỜI TẠO'),
            __('TỔNG TIỀN'),
            __('ĐÃ THANH TOÁN'),
            __('NGUỒN'),
            __('CHI NHÁNH'),
            __('TRẠNG THÁI'),
            __('GHI CHÚ'),
            __('NGÀY TẠO'),
        ];
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        return Excel::download(new ExportFile($heading, $arr_data), 'order-all.xlsx');
    }

}