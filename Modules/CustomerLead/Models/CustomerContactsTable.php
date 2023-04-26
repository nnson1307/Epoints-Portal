<?php


namespace Modules\CustomerLead\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class CustomerContactsTable extends Model
{
    use ListTableTrait;
    protected $table = 'customer_contacts';
    protected $primaryKey = 'customer_contact_id';
    protected $fillable = [
        'customer_contact_id', 'customer_id', 'customer_code', 'province_id', 'district_id',
        'postcode', 'address_default', 'contact_name', 'full_address', 'contact_phone',
        'contact_email', 'created_at', 'updated_at', 'customer_contact_code','is_deleted'
    ];

    public function getListContactByCustomerCode($customerCode)
    {
        return $this
            ->select(
                "customer_contact_id",
                "customer_contact_code",
                "postcode",
                "full_address",
                "address_default",
                "contact_name",
                "contact_phone",
                "contact_email"
            )
            ->where("customer_code", $customerCode)
            ->where("is_deleted", 0)
            ->orderBy("customer_contact_id", "asc")
            ->get();
    }

    public function add (array $data)
    {
        $add = $this->create($data);
        return $add->customer_contact_id;
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
    public function edit (array $data, $idContact)
    {
        return $this->where('customer_contact_id', $idContact)
            ->update($data);
    }
}