<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 11:56 AM
 */

namespace Modules\Delivery\Models;
use Illuminate\Database\Eloquent\Model;


class ProvinceTable extends Model
{
    protected $table="province";


    public function getOptionProvince()
    {
        return $this->select('provinceid','name')->get();
    }

}