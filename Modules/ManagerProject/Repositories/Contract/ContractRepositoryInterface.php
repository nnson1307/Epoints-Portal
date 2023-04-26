<?php


namespace Modules\ManagerProject\Repositories\Contract;


interface ContractRepositoryInterface
{
    /**
     * Lấy danh sách hợp đồng còn thời hạn sử dụng
     * @return mixed
     */
    public function getAllContractUsing($idContract = null);
}