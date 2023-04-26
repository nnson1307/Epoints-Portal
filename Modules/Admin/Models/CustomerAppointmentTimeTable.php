<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/14/2018
 * Time: 2:16 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerAppointmentTimeTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_appointment_times';
    protected $primaryKey = 'customer_appointment_time_id';
    protected $fillable = [
        'customer_appointment_time_id', 'time', 'created_at', 'updated_at','created_by','updated_by'
    ];

    /**
     *
     */
    public function _getList()
    {
        $ds = $this->select('customer_appointment_time_id','time','created_at','updated_at')
        ->orderBy('time','asc');
        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add=$this->create($data);
        return $add->customer_appointment_time_id;
    }

    /**
     * @return mixed
     */
    public function getTimeOption()
    {
        return $this->select('customer_appointment_time_id','time')
            ->orderBy('time','asc')->get();
    }

    /**
     * @param $time
     * @param $id
     * @return mixed
     */
    public function testTime($time, $id)
    {
        return $this->where('time',$time)->where('customer_appointment_time_id','<>',$id)->first();
    }
    public function getItem($id)
    {
        $ds=$this->select('customer_appointment_time_id','time','created_at','updated_at')
            ->where('customer_appointment_time_id',$id)
            ->first();
        return $ds;
    }
}