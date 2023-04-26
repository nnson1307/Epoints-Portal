<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 27/3/2019
 * Time: 10:29
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class EmailTemplateTable extends Model
{
    use ListTableTrait;
    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'image'
    ];

    public function getAll()
    {
        $ds = $this->select('id', 'image')->get();
        return $ds;
    }
}