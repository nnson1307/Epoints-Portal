<?php


namespace Modules\Delivery\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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

    /**
     * Danh sach quan/huyen co filter theo ten
     *
     * @param array $filters
     * @return mixed
     */
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
        $a= $this->select('districtid', 'postcode','name','lat', 'long','type')
            ->where('name', 'like' , '%'.$search.'%')
            ->orWhere('postcode', 'like' , '%'.$search.'%');
        return $a;

    }

    /**
     * option district
     *
     * @return mixed
     */
    public function getOptionDistrict()
    {
        $a= $this->select('districtid', 'postcode', 'name', 'lat', 'long', 'type')->get();
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
     * Lấy thông tin quận huyện
     *
     * @param $districtId
     * @return mixed
     */
    public function getItemUpdate($districtId)
    {
        return $this->where('districtid', $districtId)->first();
    }

    /**
     * Danh sách huyện theo tỉnh thành
     *
     * @param array $filters
     * @return mixed
     */
    public function getDistrictByArrayProvince($filters = [])
    {
        $search = '';
        $arrProvince = [];
        $page = (int)(isset($filters['page']) ? $filters['page'] : 1);
        if (isset($filters['search']) && $filters['search'] != null) {
            $search = $filters['search'];
            unset($filters['search']);
        }
        if (isset($filters['arrProvince']) && $filters['arrProvince'] != null) {
            $arrProvince = $filters['arrProvince'];
            unset($filters['arrProvince']);
        }
        $select = $this->select(
            'districtid', 'postcode', 'name'
        )
            ->where('name', 'like' , '%'.$search.'%')
            ->whereIn('provinceid', $arrProvince);
        return $select->paginate(10, $columns = ['*'], $pageName = 'page', $page);;
    }
}