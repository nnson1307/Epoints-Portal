<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:05 AM
 */

namespace Modules\Payment\Repositories\PaymentUnit;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Models\PaymentMethodTable;
use Modules\Payment\Models\PaymentUnitTable;

class PaymentUnitRepository implements PaymentUnitRepositoryInterface
{
    protected $paymentUnit;
    public function __construct(PaymentUnitTable $paymentUnit)
    {
        $this->paymentUnit=$paymentUnit;
    }

    /**
     * Danh sách HTTT, filter, paging
     *
     * @param array $filters
     * @return array
     */
    public function getList(array &$filters = [])
    {
        $list = $this->paymentUnit->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Lưu HTTT mới
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($input)
    {
        try {
            $dataInsert = [
                'name' => $input["name"],
                'is_actived' => $input["is_actived"],
                'created_by' => Auth::id(),
            ];
            $this->paymentUnit->add($dataInsert);
            return response()->json([
                'error' => false,
                'message' => __('Thêm đơn vị thanh toán thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm đơn vị thanh toán thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * View Edit khi chọn Edit HTTT
     *
     * @param $warrantyPackageId
     * @return array|mixed
     */
    public function dataViewEdit($warrantyPackageId)
    {
        $data = $this->paymentUnit->getInfo($warrantyPackageId);
        if($data == null){
            return [];
        }
        else{
            return $data;
        }
    }

    /**
     * Cập nhật HTTT
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($input)
    {
        try {
            $dataUpdate = [
                'name' => $input["name"],
                'is_actived' => $input["is_actived"],
                'updated_by' => Auth::id()
            ];
            $this->paymentUnit->edit($dataUpdate,$input["payment_unit_id"]);
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa đơn vị thanh toán thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa đơn vị thanh toán thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xoá HTTT
     *
     * @param $input
     * @return mixed
     */
    public function delete($input)
    {
        return $this->paymentUnit->deleteType($input);
    }
}