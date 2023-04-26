<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 17/03/2018
 * Time: 2:39 PM
 */

namespace Modules\Admin\Repositories\OrderDeliveryStatus;

use Modules\Admin\Models\OrderDeliveryStatusTable;


class OrderDeliveryStatusRepository implements OrderDeliveryStatusRepositoryInterface
{
    protected $orderDeliveryStatus;
    protected $timestamps = true;


    public function __construct(OrderDeliveryStatusTable $orderDeliveryStatus)
    {
        $this->orderDeliveryStatus = $orderDeliveryStatus;
    }


    /**
     * Lấy danh sách user
     */
    public function list(array $filters = [])
    {
        return $this->orderDeliveryStatus->getList($filters);
    }


    /**
     * Xóa user
     */
    public function remove($id)
    {
        $this->orderDeliveryStatus->remove($id);
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

        return $this->orderDeliveryStatus->add($data);
    }

    public function  edit(array $data,$id)
    {
//        if(!empty($data['password'])){
//            $data['password']=bcrypt ($data['password']);
//        }
        return $this->orderDeliveryStatus->edit($data,$id);
    }

    public function getItem($id)
    {
        // TODO: Implement getItem() method.
        return $this->orderDeliveryStatus->getItem($id);
    }


}