<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/08/2021
 * Time: 14:07
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SupplierTable extends Model
{
    protected $table = "suppliers";
    protected $primaryKey = "supplier_id";
    protected $fillable = ['supplier_id', 'supplier_name', 'description', 'is_deleted', 'updated_at', 'created_at', 'address', 'contact_name', 'contact_title', 'contact_phone','slug'];

    const NOT_DELETED = 0;

    /**
     * Lấy option đối tác
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "supplier_id as id",
                "supplier_name as name",
                "address",
                "contact_name",
                "contact_title",
                "contact_phone as phone"
            )
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }

    /**
     * Lấy thông tin đối tác
     *
     * @param $supplierId
     * @return mixed
     */
    public function getInfoById($supplierId)
    {
        return $this
            ->select(
                "supplier_id as id",
                "supplier_name as name",
                "address",
                "contact_name",
                "contact_title",
                "contact_phone as phone"
            )
            ->where("supplier_id", $supplierId)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }

    /**
     * Lấy thông tin đối tác bằng tên đói tác
     *
     * @param $supplierName
     * @return mixed
     */
    public function getInfoByName($supplierName)
    {
        return $this
            ->select(
                "supplier_id as id",
                "supplier_name as name",
                "address",
                "contact_name",
                "contact_title",
                "contact_phone as phone"
            )
            ->where("supplier_name", $supplierName)
            ->where("is_deleted", self::NOT_DELETED)
            ->first();
    }
    public function checkExist($name,$isDelete)
    {
        $select = $this->where('slug', str_slug($name))
            ->where('is_deleted', $isDelete)->first();
        return $select;
    }
    public function createData($data)
    {
        return $this->create($data)->supplier_id;
    }
}