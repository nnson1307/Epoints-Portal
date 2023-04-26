<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ZNS\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ParamsTable extends Model
{
    use ListTableTrait;
    protected $table = 'params';
    protected $primaryKey = 'params_id';

    protected $fillable = [
        'params_id',
        'name',
        'value',
        'is_zns',
        'description'
    ];

    protected function _getList($filters = [])
    {
        $query = $this->select(
            "{$this->table}.params_id",
            "{$this->table}.name",
            "{$this->table}.value",
            "{$this->table}.is_zns",
            "{$this->table}.description"
        );
        // filters tên + mô tả
        if (isset($filters["search"]) && $filters["search"] != "") {
            $query->where("{$this->table}.name", "like", "%" . $filters["search"] . "%");
        }
        $query = $query->where("{$this->table}.is_zns",1)->orderBy($this->primaryKey, 'ASC');
        return $query;
    }

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function edit(array $data, $id)
    {

        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

}