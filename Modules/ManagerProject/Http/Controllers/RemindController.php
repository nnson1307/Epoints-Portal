<?php

/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:39 PM
 */

namespace Modules\ManagerProject\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\ManagerProject\Http\Requests\Remind\RemindProjectRequest;
use Modules\ManagerProject\Repositories\Remind\RemindRepositoryInterface;


class RemindController extends Controller
{
    public function showPopupRemindPopup(Request $request){
        $rRemind = app()->get(RemindRepositoryInterface::class);
        $param = $request->all();
        $data = $rRemind->showPopupRemindPopup($param);
        return response()->json($data);
    }

    public function addRemindWork(RemindProjectRequest $request){
        $rRemind = app()->get(RemindRepositoryInterface::class);
        $param = $request->all();
        $data = $rRemind->addRemindWork($param);
        return response()->json($data);
    }
}
