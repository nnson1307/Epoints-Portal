<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 14:37
 */

namespace Modules\ManagerProject\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ManageProjectContactTable extends Model
{
    use ListTableTrait;
    protected $table = "manage_project_contact";
    protected $primaryKey = "manage_project_contact_id";
    protected $fillable = [
        "manage_project_contact_id",
        "manage_project_id",
        "name",
        "phone",
        "created_at",
        "created_by",
        "updated_at",
        "updated_by",
    ];

    public function insertArrayData($data){
        return $this->insert($data);
    }

    public function getListByIdProject($projectId){
        return $this
            ->where('manage_project_id',$projectId)
            ->get();
    }

    public function removeContact($id){
        return $this
            ->where('manage_project_id',$id)
            ->delete();
    }

}