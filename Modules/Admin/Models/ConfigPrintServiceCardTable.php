<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 31/3/2019
 * Time: 14:53
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ConfigPrintServiceCardTable extends Model
{
    use ListTableTrait;
    protected $table = 'config_print_service_card';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'logo', 'name_spa', 'background', 'background_image', 'qr_code',
        'created_at', 'updated_at', 'created_by', 'updated_by', 'card_type', 'color'
    ];

    public function _getList()
    {
        $ds = $this->select('id', 'logo', 'name_spa', 'background',
            'background_image', 'qr_code', 'card_type', 'color');
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function getItem($id)
    {
        $ds = $this->select('id', 'card_type', 'logo', 'name_spa', 'background',
            'color', 'background_image', 'qr_code')
            ->where('id', $id)->first();
        return $ds;
    }

}