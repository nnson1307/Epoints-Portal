<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 7/24/2020
 * Time: 10:52 AM
 */

namespace Modules\Shift\Http\Controllers;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Shift\Http\Requests\Shift\StoreRequest;
use Modules\Shift\Http\Requests\Shift\UpdateRequest;
use Modules\Shift\Repositories\ConfigNoti\ConfigNotiRepositoryInterface;
use Modules\Shift\Repositories\Shift\ShiftRepoInterface;

class ConfigNotiController extends Controller
{
    protected $rConfigNoti;

    public function __construct(ConfigNotiRepositoryInterface $rConfigNoti)
    {
        $this->rConfigNoti = $rConfigNoti;
    }

    /**
     * Trang cấu hình noti chấm công
     */
    public function index(){

        try {
            $listNoti = $this->rConfigNoti->getListNoti();

            return view('shift::config-shift.index', [
                'listNoti' => $listNoti
            ]);
        }catch (\Exception $e){
            dd($e->getMessage());
        }
    }

    /**
     * Trang chỉnh sửa
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(){
        $listNoti = $this->rConfigNoti->getListNoti();

        return view('shift::config-shift.edit', [
            'listNoti' => $listNoti
        ]);
    }

    /**
     * Hiển thị popup
     * @param Request $request
     */
    public function showPopup(Request $request){
        $param = $request->all();
        $data = $this->rConfigNoti->showPopup($param);
        return response()->json($data);
    }

    /**
     * Cập nhật nội dung
     * @param Request $request
     */
    public function updateMessage(Request $request){
        $param = $request->all();
        $data = $this->rConfigNoti->updateMessage($param);
        return response()->json($data);
    }

    /**
     * Cập nhật cấu hình
     * @param Request $request
     */
    public function updateNoti(Request $request){
        $param = $request->all();
        $data = $this->rConfigNoti->updateNoti($param);
        return response()->json($data);
    }
}