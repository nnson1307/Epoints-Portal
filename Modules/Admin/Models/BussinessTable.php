<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 20/3/2019
 * Time: 15:25
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BussinessTable extends Model
{
    use ListTableTrait;
    protected $table = 'bussiness';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'is_actived', 'is_deleted', 'description', 'created_by', 'updated_by',
        'created_at', 'updated_at'
    ];

    public function getData(){
        return self::where('is_deleted', 0)->get();
    }

    protected function _getList(&$filter = [])
    {

        $ds = $this->select('id', 'name', 'is_actived', 'description')
            ->where('is_deleted', 0)->orderBy('id', 'desc');
        if (isset($filter['search_bussiness']) != "") {
            $search = $filter['search_bussiness'];
            $ds->where('name', 'like', '%' . $search . '%')
                ->where('is_deleted', 0);
        }
        unset($filter['search_bussiness']);
        return $ds;
    }

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    public function getItem($id)
    {
        $ds = $this->select('id', 'name', 'is_actived', 'description')
            ->where('id', $id)->first();
        return $ds;
    }
    public function edit(array $data,$id)
    {
        return $this->where('id', $id)->update($data);
    }
    public function remove($id)
    {
        $this->where('id', $id)->update(['is_deleted' => 1]);
    }
    public function testName($name, $id)
    {
        return $this->where('name', $name)->where('id', '<>', $id)->where('is_deleted', 0)->first();
    }
    public function getBussinessOption()
    {
        return $this->select('id', 'name')
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->get();
    }

}