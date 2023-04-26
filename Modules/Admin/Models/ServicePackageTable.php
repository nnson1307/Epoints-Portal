<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 13/03/2018
 * Time: 1:10 CH
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

/**
 * ServiceGroupTable
 * @author thachleviet
 * @since March 13, 2018
 */
class ServicePackageTable extends  Model
{
    use ListTableTrait;

    /*
     * table service_package
     */
    protected $table = 'service_package' ;
    protected $primaryKey = 'service_package_id';

    /*
     * fill table
     * $var array
     */
    protected $fillable = ['service_package_id', 'service_package_name', 'is_active', 'created_at', 'updated_at', 'created_by', 'updated_by'] ;

    /*
     * Build query table
     * @author thach le viet
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function _getList()
    {
        return $this->select('service_package.service_package_id', 'service_package.service_package_name', 'service_package.is_active', 'service_package.created_at', 'service_package.updated_at', 'service_package.created_by' , 'service_package.updated_by')->where('is_delete',0)->orderBy($this->primaryKey,'desc');
    }
    // function remove item
    public function remove($id)
    {
        return $this->where($this->primaryKey,$id )->update(['is_delete'=> 1]);
    }
    /*
     * function edit
     */
    public function edit(array $data ,$id){
        return $this->where($this->primaryKey,$id)->update($data);

    }
    /*
     * function save
     */

    /*
     * function add
     */
    public function add(array $data){
        $oService =  $this->create($data);
        return $oService->service_package_id ;
    }
    /*
     * function getItem
     */
    public function getItem($id){
        return $this->where($this->primaryKey,$id )->first();
    }
}