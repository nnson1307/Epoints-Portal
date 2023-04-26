<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 10/5/2018
 * Time: 11:24 AM
 */

namespace Modules\FNB\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FNBQrCodeScanTable extends Model
{
    use ListTableTrait;
    protected $table = 'fnb_qr_code_scan';
    protected $primaryKey = 'qr_code_scan_id';
    protected $fillable
        = [
            'qr_code_scan_id',
            'qr_code_id',
            'fnb_customer_id',
            'table_id',
            'imei',
            'device_name',
            'lat',
            'lng',
            'wifi_ip',
            'wifi_name',
            'request_ip',
            'token',
            'qr_code_template_id',
            'table_id',
            'created_day',
            'created_month',
            'created_year',
            'created_at'
        ];

    public function _getList(&$filter = []){
        $oSelect = $this
            ->select(
                $this->table.'.*',
                'fnb_table.name as table_name'
            )
            ->leftJoin('fnb_table','fnb_table.table_id',$this->table.'.table_id');

        if (isset($filter['search_created_at'])){
            $time = explode(' - ',$filter['search_created_at']);
            $start = Carbon::createFromFormat('d/m/Y',$time[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('d/m/Y',$time[1])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->whereBetween($this->table.'.created_at',[$start,$end]);
            unset($filter['search_created_at']);
        }

        if (isset($filter['search_table_id'])){
            $oSelect = $oSelect->where($this->table.'.table_id',$filter['search_table_id']);
            unset($filter['search_table_id']);
        }

        if (isset($filter['qr_code_template_id'])){
            $oSelect = $oSelect->where($this->table.'.qr_code_template_id',$filter['qr_code_template_id']);
            unset($filter['qr_code_template_id']);
        }

        return $oSelect->orderBy($this->table.'.qr_code_scan_id','DESC');
    }

    /**
     * Lấy tổng số lần scan
     * @param $idTemplate
     */
    public function getTotalScan($idTemplate){
        $oSelect = $this
            ->where($this->table.'.qr_code_template_id',$idTemplate)
            ->get();

        return $oSelect;

    }

}