<?php


namespace Modules\Referral\Repositories\ReferralProgramInvite;


interface ReferralProgramInviteInterface
{
    /**
     * Lấy danh sách hoa hồng cho người giới thiệu
     * @param array $data
     * @return mixed
     */
    public function list(array &$filters = []);

    public function detailItem($id);

    public function getListCommissionOrder(array &$filters = []);

    /**
     * Lấy danh sấch nguòi giới thiệu cấp 1
     * @return mixed
     */
    public function getListReferralLevel();

    /**
     * Cập nhtậ trạng thái hoa hồng
     * @param $param
     * @return mixed
     */
    public function updateProgramInvite($param);

    public function rejectCommission($data);

    /**
     * hiển thị chi tiết lỗi
     * @param $param
     * @return mixed
     */
    public function showReject($param);

    public function showRejectCommission($param);



    /**
     * Cập nhật invite theo id referral member id và trạng thái
     * @param $data
     * @param $referralMemberId
     * @param $statusCheck
     * @return mixed
     */
    public function updateInvite($data,$referralMemberId,$statusCheck);
}
