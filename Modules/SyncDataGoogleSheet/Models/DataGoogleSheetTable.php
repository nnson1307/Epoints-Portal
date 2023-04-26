<?php

namespace Modules\SyncDataGoogleSheet\Models;

use Illuminate\Database\Eloquent\Model;

class DataGoogleSheetTable extends Model
{
    protected $table = 'cpo_customer_lead_online';
    protected $fillable = [
        'id',
        'representative',
        'date_data',
        'cpo_customer_lead_config_source_id',
        'full_name',
        'phone',
        'province',
        'district',
        'address',
        'customer_type',
        'pipeline_code',
        'journey_code',
        'customer_source',
        'code_investment',
        'source_order',
        'phone_attach',
        'birthday',
        'gender',
        'email',
        'email_attach',
        'business_clue',
        'fanpage',
        'fanpage_attach',
        'zalo',
        'tag_id',
        'tax_code',
        'number_row',
        'id_google_sheet',
        'is_success',
        'is_error',
        'note',
        'backage_product_code',
        'backage_product'
    ];

    /**
     * insert Mutiple record data googleSheet Vset
     * @param [array] $data
     * @return void
     */

    public function insertMutipleDataGoogleSheet(array $data = [])
    {
        return $this->insert($data);
    }
}
