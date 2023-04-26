<?php


namespace Modules\Payment\Repositories\DiscountCauses;
use Illuminate\Support\Facades\Auth;
use Modules\Payment\Models\DiscountCausesTable;

class DiscountCausesRepo implements DiscountCausesRepoInterface
{
    protected $discountCause;

    public function __construct(DiscountCausesTable $discountCause)
    {
        $this->discountCause = $discountCause;
    }

    public function getList(array $filters = [])
    {
        $list = $this->discountCause->getList($filters);
        return [
            "list" => $list,
        ];
    }

    /**
     * Lưu 1 loại lý do giảm giá mới
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($input)
    {
        try {
            $dataInsert = [
                'discount_causes_name_vi' => $input["discount_causes_name_vi"],
                'discount_causes_name_en' => $input["discount_causes_name_en"],
                'created_by' => Auth::id(),
            ];
            $this->discountCause->add($dataInsert);
            return response()->json([
                'error' => false,
                'message' => __('Thêm loại lý do giảm giá thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Thêm loại lý do giảm giá thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Dữ liệu của View chỉnh sửa khi chỉnh sửa
     *
     * @param $paymentPackageId
     * @return array|mixed
     */
    public function dataViewEdit($paymentPackageId)
    {
        $data = $this->discountCause->getInfo($paymentPackageId);
        if($data == null){
            return [];
        }
        else{
            return $data;
        }
    }

    /**
     * Cập nhật 1 lý do giảm giá
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($input)
    {
        try {
            $dataUpdate = [
                'discount_causes_id' => $input["discount_causes_id"],
                'discount_causes_name_vi' => $input["discount_causes_name_vi"],
                'discount_causes_name_en' => $input["discount_causes_name_en"],
                'is_active' => $input["is_active"],
                'updated_by' => Auth::id(),
            ];
            $this->discountCause->edit($dataUpdate,$input["discount_causes_id"]);
            return response()->json([
                'error' => false,
                'message' => __('Chỉnh sửa loại lý do giảm giá thành công')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => __('Chỉnh sửa loại lý do giảm giá thất bại'),
                '_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Xoá lý do giảm giá
     *
     * @param $input
     * @return mixed
     */
    public function delete($input)
    {
        return $this->discountCause->deleteDiscountCauses($input);
    }
}