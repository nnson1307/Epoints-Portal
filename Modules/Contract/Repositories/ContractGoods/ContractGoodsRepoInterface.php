<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 16:22
 */

namespace Modules\Contract\Repositories\ContractGoods;


interface ContractGoodsRepoInterface
{
    /**
     * Lấy danh sách hàng hoá
     *
     * @param array $filter
     * @return mixed
     */
    public function list(array $filter = []);
    public function listAnnexGood(array $filter = []);

    /**
     * Thay đổi hàng hoá
     *
     * @param $input
     * @return mixed
     */
    public function changeObject($input);

    /**
     * Thêm hàng hoá
     *
     * @param $input
     * @return mixed
     */
    public function store($input);

    /**
     * Tìm kiếm đơn hàng
     *
     * @param $input
     * @return mixed
     */
    public function searchOrder($input);
}