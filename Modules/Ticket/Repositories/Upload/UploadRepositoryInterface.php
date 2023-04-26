<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 12/29/2020
 * Time: 4:07 PM
 */

namespace Modules\Ticket\Repositories\Upload;


interface UploadRepositoryInterface
{
    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed
     */
    public function uploadImage($input);
}