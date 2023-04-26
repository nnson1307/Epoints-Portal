<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 10:10
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TimeWorkingTable extends Model
{
    use ListTableTrait;
    protected $table = 'time_working';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'eng_name', 'vi_name', 'is_actived', 'start_time', 'end_time',
        'updated_by', 'created_at', 'updated_at'
    ];

    public function _getList()
    {
        $ds = $this->select('id', 'eng_name', 'vi_name', 'is_actived', 'start_time', 'end_time',
            'updated_by');
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }
}