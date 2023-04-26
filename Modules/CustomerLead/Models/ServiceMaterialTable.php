<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceMaterialTable extends Model
{
    protected $table = 'service_materials';
    protected $primaryKey = 'service_material_id';
    protected $fillable = [
        'service_material_id', 'service_id', 'material_id', 'material_code', 'quantity', 'unit_id',
        'is_actived', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted'
    ];

    public function getItem($id)
    {
        $select = $this
            ->select(
                'service_materials.material_id as material_id',
                'service_materials.material_code as material_code',
                'service_materials.quantity as quantity',
                'service_materials.is_actived as is_actived',
                'service_materials.created_at as created_at',
                'service_materials.updated_at as updated_at',
                'service_materials.created_by as created_by',
                'service_materials.updated_by as update_by',
                'units.name as name',
                'units.unit_id as unit_id',
                'product_childs.product_child_name as product_child_name',
                'service_materials.service_id as mate_service_id',
                'service_materials.service_material_id as service_material_id'
            )
            ->leftJoin('services', 'services.service_id', '=', 'service_materials.service_id')
            ->leftJoin('units', 'units.unit_id', '=', 'service_materials.unit_id')
            ->leftJoin('product_childs', 'product_childs.product_child_id', '=', 'service_materials.material_id')
            ->where('service_materials.is_deleted',0)
            ->where('service_materials.service_id', $id);
        return $select->get();
    }
}