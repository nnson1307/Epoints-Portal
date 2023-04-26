<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/26/2018
 * Time: 10:50 AM
 */

namespace Modules\Admin\Repositories\UnitConversion;


use Modules\Admin\Models\UnitConversionTable;

class UnitConversionRepository implements UnitConversionRepositoryInterface
{
    protected $unitConversion;
    protected $timestamps=true;
    public function __construct(UnitConversionTable $unit_conversions)
    {
        $this->unitConversion=$unit_conversions;
    }
    //Hàm lấy danh sách
    public function list(array $filters=[])
    {
        return $this->unitConversion->getList($filters);
    }
    //function add
    public function add(array $data)
    {
        return $this->unitConversion->add($data);
    }
    //function get item edit
    public function getItem($id)
    {
        return $this->unitConversion->getItem($id);
    }
    //function edit
    public function edit(array $data,$id)
    {
        return $this->unitConversion->edit($data,$id);
    }
    //function remove
    public function remove($id)
    {
        $this->unitConversion->remove($id);
    }
    public function layDS()
    {
        return $this->unitConversion->layDS();
    }
}