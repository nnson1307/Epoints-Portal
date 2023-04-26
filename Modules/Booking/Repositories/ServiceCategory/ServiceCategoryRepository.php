<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/12/2018
 * Time: 10:19 AM
 */

namespace Modules\Booking\Repositories\ServiceCategory;


use Modules\Booking\Models\ServiceCategoryTable;

class ServiceCategoryRepository implements ServiceCategoryRepositoryInterface
{
    protected $service_category;
    protected $timestamps=true;
    public function __construct(ServiceCategoryTable $service_categories)
    {
        $this->service_category=$service_categories;
    }
    public function getOptionServiceCategory()
    {
        $array=array();
        foreach ($this->service_category->getOptionServiceCategory() as $item)
        {
            $array[$item['service_category_id']]=$item['name'];

        }
        return $array;
    }
}