<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 7/4/2019
 * Time: 11:55 AM
 */

namespace Modules\Booking\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SpaInfoTable extends Model
{
    protected $table = "spa_info";
    protected $primaryKey = "id";

    protected $fillable = [
        'id', 'name', 'code', 'phone', 'email', 'hot_line',
        'provinceid', 'districtid', 'address', 'slogan', 'bussiness_id', 'logo',
        'is_actived', 'is_deleted', 'fanpage', 'zalo', 'instagram_page', 'created_by',
        'updated_by', 'created_at', 'updated_at', 'website', 'tax_code'
    ];

    public function getItem($id)
    {
        $select = $this->select(
            'spa_info.id', 'spa_info.name', 'spa_info.slogan',
            'spa_info.phone', 'spa_info.email', 'spa_info.hot_line',
            'spa_info.fanpage', 'spa_info.zalo',
            'spa_info.instagram_page', 'spa_info.website','address',
            DB::raw('CONCAT(prv.type, " " ,prv.name) as province_name'),
            DB::raw('CONCAT(dis.type, " " ,dis.name) as district_name')
        )
            ->leftJoin('province as prv', 'prv.provinceid', '=', "spa_info.provinceid")
            ->leftJoin('district as dis', 'dis.districtid', '=', "spa_info.districtid")
            ->where('spa_info.id', $id)
            ->where('spa_info.is_deleted', 0)
            ->first();
        return $select;
    }

    public function getLogo(){
        $select = $this->get();
        return $select->first();
    }

    public function getIntroduction(){
        $oSelect = $this->select('introduction')->get();
        return $oSelect;
    }
}