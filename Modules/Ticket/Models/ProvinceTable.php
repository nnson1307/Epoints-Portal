<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 11:56 AM
 */

namespace Modules\Ticket\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class ProvinceTable extends Model
{
    protected $table="province";


    public function getOptionProvince()
    {
        return $this->select('provinceid','name')->get();
    }

}