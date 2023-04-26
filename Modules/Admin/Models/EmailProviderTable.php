<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 18/2/2019
 * Time: 10:23
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class EmailProviderTable extends Model
{
    use ListTableTrait;
    protected $table = 'email_provider';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'type', 'name_email', 'email', 'password', 'is_actived', 'email_template_id',
        'created_at', 'updated_at', 'created_by', 'updated_by'
    ];

    public function getItem($id)
    {
        return $this->select('id','type', 'name_email', 'email', 'password', 'is_actived','email_template_id')
            ->where('id', $id)->first();
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }
}