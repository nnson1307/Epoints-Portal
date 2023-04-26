<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 12/29/2020
 * Time: 4:04 PM
 */

namespace Modules\Admin\Http\Controllers;


use Illuminate\Http\Request;
use Modules\Admin\Repositories\Upload\UploadRepoInterface;

class UploadController extends Controller
{
    protected $upload;

    public function __construct(
        UploadRepoInterface $upload
    ) {
        $this->upload = $upload;
    }

    /**
     * Upload image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImageAction(Request $request)
    {
        $data = $this->upload->uploadImage($request->all());

        return response()->json($data);
    }
}