<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 11:56 AM
 */

namespace Modules\CustomerLead\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class ProvinceTable extends Model
{
    protected $table="province";


    /**
     * Lấy option tỉnh thành
     *
     * @return mixed
     */
    public function getOptionProvince()
    {
        return $this
            ->select(
                'provinceid',
                'name',
                'type'
            )->get();
    }

    /**
     * Lấy thông tin tỉnh thành
     *
     * @param $name
     * @return mixed
     */
    public function getProvinceByName($name)
    {
        return $this->where("name", $name)->first();
    }

    public function getProvinceById($id)
    {
        return $this->where("provinceid", $id)->first();
    }
}