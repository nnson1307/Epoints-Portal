<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */


namespace Modules\Admin\Repositories\ServiceGroup;


use Modules\Admin\Models\ServiceGroupTable;

class ServiceGroupRepository implements ServiceGroupRepositoryInterface
{

    /**
     * @var ServiceGroupTable
     */
    protected $serviceGroup;
    protected $timestamps = true;


    public function __construct(ServiceGroupTable $serviceGroup)
    {
        $this->serviceGroup = $serviceGroup;
    }


    /**
     *get list service group
     */
    public function list(array $filters = [])
    {
        return $this->serviceGroup->getList($filters);
    }


    /**
     * delete service group
     */
    public function remove($id)
    {
        $this->serviceGroup->remove($id);
    }


    /**
     * add service group
     */
    public function add(array $data)
    {

        return $this->serviceGroup->add($data);
    }

    public function edit(array $data ,$id)
    {
        try{
            return $this->serviceGroup->edit($data ,$id);
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
    }
    public function save(array $data ,$id)
    {
        if(!empty($id)){
            return $this->serviceGroup->edit($data ,$id);
        }else{
            return $this->serviceGroup->add($data);
        }
    }
    public function getItem($id)
    {
        return $this->serviceGroup->getItem($id);
    }

    public function checkValueIsset($id, $param){
        $oServiceGroup  =  $this->serviceGroup->getItem($id);
        if($oServiceGroup['service_group_name'] != $param['service_group_name']){
            return  false ;
        }
        return true ;
    }
}