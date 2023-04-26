<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/08/2021
 * Time: 09:32
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogTable extends Model
{
    protected $table = "contract_logs";
    protected $primaryKey = "contract_log_id";
    protected $fillable = [
        "contract_log_id",
        "contract_id",
        "change_object_type",
        "note",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log HĐ
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->contract_log_id;
    }
}