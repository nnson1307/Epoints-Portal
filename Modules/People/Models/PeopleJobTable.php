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

class PeopleJobTable extends Model
{
    protected $table = "people_job";
    protected $primaryKey = "people_job_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy thông tin nghề nghiệp
     *
     * @param $name
     * @return mixed
     */
    public function getPeopleJobByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
