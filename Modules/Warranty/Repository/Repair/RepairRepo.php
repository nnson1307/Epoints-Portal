<?php

namespace Modules\Warranty\Repository\Repair;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Warranty\Models\BranchTable;
use Modules\Warranty\Models\MaintenanceCostTypeTable;
use Modules\Warranty\Models\ObjectAccountingTypeTable;
use Modules\Warranty\Models\PaymentMethodTable;
use Modules\Warranty\Models\PaymentTable;
use Modules\Warranty\Models\PaymentTypeTable;
use Modules\Warranty\Models\ProductChildTable;
use Modules\Warranty\Models\RepairCostTable;
use Modules\Warranty\Models\RepairImageTable;
use Modules\Warranty\Models\RepairTable;
use Modules\Warranty\Models\ServiceCardTable;
use Modules\Warranty\Models\ServiceTable;
use Modules\Warranty\Models\StaffTable;

class RepairRepo implements RepairRepoInterface
{
    protected $repair;
    const OAT_EMPLOYEE = 'OAT_EMPLOYEE';
    public function __construct(RepairTable $repair)
    {
        $this->repair = $repair;
    }

    public function list(array $filters = [])
    {
        $list = $this->repair->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Data view thêm mới phiếu bảo dưỡng
     *
     * @return array|mixed
     */
    public function dataViewCreate()
    {
        $mStaff = app()->get(StaffTable::class);
        $mCostType = app()->get(MaintenanceCostTypeTable::class);

        //Lấy option nv đưa đi bảo dưỡng
        $optionStaff = $mStaff->getStaff();
        //Lấy option chi phí phát sinh
        $optionCostType = $mCostType->getCostType();

        return [
            'optionStaff' => $optionStaff,
            'optionCostType' => $optionCostType
        ];
    }

    /**
     * Lưu phiếu bảo dưỡng
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $repairDate = Carbon::createFromFormat('d/m/Y H:i', $input['repair_date'])->format('Y-m-d H:i');
            $dataInsert = [
                "repair_cost" => $input['repair_cost'],
                "insurance_pay" => $input['insurance_pay'],
                "amount_pay" => $input['amount_pay'], // tiền trả chưa bao gồm chi phí phát sinh
                "total_pay" => $input['total_amount_pay'], // tiền trả bao gồm chi phí phát sinh
                "staff_id" => $input['staff_id'],
                "object_type" => $input['object_type'],
                "object_id" => $input['object_id'],
                "object_code" => $input['object_code'],
                "object_status" => $input['object_status'],
                "repair_content" => $input['repair_content'],
                "repair_date" => $repairDate,
                "status" => "new",
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ];
            $repairId = $this->repair->add($dataInsert);
            $repairCode = 'PBD_' . date('dmY') . sprintf("%02d", $repairId);
            //Cập nhật mã phiếu bảo trì
            $this->repair->edit([
                'repair_code' => $repairCode
            ], $repairId);

            $arrInsertImage = [];
            $arrInsertCost = [];
            // Save image
            if (isset($input['imageBefore']) && count($input['imageBefore'])) {
                foreach ($input['imageBefore'] as $v) {
                    $arrInsertImage [] = [
                        "repair_code" => $repairCode,
                        "type" => "before",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            if (isset($input['imageAfter']) && count($input['imageAfter']) > 0) {
                foreach ($input['imageAfter'] as $v) {
                    $arrInsertImage [] = [
                        "repair_code" => $repairCode,
                        "type" => "after",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }
            //Insert hình ảnh trước, sau khi bảo trì
            $mRepairImage = app()->get(RepairImageTable::class);
            $mRepairImage->insert($arrInsertImage);

            if (isset($input['arrayCost']) && count($input['arrayCost']) > 0) {
                foreach ($input['arrayCost'] as $v) {
                    $arrInsertCost [] = [
                        "repair_id" => $repairId,
                        "maintenance_cost_type" => $v['costType'],
                        "cost" => $v['cost'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id(),
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }
            //Insert chi phí phát sinh
            $mRepairCost = app()->get(RepairCostTable::class);
            $mRepairCost->insert($arrInsertCost);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Thêm phiếu bảo dưỡng thành công')
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'message' => __('Thêm phiếu bảo dưỡng thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * data view chỉnh sửa phiếu bảo dưỡng
     *
     * @param $repairId
     * @return array|mixed
     */
    public function dataViewEdit($repairId)
    {
        $mStaff = app()->get(StaffTable::class);
        $mCostType = app()->get(MaintenanceCostTypeTable::class);
        $mRepairCost = app()->get(RepairCostTable::class);
        $mRepairImage = app()->get(RepairImageTable::class);
        $mPayment = app()->get(PaymentTable::class);

        //Lấy option nv thực hiện
        $optionStaff = $mStaff->getStaff();
        //Lấy option chi phí phát sinh
        $optionCostType = $mCostType->getCostType();
        //Lấy thông tin phiếu bảo dưỡng
        $info = $this->repair->getInfo($repairId);
        $info['object_name'] = $this->getObjectName($info['object_type'], $info['object_code']);
        //Lấy hình ảnh trước, sau khi bảo dưỡng
        $getImage = $mRepairImage->getImage($info['repair_code']);
        $imageBefore = [];
        $imageAfter = [];

        if (count($getImage) > 0) {
            foreach ($getImage as $v) {
                if ($v['type'] == "before") {
                    $imageBefore [] = $v['link'];
                } else if ($v['type'] == "after") {
                    $imageAfter [] = $v['link'];
                }
            }
        }

        //Lấy chi phí phát sinh khi bảo dưỡng
        $getCost = $mRepairCost->getCost($repairId);
        //Lấy thông tin thanh toán phiếu bảo dưỡng
        $getPayment = $mPayment->getPaymentByRepairCode($info['repair_code']);

        $isUpdate = 1;

        if (in_array($info['status'], ['finish', 'cancel']) || $getPayment != null) {
            $isUpdate = 0;
        }
        return [
            'optionStaff' => $optionStaff,
            'optionCostType' => $optionCostType,
            'item' => $info,
            'imageBefore' => $imageBefore,
            'imageAfter' => $imageAfter,
            'cost' => $getCost,
            'isUpdate' => $isUpdate,
            'getPayment' => $getPayment
        ];
    }

    /**
     * Cập nhật phiếu bảo dưỡng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $repairDate = Carbon::createFromFormat('d/m/Y H:i', $input['repair_date'])->format('Y-m-d H:i');
            $dataUpdate = [
                "repair_cost" => $input['repair_cost'],
                "insurance_pay" => $input['insurance_pay'],
                "amount_pay" => $input['amount_pay'], // tiền trả chưa bao gồm chi phí phát sinh
                "total_pay" => $input['total_amount_pay'], // tiền trả bao gồm chi phí phát sinh
                "staff_id" => $input['staff_id'],
                "object_type" => $input['object_type'],
                "object_id" => $input['object_id'],
                "object_code" => $input['object_code'],
                "object_status" => $input['object_status'],
                "repair_content" => $input['repair_content'],
                "repair_date" => $repairDate,
                "status" => $input['status'],
                "created_by" => Auth()->id(),
                "updated_by" => Auth()->id()
            ];
            $this->repair->edit($dataUpdate, $input['repair_id']);
            $arrInsertImage = [];
            $arrInsertCost = [];
            if (isset($input['imageBefore']) && count($input['imageBefore'])) {
                foreach ($input['imageBefore'] as $v) {
                    $arrInsertImage [] = [
                        "repair_code" => $input['repair_code'],
                        "type" => "before",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }

            if (isset($input['imageAfter']) && count($input['imageAfter']) > 0) {
                foreach ($input['imageAfter'] as $v) {
                    $arrInsertImage [] = [
                        "repair_code" => $input['repair_code'],
                        "type" => "after",
                        "link" => $v,
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }
            $mRepairImage = app()->get(RepairImageTable::class);
            //Xóa tất cả hình ảnh
            $mRepairImage->removeImage($input['repair_code']);
            //Insert hình ảnh trước, sau khi bảo dưỡng
            $mRepairImage->insert($arrInsertImage);

            if (isset($input['arrayCost']) && count($input['arrayCost']) > 0) {
                foreach ($input['arrayCost'] as $v) {
                    $arrInsertCost [] = [
                        "repair_id" => $input['repair_id'],
                        "maintenance_cost_type" => $v['costType'],
                        "cost" => $v['cost'],
                        "created_by" => Auth()->id(),
                        "updated_by" => Auth()->id(),
                        "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                        "updated_at" => Carbon::now()->format("Y-m-d H:i:s")
                    ];
                }
            }
            $mRepairCost = app()->get(RepairCostTable::class);
            //Xóa tất cả chi phí phát sinh
            $mRepairCost->removeCost($input['repair_id']);
            //Insert chi phí phát sinh
            $mRepairCost->insert($arrInsertCost);

            DB::commit();
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * View phiếu chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function modalPayment($input)
    {
        $mPaymentMethod = app()->get(PaymentMethodTable::class);
        $mObjectAccountingType = app()->get(ObjectAccountingTypeTable::class);
        $mPaymentType = app()->get(PaymentTypeTable::class);
        $optionPaymentMethod = $mPaymentMethod->getOption();
        $optionObjAccType = $mObjectAccountingType->getOption();
        $optionPaymentType = $mPaymentType->getOption();
        //Lấy thông tin phiếu bảo dưỡng
        $info = $this->repair->getInfo($input['repair_id']);

        //Render view
        $html = \View::make('warranty::repair.pop.modal-payment', [
            'info' => $info,
            'optionPaymentMethod' => $optionPaymentMethod,
            'optionObjAccType' => $optionObjAccType,
            'optionPaymentType' => $optionPaymentType
        ])->render();

        return response()->json([
            'html' => $html
        ]);
    }

    /**
     * Thêm phiếu chi
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function submitPayment($input)
    {
        try {
            $mStaff = new StaffTable();
            $mPayment = new PaymentTable();
            $moneyInput = str_replace(',', '', $input['money']);
            // check tiền chi trong input và tổng chi phí trong phiếu bảo dưỡng có giống nhau không
            $repairInfo = $this->repair->getInfo($input['repair_id']);
            if ($repairInfo != null) {
                if ((float)$moneyInput != (float)$repairInfo['total_pay']) {
                    return response()->json([
                        'error' => true,
                        'message' => __('Số tiền không hợp lệ')
                    ]);
                }
            }
            // lấy branch code theo nhân viên
            $staffInfo = $mStaff->getItem($input['staff_id']);

            $dataInsert = [
                'branch_code' => $staffInfo != null ? $staffInfo['branch_code'] : null,
                'staff_id' => $input['staff_id'],
                'total_amount' => $moneyInput,
                'status' => 'paid',
                'note' => $input['note'],
                'payment_date' => Carbon::now()->format('Y-m-d H:i:s'),
                'object_accounting_type_code' => self::OAT_EMPLOYEE,
                'accounting_id' => $input['staff_id'],
                'accounting_name' => $staffInfo != null ? $staffInfo['full_name']: null,
                'payment_type' => $input['payment_type'],
                'document_code' => $input['document_code'],
                'payment_method' => $input['payment_method'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
                'is_delete' => 0,
            ];

            $paymentId = $mPayment->add($dataInsert);
            // payment code
            $paymentCode = 'PM_' . date('dmY') . sprintf("%02d", $paymentId);
            $mPayment->edit([
                'payment_code' => $paymentCode
            ], $paymentId);

            return response()->json([
                'error' => false,
                'message' => __('Thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => false,
                'message' => __('Thất bại')
            ]);
        }
    }

    /**
     * Lấy tên của đối tượng được bảo hành
     *
     * @param $objectType
     * @param $objectCode
     * @return string
     */
    private function getObjectName($objectType, $objectCode)
    {
        $name = '';

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(ProductChildTable::class);
                //Lấy thông tin sản phẩm
                $getInfo = $mProduct->getProduct($objectCode);
                $name = $getInfo != null ? $getInfo['product_child_name'] : '';

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dịch vụ
                $getInfo = $mService->getService($objectCode);
                $name = $getInfo != null ? $getInfo['service_name'] : '';

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCardTable::class);
                //Lấy thông tin thẻ dịch vụ
                $getInfo = $mServiceCard->getServiceCard($objectCode);
                $name = $getInfo != null ? $getInfo['name'] : '';

                break;
        }

        return $name;
    }
}