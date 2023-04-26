<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerProject\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ManagerConfigListTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_config_list';
    protected $primaryKey = 'manage_config_list_id';

    protected $fillable = [
        'manage_config_list_id', 'name', 'value', 'route_name',
        'type', 'user_id'
    ];
    protected function _getList($filters = [])
    {

        $query = $this->select(
            'manage_config_list_id',
            'name',
            'value',
            'route_name',
            'type',
            'user_id'
        )
            ->orderBy($this->primaryKey, 'desc');
        return $query;
    }
    public function add(array $data)
    {
        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_config_list_id;
    }

    public function remove($id)
    {
        return $this->where('user_id', $id)->delete();
    }

    public function edit(array $data, $id, $route_name)
    {
        return $this->where('user_id', $id)->where('route_name', $route_name)->update($data);
    }

    public function getItem($id)
    {
        return $this->where('user_id', $id)->first();
    }

    /*
    * check exist
    */
    public function checkExist($id = '', $route_name = '')
    {
        return $this->where('user_id', $id)
            ->where('route_name', $route_name)
            ->first();
    }

    /**
     * lấy cấu hình hiển thị danh sách dự án theo route name
     * @param string $routeName 
     * @return mixed
     */

    public function getItemByRoute($routeName)
    {
        return $this
            ->where('route_name', $routeName)
            ->first();
    }
}
