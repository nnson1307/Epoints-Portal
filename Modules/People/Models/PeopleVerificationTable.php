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

class PeopleVerificationTable extends Model
{
    protected $table = "people_verification";
    protected $primaryKey = "people_verification_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];
}
