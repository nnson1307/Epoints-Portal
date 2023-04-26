<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/25/2018
 * Time: 4:05 PM
 */

namespace Modules\Admin\Repositories\Unit;


interface UnitRepositoryInterface
{
    public function list(array $filters = []);

    /**
     * Add unit
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);

    /**
     * Delete unit
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit unit
     *
     * @param array $data ,$id
     * @return number
     */
    public function edit(array $data, $id);

    public function getItem($id);

//    public function test($name);
    public function getUnitOption();

    public function testName($name, $id);

    /**
     * get all unit
     */
    public function getAll();

    /*
     * get option edit product
     */
    public function getOptionEditProduct($id);
    /*
     * get where not in
     */
    public function getUnitWhereNotIn($id);
}