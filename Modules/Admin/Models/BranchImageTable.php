<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 13/3/2019
 * Time: 18:12
 */

namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BranchImageTable extends Model
{
    use ListTableTrait;
    protected $table = "branch_images";
    protected $primaryKey="branch_image_id";

    protected $fillable=[
      'branch_image_id','branch_id','name','type','created_at','updated_at','created_by'
    ];

    public function add(array $data)
    {
        $branch_img=$this->create($data);
        return $branch_img->branch_image_id;
    }
    public function getItem($id)
    {
        $ds=$this->select('branch_image_id','branch_id','name')
            ->where('branch_id',$id)->get();
        return $ds;
    }
    public function remove($name)
    {
        return $this->where('name',$name)->delete();
    }
}