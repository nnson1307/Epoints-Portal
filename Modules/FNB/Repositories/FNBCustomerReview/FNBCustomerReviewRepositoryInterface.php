<?php


namespace Modules\FNB\Repositories\FNBCustomerReview;


interface FNBCustomerReviewRepositoryInterface
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
    public  function getListCustomerReview($input);

    /**
     * danh sách phương thức thanh toán
     * @return mixed
     */
    public  function reviewList();


}