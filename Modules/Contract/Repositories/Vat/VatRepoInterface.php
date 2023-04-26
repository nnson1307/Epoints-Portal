<?php

namespace Modules\Contract\Repositories\Vat;

interface VatRepoInterface
{
    /**
     * Lấy data danh sách VAT
     *
     * @param array $filter
     * @return mixed
     */
    public function getList(array $filter = []);

    /**
     * Thêm VAT
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Lấy data view edit
     *
     * @param $input
     * @return mixed
     */
    public function getDataViewEdit($input);

    /**
     * Chỉnh sửa VAT
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Thay đổi trạng thái VAT
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}