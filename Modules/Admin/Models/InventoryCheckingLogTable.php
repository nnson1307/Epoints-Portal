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

class InventoryCheckingLogTable extends Model
{
    use ListTableTrait;
    protected $table = 'inventory_checking_log';
    protected $primaryKey = 'inventory_checking_log_id';

    protected $fillable = ['inventory_checking_log_id', 'inventory_checking_id', 'staff_id', 'content', 'reason', 'created_at', 'created_by'];

    /**
     * Thêm log
     */
    public function insertLog($data){
        return $this
            ->insertGetId($data);
    }

    /**
     * lấy danh sách log có phân trang
     */
    public function getListLog($filter = []){

        $page = (int)($filter['page'] ?? 1);
        $display = (int)($filter['perpage'] ?? PAGING_ITEM_PER_PAGE);

        $oSelect = $this
            ->select(
                $this->table.'.inventory_checking_log_id',
                $this->table.'.inventory_checking_id',
                $this->table.'.staff_id',
                $this->table.'.content',
                $this->table.'.reason',
                $this->table.'.created_at',
                'staffs.full_name'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.staff_id');
        
        if(isset($filter['inventory_checking_id'])){
            $oSelect = $oSelect->where($this->table.'.inventory_checking_id',$filter['inventory_checking_id']);
        }
            
        return $oSelect->orderBy($this->table.'.inventory_checking_log_id','DESC')->paginate($display, $columns = ['*'], $pageName = 'page', $page);
    }
}
//