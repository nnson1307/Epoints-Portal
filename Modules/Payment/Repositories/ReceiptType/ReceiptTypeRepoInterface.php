<?php

namespace Modules\Payment\Repositories\ReceiptType;

interface ReceiptTypeRepoInterface
{
    /**
     * Danh sách loại phiếu thu
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Thêm mới loại phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Data view chỉnh sửa
     *
     * @param $id
     * @return mixed
     */
    public function dataViewEdit($id);

    /**
     * Cập nhật loại phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá loại phiếu thu
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Cập nhật trạng thái
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}