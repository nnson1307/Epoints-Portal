<?php

namespace Modules\Warranty\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategoryTable extends Model
{
    protected $table = 'service_categories';
    protected $primaryKey = 'service_category_id';

    public function getOptionServiceCategory()
    {
        return $this->select('service_category_id', 'name', 'description', 'is_actived')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get()->toArray();
    }
}