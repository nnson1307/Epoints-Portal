<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManagerWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ManageTagsTable extends Model
{
    use ListTableTrait;
    protected $table = 'manage_tags';
    protected $primaryKey = 'manage_tag_id';

    protected $fillable = ['manage_tag_id', 'manage_tag_name', 'manage_tag_icon','created_by',
        'updated_by', 'created_at', 'updated_at','is_active'];

    
    public function staff_created()
    {
        return $this->belongsTo('Modules\ManagerWork\Models\StaffTable','created_by','staff_id');
    }
    public function work()
    {
        return $this->hasMany('Modules\ManagerWork\Models\ManagerWorkTable','manage_tag_id','manage_tag_id');
    }
    
    protected function _getList($filters = [])
    {
         
        $query = $this->select('manage_tag_id', 'manage_tag_name', 'manage_tag_icon','created_by',
        'updated_by', 'created_at', 'updated_at','is_active')
            ->orderBy($this->primaryKey, 'desc');

        // filters tên + mô tả
         if (isset($filters["search"]) && $filters["search"] != "") {
            $search = $filters["search"];
            $query->where("manage_tag_name", "like", "%" . $search . "%");
        }
        // filters nguoi tao
         if (isset($filters["created_by"]) && $filters["created_by"] != "") {
            $query->where("created_by", $filters["created_by"]);
        }
        // filter ngày tạo
        if (isset($filters["created_at"]) && $filters["created_at"] != "") {
            $arr_filter = explode(" - ", $filters["created_at"]);
            $startTime = Carbon::createFromFormat("d/m/Y", $arr_filter[0])->format("Y-m-d 00:00:00");
            $endTime = Carbon::createFromFormat("d/m/Y", $arr_filter[1])->format("Y-m-d 00:00:00");
            $query->whereDate("created_at", ">=", $startTime);
            $query->whereDate("created_at", "<=", $endTime);
        }
        return $query;
    }

    public function getAll()
    {
        return $this->orderBy($this->primaryKey, 'desc')->get();
    }

    public function getName(){
        $oSelect= self::select("manage_tag_id","manage_tag_name")->get();
        return ($oSelect->pluck("manage_tag_name","manage_tag_id")->toArray());
    }

    public function add(array $data)
    {

        $oCustomerGroup = $this->create($data);
        return $oCustomerGroup->manage_tag_id;
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

    /*
    * check exist
    */
    public function checkExist($name = '', $id = '')
    {
        $select = $this->where('manage_tag_name', $name)
        ->where('manage_tag_id','<>', $id)
        ->first();
        return $select;
    }

}