<?php

namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;

class ServiceCardSoldImageTable extends Model
{
    protected $table = "service_card_sold_images";
    protected $primaryKey = "service_card_sold_image_id";
    protected $fillable = [
        'service_card_sold_image_id',
        'customer_service_card_code',
        'order_code',
        'type',
        'link',
        'is_deleted',
        'created_at',
        'updated_at',
    ];
    const NOT_DELETE = 0;
    const IS_DELETE = 1;

    public function store(array $data)
    {
        return $this->create($data);
    }

    /**
     * Lấy danh sách hình ảnh theo mã thẻ dịch vụ đã bán
     *
     * @param $cardCode
     * @param $orderCode
     * @param null $type
     * @return mixed
     */
    public function getListImageByCode($cardCode, $orderCode, $type = null)
    {
        $select = $this->select(
            'service_card_sold_image_id',
            'customer_service_card_code',
            'order_code',
            'type',
            'link'
        )
            ->where('customer_service_card_code', $cardCode)
            ->where('order_code', $orderCode)
            ->where('is_deleted', self::NOT_DELETE);
        if ($type != null) {
            $select->where('type', $type);
        }
        return $select->orderBy('service_card_sold_image_id', 'desc')->get();
    }

    /**
     * Xoá ảnh theo card code, order code trừ những ảnh trong arrImageOld
     *
     * @param $cardCode
     * @param $orderCode
     * @param $type
     * @param array $arrImageOld
     * @return mixed
     */
    public function deleteImageSCSold($cardCode, $orderCode, $type, array $arrImageOld)
   {
       return $this->where('customer_service_card_code', $cardCode)
           ->where('order_code', $orderCode)
           ->where('type', $type)
           ->whereNotIn('service_card_sold_image_id', $arrImageOld)
           ->update(['is_deleted' => self::IS_DELETE]);
   }
}