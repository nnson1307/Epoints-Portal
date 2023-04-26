<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 9/27/2018
 * Time: 2:10 PM
 */

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Repositories\Room\RoomRepositoryInterface;

class RoomController extends Controller
{
    protected $room;

    public function __construct(RoomRepositoryInterface $rooms)
    {
        $this->room = $rooms;
    }

    //View index
    public function indexAction()
    {
        $un = $this->room->list();
        return view('admin::room.index', [
            'LIST' => $un,
            'FILTER' => $this->filters()
        ]);
    }

    //Filter
    protected function filters()
    {
        return [
            'is_actived' => [
                'data' => [
                    '' => __('Chọn trạng thái'),
                    1 => __('Hoạt động'),
                    0 => __('Tạm ngưng')
                ]
            ]
        ];
    }

    //function view list
    public function listAction(Request $request)
    {
        $filter = $request->only(['page', 'display', 'search_type', 'search_keyword', 'is_actived']);
        $roomList = $this->room->list($filter);
        return view('admin::room.list', ['LIST' => $roomList, 'page' => $filter['page']]);
    }

    //function add
    public function submitAddAction(Request $request)
    {
        $name = $request->name;
        $testName = $this->room->testName(str_slug($name), '0');

        if ($testName == null) {
            $data = [
                'name'=>$request->name,
                'slug'=>str_slug($request->name),
                'seat'=>$request->seat,
                'created_by'=>Auth::id()
            ];
            $this->room->add($data);
            return response()->json(['status' => '', 'close' => $request->close]);
        } else {
            return response()->json(['status' => 'Tên phòng đã tồn tại']);
        }


    }

    //function change status
    public function changeStatusAction(Request $request)
    {
        $change = $request->all();
        $data['is_actived'] = ($change['action'] == 'unPublish') ? 1 : 0;
        $this->room->edit($data, $change['id']);
        return response()->json([
            'status' => 0
        ]);
    }

    //function remove
    public function removeAction($id)
    {
        $this->room->remove($id);
        return response()->json([
            'error' => 0,
            'message' => 'Remove success'
        ]);
    }

    //function edit
    public function editAction(Request $request)
    {
        $id = $request->id;
        $item = $this->room->getItem($id);
        $data = [
            'room_id' => $item->room_id,
            'name' => $item->name,
            'seat' => $item->seat,
//            'seat_using'=>$item->seat_using,
            'is_actived' => $item->is_actived
        ];
        return response()->json($data);
    }

    //function submit edit
    public function submitEditAction(Request $request)
    {
        $name = $request->name;
        $id = $request->id;
        $testName = $this->room->testName(str_slug($name), $id);
        if ($testName == null) {
            $data = [
                'name' => $request->name,
                'slug'=>str_slug($request->name),
                'seat' => $request->seat,
                'is_actived' => $request->is_actived
            ];
            $data['updated_by'] = Auth::id();
            $this->room->edit($data, $id);
            return response()->json(['status' => '']);
        } else {
            return response()->json(['status' => 'Tên phòng đã tồn tại']);
        }

    }
}