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

class ReligionTable extends Model
{
    protected $table = "religion";
    protected $primaryKey = "religion_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy thông tin tôn giáo bằng tên
     *
     * @param $name
     * @return mixed
     */
    public function getReligionByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
