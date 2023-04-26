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

class ConfigColumnTable extends Model
{
    use ListTableTrait;
    protected $table = 'config_column';
    protected $primaryKey = 'config_column_id';

    const IS_DEFAULT = 1;

    protected $fillable
        = [
            'config_column_id',
            'route',
            'type',
            'order',
            'name',
            'column_type',
            'column_nameConfig_vi',
            'column_nameConfig_en',
            'column_placeholder_vi',
            'column_placeholder_en',
            'column_class',
            'column_id',
            'column_name',
            'is_default',
            'created_at',
            'created_by',
            'updated_at',
            'updated_by',
        ];

    /**
     * Lấy danh sách cấu hình theo route
     * @param $route
     */
    public function getAllByRoute($route){
        return $this->where('route',$route)
            ->orderBy('order','ASC')
            ->get();
    }

    /**
     * Lấy danh sách cấu hình mặc định
     * @param $route
     */
    public function getAllByRouteDefault($route){
        return $this->where('route',$route)
            ->where('is_default',self::IS_DEFAULT)
            ->orderBy('order','ASC')
            ->get();
    }

}