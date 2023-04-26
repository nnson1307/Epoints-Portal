<?php


namespace Modules\ManagerProject\Repositories\Contract;


use Modules\ManagerProject\Models\ContractTable;

class ContractRepository implements ContractRepositoryInterface
{
    protected $mContract;

    public function __construct(ContractTable $contract)
    {
        $this->mContract = $contract;
    }

    /**
     * Lấy danh sách hợp đồng còn thời hạn
     */
    public function getAllContractUsing($idContract = null){
        return $this->mContract->getAllContractUsing($idContract);
    }
}