<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:48 CH
 */

namespace Modules\Admin\Repositories\OrderPaymentType;


use Modules\Admin\Models\OrderPaymentTypeTable;



class OrderPaymentTypeRepository implements OrderPaymentTypeRepositoryInterface
{

    /**
     * @var OrderPaymentTypeTable
     */
    protected $orderPaymentType;
    protected $timestamps = true;
    public function __construct(OrderPaymentTypeTable $orderPaymentType)
    {
        $this->orderPaymentType = $orderPaymentType;
    }
    /**
     *get list customer Group
     */
    public function list(array $filters = [])
    {
        return $this->orderPaymentType->getList($filters);
    }
    /**
     * delete customer Group
     */
    public function remove($id)
    {

        $this->orderPaymentType->remove($id);
    }
    /**
     * add customer Group
     */
    public function add(array $data)
    {
        return $this->orderPaymentType->add($data);
    }
    /*
     * edit customer Group
     */
    public function edit(array $data ,$id)
    {
        try{
            if ($this->orderPaymentType->edit($data ,$id) === false) throw new \Exception() ;
            return $id;
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
        return false;
    }
    /*
     *  update or add
     */
    public function save(array $data ,$id)
    {
        if(!empty($id)){
            return $this->orderPaymentType->edit($data ,$id);
        }else{

            return $this->orderPaymentType->add($data);
        }
    }
    public function getItem($id)
    {
        return $this->orderPaymentType->getItem($id);
    }

}