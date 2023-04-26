<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class RoomTable extends Model
{
    protected $table="rooms";
    protected $primaryKey="room_id";
    protected $fillable=[
        'room_id','name','seat','seat_using','created_by','updated_by',
        'created_at','updated_at','is_actived','is_deleted','slug'
    ];

    /**
     * @return mixed
     */
    public function getRoomOption()
    {
        return $this->select('room_id','name','seat')->where('is_deleted',0)->get()->toArray();
    }
}