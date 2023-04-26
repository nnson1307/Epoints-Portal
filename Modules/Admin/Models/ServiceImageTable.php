<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/6/2018
 * Time: 2:05 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceImageTable extends Model
{
    use ListTableTrait;
    protected $table = "service_images";
    protected $primaryKey = 'service_image_id';
    protected $fillable = [
        'service_image_id', 'service_id', 'name', 'type', 'created_at', 'created_by','updated_at'
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->service_image_id;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('service_image_id', $id)->update($data);
    }

    /**
     * @param $id
     */
    public function getItem($id)
    {
        $ds=$this->leftJoin('services','services.service_id','=','service_images.service_id')
            ->select('service_images.service_image_id','service_images.name')
            ->where('service_images.service_id',$id)->get();
        return $ds;
    }
    public function remove($name)
    {
        return $this->where('service_images.name',$name)->delete();
    }
}