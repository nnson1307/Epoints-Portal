<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 16:21
 */

namespace Modules\Contract\Repositories\ContractGoods;


use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Modules\Contract\Models\ContractAnnexGoodsTable;
use Modules\Contract\Models\ContractAnnexModel;
use Modules\Contract\Models\ContractAnnexTable;
use Modules\Admin\Repositories\Order\OrderRepositoryInterface;
use Modules\Contract\Models\ContractCategoriesTable;
use Modules\Contract\Models\ContractGoodsTable;
use Modules\Contract\Models\ContractLogGoodsTable;
use Modules\Contract\Models\ContractLogReceiptSpendTable;
use Modules\Contract\Models\ContractLogTable;
use Modules\Contract\Models\ContractMapOrderTable;
use Modules\Contract\Models\ContractPartnerTable;
use Modules\Contract\Models\ContractPaymentTable;
use Modules\Contract\Models\ContractReceiptDetailTable;
use Modules\Contract\Models\ContractReceiptTable;
use Modules\Contract\Models\ContractSpendTable;
use Modules\Contract\Models\ContractTable;
use Modules\Contract\Models\DealDetailTable;
use Modules\Contract\Models\DealTable;
use Modules\Contract\Models\OrderDetailTable;
use Modules\Contract\Models\OrderLogTable;
use Modules\Contract\Models\OrderTable;
use Modules\Contract\Models\ProductChildTable;
use Modules\Contract\Models\ReceiptDetailTable;
use Modules\Contract\Models\ReceiptTable;
use Modules\Contract\Models\ServiceCardTable;
use Modules\Contract\Models\ServiceTable;
use Modules\Contract\Models\UnitTable;
use Modules\Contract\Repositories\Contract\ContractRepoInterface;

class ContractGoodsRepo implements ContractGoodsRepoInterface
{
    const GOODS = "goods";
    const RECEIPT = "receipt";

