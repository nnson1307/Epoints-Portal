<?php


namespace Modules\FNB\Repositories\FNBCustomerRequest;


interface FNBCustomerRequestRepositoryInterface
{
    /**
     * Thay đổi trạng thái yêu cầu khách hàng
     * @param $data
     * @return mixed
     */
    public function confirmCustomerRequest($data);
}