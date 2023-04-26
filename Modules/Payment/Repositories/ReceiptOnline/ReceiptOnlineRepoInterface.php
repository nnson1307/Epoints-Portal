<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 04/01/2022
 * Time: 14:03
 */

namespace Modules\Payment\Repositories\ReceiptOnline;


interface ReceiptOnlineRepoInterface
{
    /**
     * Danh sách giao dịch online
     *
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters = []);

    /**
     * Huỷ thanh toán chuyển khoản
     *
     * @param $input
     * @return mixed
     */
    public function cancel($input);

    /**
     * Thanh toán chuyển khoản thành công
     *
     * @param $input
     * @return mixed
     */
    public function success($input);
}