    /**
     * Danh sách hàng hoá
     *
     * @param array $filter
     * @return array|mixed
     */
    public function list(array $filter = [])
    {
        $mGoods = app()->get(ContractGoodsTable::class);
        $mUnit = app()->get(UnitTable::class);
        $mContract = app()->get(ContractTable::class);
        $mContractMapOrder = app()->get(ContractMapOrderTable::class);

        //Lấy ds hàng hoà
        $getGoods = $mGoods->getList($filter);
        //Lấy option đơn vị tính
        $optionUnit = $mUnit->getOption();
        //Lấy thông tin HĐ
        $info = $mContract->getInfo($filter['contract_id']);
        //Lấy thông tin đơn hàng gần nhất để thanh toán
        $getOrder = $mContractMapOrder->getOrderMap($info['contract_code']);

        return [
            'LIST' => $getGoods,
            'countGoods' => count($getGoods),
            'optionUnit' => $optionUnit,
            'infoOrder' => $getOrder
        ];
    }
    public function listAnnexGood(array $filter = [])
    {
        $mContract = app()->get(ContractTable::class);
        $mContractMapOrder = new ContractMapOrderTable();
        $mGoods = app()->get(ContractGoodsTable::class);
        $mUnit = app()->get(UnitTable::class);
        $mContractAnnexGood = new ContractAnnexGoodsTable();
        $mContractAnnex = new ContractAnnexModel();
        $mContractSpend = app()->get(ContractSpendTable::class);
        $mContractPayment = app()->get(ContractPaymentTable::class);
        $dataAnnexLocal = (array)json_decode($filter['dataAnnexLocal']);
        // check đã thanh toán
        $isReceipted = 1;
        $info = $mContract->getInfo($dataAnnexLocal['contract_id']);
        if($info['type'] == 'sell'){
            $dataMap = $mContractMapOrder->getOrderMap($info['contract_code']);
            if($dataMap != null){
                $dataOrder = $mContractMapOrder->getOrderMapByContract($info['contract_code'], $dataMap['order_code']);
                if($dataOrder != null){
                    $isReceipted = 0;
                }
            }
        }
        else{
            //Lấy giá trị hợp đồng
            $payment = $mContractPayment->getPaymentByContract($dataAnnexLocal['contract_id']);

            $lastTotalAmount = $payment['last_total_amount'] != null ? floatval($payment['last_total_amount']) : 0;
            //Lấy tiền đã thu của HĐ
            $getAmountPaid = $mContractSpend->getAmountSpend($dataAnnexLocal['contract_id']);

            $amountPaid = $getAmountPaid != null ? floatval($getAmountPaid['total_amount']) : 0;

            //Nếu hđ mua thì check đã chi hết tiền chua mới cho update
            if (($lastTotalAmount - $amountPaid) > 0) {
                $isReceipted = 0;
            }
        }

        // lưu thông tin phụ lục
        $infoAnnex = $mContractAnnex->getInfoByCode($dataAnnexLocal['contract_annex_code']);
        if($infoAnnex == null){
            //Lấy ds hàng hoà
            $getGoods = $mGoods->getList($filter);
        }
        else{
            $newFilter['contract_annex_id'] = $infoAnnex['contract_annex_id'];
            $getGoods =$mContractAnnexGood->getList($newFilter);
            if(count($getGoods) == 0)
            {
                $getGoods = $mGoods->getList($filter);
            }
        }
        //Lấy option đơn vị tính
        $optionUnit = $mUnit->getOption();

        return [
            'LIST' => $getGoods,
            'countGoods' => count($getGoods),
            'optionUnit' => $optionUnit,
            'isReceipted' => $isReceipted
        ];
    }
    /**
     * Thay đổi hàng hoá
     *
     * @param $input
     * @return mixed|void
     */
    public function changeObject($input)
    {
        $mProduct = app()->get(ProductChildTable::class);
        $mService = app()->get(ServiceTable::class);
        $mServiceCard = app()->get(ServiceCardTable::class);

        $objectCode = "";
        $objectName = "";
        $price = 0;
        $unitId = "";
        $isAppliedKpi = 1;

        switch ($input['object_type']) {
            case "product":
                //Lấy thông tin sản phẩm
                $info = $mProduct->getInfo($input['object_id']);

                $objectCode = $info != null ? $info['product_code'] : '';
                $objectName = $info != null ? $info['product_child_name'] : '';
                $price = $info != null ? $info['price'] : '';
                $unitId = $info != null ? $info['unit_id'] : '';
                $isAppliedKpi = $info != null ? $info['is_applied_kpi'] : 1;
                break;
            case "service":
                //Lấy thông tin dịch vụ
                $info = $mService->getInfo($input['object_id']);

                $objectCode = $info != null ? $info['service_code'] : '';
                $objectName = $info != null ? $info['service_name'] : '';
                $price = $info != null ? $info['price'] : '';
                break;
            case "service_card":
                //Lấy thông tin thẻ dịch  vụ
                $info = $mServiceCard->getInfo($input['object_id']);

                $objectCode = $info != null ? $info['code'] : '';
                $objectName = $info != null ? $info['name'] : '';
                $price = $info != null ? $info['price'] : '';
                break;
        }

        if ($input['contract_category_type'] == 'sell') {
            $mContractPartner = app()->get(ContractPartnerTable::class);
            $mOrderRepo = app()->get(OrderRepositoryInterface::class);

            //Lấy thông tin đối tác
            $infoPartner = $mContractPartner->getPartnerByContract($input['contract_id']);
            //Lấy giá khuyến mãi
            $getPrice = $mOrderRepo->getPromotionDetail($input['object_type'], $objectCode, $infoPartner['partner_object_id'], 'live', 1);

            if ($getPrice != null) {
                $price = $getPrice;
            }
        }

        return [
            "objectCode" => $objectCode,
            "objectName" => $objectName,
            "price" => $price,
            "unitId" => $unitId,
            "isAppliedKpi" => $isAppliedKpi,
        ];
    }

