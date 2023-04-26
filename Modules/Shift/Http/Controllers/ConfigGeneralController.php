<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/10/2022
 * Time: 16:00
 */

namespace Modules\Shift\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Shift\Repositories\ConfigGeneral\ConfigGeneralRepoInterface;

class ConfigGeneralController extends Controller
{
    protected $configGeneral;

    public function __construct(
        ConfigGeneralRepoInterface $configGeneral
    ) {
        $this->configGeneral = $configGeneral;
    }

    /**
     * View cấu hình chung
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data = $this->configGeneral->getDataGeneral();

        return view('shift::config-general.index', $data);
    }

    /**
     * Chỉnh sửa cấu hình chung
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $data = $this->configGeneral->update($request->all());

        return response()->json($data);
    }
}