<?php
/**
 * ServiceTimeTable
 * Le Dang Sinh
 * Date: 3/28/2018
 */

namespace Modules\Services\Models;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ServiceTimeTable extends Model
{
    use ListTableTrait;
    protected $table = 'service_time';
    protected $primaryKey="service_time_id";

    protected $fillable = [
        'service_time_id', 'time', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    protected function _getList()
    {
        $oSelect  = $this->select('service_time_id', 'time', 'is_active', 'is_delete', 'created_at', 'updated_at', 'created_by', 'updated_by')->where('is_delete','=',0);
        return $oSelect;
    }

    public function getItem($id){
        return  $this->where($this->primaryKey,$id)->first();
    }

    public function getOptionServiceTime()
    {
        return $this->select('service_time_id','time')->get();
    }
}