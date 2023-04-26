<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/09/2021
 * Time: 10:19
 */

namespace Modules\Contract\Models;


use Illuminate\Database\Eloquent\Model;

class ContractLogFileTable extends Model
{
    protected $table = "contract_log_files";
    protected $primaryKey = "contract_log_file_id";
    protected $fillable = [
        "contract_log_files",
        "contract_log_id",
        "contract_file_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log thông tin chung
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}