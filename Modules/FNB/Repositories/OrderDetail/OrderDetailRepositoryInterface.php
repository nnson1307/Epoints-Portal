<?php


namespace Modules\FNB\Repositories\OrderDetail;


interface OrderDetailRepositoryInterface
{
    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id);

    public function remove($id);

    public function add($data);

    //Lấy dữ liệu chi tiết hóa đơn theo order_id và object_type
    public function getValueByOrderIdAndObjectType($orderId, $objectType);


}