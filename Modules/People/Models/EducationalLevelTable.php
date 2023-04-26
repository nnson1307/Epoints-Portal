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

class EducationalLevelTable extends Model
{
    protected $table = "educational_level";
    protected $primaryKey = "educational_level_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy trình độ học vấn bằng tên
     *
     * @param $name
     * @return mixed
     */
    public function getEducationLevelByName($name)
    {
        return $this->where("name", $name)->first();
    }

    public function getListByReport(){
        return $this->get();
    }
}
