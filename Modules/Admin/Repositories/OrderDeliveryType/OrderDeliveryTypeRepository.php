<?php
/**
 * Created by PhpStorm.
 * User: nhu
 * Date: 20/03/2018
 * Time: 10:17
 */

namespace Modules\Admin\Repositories\OrderDeliveryType;
use Modules\Admin\Models\OrderDeliveryTypeTable;

class OrderDeliveryTypeRepository  implements OrderDeliveryTypeRepositoryInterface
{
    protected $orderDeliveryType;
    protected $timestamps = true;
    public function __construct(OrderDeliveryTypeTable $orderDeliveryType)
    {
        $this->orderDeliveryType = $orderDeliveryType;
    }
    /**
     *get list Order Delivery Type
     */
    public function list(array $filters = [])
    {
        return $this->orderDeliveryType->getList($filters);
    }
    /**
     * delete Order Delivery Type
     */
    public function remove($id)
    {
        $this->orderDeliveryType->remove($id);
    }
    /**
     * add Order Delivery Type
     */
    public function add(array $data)
    {

        return $this->orderDeliveryType->add($data);
    }
    /*
     * edit Order Delivery Type
     */
    public function edit(array $data ,$id)
    {
        // check has image remove
        try{
            if ($this->orderDeliveryType->edit($data ,$id) === false) throw new \Exception() ;
            return $id;
        }
        catch(\Exception  $e){
            $e->getMessage();
        }
        return false;
    }
    /*
     *  update or add Order Delivery Type
     */
    public function save(array $data ,$id)
    {
        if(!empty($id)){
            return $this->orderDeliveryType->edit($data ,$id);
        }else{

            return $this->orderDeliveryType->add($data);
        }
    }
    public function getItem($id)
    {
        return $this->orderDeliveryType->getItem($id);
    }

}