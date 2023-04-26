<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 12/29/2020
 * Time: 4:07 PM
 */

namespace Modules\Referral\Repositories\Upload;


interface UploadRepoInterface
{
    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed
     */
    public function uploadImage($input);
}