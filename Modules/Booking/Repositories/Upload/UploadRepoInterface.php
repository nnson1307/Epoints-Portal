<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 3:29 PM
 */

namespace Modules\Booking\Repositories\Upload;


interface UploadRepoInterface
{
    /**
     * Upload avatar
     *
     * @param $input
     * @return mixed
     */
    public function uploadAvatar($input);

    /**
     * Upload hình ảnh nhận hàng của nv giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function uploadPickUp($input);

    /**
     * Upload hình ảnh giao hàng của nv giao hàng
     *
     * @param $input
     * @return mixed
     */
    public function uploadDrop($input);
}