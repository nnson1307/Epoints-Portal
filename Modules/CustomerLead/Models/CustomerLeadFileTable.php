<?php

namespace Modules\CustomerLead\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerLeadFileTable extends Model
{
    use ListTableTrait;
    protected $table = "cpo_customer_lead_files";
    protected $primaryKey = "customer_lead_file_id";

    protected $fillable = [
        'customer_lead_file_id',
        'customer_lead_id',
        'file_name',
        'path',
        'content',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    public function getListFileCustomerLead($customer_lead_id){
        $oSelect = $this
            ->select(
                $this->table.'.customer_lead_file_id',
                $this->table.'.customer_lead_id',
                $this->table.'.file_name',
                $this->table.'.path',
                $this->table.'.content',
                $this->table.'.created_at',
                $this->table.'.updated_at',
                'staffs.full_name as created_by',
                'updated.full_name as updated_by'
            )
            ->leftJoin('staffs','staffs.staff_id',$this->table.'.created_by')
            ->leftJoin('staffs as updated','updated.staff_id',$this->table.'.updated_by')
            ->where($this->table.'.customer_lead_id',$customer_lead_id)
            ->orderBy($this->table.'.created_at','DESC');

        return $oSelect->get();
    }

    /**
     * Tạo File
     * @param $data
     */
    public function createFile($data){
        return $this->insertGetId($data);
    }

    /**
     * Update File
     * @param $data
     */
    public function updateFile($fileId, $data){
        return $this->where('customer_lead_file_id', $fileId)->update($data);
    }

}