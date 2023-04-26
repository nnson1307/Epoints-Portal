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

class PeopleFamilyRelationshipTypeTable extends Model
{
    protected $table = "people_family_relationship_type";
    protected $primaryKey = "people_family_relationship_type_id";
    protected $fillable = [
        "name",
        "created_at",
        "updated_at",
    ];
}
