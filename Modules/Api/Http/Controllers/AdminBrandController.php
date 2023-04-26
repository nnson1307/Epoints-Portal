<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return $this->responseJson(CODE_SUCCESS, null, []);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'brand_id' => 'required|numberic',
            'full_name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'is_actived' => 'nullable',
            'is_change_pass' => 'nullable',
        ]);
        return $this->responseJson(CODE_SUCCESS, null, []);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        return $this->responseJson(CODE_SUCCESS, null, []);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        return $this->responseJson(CODE_SUCCESS, null, []);
    }
}
