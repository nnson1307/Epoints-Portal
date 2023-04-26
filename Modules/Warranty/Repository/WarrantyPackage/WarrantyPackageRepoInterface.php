<?php

namespace Modules\Warranty\Repository\WarrantyPackage;

interface WarrantyPackageRepoInterface
{
    /**
     * Danh sách gói bảo hành
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data cho view thêm mới
     *
     * @return mixed
     */
    public function dataViewCreate();

    /**
     * Lưu gói bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data cho view chỉnh sửa
     *
     * @param $warrantyPackageCode
     * @return mixed
     */
    public function dataViewEdit($warrantyPackageCode);

    /**
     * Cập nhật gói bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá gói bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function delete($input);

    /**
     * Cập nhật trạng thái bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function updateStatus($input);

    /**
     * Data chi tiết gói bảo hành
     *
     * @param $warrantyPackageId
     * @return mixed
     */
    public function dataViewDetail($warrantyPackageId);

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
     * Phân trang ds discount sp, dv, thẻ dv cho view chi tiết
     *
     * @param array $filter
     * @return mixed
     */
    public function listDiscountDetail($filter = []);

    /**
     * Xóa dòng table sp, dv, thẻ db
     *
     * @param $data
     * @return mixed
     */
    public function removeTr($data);

}