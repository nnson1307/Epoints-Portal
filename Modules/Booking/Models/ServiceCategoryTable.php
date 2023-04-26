<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/12/2018
 * Time: 10:19 AM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCategoryTable extends Model
{
    protected $table = 'service_categories';
    protected $primaryKey = 'service_category_id';
    protected $fillable = [
        'service_category_id', 'name', 'description', 'is_actived', 'is_deleted', 'updated_at',
        'created_at', 'created_by', 'updated_by', 'slug'
    ];

    public function getOptionServiceCategory()
    {
        return $this->select('service_category_id', 'name', 'description', 'is_actived')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get()->toArray();
    }
}