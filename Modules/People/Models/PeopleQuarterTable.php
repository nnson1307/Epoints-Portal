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

class PeopleQuarterTable extends Model
{
    protected $table = "people_quarter";
    protected $primaryKey = "people_quarter_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy thông tin tổ dân phố bằng tên
     *
     * @param $name
     * @return mixed
     */
    public function getPeopleQuarterByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
