<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 11/16/2018
 * Time: 4:39 PM
 */

namespace Modules\Admin\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use MyCore\Models\Traits\ListTableTrait;

class InventoryCheckingStatusTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_checking_status';
    protected $primaryKey = 'inventory_checking_status_id';

    protected $fillable = ['inventory_checking_status_id', 'name', 'is_delete', 'is_active', 'is_default', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    /**
     * Lấy tất cả trạng thái
     */
    public function getAll(){
        return $this
            ->select('inventory_checking_status_id','name','is_default')
            ->where('is_delete',0)
            ->where('is_active',1)
            ->get();
    }

    /**
     * Lấy status by name
     */
    public function getStatusByName($statusName){
        return $this
            ->where('name',$statusName)
            ->first();
    }

    /**
     * Thêm trạng thái
     * @param $statusName
     */
    public function addStatus($data){
        return $this
            ->insertGetId($data);
    }

    /**
     * Lấy trạng thái mặc định đầu tiên
     */
    public function getFirstDefault(){
        return $this
            ->where('is_delete',0)
            ->where('is_active',1)
            ->where('is_default',1)
            ->first();
    }
}
