<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:36 PM
 */

namespace Modules\ManagerWork\Repositories\TypeWork;

use Modules\ManagerWork\Models\ManagerWorkTable;
use Modules\ManagerWork\Models\TypeWorkTable;

class TypeWorkRepository implements TypeWorkRepositoryInterface
{
    /**
     * @var typeWork ableTable
     */
    protected $typeWork;
    protected $timestamps = true;

    public function __construct(TypeWorkTable $typeWork)
    {
        $this->typeWork = $typeWork;
    }

    /**
     *get list customers Group
     */
    public function list(array $filters = [])
    {
        return $this->typeWork->getList($filters);
    }
    
    public function getAll(array $filters = [])
    {
        return $this->typeWork->getAll($filters);
    }
    public function getName()
    {
        return $this->typeWork->getName();
    }

    /**
     * delete customers Group
     */
    public function remove($data)
    {

        try{

            $mManageWork = new ManagerWorkTable();

            $listWork = $mManageWork->getWorkByTypeWork($data['id']);

            if (count($listWork) != 0){
                return [
                    'error' => true,
                    'message' => __('Loại công việc đã được dùng không thể xoá')
                ];
            } else {
                $this->typeWork->remove($data['id']);
                return [
                    'error' => false,
                    'message' => __('Xoá loại công việc thành công')
                ];
            }
        }catch (\Exception $e){
            return [
                'error' => true,
                'message' => __('Xoá loại công việc thất bại')
            ];
        }
    }

    /**
     * add customers Group
     */
    public function add(array $data)
    {

        return $this->typeWork->add($data);
    }

    /*
     * edit customers Group
     */
    public function edit(array $data, $id)
    {
        return $this->typeWork->edit($data, $id);
    }

    /*
     *  get item
     */
    public function getItem($id)
    {
        return $this->typeWork->getItem($id);
    }

    /*
     *  test code
     */
    public function testCode($code, $id)
    {
        return $this->typeWork->testCode($code, $id);
    }

    /*
    * check exist
    */
    public function checkExist($name = '',$id = '')
    {
        return $this->typeWork->checkExist($name,$id);
    }

}