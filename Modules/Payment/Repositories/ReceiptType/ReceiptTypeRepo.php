<?php

namespace Modules\Payment\Repositories\ReceiptType;

use Modules\Payment\Models\ReceiptTypeTable;

class ReceiptTypeRepo implements ReceiptTypeRepoInterface
{
    protected $receiptType;
    public function __construct(ReceiptTypeTable $receiptType)
    {
        $this->receiptType = $receiptType;
    }

    public function list(array $filters = [])
    {
        $list = $this->receiptType->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Thêm mới loại phiếu thu
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            $data = [
                "receipt_type_name_vi" => $input['name_vi'],
                "receipt_type_name_en" => $input['name_en'],
                "is_system" => 0
            ];
            $receiptTypeId = $this->receiptType->add($data);
            // update code
            $receiptTypeCode = 'RTC_' . date('dmY') . sprintf("%02d", $receiptTypeId);
            $this->receiptType->edit([
                "receipt_type_code" => $receiptTypeCode
            ], $receiptTypeId);
            return [
                'error' => false,
                'message' => __('Thêm thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm thất bại')
            ];
        }
    }

    /**
     * Data view chinh sua
     *
     * @param $id
     * @return array|mixed
     */
    public function dataViewEdit($id)
    {
        $receiptTypeInfo = $this->receiptType->getItem($id);
        return [
            'item' => $receiptTypeInfo
        ];
    }

    /**
     * Cập nhật loại phiếu thu
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            $data = [
                "receipt_type_name_vi" => $input['name_vi'],
                "receipt_type_name_en" => $input['name_en'],
                "is_active" => (int)$input['is_active']
            ];
            $this->receiptType->edit($data, $input['receipt_type_id']);
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }

    /**
     * Xoá loại phiếu thu
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            $this->receiptType->deleteType($input['receiptTypeId']);
            return [
                'error' => false,
                'message' => __('Xoá thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xoá thất bại')
            ];
        }
    }

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatus($input)
    {
        try {
            $this->receiptType->edit([
                'is_active' => $input['isActive']
            ], $input['receiptTypeId']);
            return [
                'error' => false,
                'message' => __('Cập nhật thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Cập nhật thất bại')
            ];
        }
    }
}