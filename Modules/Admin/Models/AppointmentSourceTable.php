<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 17/1/2019
 * Time: 14:15
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AppointmentSourceTable extends Model
{
    use ListTableTrait;
    protected $table = 'appointment_source';
    protected $primaryKey = 'appointment_source_id';
    protected $fillable = [
        'appointment_source_id','appointment_source_name','description','created_at',
        'updated_at','created_by','updated_by','is_deleted'
    ];
    public function getOption()
    {
        return $this->select('appointment_source_id',
            'appointment_source_name','description')
            ->where('is_deleted',0)->get()->toArray();
    }
}