<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 10/12/2018
 * Time: 10:19 AM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceCategoryTable extends Model
{
    use ListTableTrait;
    protected $table = 'service_categories';
    protected $primaryKey = 'service_category_id';
    protected $fillable = [
        'service_category_id', 'name', 'description', 'is_actived', 'is_deleted', 'updated_at',
        'created_at', 'created_by', 'updated_by', 'slug'
    ];

    //function lấy danh sách
    public function _getList()
    {
        $ds = $this->select('service_category_id', 'name', 'description', 'is_actived', 'updated_at',
            'created_at', 'created_by', 'updated_by')->where('is_deleted', 0)->orderBy('service_category_id', 'desc');
        return $ds;
    }

    //function add
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    //function xoa
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    public function testName($name, $id)
    {
        return $this->where('slug', $name)->where('service_category_id', '<>', $id)->where('is_deleted', 0)->first();
    }

    public function getOptionServiceCategory()
    {
        return $this->select('service_category_id', 'name', 'description', 'is_actived')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get()->toArray();
    }

    //function get dữ liệu khi edit
    public function getItem($id)
    {
        return $this->select('service_category_id', 'name', 'description', 'is_actived', 'updated_at',
            'created_at', 'created_by', 'updated_by')
            ->where($this->primaryKey, $id)->first();
    }

    //function edit
    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getName()
    {
        $oSelect = self::select("service_category_id", "name")->where('is_deleted', 0)->get();
        return (["" => "Tất cả"]) + ($oSelect->pluck("name", "service_category_id")->toArray());
    }
}