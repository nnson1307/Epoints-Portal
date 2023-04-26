<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\ServiceType;


use Modules\Admin\Models\ServiceTypeTable;

class ServiceTypeRepository implements ServiceTypeRepositoryInterface
{

    /**
     * @var ServiceTypeTable
     */
    protected $serviceType;
    protected $timestamps = true;


    public function __construct(ServiceTypeTable $serviceType)
    {
        $this->serviceType = $serviceType;
    }


    /**
     *get list services Type
     */
    public function list(array $filters = [])
    {
        return $this->serviceType->getList($filters);
    }


    /**
     * delete services Type
     */
    public function remove($id)
    {
        $this->serviceType->remove($id);
    }
    /**
     * add services Type
     */
    public function add(array $data)
    {

        return $this->serviceType->add($data);
    }

    public function edit(array $data ,$id)
    {
        try{
            return $this->serviceType->edit($data ,$id);
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
    }
    public function save(array $data ,$id)
    {
        if(!empty($id)){
            return $this->serviceType->edit($data ,$id);
        }else{

            return $this->serviceType->add($data);
        }
    }
    public function getItem($id)
    {
        return $this->serviceType->getItem($id);
    }

    public function checkValueIsset($id,$param){
        $oServiceType  =  $this->serviceType->getItem($id);
        if($oServiceType['service_type_name'] != $param['service_type_name']){
            return  false ;
        }
        return true ;
    }
}