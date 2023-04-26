<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class SpaInfoTable extends Model
{
    protected $table = "spa_info";
    protected $primaryKey = 'id';

    /**
     * Lấy thông tin spa theo id
     *
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->select(
            'id', 'spa_info.name as name', 'code', 'phone', 'is_actived',
            'is_deleted', 'email', 'hot_line',
            'address', 'slogan', 'bussiness_id',
            'logo', 'fanpage', 'zalo', 'instagram_page',
            'district.name as district_name',
            'district.type as district_type',
            'province.name as province_name',
            'tax_code',
            'spa_info.is_part_paid'
        )
            ->leftJoin('province', 'province.provinceid', '=', 'spa_info.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'spa_info.districtid')
            ->where('id', $id)
            ->first();
    }
}