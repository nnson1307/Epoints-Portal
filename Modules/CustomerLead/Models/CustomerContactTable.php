<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/31/2020
 * Time: 4:32 PM
 */

namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerContactTable extends Model
{
    protected $table = "cpo_customer_contact";
    protected $primaryKey = "customer_contact_id";
    protected $fillable = [
        "customer_contact_id",
        "customer_lead_code",
        "full_name",
        "phone",
        "email",
        "staff_title_id",
        "address",
        "created_at",
        "updated_at"
    ];

    /**
     * Lấy thông tin liên hệ của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function getContact($leadCode)
    {
        return $this
            ->select(  
                "customer_contact_id",
                "customer_lead_code",
                "full_name",
                "phone",
                "email",
                "address",
                "staff_title.staff_title_id",
                "staff_title.staff_title_name",
                "$this->table.created_at"
            )
            ->leftJoin('staff_title', 'staff_title.staff_title_id', '=', "$this->table.staff_title_id")
            ->where("customer_lead_code", $leadCode)
            ->get();
    }
    public function getArrayContact($leadCode)
    {
        return $this
            ->select(
                "full_name",
                "phone",
                "email",
                "address"
            )
            ->where("customer_lead_code", $leadCode)
            ->get()->toArray();
    }

    /**
     * Xóa thông tin liên hệ của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function removeContact($leadCode)
    {
        return $this->where("customer_lead_code", $leadCode)->delete();
    }

    /**
     * Thêm liên hệ của KH tiềm năng
     *
     * @param $leadCode
     * @return mixed
     */
    public function addContact($data){
        return $this->create($data);
    }

    public function getDetailContact ($idCusContact)
    {
        return $this->select(
            'customer_contacts.*',
            'province.name as province_name',
            'district.name as district_name'
        )
            ->leftJoin('province', 'province.provinceid', '=', 'customer_contacts.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customer_contacts.district_id')
            ->where('customer_contact_id', $idCusContact)
            ->where('is_deleted', 0)->first();
    }
}