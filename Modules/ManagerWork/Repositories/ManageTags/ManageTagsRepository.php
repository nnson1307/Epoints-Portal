<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\ManageTags;

use Modules\ManagerWork\Models\ManagerWorkTagTable;
use Modules\ManagerWork\Models\ManageTagsTable;

class ManageTagsRepository implements ManageTagsRepositoryInterface
{
    /**
     * @var manageTags ableTable
     */
    protected $manageTags;
    protected $timestamps = true;
    protected $mManageWorkTag;

    public function __construct(ManageTagsTable $manageTags, ManagerWorkTagTable $managerWorkTagTable)
    {
        $this->manageTags = $manageTags;
        $this->mManageWorkTag = $managerWorkTagTable;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->manageTags->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->manageTags->getAll($filters);
    }
    public function getName()
    {
        return $this->manageTags->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $total = $this->mManageWorkTag->checkTagIsUsed($id);
        if($total){
            return [
                'error' => 1,
                'message' => __('Tag đã sử dụng, không thể xoá')
            ];
        } else {
            $this->manageTags->remove($id);
            return [
                'error' => 0,
                'message' => __('Xoá thành công')
            ];
        }



    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->manageTags->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->manageTags->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->manageTags->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->manageTags->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExist($name = '',$id = '')
    {
        return $this->manageTags->checkExist($name,$id);
    }

}