<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 3:27 PM
 */

namespace Modules\Booking\Http\Controllers;


use Modules\Booking\Http\Requests\Upload\UploadAvatarRequest;
use Modules\Booking\Http\Requests\Upload\UploadImageDropRequest;
use Modules\Booking\Http\Requests\Upload\UploadImagePickUpRequest;
use Modules\Booking\Repositories\Upload\UploadRepoInterface;
use MyCore\Http\Response\ResponseFormatTrait;

class UploadController extends Controller
{
    use ResponseFormatTrait;
    protected $upload;

    public function __construct(
        UploadRepoInterface $upload
    ) {
        $this->upload = $upload;
    }

    /**
     * Upload avatar
     *
     * @param UploadAvatarRequest $request
     * @return mixed
     */
    public function uploadAvatarAction(UploadAvatarRequest $request)
    {
        $data = $this->upload->uploadAvatar($request->all());

        return [
            'ErrorCode' => 0,
            'ErrorDescription' => CODE_SUCCESS,
            'Data' => $data
        ];
    }

    /**
     * Upload hình ảnh nhận hàng của nv giao hàng
     *
     * @param UploadImagePickUpRequest $request
     * @return array
     */
    public function uploadImagePickUpAction(UploadImagePickUpRequest $request)
    {
        $data = $this->upload->uploadPickUp($request->all());

        return [
            'ErrorCode' => 0,
            'ErrorDescription' => CODE_SUCCESS,
            'Data' => $data
        ];
    }

    /**
     * Upload hình ảnh giao hàng của nv giao hàng
     *
     * @param UploadImageDropRequest $request
     * @return array
     */
    public function uploadImageDropAction(UploadImageDropRequest $request)
    {
        $data = $this->upload->uploadDrop($request->all());

        return [
            'ErrorCode' => 0,
            'ErrorDescription' => CODE_SUCCESS,
            'Data' => $data
        ];
    }
}