<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/26/2018
 * Time: 10:49 AM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class UnitConversionTable extends Model
{
    use ListTableTrait;
    protected $table = "unit_conversions";
    protected $primaryKey = "unit_conversion_id";
    protected $fillable = [
        'unit_conversion_id', 'unit_id', 'unit_standard', 'conversion_rate', 'created_at', 'updated_at'
    ];

    //function lay danh sach
    public function _getList()
    {
        $ds = $this->leftJoin('units', 'units.unit_id', '=', 'unit_conversions.unit_id')
            ->leftJoin('units as u', 'u.unit_id', '=', 'unit_conversions.unit_standard')
            ->select('unit_conversions.*', 'unit_conversions.unit_conversion_id', 'unit_conversions.unit_id as unit_id',
                'unit_conversions.unit_standard as unit_standard'
                ,'unit_conversions.conversion_rate',
                'unit_conversions.created_at', 'unit_conversions.updated_at',
                'unit_conversions.is_deleted', 'units.name as name',
                'u.name as standard_name')
            ->where('unit_conversions.is_deleted', 0);

        return $ds;
    }

    //function add unit conversion
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    //function remove
    public function remove($id)
    {
        $this->where($this->primaryKey, $id)->update(['is_deleted' => 1]);
    }

    public function getItem($id)
    {
        $ds = $this->leftJoin('units', 'units.unit_id', '=', 'unit_conversions.unit_id')
            ->select('unit_conversions.*', 'unit_conversions.unit_conversion_id as id', 'unit_conversions.unit_id as unit_id'
                , 'unit_conversions.unit_standard as unit_standard'
                , 'unit_conversions.conversion_rate', 'unit_conversions.created_at', 'unit_conversions.updated_at',
                'unit_conversions.is_deleted', 'units.name as name')
            ->where($this->primaryKey, $id)->first();
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }
}