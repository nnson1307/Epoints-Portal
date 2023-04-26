<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 11/21/2019
 * Time: 11:43 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigDetailTable extends Model
{
    protected $table = "config_detail";
    protected $primaryKey = "id";
    public $timestamps = false;
    protected $fillable
        = [
            'id','config_id', 'key', 'name','value'
        ];

    public function getAllById($id)
    {
        return $this->where('config_id',$id)->get();
    }

    public function edit($key, $value)
    {
        return $this->where('key',$key)->update(['value' => $value]);
    }

}