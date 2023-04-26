<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ZNS\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class TemplateButtonTable extends Model
{
    use ListTableTrait;

    protected $table = 'zns_template_button';
    protected $primaryKey = 'zns_template_button_id';

    protected $fillable = [
        'zns_template_button_id',
        'zns_template_id',
        'title',
        'icon',
        'content',
        'phone_number',
        'link',
        'created_at',
        'updated_at',
        'type_button'
    ];

    public function add(array $data)
    {
        $oData = $this->create($data);
        return $oData->zns_template_button_id;
    }

    public function remove($id)
    {
        return $this->where($this->primaryKey, $id)->delete();
    }

    public function removeByZnsTemplateId($zns_template_id)
    {
        return $this->where("{$this->table}.zns_template_id", $zns_template_id)->delete();
    }

    public function edit(array $data, $id)
    {
        return $this->where($this->primaryKey, $id)->update($data);
    }

    public function getItem($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function getItemByZnsTemplateId($zns_template_id)
    {
        return $this->where("{$this->table}.zns_template_id", $zns_template_id)->get()->toArray();
    }
}