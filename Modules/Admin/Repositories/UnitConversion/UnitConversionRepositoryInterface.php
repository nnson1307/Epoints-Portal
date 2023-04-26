<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/26/2018
 * Time: 10:50 AM
 */

namespace Modules\Admin\Repositories\UnitConversion;


interface UnitConversionRepositoryInterface
{
    public function list(array $filters=[]);
    /**
     * Add unit_conversion
     *
     * @param array $data
     * @return number
     */
    public function add(array $data);
    /**

     * Delete unit_conversion
     *
     * @param number $id
     */
    public function remove($id);

    /**
     * Edit unit_conversion
     *
     * @param array $data,$id
     * @return number
     */
    public function edit(array $data,$id);
    public function getItem($id);
    public function layDS();
//    public function getUnitOption() ;

}