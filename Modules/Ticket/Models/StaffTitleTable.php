<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/17/2018
 * Time: 1:26 PM
 */

namespace Modules\Ticket\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class StaffTitleTable extends Model
{
    use ListTableTrait;
    protected $table = 'staff_title';
    protected $primaryKey = 'staff_title_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_title_id', 'staff_title_name',
        'staff_title_code', 'staff_title_description',
        'is_active', 'is_delete', 'created_at', 'updated_at',
        'created_by', 'updated_by', 'slug', 'is_system'
    ];

    protected function getList()
    {
        return $this->select(
            'staff_title_id', 'staff_title_name',
            'staff_title_code', 'staff_title_description',
            'is_active', 'is_delete', 'created_at', 'updated_at',
            'created_by', 'updated_by', 'is_system')->where('is_delete', 0);
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }
    
    public function add(array $data)
    {
        $oTitle = $this->create($data);
        return $oTitle->staff_title_id;
    }

    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_delete' => 1]);
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getEdit($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }


    public function getStaffTitleOption()
    {
        return $this->select('staff_title_id', 'staff_title_name')->where('is_delete', 0)->get()->toArray();
    }

    /*
     * test name
     */
    public function testName($name)
    {
        return $this->where('slug', str_slug($name))->where('is_delete', 0)->first();
    }

    public function testIsDeleted($name)
    {
        return $this->where('slug', str_slug($name))->where('is_delete', 1)->first();
    }

    public function editByName($name)
    {
        return $this->where('staff_title_name', $name)->update(['is_delete' => 0]);
    }

    public function testNameId($name, $id)
    {
        return $this->where('staff_title_name', $name)->where('staff_title_id', '<>', $id)->where('is_delete', 0)->first();
    }

    public function getOption()
    {
        $select = $this->select('staff_title_id', 'staff_title_name')
            ->where('is_delete', 0)
            ->where('is_active', 1)
            ->get();
        return $select;
    }

//    public function getList()
//    {
//        $select = $this->select('staff_title_id', 'staff_title_name','is_active','staff_title_code')
//            ->where('is_delete', 0)->get();
//        return $select;
//    }

}