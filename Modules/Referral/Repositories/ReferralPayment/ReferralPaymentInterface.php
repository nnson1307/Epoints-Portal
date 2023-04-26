<?php


namespace Modules\Referral\Repositories\ReferralPayment;


interface ReferralPaymentInterface
{
//    Lấy danh sách payment
    public function list(array &$filter = []);

    /**
     * lấy chi tiết payment
     * @param $id
     * @return mixed
     */
    public function getDetail($id);

    public function getAll();
}