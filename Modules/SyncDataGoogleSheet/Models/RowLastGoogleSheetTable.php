<?php

namespace Modules\SyncDataGoogleSheet\Models;

use Illuminate\Database\Eloquent\Model;

class RowLastGoogleSheetTable extends Model
{
    protected $table = 'cpo_customer_lead_row_gsheet';
    protected $fillable = [
        'id',
        'number_row_last',
        'id_google_sheet'
    ];
    // lấy số  hàng mặc định //
    const ROW_DEFAULT = 0;
    // set id mặc định không đổi //
    const ID_DEFAULT = 1;

    /**
     * Lấy số hàng cuối cùng insert
     * @param [string] $idGoogleSheet
     * @return mixed
     */

    public function getRowLastInsertByIdGoogleSheet(string $idGoogleSheet )
    {
            return $this->where('id_google_sheet', $idGoogleSheet)->first();
    }

    /**
     * Lấy số hàng mặc định 
     * @return int
     */

    public function getRowDefault()
    {
        return $this::ROW_DEFAULT;
    }

    /**
     * cập nhật lại hàng cuối cùng insert 
     * @param [array] $rowlast
     * @return void
     */
    public function updateRowlast(array $rowlast = [])
    {   
        return $this->updateOrCreate(['id_google_sheet' => $rowlast['id_google_sheet']], $rowlast);
    }
}
