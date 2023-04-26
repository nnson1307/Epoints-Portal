<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 4:05 PM
 */

namespace Modules\Admin\Repositories\Unit;

use Modules\Admin\Models\UnitTable;

class UnitRepository implements UnitRepositoryInterface
{
    protected $unit;
    protected $timestamps = true;

    public function __construct(UnitTable $units)
    {
        $this->unit = $units;
    }

    //Hàm lấy danh sách
    public function list(array $filters = [])
    {
        return $this->unit->getList($filters);
    }

    //function add
    public function add(array $data)
    {
        return $this->unit->add($data);
    }

    //function get item edit
    public function getItem($id)
    {
        return $this->unit->getItem($id);
    }

    //functic get name
    public function test($name)
    {
        return $this->unit->test($name);
    }

    //function edit
    public function edit(array $data, $id)
    {
        return $this->unit->edit($data, $id);
    }

    //function remove
    public function remove($id)
    {
        $this->unit->remove($id);
    }

    //function lay gia tri
    public function getUnitOption()
    {
        $array = array();
        foreach ($this->unit->getUnitOption() as $item) {
            $array[$item['unit_id']] = $item['name'];
        }
        return $array;
    }

    //function test name
    public function testName($name, $id)
    {
        return $this->unit->testName($name, $id);
    }

    /*
     * get unit
     */
    public function getAll()
    {
        $array = [];
        $data = $this->unit->getAll();
        foreach ($data as $item) {
            $array[$item['unit_id']] = $item['name'];
        }
        return $array;
    }

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id)
    {
        return $this->unit->getOptionEditProduct($id);
    }

    /*
     * get where not in
     */
    public function getUnitWhereNotIn($id)
    {
        return $this->unit->getUnitWhereNotIn($id);
    }
}