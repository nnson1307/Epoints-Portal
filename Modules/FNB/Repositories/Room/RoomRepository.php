<?php


namespace Modules\FNB\Repositories\Room;


use Modules\FNB\Models\RoomTable;

class RoomRepository implements RoomRepositoryInterface
{
    private $room;

    public function __contruct(RoomTable $room){
        $this->room = $room;
    }

    public function getRoomOption()
    {
        $room = app()->get(RoomTable::class);
        return $room->getRoomOption();
    }
}