<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\ServicePackage;


use Modules\Admin\Models\ServicePackageTable;

class ServicePackageRepository implements ServicePackageRepositoryInterface
{

    /**
     * @var ServicePackageTable
     */
    protected $servicePackage;
    protected $timestamps = true;


    public function __construct(ServicePackageTable $servicePackage)
    {
        $this->servicePackage = $servicePackage;
    }


    /**
     *get list services Package
     */
    public function list(array $filters = [])
    {
        return $this->servicePackage->getList($filters);
    }


    /**
     * delete services Package
     */
    public function remove($id)
    {
        $this->servicePackage->remove($id);
    }


    /**
     * add services Package
     */
    public function add(array $data)
    {

        return $this->servicePackage->add($data);
    }

    public function edit(array $data ,$id)
    {
        try{
            return $this->servicePackage->edit($data ,$id);
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
    }
    public function save(array $data ,$id)
    {
        if(!empty($id)){
            return $this->servicePackage->edit($data ,$id);
        }else{

            return $this->servicePackage->add($data);
        }
    }
    public function getItem($id)
    {
        return $this->servicePackage->getItem($id);
    }

    public function checkValueIsset($id,$param){
        $oServicePackage  =  $this->servicePackage->getItem($id);
        if($oServicePackage['service_package_name'] != $param['service_package_name']){
            return  false ;
        }
        return true ;
    }
}