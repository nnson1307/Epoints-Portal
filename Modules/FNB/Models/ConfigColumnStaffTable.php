<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ConfigColumnStaffTable extends Model
{
    use ListTableTrait;
    protected $table = 'config_column_staff';
    protected $primaryKey = 'config_column_staff_id';
    protected $fillable
        = [
            'config_column_staff_id',
            'staff_id',
            'route',
            'config_column_id',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

    /**
     * lấy danh
     * @param $staffId
     * @param $route
     */
    public function getAllByRoute($staffId,$route){
        return $this
            ->select(
                $this->table.'.*',
                'config_column.type',
                'config_column.column_nameConfig_vi',
                'config_column.column_nameConfig_en',
                'config_column.column_placeholder_vi',
                'config_column.column_placeholder_en',
                'config_column.column_type',
                'config_column.column_class',
                'config_column.column_id',
                'config_column.column_name',
                'config_column.is_default'
            )
            ->join('config_column','config_column.config_column_id',$this->table.'.config_column_id')
            ->where($this->table.'.route',$route)
            ->where($this->table.'.staff_id',$staffId)
            ->orderBy('config_column.order','ASC')
            ->get();
    }

    /**
     * Xóa cấu hình theo nhân viên
     */
    public function removeConfig($staffId,$route){
        return $this
            ->where('staff_id',$staffId)
            ->where('route',$route)
            ->delete();
    }

    /**
     * Lưu cấu hình
     * @param $data
     */
    public function addConfig($data){
        return $this
            ->insert($data);
    }
}