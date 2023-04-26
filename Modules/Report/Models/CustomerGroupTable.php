<?php


namespace Modules\Report\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerGroupTable extends Model
{
    protected $table = 'customer_groups';
    protected $primaryKey = 'customer_group_id';

    public function getOption()
    {
        return $this->select('customer_group_id','group_name')
            ->where('is_deleted',0)->where('is_actived',1)
            ->get()->toArray();
    }
}