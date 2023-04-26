<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/27/2020
 * Time: 4:26 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ConfigSourceLeadMapTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead_config_source_map";
    protected $primaryKey = "cpo_customer_lead_config_source_map_id";
    protected $fillable = [
        "cpo_customer_lead_config_source_map_id",
        "cpo_customer_lead_config_source_id",
        "department_id",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by"
    ];

    public function getAll($cpo_customer_lead_config_source_id){
        return $this
            ->join('departments','departments.department_id',$this->table.'.department_id')
            ->where('cpo_customer_lead_config_source_id',$cpo_customer_lead_config_source_id)
            ->select(
                $this->table.'.*',
                'departments.department_name'
            )
            ->get();

    }

    public function addItem($data){
        return $this->insert($data);
    }

    public function removeItem($id){
        return $this
            ->where('cpo_customer_lead_config_source_id',$id)
            ->delete();
    }
}