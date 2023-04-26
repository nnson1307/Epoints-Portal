<?php


namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ZoneTable extends Model
{
    use ListTableTrait;
    protected $table = "zone";
    protected $primaryKey = "zone_id";

    protected $fillable = [
        'zone_id', 'country_code', 'zone_name'
    ];

    public function getAll(){
        $oSelect = $this->get();
        return $oSelect;
    }
}