<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 12/29/2020
 * Time: 4:07 PM
 */

namespace Modules\Admin\Repositories\Upload;


use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UploadRepo implements UploadRepoInterface
{
    protected $s3Disk;

    public function __construct(
        S3UploadsRedirect $s3
    )
    {
        $this->s3Disk = $s3;
    }

    /**
     * Upload hình ảnh
     *
     * @param $input
     * @return mixed|void
     */
    public function uploadImage($input)
    {
        if ($input['file'] != null) {
            $fileName = $this->uploadImageS3($input['file'], $input['link'], $input['is_base_64'] ?? false);

            return [
                'error' => 0,
                'file' => $fileName['path'],
                'type' => $fileName['type']
            ];
        }
    }

    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @param $isBase64
     * @return array
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function uploadImageS3($file, $link, $isBase64 = false)
    {
        $time = Carbon::now();
        $idTenant = session()->get('idTenant');

        $to = $idTenant . '/' . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        if ($isBase64) {
            $image_parts = explode(";base64,", $file);
            $image_type_aux = explode("image/", $image_parts[0]);

            $fileType = $image_type_aux[1]; //Lấy loại image từ file base64
        } else {
            $fileType = $file->getClientOriginalExtension();
        }

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $fileType;

        Storage::disk('public')->put($to . $file_name, file_get_contents($file), 'public');

        //Lấy real path trên s3
        return [
            'path' => $this->s3Disk->getRealPath($to . $file_name),
            'type' => $fileType
        ];
    }

}