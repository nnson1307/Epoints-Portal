<?php


namespace Modules\Referral\Repositories\ReferralMember;


interface ReferralMemberInterface
{
    /**
     * Lấy danh sách giới thiệu
     * @param array $data
     * @return mixed
     */
    public function list(array &$filters = []);

    public function listChild(array &$filters = []);

    public function listRefferal(array &$filters = []);

    /**
     * Cập nhật dựa trên id member
     * @param $data
     * @param $id
     * @return mixed
     */
    public function updateMember($data,$id);

    /**
     * Lấy chi tiết thành viên
     * @param $id
     * @return mixed
     */
    public function getDetailCustomer($id);

    /**
     * ĐỢi cải lão hoàn đòòn
     * @param $data
     * @return mixed
     */
    public function changeStatusReferralMember($data);

    public function getAll();

    public function getTotalRefer($referralMemberId);

    public function getDetailInvite($id);
}
