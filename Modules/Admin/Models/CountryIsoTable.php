<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CountryIsoTable extends Model
{
    use ListTableTrait;
    protected $table = "country_iso";
    protected $primaryKey = "country_iso_id";

    protected $fillable = [
        'country_iso_id','country_iso', 'country_iso3', 'calling_code','country_name'
    ];

    public function getAll(){
        $oSelect = $this->get();
        return $oSelect;
    }

    public function getDetailByIso($country_iso){
        $oSelect = $this->where('country_iso',$country_iso)->first();
        return $oSelect;
    }
}