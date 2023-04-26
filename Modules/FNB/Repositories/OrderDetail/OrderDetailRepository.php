<?php


namespace Modules\FNB\Repositories\OrderDetail;


use Modules\FNB\Models\OrderDetailTable;
use Modules\FNB\Repositories\ProductAttribute\ProductAttributeRepositoryInterface;

class OrderDetailRepository implements OrderDetailRepositoryInterface
{
    private $order_detail;

    /**
     * OrderDetailRepository constructor.
     * @param OrderDetailTable $order_details
     */
    public function __construct(OrderDetailTable $order_details)
    {
        $this->order_detail = $order_details;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        $rProductAttribute = app()->get(ProductAttributeRepositoryInterface::class);
        $list = $this->order_detail->getItem($id);
        $list = collect($list)->toArray();
        foreach ($list as $key => $item){
            if (isset($item['product_attribute_json'])){
                $tmpName = $rProductAttribute->getNameAttribute(json_decode($item['product_attribute_json']));

                $list[$key]['name_attribute'] = $tmpName;
            }

        }

        return $list;
    }

    public function remove($id_order) {
        return $this->order_detail->remove($id_order);
    }

    public function add($data)
    {
        return $this->order_detail->add($data);
    }

    //Lấy dữ liệu chi tiết hóa đơn theo order_id và object_type
    public function getValueByOrderIdAndObjectType($orderId, $objectType)
    {
        $select = $this->order_detail->getValueByOrderIdAndObjectType($orderId, $objectType);
        return $select;
    }
}