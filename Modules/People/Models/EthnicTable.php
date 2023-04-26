<?php
/**
 * Created by PhpStorm
 * User: Huniel
 * Date: 4/26/2022
 * Time: 4:32 PM
 */

namespace Modules\People\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EthnicTable extends Model
{
    protected $table = "ethnic";
    protected $primaryKey = "ethnic_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy thông tin dân tộc bằng tên
     *
     * @param $name
     * @return mixed
     */
    public function getEthnicByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
