<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 16/1/2019
 * Time: 17:32
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerAppointmentDetailTable extends Model
{
    use ListTableTrait;
    protected $table='customer_appointment_details';
    protected $primaryKey='customer_appointment_detail_id';
    protected $fillable=[
      'customer_appointment_detail_id','customer_appointment_id','service_id','staff_id','room_id','created_at','updated_at',
      'created_by','updated_by','is_deleted','customer_order'
    ];
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add=$this->create($data);
        return $add->customer_appointment_detail_id;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('customer_appointment_detail_id',$id)->update($data);
    }
    public function remove($id)
    {
        return $this->where('customer_appointment_detail_id',$id)->delete();
    }
    public function groupItem($customer_appointment_id)
    {
        $ds=$this->select('customer_order','room_id','staff_id')
            ->where('customer_appointment_id',$customer_appointment_id)
            ->groupBy('customer_order')
            ->get();
        return $ds;
    }
    public function getItem($customer_appointment_id)
    {
        $ds=$this->leftJoin('services','services.service_id','=','customer_appointment_details.service_id')
            ->select('customer_appointment_details.customer_appointment_detail_id',
                'customer_appointment_details.customer_appointment_id',
                'customer_appointment_details.service_id',
                'customer_appointment_details.staff_id',
                'customer_appointment_details.room_id',
                'customer_appointment_details.customer_order',
                'services.service_name')
            ->where('customer_appointment_details.customer_appointment_id',$customer_appointment_id)
           ->get();
        return $ds;
    }
    public function groupItemDetail($customer_appointment_id)
    {
        $ds=$this->leftJoin('services','services.service_id','=','customer_appointment_details.service_id')
            ->select('customer_appointment_details.customer_appointment_id',
                'customer_appointment_details.service_id',
                'customer_appointment_details.staff_id',
                'customer_appointment_details.room_id',
                'customer_appointment_details.customer_order',
                'services.service_name',
                DB::raw('customer_appointment_details.service_id as quantity'))
            ->where('customer_appointment_details.customer_appointment_id',$customer_appointment_id)
            ->groupBy('customer_appointment_details.service_id')
            ->get();
        return $ds;
    }
}