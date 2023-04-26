<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierTable extends Model
{
    protected $table = "suppliers";
    protected $primaryKey = "supplier_id";

    const NOT_DELETE = 0;

    /**
     * Lấy các option nhà cung cấp
     *
     * @return mixed
     */
    public function getOption()
    {
        $select = $this->select(
            "supplier_id as accounting_id",
            "supplier_name as accounting_name"
        )->where("is_deleted", self::NOT_DELETE);
        return $select->get();
    }

    /**
     * Lấy thông tin nhà cung cấp
     *
     * @param $supplierId
     * @return mixed
     */
    public function getItem($supplierId)
    {
        return $this->select(
            'supplier_id as accounting_id',
            'supplier_name as accounting_name',
            'contact_name',
            'contact_title',
            'contact_phone'
        )
            ->where('supplier_id', $supplierId)
            ->where('is_deleted', self::NOT_DELETE)
            ->first();
    }

    /**
     * Lấy thông tin nhà cung cấp - dùng làm history
     *
     * @param $supplierId
     * @return mixed
     */
    public function getInfo($supplierId)
    {
        return $this
            ->select(
                'supplier_id',
                'supplier_name',
                'contact_name',
                'contact_title',
                'contact_phone'
            )
            ->where('supplier_id', $supplierId)
            ->first();;
    }
}