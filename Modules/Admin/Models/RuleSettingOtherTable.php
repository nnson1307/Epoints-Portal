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

class RuleSettingOtherTable extends Model
{
    use ListTableTrait;
    protected $table = 'rule_setting_other';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'is_actived', 'day', 'updated_by', 'updated_at'
    ];

    protected function _getList()
    {
        $ds = $this->select('id', 'name', 'is_actived', 'day');
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

}