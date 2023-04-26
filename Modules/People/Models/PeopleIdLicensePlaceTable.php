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

class PeopleIdLicensePlaceTable extends Model
{
    protected $table = "people_id_license_place";
    protected $primaryKey = "people_id_license_place_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy nơi cấp của CMND
     *
     * @param $name
     * @return mixed
     */
    public function getLicensePlaceByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
