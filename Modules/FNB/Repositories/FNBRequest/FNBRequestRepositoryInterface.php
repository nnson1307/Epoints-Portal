<?php


namespace Modules\FNB\Repositories\FNBRequest;


interface FNBRequestRepositoryInterface
{
    /**
     * danh sách bàn
     * @return mixed
     */
    public  function getListTable();

    /**
     * danh sách yêu cầu
     * @param $input
     * @return mixed
     */
    public  function getListRequest($input);

    /**
     * danh sách phương thức thanh toán
     * @return mixed
     */
    public  function getListPaymentMethod();


}