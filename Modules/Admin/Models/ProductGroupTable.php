<?php
namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * User Model
 *
 * @author isc-daidp
 * @since Feb 23, 2018
 */
class ProductGroupTable extends Model
{

    use ListTableTrait;
    protected $table = 'product_group';
    protected $primaryKey="product_group_id";



    protected $fillable = [
        'product_group_id','product_image','product_group_name', 'product_group_code', 'product_group_description', 'is_active', 'created_at', 'updated_at', 'created_by', 'updated_by','is_delete'
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    protected function _getList()
    {
//        return $this->select('id', 'name', 'email', 'is_active', 'created_at', 'updated_at');

        $oSelect  = $this->select('product_image','product_group_id as id', 'product_group_name as name','product_group_code as code','product_group_description as detail', 'is_active', 'created_at', 'updated_at','created_by', 'updated_by')->where('is_delete','=',0);
        return $oSelect;
    }


    /**
     * Remove user
     *
     * @param number $id
     */
    public function remove($id)
    {
        return $this->where($this->primaryKey,$id )->update(['is_delete'=> 1]);   // $this-> tuc la select cai bang productGroupTable hien tai
    }


    /**
     * Insert user to database
     *
     * @param array $data
     * @return number
     */
    public function add(array $data)
    {
        $oUser = $this->create($data);

        return $oUser->product_group_id;
    }

    public function edit(array $data,$id)
    {
        return $this->where($this->primaryKey,$id)->update($data);

    }

    public function getItem($id){
        return  $this->where($this->primaryKey,$id)->first();
    }
}