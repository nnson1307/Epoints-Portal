<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/03/2018
 * Time: 2:39 PM
 */

namespace Modules\Admin\Repositories\MemberLevel;

use Modules\Admin\Models\MemberLevelTable;

class MemberLevelRepository implements MemberLevelRepositoryInterface
{
    protected $memberLevel;
    protected $timestamps = true;


    public function __construct(MemberLevelTable $memberLevel)
    {
        $this->memberLevel = $memberLevel;
    }


    /**
     * Lấy danh sách user
     */
    public function list(array $filters = [])
    {
        return $this->memberLevel->getList($filters);
    }


    /**
     * Xóa user
     */
    public function remove($id)
    {
        $this->memberLevel->remove($id);
    }

//    public function getListProvinceOptions()
//    {
//        $this->user->getListProvinceOptions();
//    }
//
//    public function getListDistrictOptions($id)
//    {
//        $this->user->getListDistrictOptions($id);
//    }
//
//    public function getxa($id)
//    {
//        $this->user->getxa($id);
//    }
    /**
     * Thêm user
     */
    public function add(array $data)
    {
//        $data['password'] = bcrypt($data['password']);

        return $this->memberLevel->add($data);
    }

    public function edit(array $data, $id)
    {
//        if(!empty($data['password'])){
//            $data['password']=bcrypt ($data['password']);
//        }
        return $this->memberLevel->edit($data, $id);
    }

    public function getItem($id)
    {

        return $this->memberLevel->getItem($id);
    }


    public function getOptionMemberLevel()
    {
        $array=array();
        foreach ($this->memberLevel->getOptionMemberLevel() as $value) { //bien $value se bang gia tri tra ve cua ham getOptionMemberLevel
            $array[$value['member_level_id']]=$value['member_level_name'];//ma ham getOptionMemberLevel truy van database tra ve MEMBER_ID va Member_name
        }           //=> bien $value se co gia tri mang [member_id,member_name]
        return $array;// sau do ta tra ve vong lap foreach theo gia tri id va name cua value

    }
    public function testName($id,$name){
        return $this->memberLevel->testName($id,$name);
    }

    /**
     * @param $point
     * @return mixed
     */
    public function rankByPoint($point)
    {
        return $this->memberLevel->rankByPoint($point);
    }
}