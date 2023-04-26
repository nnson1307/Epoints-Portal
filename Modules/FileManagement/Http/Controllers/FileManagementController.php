<?php

namespace Modules\FileManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\ManagerWork\Http\Api\ManageFileApi;

class FileManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();

        $mApiStaffUser = app()->get(\Modules\User\Http\Controllers\Api\StaffApi::class);

        $staffLogin = $mApiStaffUser->refeshTokenStaff(['refresh_token' => $user['remember_token']]);


        if (isset($staffLogin) && $staffLogin != null) {
            $apiManageFile = app()->get(ManageFileApi::class);

            $apiManageFile->loginManageFIle($staffLogin['access_token']);
        }

        $brand_code = session()->get('brand_code');

        return view('filemanagement::index',['access_token' => $staffLogin['access_token'],'brand_code' => $brand_code]);
    }

}
