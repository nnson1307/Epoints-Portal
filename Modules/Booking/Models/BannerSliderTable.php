<?php


namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class BannerSliderTable extends Model
{
    protected $table = "banner_slider";
    protected $primaryKey = "id";

    //function fillable
    protected $fillable = [
        'id',
        'name',
        'type',
        'link',
        'position',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    public function getAllSlider(){
        $oSelect = $this->get()->sortBy('position');
        return $oSelect;
    }

}