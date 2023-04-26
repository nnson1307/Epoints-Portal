<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/8/2020
 * Time: 2:27 PM
 */

namespace Modules\Promotion\Repositories\Promotion;


use Illuminate\Http\Request;

interface PromotionRepoInterface
{
    /**
     * Danh sách CTKM
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data view create
     *
     * @return mixed
     */
    public function dataCreate();

    /**
     * Show popup sp/dv/thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function showPopup($data);

    /**
     * Ajax filter, phân trang product
     *
     * @param $filter
     * @return mixed
     */
    public function listProduct($filter);

    /**
     * Ajax filter, phân trang service
     *
     * @param $filter
     * @return mixed
     */
    public function listService($filter);

    /**
     * Ajax filter, phân trang service card
     *
     * @param $filter
     * @return mixed
     */
    public function listServiceCard($filter);

    /**
     * Chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function chooseAll($data);

    /**
     * Chọn sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function choose($data);

    /**
     * Bỏ chọn all trên 1 page sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function unChooseAll($data);

    /**
     * Bỏ chọn sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function unChoose($data);

    /**
     * Submit chọn sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function submitChoose($data);

    /**
     * Phân trang ds discount sp, dv, thẻ dv
     *
     * @param array $filter
     * @return mixed
     */
    public function listDiscount($filter = []);

    /**
     * Thay đổi giá khuyến mãi
     *
     * @param $data
     * @return mixed
     */
    public function changePrice($data);

    /**
     * Xóa dòng table sp, dv, thẻ db
     *
     * @param $data
     * @return mixed
     */
    public function removeTr($data);

    /**
     * Thay đổi trạng thái table sp, dv, thẻ dv
     *
     * @param $data
     * @return mixed
     */
    public function changeStatusTr($data);

    /**
     * Option sp, dv, thẻ dv
     *
     * @param array $filter
     * @return mixed
     */
    public function listOption($filter = []);

    /**
     * Thay đổi loại quà tặng
     *
     * @param $data
     * @return mixed
     */
    public function changeGiftType($data);

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param $filter
     * @return mixed
     */
    public function listGift($filter = []);

    /**
     * Thay đổi quà tặng
     *
     * @param $data
     * @return mixed
     */
    public function changeGift($data);

    /**
     * Thay đổi số lượng cần mua
     *
     * @param $data
     * @return mixed
     */
    public function changeQuantityBuy($data);

    /**
     * Thay đổi số lượng quà tặng
     *
     * @param $data
     * @return mixed
     */
    public function changeNumberGift($data);

    /**
     * Submit thêm CTKM
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view edit
     *
     * @param $promotionId
     * @return mixed
     */
    public function dataEdit($promotionId);

    /**
     * Submit chỉnh sửa CTKM
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xóa CTKM
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Thay đổi trạng thái CTKM
     *
     * @param $input
     * @return mixed
     */
    public function changeStatusPromotion($input);

    /**
     * Phân trang ds discount sp, dv, thẻ dv
     *
     * @param array $filter
     * @return mixed
     */
    public function listDiscountDetail($filter = []);

    /**
     * Phân trang ds gift sp, dv, thẻ dv
     *
     * @param $filter
     * @return mixed
     */
    public function listGiftDetail($filter = []);

    /**
     * Load session all
     *
     * @param $promotionCode
     * @return mixed
     */
    public function loadSessionAll($promotionCode);
}