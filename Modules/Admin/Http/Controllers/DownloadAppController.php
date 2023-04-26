<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 13/05/2022
 * Time: 15:48
 */

namespace Modules\Admin\Http\Controllers;


class DownloadAppController extends Controller
{
    public function index()
    {
        return view('admin::download-app.index');
    }
}