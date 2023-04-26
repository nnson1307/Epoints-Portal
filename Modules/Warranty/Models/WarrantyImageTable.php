<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 3/3/2021
 * Time: 11:26 AM
 */

namespace Modules\Warranty\Models;



namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantyImageTable extends Model
{
    protected $table = "warranty_images";
    protected $primaryKey = "warranty_image_id";

    protected $fillable = [
        'warranty_image_id',
        'warranty_card_code',
        'link',
        'is_deleted',
        'created_at',
        'updated_at'
    ];

    const NOT_DELETE = 0;

    /**
     * Lấy hình ảnh trước và sau khi bảo hành
     *
     * @param $warrantyCode
     * @return mixed
     */
    public function getImageWarranty($warrantyCode)
    {
        return $this
            ->select(
                "warranty_image_id",
                "warranty_card_code",
                "link"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Danh sách image theo thẻ bảo hành
     *
     * @param $cardCode
     * @return mixed
     */
    public function getImageByCardCode($cardCode)
    {
        $select = $this->select(
            'warranty_image_id',
            'warranty_card_code',
            'link',
            'is_deleted'
        )
            ->where('is_deleted', 0)
            ->where('warranty_card_code', $cardCode);
        return $select->get();
    }

    /**
     * Thêm mới ảnh thẻ bảo hành
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Chỉnh sửa thẻ bảo hành theo code
     *
     * @param $data
     * @param $cardCode
     * @return mixed
     */
    public function editByCode($data, $cardCode)
    {
        return $this->where("warranty_card_code", $cardCode)->update($data);
    }
}