<?php

namespace Modules\Warranty\Repository\WarrantyCard;

interface WarrantyCardRepoInterface
{
    /**
     * Danh sách phiếu bảo hành
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data view chỉnh sửa phiếu bảo hành điện tử
     *
     * @param $warrantyCardId
     * @return mixed
     */
    public function dataViewEdit($warrantyCardId);

    /**
     * Cập nhật thẻ bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Huỷ thẻ bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function cancel($input);

    /**
     * Kích hoạt thẻ bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function active($input);

    /**
     * Load tab chi tiết phiếu bảo hành
     *
     * @param $input
     * @return mixed
     */
    public function loadTabDetail($input);

}