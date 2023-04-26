<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/6/2018
 * Time: 2:12 PM
 */

namespace Modules\Admin\Repositories\ServiceImage;


use Modules\Admin\Models\ServiceImageTable;

class ServiceImageRepository implements ServiceImageRepositoryInterface
{
    protected $service_image;
    protected $timestamps=true;

    /**
     * ServiceImageRepository constructor.
     * @param ServiceImageTable $service_images
     */
    public function __construct(ServiceImageTable $service_images)
    {
        $this->service_image=$service_images;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->service_image->add($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->service_image->edit($data,$id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->service_image->getItem($id);
    }
    public function remove($name){
        return $this->service_image->remove($name);
    }
}