    /**
     * Thêm hàng hoá
     *
     * @param $input
     * @return mixed|void
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mContract = app()->get(ContractTable::class);
            $mContractGoods = app()->get(ContractGoodsTable::class);
            $mLog = app()->get(ContractLogTable::class);
            $mLogGoods = app()->get(ContractLogGoodsTable::class);
            $mContractMapOrder = app()->get(ContractMapOrderTable::class);
            $mContractCategory = new ContractCategoriesTable();
            $mOrder = app()->get(OrderTable::class);
            $mOrderDetail = app()->get(OrderDetailTable::class);
            $mContractPayment = app()->get(ContractPaymentTable::class);

            //Lấy thông tin HĐ
            $infoContract = $mContract->getInfo($input['contract_id']);
            //Lấy thông tin loại HĐ
            $infoCategory = $mContractCategory->getItem($infoContract['contract_category_id']);

            $totalAmount = 0;
            $totalVAT = 0;
            $totalDiscount = 0;
            $lastTotalAmount = 0;
            $countNoKpi = 0;
            //Xoá hàng hoá cũ
            $mContractGoods->removeGoodsByContract($input['contract_id']);

            $orderCodeLoad = isset($input['order_code']) ? $input['order_code'] : null;

            if (isset($input['arrData']) && count($input['arrData']) > 0) {
                //Lưu log hợp đồng khi thêm hàng hoá
                $logId = $mLog->add([
                    "contract_id" => $input['contract_id'],
                    "change_object_type" => self::GOODS,
                    "note" => __('Thay đổi hàng hoá'),
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);

                foreach ($input['arrData'] as $v) {
                    //Lưu thông tin hàng hoá
                    $goodsId = $mContractGoods->add([
                        "contract_id" => $input['contract_id'],
                        "object_type" => $v['object_type'],
                        "object_name" => $v['object_name'],
                        "object_id" => $v['object_id'],
                        "object_code" => $v['object_code'],
                        "unit_id" => $v['unit_id'],
                        "price" => $v['price'],
                        "quantity" => $v['quantity'],
                        "discount" => $v['discount'],
                        "tax" => $v['tax'],
                        "amount" => $v['amount'],
                        "note" => $v['note'],
                        "is_applied_kpi" => $v['is_applied_kpi'],
                        "order_code" => $v['order_code'] != null ? $v['order_code'] : $orderCodeLoad,
                        "staff_id" => $v['staff_id'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id()
                    ]);

                    //Log detail
                    $mLogGoods->add([
                        "contract_log_id" => $logId,
                        "contract_godds_id" => $goodsId,
                        "object_type" => $v['object_type'],
                        "object_name" => $v['object_name'],
                        "object_id" => $v['object_id'],
                        "object_code" => $v['object_code'],
                        "unit_id" => $v['unit_id'],
                        "price" => $v['price'],
                        "quantity" => $v['quantity'],
                        "discount" => $v['discount'],
                        "tax" => $v['tax'],
                        "amount" => $v['amount'],
                        "note" => $v['note']
                    ]);

                    $totalAmount += $v['price'] * $v['quantity'];
                    $totalVAT += $v['tax'];
                    $totalDiscount += $v['discount'];
                    $lastTotalAmount += $v['amount'];

                    $countNoKpi += (int)$v['is_applied_kpi'];
                }
            }

            $mContract->edit([
                'is_applied_kpi' => $countNoKpi == 0 ? 0 : 1
            ], $input['contract_id']);

            $infoOrder = null;

            if ($infoCategory['type'] == 'sell') {
                $input['total_amount'] = $totalAmount;
                $input['total_discount'] = $totalDiscount;
                $input['total_tax'] = $totalVAT;
                $input['last_total_amount'] = $lastTotalAmount;

                //Lấy thông tin đơn hàng
                $infoOrder = $mOrder->getInfoByCode($orderCodeLoad);

                //Nếu order_code = null thì tạo đơn hàng mới
                if ($infoOrder == null && $orderCodeLoad == null) {
                    //Tạo đơn hàng mới
                    $infoOrder = $this->_insertOrder($infoContract, $input);
                }

                //Cập nhật lại order code ở contract_goods
                $mContractGoods->editByContractId([
                    'order_code' => $infoOrder['order_code']
                ],$input['contract_id']);

                //Xoá sản phâm của đơn hàng
                $mOrderDetail->removeDetailByOrder($infoOrder['order_id']);
                //Update thông tin đơn hàng
                if (isset($input['arrData']) && count($input['arrData']) > 0) {
                    foreach ($input['arrData'] as $v) {
                        $mOrderDetail->add([
                            "order_id" => $infoOrder['order_id'],
                            "object_id" => $v['object_id'],
                            "object_name" => $v['object_name'],
                            "object_type" => $v['object_type'],
                            "object_code" => $v['object_code'],
                            "staff_id" => $v['staff_id'],
                            "price" => $v['price'],
                            "quantity" => $v['quantity'],
                            "discount" => $v['discount'],
                            "amount" => $v['amount'],
                            "tax" => $v['tax']
                        ]);
                    }
                }

                //Kiểm tra đơn hàng đã map với hợp đồng chưa
                $checkOrderMap = $mContractMapOrder->getOrderMapByContract($infoContract['contract_code'], $orderCodeLoad);

                if ($checkOrderMap == null) {
                    //Insert map với hđ
                    $mContractMapOrder->add([
                        'contract_code' => $infoContract['contract_code'],
                        'order_code' => $infoOrder['order_code'],
                        'source' => $input['contract_source']
                    ]);

                    //Lần đầu map với đơn hàng thì insert chi tiết thu
                    if (in_array($infoOrder['process_status'], ['paysuccess', 'pay-half'])) {
                        //Nếu đơn hàng đã thanh toán - insert chi tiết thu
                        $this->_insertContractReceipt($infoContract, $infoOrder['order_id']);
                    }
                }

                //Update giá trị đơn hàng
                $mOrder->edit([
                    'total' => $totalAmount,
                    'discount' => $totalDiscount,
                    'amount' => $lastTotalAmount,
                    'total_tax' => $totalVAT
                ], $infoOrder['order_id']);
            }

            //Update giá trị hợp đồng (nếu là hđ bán, or là hđ mua có check lấy giá trị)
            if ($infoCategory['type'] == 'sell' || $infoContract['is_value_goods'] == 1) {
                $mContractPayment->edit([
                    'total_amount' => $totalAmount,
                    'tax' => $totalVAT,
                    'discount' => $totalDiscount,
                    'total_amount_after_discount' => $totalAmount - $totalDiscount,
                    'last_total_amount' => $lastTotalAmount
                ], $infoContract['contract_id']);
            }

            $mContractRepo = app()->get(ContractRepoInterface::class);
            $mContractRepo->saveContractNotification('updated_content', $input['contract_id'], __('Hàng hoá'));
            DB::commit();

            return response()->json([
                "error" => false,
                "message" => __("Lưu thành công"),
                "infoOrder" => $infoOrder
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                "error" => true,
                "message" => __("Lưu thất bại"),
                "_message" => $e->getMessage() . ' ' . $e->getLine()
            ]);
        }
    }

    /**
     * Thêm nhanh đơn hàng khi tạo hợp đồng bán
     *
     * @param $infoContract
     * @param $input
     * @return array
     */
    private function _insertOrder($infoContract, $input)
    {
        $mContractPartner = app()->get(ContractPartnerTable::class);

        //Lấy thông tin đối tác
        $infoPartner = $mContractPartner->getPartnerByContract($infoContract['contract_id']);

        if ($infoPartner != null) {
            $mOrder = app()->get(OrderTable::class);
            $mOrderLog = app()->get(OrderLogTable::class);

            //Thêm đơn hàng
            $idOrder = $mOrder->add([
                'branch_id' => Auth()->user()->branch_id,
                'customer_id' => $infoPartner['partner_object_id'],
                'total' => $input['total_amount'],
                'discount' => $input['total_discount'],
                'amount' => $input['last_total_amount'],
                'tranport_charge' => 0,
                'total_tax' => $input['total_tax'],
                'order_source_id' => 1,
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);
            //Update order code
            $orderCode = 'DH_' . date('dmY') . sprintf("%02d", $idOrder);
            $mOrder->edit([
                'order_code' => $orderCode
            ], $idOrder);
            //Insert order log đơn hàng mới
            $mOrderLog->insert([
                'order_id' => $idOrder,
                'created_type' => 'backend',
                'status' => 'new',
                'created_by' => Auth()->id(),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'note_vi' => 'Đặt hàng thành công',
                'note_en' => 'Order success'
            ]);

            //Lấy thông tin đơn hàng
            return $mOrder->getOrderByCode($infoPartner['partner_object_id'], $orderCode);
        }
    }

    /**
     * Thêm chi tiết thu cho hợp đồng
     *
     * @param $infoContract
     * @param $orderId
     */
    private function _insertContractReceipt($infoContract, $orderId)
    {
        $mReceipt = app()->get(ReceiptTable::class);
        $mReceiptDetail = app()->get(ReceiptDetailTable::class);
        $mContractReceipt = app()->get(ContractReceiptTable::class);
        $mContractReceiptDetail = app()->get(ContractReceiptDetailTable::class);
        $mLog = app()->get(ContractLogTable::class);
        $mLogReceipt = app()->get(ContractLogReceiptSpendTable::class);

        //Lấy thông tin thanh toán của đơn hàng
        $getReceipt = $mReceipt->getTotalReceipt($orderId);

        if (count($getReceipt) > 0) {
            foreach ($getReceipt as $v) {
                //Thêm đợt thu
                $contractReceiptId = $mContractReceipt->add([
                    'contract_id' => $infoContract['contract_id'],
                    'receipt_code' => $v['receipt_code'],
                    'content' => __("Thanh toán đơn hàng") . ' ' . $v['order_code'],
                    'collection_date' => Carbon::now()->format('Y-m-d'),
                    'collection_by' => $v['staff_id'],
                    'total_amount_receipt' => $v['amount_paid'],
                    'created_by' => Auth()->id(),
                    'updated_by' => Auth()->id()
                ]);
                //Lấy thông tin chi tiết thanh toán
                $getReceiptDetail = $mReceiptDetail->getReceiptDetail($v['receipt_id']);

                $arrReceiptDetail = [];

                if (count($getReceiptDetail) > 0) {
                    foreach ($getReceiptDetail as $v1) {
                        $arrReceiptDetail [] = [
                            "contract_receipt_id" => $contractReceiptId,
                            "amount_receipt" => $v1['amount'],
                            "payment_method_id" => $v1['payment_method_id'],
                            "created_at" => Carbon::now()->format('Y-m-d H:i:s'),
                            "updated_at" => Carbon::now()->format('Y-m-d H:i:s')
                        ];
                    }
                }
                //Thêm chi tiết đợt thu
                $mContractReceiptDetail->insert($arrReceiptDetail);

                //Lưu log hợp đồng khi trigger thu - chi
                $logId = $mLog->add([
                    "contract_id" => $infoContract['contract_id'],
                    "change_object_type" => self::RECEIPT,
                    "note" => __('Thêm đợt thu'),
                    "created_by" => Auth()->id(),
                    "updated_by" => Auth()->id()
                ]);
                //Log detail
                $mLogReceipt->add([
                    "contract_log_id" => $logId,
                    "object_type" => self::RECEIPT,
                    "object_id" => $contractReceiptId
                ]);
            }

        }
    }

    /**
     * Tìm kiếm đơn hàng
     *
     * @param $input
     * @return mixed|void
     */
    public function searchOrder($input)
    {
        $mContractPartner = app()->get(ContractPartnerTable::class);
        $mOrder = app()->get(OrderTable::class);
        $mOrderDetail = app()->get(OrderDetailTable::class);

        //Lấy thông tin đối tác
        $infoPartner = $mContractPartner->getPartnerByContract($input['contract_id']);
        //Lấy thông tin đơn hàng củ đối tác
        $infoOrder = $mOrder->getOrderByCode($infoPartner['partner_object_id'], $input['search_order']);

        if ($infoOrder != null) {
            //Lấy thông tin sản phẩm từ đơn hàng
            $orderDetail = $mOrderDetail->getDetail($infoOrder['order_id']);
            if (count($orderDetail) > 0) {
                return response()->json([
                    "error" => false,
                    "message" => __("Đã thêm hàng hoá vào danh sách"),
                    "data" => $orderDetail,
                    "infoOrder" => $infoOrder
                ]);
            } else {
                return response()->json([
                    "error" => true,
                    "message" => __("Không tìm thấy hàng hoá của mã đơn hàng này"),
                ]);
            }
        } else {
            return response()->json([
                "error" => true,
                "message" => __("Không tìm thấy hàng hoá của mã đơn hàng này"),
            ]);
        }
    }
}