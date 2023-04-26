<?php
/**
 * Created by PhpStorm   .
 * User: nhandt
 * Date: 10/15/2021
 * Time: 3:33 PM
 * @author nhandt
 */


namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractRoleDataConfigTable extends Model
{
    protected $table = "contract_role_data_config";
    protected $primaryKey = "contract_role_data_config_id";
    protected $fillable = [
      "contract_role_data_config_id",
      "role_group_id",
      "role_data_type",
      "created_by",
      "updated_by",
      "created_at",
      "updated_at",
    ];

    public function deleteData()
    {
        return $this->whereNotNull("contract_role_data_config_id")->delete();
    }
    public function createData($data)
    {
        return $this->insert($data);
    }
}