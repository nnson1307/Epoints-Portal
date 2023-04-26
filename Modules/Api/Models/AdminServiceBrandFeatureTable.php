<?php


namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AdminServiceBrandFeatureTable extends Model
{
    use ListTableTrait;
    protected $table = "admin_service_brand_feature";
    protected $primaryKey = "service_brand_feature_id";
    protected $fillable = [
        "service_brand_feature_id",
        "brand_id",
        "service_id",
        "feature_group_id",
        "is_actived",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
        "brand_update_at",
        "brand_update_by"
    ];

    public function createBrandFeature($data)
    {
        $oSelect = $this->insert($data);
        return $oSelect;
    }

    public function deleteByBrandId($id)
    {
        $oSelect = $this->delete();
        return $oSelect;
    }

    public function getFeatureByIdService($id)
    {
        $oSelect = $this->where('service_id', $id)->get();
        return $oSelect;
    }

    public function getDetail($service, $id)
    {
        $oSelect = $this->where('feature_group_id', $id)->where('service_id', $service)->first();
        return $oSelect;
    }
}
