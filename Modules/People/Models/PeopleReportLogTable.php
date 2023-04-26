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

class PeopleReportLogTable extends Model
{
    protected $table = "people_report_log";
    protected $primaryKey = "people_report_log_id";
    protected $fillable = [
        "people_report_log_id",
        "birthyear",
    ];
}
