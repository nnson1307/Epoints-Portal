<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\ManageStatus;

use Modules\ManagerWork\Models\ManageStatusConfigMapTable;
use Modules\ManagerWork\Models\ManageStatusTable;

class ManageStatusRepository implements ManageStatusRepositoryInterface
{
    /**
     * @var ManageStatus ableTable
     */
    protected $manageStatus;
    protected $timestamps = true;

    public function __construct(ManageStatusTable $manageStatus)
    {
        $this->manageStatus = $manageStatus;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->manageStatus->getList($filters);
    }
    
    public function getName()
    {
        return $this->manageStatus->getName();
    }

    public function getColorList()
    {
        return $this->manageStatus->getColorList();
    }

    /**
     * delete customers Group
     */
    public function remove($id)
    {
        $this->manageStatus->remove($id);
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->manageStatus->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->manageStatus->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->manageStatus->getItem($id);
    }

    /*
    * check exist
    */
    public function checkExist($name = '',$id = '')
    {
        return $this->manageStatus->checkExist($name,$id);
    }

    /**
     * lấy danh sách trạng thái cập nhật
     * @param $workId
     * @return mixed|void
     */
    public function getListStatus($workDetail)
    {
        $mManageStatusConfigMap = new ManageStatusConfigMapTable();
        $listStatusConfig = $mManageStatusConfigMap->getListStatusByConfig($workDetail['manage_status_id']);
        $mManageStatus = new ManageStatusTable();
        $listStatus = [];
        if (count($listStatusConfig) != 0){
            $listStatusConfig = collect($listStatusConfig)->pluck('manage_status_id')->toArray();
            $listStatusConfig = array_merge($listStatusConfig,[$workDetail['manage_status_id']]);
            $listStatus = $mManageStatus->getAll($listStatusConfig);
            $listStatus = collect($listStatus)->pluck("manage_status_name","manage_status_id")->toArray();
        }

        return $listStatus;
    }

}