<?php

namespace Modules\Contract\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class VatTable extends Model
{
    use ListTableTrait;
    protected $table = "vats";
    protected $primaryKey = "vat_id";
    protected $fillable = [
        "vat_id",
        "vat",
        "description",
        "type",
        "is_actived",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;

    /**
     * Danh sách VAT
     *
     * @param $filter
     * @return mixed
     */
    protected function _getList(&$filter = [])
    {
        $ds = $this
            ->select(
                "vat_id",
                "vat",
                "description",
                "type",
                "is_actived"
            )
            ->orderBy("{$this->table}.vat_id", "desc");

        return $ds;
    }

    /**
     * Thêm VAT
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->vat_id;
    }

    /**
     * Lấy thông tin VAT
     *
     * @param $idVat
     * @return mixed
     */
    public function getInfo($idVat)
    {
        return $this
            ->select(
                "vat_id",
                "vat",
                "description",
                "type",
                "is_actived"
            )
            ->where("vat_id", $idVat)
            ->first();
    }

    /**
     * Chỉnh sửa VAT
     *
     * @param array $data
     * @param $idVat
     * @return mixed
     */
    public function edit(array $data, $idVat)
    {
        return $this->where("vat_id", $idVat)->update($data);
    }

    /**
     * Lấy option VAT
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "vat_id",
                "vat",
                "description",
                "type",
                "is_actived"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->get();
    }
}