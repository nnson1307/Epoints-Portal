<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 21/3/2019
 * Time: 15:10
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BannerSliderTable extends Model
{
    use ListTableTrait;
    protected $table = 'banner_slider';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'type', 'link', 'position', 'created_by', 'updated_by',
        'created_at', 'updated_at'
    ];

    protected function _getList($filter = [])
    {
        $ds = $this->select('id', 'name', 'type', 'link', 'position')
            ->orderBy('position', 'asc');
        return $ds;
    }

    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    public function getItem($id)
    {
        $ds = $this->select('id', 'name', 'link', 'position')
            ->where('id', $id)->first();
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function remove($id)
    {
        $this->where('id', $id)->delete();
    }
}
