<?php
/**
 * Created by PhpStorm.
 * User: Huy
 * Date: 10/11/2018
 * Time: 4:08 PM
 */

namespace Modules\Admin\Repositories\UploadImage;


interface UploadImageRepositoryInterface
{
    public function uploadSingleFile($file);
    public function deleteTempImage($file);
    public function moveFromTemp($files);
}