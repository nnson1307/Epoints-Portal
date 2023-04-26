<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 1/29/2019
 * Time: 2:47 PM
 */

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class BrandNameTable extends Model
{
    protected $table = "brandname";
    protected $primaryKey = "id";

    protected $fillable = ['id', 'name', 'updated_by', 'created_by', 'updated_at', 'created_at', 'is_active'];

    public function getOption()
    {
        $select = $this->select('id', 'name')->where('is_active', 1)->get();
        return $select;
    }

    public function getItem($id)
    {
        $select = $this->where('id', $id)->first();
        return $select;
    }
}