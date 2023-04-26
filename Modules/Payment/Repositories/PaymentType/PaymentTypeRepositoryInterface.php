<?php
/**
 * Created by PhpStorm.
 * User: Nhandt
 * Date: 03/08/2021
 * Time: 17:05 AM
 */

namespace Modules\Payment\Repositories\PaymentType;

interface PaymentTypeRepositoryInterface
{
    public function getPaymentTypeOption();

    /**
     * Thêm nhanh loại phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function storeQuickly($input);

    /**
     * Danh sách loại phiếu chi
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Thêm mới loại phiếu chi
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
     * Cập nhật loại phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function update($input);

    /**
     * Xoá loại phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function destroy($input);

    /**
     * Cập nhật trạng thái loại phiếu chi
     *
     * @param $input
     * @return mixed
     */
    public function changeStatus($input);
}