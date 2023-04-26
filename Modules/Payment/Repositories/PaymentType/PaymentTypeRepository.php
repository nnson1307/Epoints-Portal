<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:05 AM
 */

namespace Modules\Payment\Repositories\PaymentType;

use Modules\Payment\Models\PaymentTypeTable;

class PaymentTypeRepository implements PaymentTypeRepositoryInterface
{
    protected $paymentType;

    public function __construct(PaymentTypeTable $paymentType)
    {
        $this->paymentType = $paymentType;
    }

    public function getPaymentTypeOption()
    {
        $array = array();
        foreach ($this->paymentType->getPaymentTypeOption() as $item) {
            $array[$item['payment_type_id']] = $item['payment_type_name_vi'];
        }
        return $array;
    }

    /**
     * Thêm nhanh loại phiếu chi
     *
     * @param $input
     * @return array
     */
    public function storeQuickly($input)
    {
        try {
            //Thêm loại phiếu chi
            $idType = $this->paymentType->add([
                'payment_type_name_vi' => $input['payment_type_name'],
                'payment_type_name_en' => $input['payment_type_name'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            return [
                'error' => false,
                'message' => __('Thêm mới thành công'),
                'payment_type_id' => $idType
            ];
        } catch (\Exception $ex) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại')
            ];
        }
    }

    public function list(array $filters = [])
    {
        $list = $this->paymentType->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Thêm mới loại thanh toán
     *
     * @param $input
     * @return array|mixed
     */
    public function store($input)
    {
        try {
            $data = [
                "payment_type_name_vi" => $input['name_vi'],
                "payment_type_name_en" => $input['name_en'],
//                "is_system" => 0
            ];
            $paymentTypeId = $this->paymentType->add($data);
            // update code
//            $paymentTypeCode = 'RTC_' . date('dmY') . sprintf("%02d", $paymentTypeId);
//            $this->paymentType->edit([
//                "payment_type_code" => $paymentTypeCode
//            ], $paymentTypeId);
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
        $paymentTypeInfo = $this->paymentType->getItem($id);
        return [
            'item' => $paymentTypeInfo
        ];
    }

    /**
     * Cập nhật loại thanh toán
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            $data = [
                "payment_type_name_vi" => $input['name_vi'],
                "payment_type_name_en" => $input['name_en'],
                "is_active" => (int)$input['is_active']
            ];
            $this->paymentType->edit($data, $input['payment_type_id']);
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
     * Xoá loại thanh toán
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            $this->paymentType->deleteType($input['paymentTypeId']);
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
            $this->paymentType->edit([
                'is_active' => $input['isActive']
            ], $input['paymentTypeId']);
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