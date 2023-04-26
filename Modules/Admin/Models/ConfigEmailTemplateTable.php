<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 1/4/2019
 * Time: 12:05
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class ConfigEmailTemplateTable extends Model
{
    use ListTableTrait;
    protected $table = 'config_email_template';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'logo', 'website', 'background_header', 'color_header', 'background_body', 'color_body',
        'background_footer', 'color_footer', 'image', 'updated_at', 'updated_by'
    ];

    public function _getList()
    {
        $ds = $this->select('id', 'logo', 'website', 'background_header', 'color_header',
            'background_body', 'color_body', 'background_footer', 'color_footer', 'image');
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

    public function getItem($id)
    {
        $ds = $this->select('id', 'logo', 'website', 'background_header', 'color_header',
            'background_body', 'color_body', 'background_footer', 'color_footer', 'image')
            ->where('id', $id)->first();
        return $ds;
    }
}