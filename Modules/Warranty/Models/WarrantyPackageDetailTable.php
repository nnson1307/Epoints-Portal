<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class WarrantyPackageDetailTable extends Model
{
    protected $table = "warranty_packed_detail";
    protected $primaryKey = "warranty_packed_detail_id";
    protected $fillable = [
        'warranty_packed_detail_id',
        'warranty_packed_code',
        'object_type',
        'object_id',
        'object_code',
        'updated_at',
        'created_at',
    ];

    /**
     * Insert chi tiết gói bảo hành
     *
     * @param $data
     * @return mixed
     */
    public function add($data)
    {
        return $this->create($data)->{$this->primaryKey};
    }

    /**
     * Xoá chi tiết gói bảo hành theo package code
     *
     * @param $code
     * @return mixed
     */
    public function removeByPackageCode($code)
    {
        return $this->where("{$this->table}.warranty_packed_code", $code)->delete();
    }

    /**
     * Chi tiết gói bảo hành theo package code
     *
     * @param $code
     * @return mixed
     */
    public function getDetailByPackageCode($code)
    {
        $select = $this->select(
            'warranty_packed_detail_id',
            'warranty_packed_code',
            'object_type',
            'object_id',
            'object_code'
        )->where('warranty_packed_code', $code);
        return $select->get();
    }

    /**
     * Lấy tất cả object hoặc những object nàm ngoài gói bảo hành $warrantyPackedCode
     *
     * @param null $warrantyPackedCode
     * @return mixed
     */
    public function getAllObject($warrantyPackedCode = null)
    {
        $select = $this->select(
            "{$this->table}.warranty_packed_detail_id",
            "{$this->table}.warranty_packed_code",
            "{$this->table}.object_type",
            "{$this->table}.object_id",
            "{$this->table}.object_code"
        )
            ->join("warranty_packed", "warranty_packed.packed_code", "=", "{$this->table}.warranty_packed_code")
            ->where("warranty_packed.is_deleted", 0);
        if ($warrantyPackedCode != null) {
            $select->where("{$this->table}.warranty_packed_code", "<>", $warrantyPackedCode);
        }
        return $select->get();
    }
}