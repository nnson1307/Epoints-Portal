<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 18/2/2019
 * Time: 14:44
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class EmailConfigTable extends Model
{
    use ListTableTrait;
    protected $table = 'email_config';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'key', 'value', 'title', 'content', 'is_actived', 'created_by', 'updated_by', 'created_at',
        'updated_at', 'actived_by', 'datetime_actived', 'time_sent'
    ];

    protected function _getList(&$filter = [])
    {
        $oSelect = $this->select('id', 'key', 'value', 'title', 'content', 'is_actived');
        return $oSelect;
    }

    /**
     * Lấy ds cấu hình email ko phân trang
     *
     * @param array $filter
     * @return mixed
     */
    public function getListConfig(&$filter = [])
    {
        $oSelect = $this->select('id', 'key', 'value', 'title', 'content', 'is_actived');
        return $oSelect->get();
    }

    public function getConfig()
    {
        return $this->select('id', 'key', 'title', 'content', 'is_actived')->get();
    }

    public function getItem($id)
    {
        return $this->select(
            'id',
            'key',
            'value',
            'title',
            'content',
            'is_actived',
            'time_sent')
            ->where('id', $id)->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function getSubject($key)
    {
        $ds = $this->select('title')->where('key', $key)->first();
        return $ds;
    }
}