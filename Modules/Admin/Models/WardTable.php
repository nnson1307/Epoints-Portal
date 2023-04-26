<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 5:41 PM
 */

namespace Modules\Admin\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class WardTable extends Model
{
    protected $table='ward';
    protected $fillable=['ward_id','name','type','location','district_id'];

    public function getOptionWard($id)
    {
//        return $this->select('wardid','name')->where('districtid',$id)->get();
        return $this->select('ward_id','name','location','type')->where('district_id',$id)->get();
    }

    /**
     * lấy chi tiết ward
     * @param $id
     * @return mixed
     */
    public function getOptionWardDetail($wardid)
    {
//        return $this->select('wardid','name')->where('districtid',$id)->get();
        return $this->select('ward_id','name','location','type')->where('ward_id',$wardid)->first();
    }

    /**
     * Lấy thông tin phường/xã
     *
     * @param $districtId
     * @param $wardName
     * @return mixed
     */
    public function getWardByName($districtId, $wardName)
    {
        return $this
            ->where("district_id", $districtId)
            ->whereRaw('TRIM(LOWER(`name`)) LIKE ? ',[trim(strtolower($wardName)).'%'])
            ->first();
    }
}