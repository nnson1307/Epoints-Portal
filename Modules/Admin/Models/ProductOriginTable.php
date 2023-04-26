<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/13/2018
 * Time: 1:31 PM
 */
namespace Modules\Admin\Models;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ProductOriginTable extends Model
{
    use ListTableTrait;
    protected $table = 'product_origin';
    protected $primaryKey='product_origin_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_origin_id', 'product_origin_name','product_origin_code', 'product_origin_description',
        'is_active','is_delete' ,'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    /**
     * Build query table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList(){
        return $this->select('product_origin_id', 'product_origin_name','product_origin_code', 'product_origin_description',
            'is_active','is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by')->where('is_delete',0);
    }

    /**
     * add product origin
     *
     * @param number $id
     */
    public function add(array $data){
        $oProductOrigin=$this->create($data);
        return $oProductOrigin->id;
    }

    public function remove($id){
        $this->where($this->primaryKey,$id)->update(['is_delete'=>1]);
    }
    public function edit(array $data,$id){
        return $this->where($this->primaryKey,$id)->update($data);
    }
    public function getEdit($id){
        return $this->where($this->primaryKey,$id)->first();
    }


}