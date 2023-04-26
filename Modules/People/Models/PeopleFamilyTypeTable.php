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

class PeopleFamilyTypeTable extends Model
{
    protected $table = "people_family_type";
    protected $primaryKey = "people_family_type_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];

    /**
     * Lấy thành phần gia đình bằng tên
     *
     * @param $name
     * @return mixed
     */
    public function getFamilyTypeByName($name)
    {
        return $this->where("name", $name)->first();
    }
}
