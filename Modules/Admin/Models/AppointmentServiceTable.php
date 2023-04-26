<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/12/2018
 * Time: 4:16 PM
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class AppointmentServiceTable extends Model
{
    use ListTableTrait;
    protected $table = 'appointment_services';
    protected $primaryKey='appointment_service_id';
    protected $fillable = [
        'appointment_service_id', 'customer_appointment_id', 'service_id', 'quantity','created_at','updated_at'
    ];

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add=$this->create($data);
        return $add->appointment_service_id;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('appointment_service_id',$id)->update($data);
    }
    public function detailCustomer($id)
    {
        $ds=$this->leftJoin('services','services.service_id','=','appointment_services.service_id')
            ->select('services.service_name',
                'appointment_services.quantity','appointment_services.customer_appointment_id')
            ->where('appointment_services.customer_appointment_id',$id)
            ->where('appointment_services.is_deleted',0)->get();
        return $ds;
    }
}
//