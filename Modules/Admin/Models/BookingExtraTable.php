<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 22/3/2019
 * Time: 17:04
 */

namespace Modules\Admin\Models;


use Illuminate\Database\Eloquent\Model;
use MyCore\Models\Traits\ListTableTrait;

class BookingExtraTable extends Model
{
    use ListTableTrait;
    protected $table = 'booking_extra';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'name', 'value', 'image', 'updated_by', 'created_at', 'updated_at'
    ];

    protected function _getList()
    {
        $ds = $this->select('id', 'name', 'value', 'image');
        return $ds;
    }

    public function edit(array $data, $id)
    {
        return $this->where('id', $id)->update($data);
    }

}