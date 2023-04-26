<?php

namespace Modules\ChatHub\Models;

use Illuminate\Database\Eloquent\Model;

class BOBrandTable extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'piospa_brand';
    protected $primaryKey = 'brand_id';
    protected $fillable = [
        'parent_id','tenant_id', 'brand_name','brand_code', 'brand_url', 'brand_avatar', 'brand_banner', 'brand_about','brand_contr','hotline', 'company_name',
        'company_code', 'position', 'display_name', 'is_published', 'is_activated', 'is_deleted', 'created_at', 'updated_at', 'created_by', 'updated_by'];
    public function getBrandId($name){
        $select=$this->where('brand_code', '=', $name)->first();
        return $select['brand_id'];
    }
}