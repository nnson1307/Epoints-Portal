<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/24/2018
 * Time: 10:37 AM
 */

namespace Modules\ZNS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class BranchTable extends Model
{
    use ListTableTrait;
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    //function fillable
    protected $fillable = [
        'branch_id',
        'branch_name',
        'address',
        'description',
        'is_actived',
        'is_deleted',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'phone',
        'email',
        'hot_line',
        'provinceid',
        'districtid',
        'is_representative',
        'representative_code',
        'slug',
        'latitude',
        'longitude',
        'branch_code'
    ];

    
    public function getName()
    {
        $oSelect = self::select("branch_id", "branch_name")->where('is_deleted', 0)->get();
        return (["" => __("Chọn chi nhánh")]) + ($oSelect->pluck("branch_name", "branch_id")->toArray());
    }

}