<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 2:11 PM
 */

namespace Modules\Admin\Repositories\Room;

use Modules\Admin\Models\RoomTable;
class RoomRepository implements RoomRepositoryInterface
{
    protected $room;
    protected $timestamps=true;
    public function __construct(RoomTable $rooms)
    {
        $this->room=$rooms;
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function list(array $filters=[])
    {
        return $this->room->getList($filters);
    }


    /**
     * @param array $data
     * @return mixed|number
     */
    public function add(array $data)
    {
        return $this->room->add($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->room->getItem($id);
    }
    //functic get name
//    public function test($name)
//    {
//        return $this->unit->test($name);
//    }


    /**
     * @param array $data
     * @param $id
     * @return mixed|number
     */
    public function edit(array $data, $id)
    {
        return $this->room->edit($data,$id);
    }

    /**
     * @param number $id
     */
    public function remove($id)
    {
        $this->room->remove($id);
    }

    /**
     * @return array
     */
    public function getRoomOption()
    {
        $array=array();
        foreach ($this->room->getRoomOption() as $item)
        {
            $array[$item['room_id']]=$item['name'];
        }
        return $array;
    }

    /**
     * @param $name
     * @param $id
     * @return mixed
     */
    public function testName($name, $id)
    {
        return $this->room->testName($name,$id);
    }
}