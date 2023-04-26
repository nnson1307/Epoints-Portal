<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 30/1/2019
 * Time: 09:44
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class EmailCampaignDetailTable extends Model
{
    use ListTableTrait;
    protected $table = 'campaign_detail_detail';
    protected $primaryKey = 'campaign_detail_id';
    protected $fillable = [
        'campaign_detail_id', 'campaign_id', 'customer_email', 'customer_name', 'status', 'created_at',
        'updated_at', 'gender', 'birthday', 'date_send'
    ];
    public function add(array $data)
    {
        $add=$this->create($data);
        return $add->campaign_detail_id;
    }
}