<?php


namespace Modules\Referral\Repositories\ReferralProgramInvite;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Referral\Models\ReferralMemberDetailTable;
use Modules\Referral\Models\ReferralProgramCommissionTable;
use Modules\Referral\Models\ReferralProgramInviteTable;
use Modules\Referral\Repositories\ReferralMember\ReferralMemberInterface;

class ReferralProgramInviteRepo implements ReferralProgramInviteInterface
{
    private $_mDB;
    public function __construct(ReferralProgramInviteTable $table){
        $this->_mDB = $table;
    }

    public function list(array &$filters = [])
    {
        $list = $this->_mDB->getList($filters);
        return $list;
    }

    public function detailItem($id)
    {
        $data = $this->_mDB->getDetail($id);

        if($data->referral_program_type === 'cps'){
            $result = $this->_mDB->getDetailCPS($id);
        } else {
            $result = $this->_mDB->getDetailCPS($id);
        }

//        dd($result);

        return $result;
    }



    public function getListCommissionOrder(array &$filters = [])
    {
        $list = $this->_mDB->getListCommissionOrder($filters);
        return $list;
    }

    /**
     * Lấy danh sách người giới thiệu cấp 1
     * @return mixed|void
     */
    public function getListReferralLevel()
    {
        return $this->_mDB->getListReferralLevel();
    }

//    public function cập nhật trạng thái hoa hồng
    public function updateProgramInvite($param)
    {
        try {
            $rReferralMember = app()->get(ReferralMemberInterface::class);

            $id = $param['referral_program_invite_id'];

            $mCommission = new ReferralProgramCommissionTable();

            $arrCommission = $mCommission->getAllByInviteId($id);

            if(count($arrCommission)){
                foreach ($arrCommission as $commission){
                    $data['referral_program_commission_id'] = $commission['referral_program_commission_id'];
                    $data['reason'] = $param['reason'];
                    $this->_rejectCommission($data);
                }
            }

            $data = [
                'reject_approve_note' => $param['reason'],
                'status' => 'reject',
                'reject_by' => Auth::id(),
                'reject_at' => Carbon::now(),
                'reject_day' => Carbon::now()->day,
                'reject_month' => Carbon::now()->month,
                'reject_year' => Carbon::now()->year,
            ];


//            Cập nhật trạng thái hoa hồng
            $this->_mDB->updateProgramInvite($data,$id);

            return [
                'error' => false,
                'message' => __('Cập nhật trạng thái hoa hồng thành công')
            ];
        }catch (\Exception $exception) {
            dd($exception);
            return [
                'error' => true,
                'message' => __('Cập nhật trạng thái hoa hồng bất bại')
            ];
        }
    }

    public function rejectCommission($data){
        return $this->_rejectCommission($data);
    }

    protected function _rejectCommission($data){

        $mCommission = new ReferralProgramCommissionTable();
        $rReferralMember = app()->get(ReferralMemberInterface::class);

        $id = $data['referral_program_commission_id'];
        $detail = $mCommission->getDetail($id, ['new', 'approve']);

        if(in_array($detail['status'], ['new', 'approve'])){
            $totalMoney = 0;
            $totalCommission = 0;
            if ($detail['status'] === 'new'){
                $totalMoney = (double)$detail['referral_member_total_money'] - (double)$detail['total_money'];

                $rReferralMember->updateMember([
                    'total_money' => $totalMoney ],
                    $detail['referral_member_id']
                );

            } else if ($detail['status'] === 'approve'){
                $totalCommission = (double)$detail['referral_member_total_commission'] - (double)$detail['total_commission'];
                $totalMoney = (double)$detail['referral_member_total_money'] + (double)$detail['total_commission'];

                $rReferralMember->updateMember([
                    'total_commission' => $totalCommission,
                    'total_money' => $totalMoney
                ],
                    $detail['referral_member_id']
                );
            }

            $referral_from = 'order_success';
            if($detail['referral_program_type'] === 'cpi'){
                $referral_from = 'account_register';
            }

            $dataDetail[] = [
                'referral_member_id' => $detail['referral_member_id'],
                'referral_from' => $referral_from,
                'action' => 'reject',
                'type' => 'minus',
                'obj_id' => $id,
                'total_money' => $totalMoney,
                'total_commission' => $totalCommission,
                'created_at' => Carbon::now()
            ];

            $mReferralMemberDetail = new ReferralMemberDetailTable();
            $mReferralMemberDetail->insertData($dataDetail);

            $data = [
                'reject_approve_note' => $data['reason'],
                'status' => 'reject',
                'reject_by' => Auth::id(),
                'reject_at' => Carbon::now(),
                'reject_day' => Carbon::now()->day,
                'reject_month' => Carbon::now()->month,
                'reject_year' => Carbon::now()->year,
            ];

            $mCommission->updateItems($data,$id);

            return [
                'error' => false,
                'message' => __('Cập nhật trạng thái hoa hồng thành công'),
            ];
        }

        return [
            'error' => true,
            'message' => 'Loi khong xac dinh'
        ];


    }

    public function showReject($param)
    {
        try {

            $id = $param['referral_program_invite_id'];
            $detail = $this->_mDB->getDetail($id);
            return [
                'error' => false,
                'message' => __('Cập nhật trạng thái hoa hồng thành công'),
                'reason' => $detail['reject_approve_note'],
                'title' => __('Lý do từ chối')
            ];
        }catch (\Exception $exception) {
            return [
                'error' => true,
                'message_error' => $exception->getMessage(),
                'message' => __('Cập nhật trạng thái hoa hồng bất bại')
            ];
        }
    }

    public function showRejectCommission($param)
    {
        try {
            $mCommission = new ReferralProgramCommissionTable();
            $id = $param['referral_program_commission_id'];
            $detail = $mCommission->getDetail($id, ['reject']);

            return [
                'error' => false,
                'message' => __('Cập nhật trạng thái hoa hồng thành công'),
                'reason' => $detail['reject_approve_note'],
                'title' => __('Lý do từ chối')
            ];
        }catch (\Exception $exception) {
            return [
                'error' => true,
                'message_error' => $exception->getMessage(),
                'message' => __('Cập nhật trạng thái hoa hồng bất bại')
            ];
        }
    }

    /**
     * Cập nhật theo id referral member id và trạng thái
     * @param $data
     * @param $referralMemberId
     * @param $statusCheck
     * @return mixed|void
     */
    public function updateInvite($data, $referralMemberId, $statusCheck)
    {
        return $this->_mDB->updateByStatusMemberId($data, $referralMemberId, $statusCheck);
    }
}
