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

class PeopleHealthTypeTable extends Model
{
    protected $table = "people_health_type";
    protected $primaryKey = "people_health_type_id";
    protected $fillable = [
        "name",
    ];
}
