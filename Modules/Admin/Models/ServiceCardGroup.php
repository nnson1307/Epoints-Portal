<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceCardGroup extends Model
{
    use ListTableTrait;
    protected $table = "service_card_groups";
    protected $primaryKey = "service_card_group_id";
    protected $fillable = ['service_card_group_id', 'name', 'description', 'created_at', 'updated_at', 'is_deleted', 'created_by', 'updated_by', 'slug'];

    protected function _getList()
    {
        return $this->select('service_card_group_id', 'name', 'description', 'created_at', 'updated_at', 'is_deleted', 'created_by', 'updated_by')
            ->where('is_deleted', 0)->orderBy($this->primaryKey, 'desc');
    }

    public function getAllName()
    {
        return self::select('service_card_group_id', 'name')->get();
    }

    public function add($array)
    {
        return self::create($array);
    }

    public function getItem($id)
    {
        return $this->select('service_card_group_id', 'name', 'description', 'created_at', 'updated_at', 'is_deleted', 'created_by', 'updated_by')
            ->where('service_card_group_id', $id)->first()->toArray();
    }

    public function getOption()
    {
        return $this->select('service_card_group_id', 'name')->get()->toArray();
    }

    public function checkName($id, $name)
    {
        if ($id == null) {
            return $this->where('name', $name)->first();
        } else {
            return $this->where('name', $name)->where('id', $id)->first();
        }
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);

    }

    public function checkSlug($name, $id)
    {
        return $this->where($this->primaryKey, '<>', $id)
            ->where('slug', $name)
            ->where('is_deleted', 0)
            ->first();
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }
}
