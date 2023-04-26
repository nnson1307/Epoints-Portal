<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/12/2018
 * Time: 10:19 AM
 */

namespace Modules\Admin\Repositories\ServiceCategory;


use Modules\Admin\Models\ServiceCategoryTable;

class ServiceCategoryRepository implements ServiceCategoryRepositoryInterface
{
    protected $service_category;
    protected $timestamps=true;
    public function __construct(ServiceCategoryTable $service_categories)
    {
        $this->service_category=$service_categories;
    }
    public function list(array $filters = [])
    {
        return $this->service_category->getList($filters);
    }
    public function remove($id)
    {
        $this->service_category->remove($id);
    }

    /**
     * add service_category Group
     */
    public function add(array $data)
    {
        return $this->service_category->add($data);
    }
    /*
     * edit service_category Group
     */
    public function edit(array $data ,$id)
    {

        return $this->service_category->edit($data,$id);
    }
    /*
     *  update or add
     */

    public function getItem($id)
    {
        return $this->service_category->getItem($id);
    }
    public function testName($name, $id)
    {
        return $this->service_category->testName($name,$id);
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