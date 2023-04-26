<?php


namespace Modules\BookingWeb\Models;


use Illuminate\Database\Eloquent\Model;

class ProvinceTable extends Model
{
    protected $table = 'province';
    protected $primaryKey = 'id';
    protected $fillable = [
        'provinceid','name','type','location_id',
    ];

    public function getProvinceOption()
    {
        $get_all= $this->select('provinceid', 'name')->get();

        $array = array();
        foreach ($get_all as $item)
        {
            $array[$item['provinceid']] = $item['name'];
        }

        return $array;
    }
}