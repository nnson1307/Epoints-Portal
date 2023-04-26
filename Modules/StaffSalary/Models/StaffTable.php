<?php
/**
 * Created by PhpStorm.
 * User: hieupc
 * Date: 4/18/22
 * Time: 5:48 PM
 */

namespace Modules\StaffSalary\Models;

use Illuminate\Database\Eloquent\Model;
// use MyCore\Models\Traits\ListTableTrait;
use Carbon\Carbon;

class StaffTable extends Model
{
    // use ListTableTrait;
    protected $table = "staffs";
    protected $primaryKey = "staff_id";
//    protected $fillable = [
//        "staff_id",
//    ];


}