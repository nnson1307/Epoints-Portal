<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 12:45 PM
 */

namespace Modules\CustomerLead\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class DistrictTable extends Model
{
    use ListTableTrait;
    protected $table="district";
    protected $primaryKey = "districtid";
    protected $fillable=[
        'districtid',
        'postcode',
        'name',
        'provinceid',
        'lat',
        'long',
        'type'
    ];

    public $timestamps = false;


    public function getOptionDistrict($id)
    {
        return  $this->select('districtid', 'postcode','name','lat', 'long','type')->where('provinceid', $id)->get();
    }

    public function getAllDistrict()
    {
        return $this->get();
    }

    public function truncateDistrict()
    {
        return $this->truncate();
    }

    public function add(array $data)
    {
        return $this->create($data);
    }

    public function _getList(&$filters = [])
    {
        $search = '';
        $id='';
        if(isset($filters['search']) && $filters['search'] != null) {
            $search = $filters['search'];
            unset($filters['search']);
        }
        if(isset($filters['id_province']) && $filters['id_province'] != null) {
            $id = $filters['id_province'];
            unset($filters['id_province']);
        }
        $a= $this->select('districtid', 'postcode','name','lat', 'long','type')->where('provinceid',$id)
            ->where('name', 'like' , '%'.$search.'%');
        return $a;

    }

    /**
     * Lấy thông tin quận huyện
     *
     * @param $districtId
     * @return mixed
     */
    public function getItem($districtId)
    {
        return $this->where("districtid", $districtId)->first();
    }

    /**
     * Lấy thông tin quận/ huyện bằng tên
     *
     * @param $provinceId
     * @param $name
     * @return mixed
     */
    public function getDistrictByName($provinceId, $name)
    {
        return $this
            ->where("provinceid", $provinceId)
            ->where("name", $name)
            ->first();
    }

}