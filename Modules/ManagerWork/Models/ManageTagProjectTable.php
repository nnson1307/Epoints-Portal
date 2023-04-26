<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 20/09/2022
 * Time: 14:40
 */

namespace Modules\ManagerWork\Models;


use Illuminate\Database\Eloquent\Model;

class ManageTagProjectTable extends Model
{
    protected $table = "manage_project_tag";
    protected $primaryKey = "manage_project_tag_id";
    protected $fillable = [
        'manage_project_tag_id',
        'manage_project_id',
        'tag_id'
    ];
    const IS_ACTIVE = 1;

}