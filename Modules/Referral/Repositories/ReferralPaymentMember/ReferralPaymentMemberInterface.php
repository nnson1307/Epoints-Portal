<?php


namespace Modules\Referral\Repositories\ReferralPaymentMember;


interface ReferralPaymentMemberInterface
{
    public function list(array &$filter = []);

    /**
     * Lấy danh sách referral_member id có tồn tại trong payment_member
     * @param $list
     * @return mixed
     */
    public function getListMemberInPaymentMember($list);

    /**
     * Từ chối thanh toán
     * @param $data
     * @return mixed
     */
    public function rejectPayment($data);
}