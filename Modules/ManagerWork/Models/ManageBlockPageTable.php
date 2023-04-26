<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ManageBlockPageTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_block_page';
    protected $primaryKey = 'manage_position_block_id';

    protected $fillable = [
        'manage_position_block_id',
        'staff_id',
        'route_page',
        'key_block',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    /**
     * Xóa block theo id nhân viên và route
     * @param $StaffId
     * @param $route
     */
    public function removeBlock($staffId,$route){
        return $this
            ->where('staff_id',$staffId)
            ->where('route_page',$route)
            ->delete();
    }

    /**
     * Thêm block
     * @param $data
     * @return mixed
     */
    public function addBlock($data){
        return $this->insert($data);
    }

    /**
     * Lấy danh sách vị trí block
     */
    public function getListBlock($routeName,$staffId){
        return $this
            ->where('staff_id',$staffId)
            ->where('route_page',$routeName)
            ->get();
    }

}