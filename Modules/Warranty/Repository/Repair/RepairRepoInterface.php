<?php

namespace Modules\Warranty\Repository\Repair;

interface RepairRepoInterface
{
    /**
     * Danh sách phiếu bảo dưỡng
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data view tạo phiếu bảo dưỡng
     *
     * @return mixed
     */
    public function dataViewCreate();

    /**
     * Lưu thông tin bảo dưỡng
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view chinh sua phiếu bảo dưỡng
     *
     * @param $repairId
     * @return mixed
     */
    public function dataViewEdit($repairId);

    /**
     * Cập nhật thông tin bảo dưỡng
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Render modal phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function modalPayment($input);

    /**
     * Thêm phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function submitPayment($input);
}