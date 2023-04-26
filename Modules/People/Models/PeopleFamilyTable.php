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

class PeopleFamilyTable extends Model
{
    protected $table = "people_family";
    protected $primaryKey = "people_family_id";
    protected $fillable = [
        "people_family_id",
        "people_id",
        "people_family_relationship_type_id",
        "full_name",
        "birth_year",
        "people_job_id",
        "address",
        "before_30041975",
        "after_30041975",
        "current",
    ];

    public $timestamps = false;

    /**
     * Thêm thông tin quan hệ của công dân
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->people_family_id;
    }

    /**
     * Lấy thông tin quan hệ với công dân
     *
     * @param $peopleId
     * @param $relationshipTypeId
     * @return mixed
     */
    public function getFamilyPeople($peopleId, $relationshipTypeId)
    {
        return $this
            ->select(
                "{$this->table}.full_name",
                "{$this->table}.birth_year",
                "{$this->table}.current",
                "j.name as job_name"
            )
            ->leftJoin("people_job as j", "j.people_job_id", "=", "{$this->table}.people_job_id")
            ->where("{$this->table}.people_id", $peopleId)
            ->where("{$this->table}.people_family_relationship_type_id", $relationshipTypeId)
            ->first();
    }
}
