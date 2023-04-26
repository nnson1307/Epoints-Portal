<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 12/5/2018
 * Time: 2:39 PM
 */

namespace Modules\Admin\Repositories\ReceiptDetail;


interface ReceiptDetailRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id);
    public function sumAmmount($id);

    /**
     * Lấy tổng tiền theo từng loại phương thức thanh toán
     *
     * @return mixed
     */
    public function getSumMoneyByReceiptType();

    public function getSumMoneyByReceiptTypeOptimize();

    /**
     * Lấy tổng tiền theo từng loại phương thức thanh toán có fillter
     *
     * @param $startTime
     * @param $endTime
     * @param $branchId
     * @return mixed
     */
    public function getSumMoneyByReceiptTypeFilter($startTime, $endTime, $branchId);

    public function getItemPaymentByOrder($id);
}