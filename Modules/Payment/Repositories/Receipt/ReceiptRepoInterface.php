<?php

namespace Modules\Payment\Repositories\Receipt;

interface ReceiptRepoInterface
{
    /**
     * Danh sách phiếu thu
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Data cho view thêm mới phiếu thu
     *
     * @return mixed
     */
    public function dataViewCreate();

    /**
     * Lưu phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data cho view chỉnh sửa phiếu thu
     *
     * @param $id
     * @return mixed
     */
    public function dataViewEdit($id);

    /**
     * Cập nhật phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function delete($input);

    /**
     * Load option đối tượng phiếu thu theo loại
     *
     * @param $input
     * @return mixed
     */
    public function loadOptionObjectAccounting($input);

    /**
     * View in bill
     *
     * @param $input
     * @return mixed
     */
    public function printBill($input);

    /**
     * Save log print bill
     *
     * @param $input
     * @return mixed
     */
    public function saveLogPrintBill($input);

    /**
     * Lấy data chi tiết phiếu thu
     *
     * @param $id
     * @return mixed
     */
    public function dataViewDetail($id);

    /**
     * Export excel phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function exportExcel($input);
}