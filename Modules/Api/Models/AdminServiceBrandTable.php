<?php


namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AdminServiceBrandTable extends Model
{
    use ListTableTrait;
    protected $table = "admin_service_brand";
    protected $primaryKey = "service_brand_id";
    protected $fillable = [
        "service_brand_id",
        "service_id",
        "brand_id",
        "is_deleted",
        "is_actived",
        "created_by",
        "created_at",
        "updated_by",
        "updated_at"
    ];

    public function createBrand($data)
    {
        $oSelect = $this->insert($data);
        return $oSelect;
    }

    public function deleteByBrandId($id)
    {
        $oSelect = $this->delete();
        return $oSelect;
    }

    public function getListCore(&$filter = [])
    {
        $oSelect = $this->orderBy('service_brand_id', 'DESC');
        return $oSelect;
    }

    public function getDetail($id)
    {
        $oSelect = $this->where('service_id', $id)->first();
        return $oSelect;
    }
}
