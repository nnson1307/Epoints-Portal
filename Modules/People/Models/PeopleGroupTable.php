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

class PeopleGroupTable extends Model
{
    protected $table = "people_group";
    protected $primaryKey = "people_group_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy thông tin khu phố bằng id
     *
     * @param $name
     * @return mixed
     */
    public function getPeopleGroupByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
