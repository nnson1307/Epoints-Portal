<?php


namespace Modules\Referral\Repositories\ReferralMember;


use Modules\Admin\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Modules\Referral\Models\ReferralMemberTable;
class ReferralMemberRepo implements ReferralMemberInterface
{
    private $_mDB;
    public function __construct(ReferralMemberTable $table){
        $this->_mDB = $table;
    }

    public function list(array &$filters = [])
    {
        return $this->_mDB->getList($filters);
    }

    public function listChild(array &$filters = [])
    {
        return $this->_mDB->listChild($filters);
    }



    public function listRefferal(array &$filters = [])
    {
        return $this->_mDB->listRefferal($filters);
    }

    /**
     * @param $data
     * @param $id
     * @return mixed|void
     */
    public function updateMember($data,$id){
        return $this->_mDB->updateMember($data,$id);
    }

    public function getDetailCustomer($id)
    {
        return $this->_mDB->getDetailCustomer($id);
    }

    /**
     * Cập nhật trạng thái referral member
     * @param $data
     * @return mixed|void
     */
    public function changeStatusReferralMember($data)
    {
        try {
            $this->_mDB->updateMember(['status' => $data['active'] == 1 ? 'active' : 'inactive'], $data['referral_member_id']);

            return [
                'error'=> false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        }catch (Exception $e){
            return [
                'error'=> true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }

    /**
     * Lấy danh sách member
     */
    public function getAll()
    {
        return $this->_mDB->getAll();
    }

    /**
     * Lấy tổng người đã giới thiệu
     * @param $referralMemberId
     * @return mixed|void
     */
    public function getTotalRefer($referralMemberId)
    {
        return $this->_mDB->getTotalRefer($referralMemberId);
    }

    public function getDetailInvite($id)
    {
        return $this->_mDB->getDetailInvite($id);
    }
}
