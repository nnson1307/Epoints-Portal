<?php


namespace Modules\FNB\Repositories\PromotionMaster;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\FNB\Models\PromotionMasterTable;

class PromotionMasterRepository implements PromotionMasterRepositoryInterface
{
    private $promotionMaster;

    public function __construct(PromotionMasterTable $promotionMaster)
    {
        $this->promotionMaster = $promotionMaster;
    }

    /**
     * Lấy thông tin promotion
     * @param $promotionId
     * @return mixed|void
     */
    public function dataEdit($promotionId)
    {
        return $this->promotionMaster->getInfo($promotionId);
    }

    /**
     * Cập nhật nội dung
     * @param $data
     * @return mixed|void
     */
    public function update($input)
    {
        DB::beginTransaction();
        try {
            $dataMaster = [
                "promotion_name_en" => $input['promotion_name_en'],
                "description_en" => $input['description_en'],
                "description_detail_en" => $input['description_detail_en'],
            ];

            if ($input['image_en'] != null) {
                $dataMaster['image_en'] = $input['image_en'];
            }

            //update promotion master
            $this->promotionMaster->edit($dataMaster, $input['promotion_id']);
            DB::commit();
            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        }catch (Exception $e){
            DB::rollback();
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại'),
                '_message' => $e->getMessage()
            ];
        }
    }
}