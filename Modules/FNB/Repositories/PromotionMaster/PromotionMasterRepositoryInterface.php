<?php


namespace Modules\FNB\Repositories\PromotionMaster;


interface PromotionMasterRepositoryInterface
{
    /**
     * Lấy chi tiết promotỉon
     * @param $promotionId
     * @return mixed
     */
    public function dataEdit($promotionId);

    /**
     * Cập nhật nội dung
     * @param $data
     * @return mixed
     */
    public function update($input);
}