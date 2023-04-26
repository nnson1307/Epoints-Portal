<?php
/**
 * Created by PhpStorm.
 * User: SonVeratti
 * Date: 3/27/2018
 * Time: 11:56 AM
 */

namespace Modules\ZNS\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;


class ProvinceTable extends Model
{
    protected $table="province";


    public function getOption()
    {
        $oSelect = $this->select('provinceid','name')->get();
        return ($oSelect->pluck("name", "provinceid")->toArray());
    }

}