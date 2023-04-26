<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 21/3/2019
 * Time: 09:30
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class SpaInfoTable extends Model
{
    use ListTableTrait;
    protected $table = "spa_info";
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'code',
        'phone',
        'email',
        'hot_line',
        'provinceid',
        'districtid',
        'address',
        'slogan',
        'bussiness_id',
        'logo',
        'is_actived',
        'is_deleted',
        'fanpage',
        'zalo',
        'instagram_page',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
        'tax_code',
        'is_part_paid',
        'introduction',
        'branch_apply_order',
        'total_booking_time'
    ];

    protected function _getList(&$filter = [])
    {
        $ds = $this->leftJoin('bussiness', 'bussiness.id', '=', 'spa_info.bussiness_id')
            ->leftJoin('province', 'province.provinceid', '=', 'spa_info.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'spa_info.districtid')
            ->select('spa_info.name',
                'spa_info.phone',
                'spa_info.code',
                'spa_info.address',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name',
                'spa_info.is_actived',
                'spa_info.id')
            ->where('spa_info.is_deleted', 0)->orderBy('spa_info.id', 'desc');
        if (isset($filter['search_info']) != "") {
            $search = $filter['search_info'];
            $ds->where('spa_info.name', 'like', '%' . $search . '%')
                ->orWhere('spa_info.code', 'like', '%' . $search . '%')
                ->orWhere('spa_info.phone', 'like', '%' . $search . '%')
                ->where('spa_info.is_deleted', 0);
        }
        unset($filter['search_info']);
        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->where('name', $name)
            ->where('id', '<>', $id)->where('is_deleted', 0)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem()
    {
        return $this
            ->leftJoin('province', 'province.provinceid', '=', 'spa_info.provinceid')
            ->leftJoin('district', 'district.districtid', '=', 'spa_info.districtid')
            ->select(
                'spa_info.id',
                'spa_info.name',
                'spa_info.code',
                'spa_info.phone',
                'spa_info.is_actived',
                'spa_info.is_deleted',
                'spa_info.email',
                'spa_info.hot_line',
                'spa_info.provinceid',
                'spa_info.districtid',
                'spa_info.address',
                'spa_info.slogan',
                'spa_info.bussiness_id',
                'spa_info.logo',
                'spa_info.fanpage',
                'spa_info.zalo',
                'spa_info.instagram_page',
                'spa_info.branch_apply_order',
                'spa_info.total_booking_time',
                'province.type as province_type',
                'province.name as province_name',
                'district.type as district_type',
                'district.name as district_name',
                'tax_code',
                'spa_info.is_part_paid'
            )
            ->get();
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->where('id', $id)->update(['is_deleted' => 1]);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function getInfoSpa()
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
            ->where('id', 1)
            ->first();
    }

    public function getIntroduction()
    {
        return $this->select('id','spa_info.introduction')->get();
    }

    public function updateIntroduction($data){
        return $this->where('id',$data['id'])->update(['introduction' => $data['description']]);
    }

}