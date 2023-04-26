<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 14:47
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class RuleMenuTable extends Model
{
    use ListTableTrait;
    protected $table = 'rule_menu';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'is_actived', 'position', 'updated_by', 'created_at', 'updated_at'
    ];

    protected function _getList()
    {
        $ds = $this->select('id', 'name', 'is_actived', 'position')
            ->orderBy('position','asc');
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }
}