<?php


namespace Modules\Referral\Repositories\ReferralPaymentMember;


use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Referral\Models\ReferralPaymentMemberTable;
use Modules\Referral\Repositories\ReferralProgramInvite\ReferralProgramInviteInterface;

class ReferralPaymentMemberRepo implements ReferralPaymentMemberInterface
{
    private $_mDB;

    public function __construct(ReferralPaymentMemberTable $table)
    {
        $this->_mDB = $table;
    }

//    Lấy danh sách kì hoa hồng
    public function list(array &$filter = [])
    {
        return $this->_mDB->getList($filter);
    }

    /**
     * Lấy danh sách referral_member id có tồn tại trong referral_payment_member
     * @param $list
     * @return mixed|void
     */
    public function getListMemberInPaymentMember($list)
    {
        if (count($list) != 0){
            $list = collect(collect($list)->toArray()['data'])->pluck('referral_member_id')->unique();
            $listPayment = $this->_mDB->getListByIdReferralMember($list);

            if (count($listPayment) != 0){
                $listPayment = collect($listPayment)->keyBy('referral_member_id');
            }

            return $listPayment;
        }

        return $list;
    }

    /**
     * Từ chối thanh toán
     * @param $data
     * @return mixed|void
     */
    public function rejectPayment($data)
    {
        try {
            $rReferralProgramInvite = app()->get(ReferralProgramInviteInterface::class);
            if (!isset($data['referral_payment_member_id'])) {
                return [
                    'error' => true,
                    'message' => __('Từ chối thất bại')
                ];
            }
            $detail = $this->_mDB->getDetail($data['referral_payment_member_id']);
//            Cập nhật trạng thái payment member
            $this->_mDB->updatePaymentMember(['status' => 'reject'],$data['referral_payment_member_id']);

//            Cập nhật trạng thái payment invite
            $rReferralProgramInvite->updateInvite(['status' => 'approve'],$detail['referral_member_id'],'waiting_payment');

            return [
                'error' => false,
                'message' => __('Từ chối thành công')
            ];

        }catch (Exception $exception){
            return [
                'error' => true,
                'message' => __('Từ chối thất bại')
            ];
        }
    }
}