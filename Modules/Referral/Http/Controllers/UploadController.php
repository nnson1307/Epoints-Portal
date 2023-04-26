<?php


namespace Modules\Referral\Http\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Referral\Repositories\Upload\UploadRepoInterface;

class UploadController extends Controller
{
    protected $upload;

    public function __construct(UploadRepoInterface $upload){
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