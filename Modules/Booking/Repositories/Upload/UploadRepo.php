<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 3:28 PM
 */

namespace Modules\Booking\Repositories\Upload;


use App\Http\Middleware\S3UploadsRedirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\Booking\Models\CustomerTable;
use Modules\Booking\Models\DeliveryHistoryTable;

class UploadRepo implements UploadRepoInterface
{
    protected $s3Disk;

    public function __construct(
        S3UploadsRedirect $s3
    ) {
        $this->s3Disk = $s3;
    }

    /**
     * Upload customer avatar
     *
     * @param $input
     * @return array|mixed
     */
    public function uploadAvatar($input)
    {
        $mCustomer = new CustomerTable();
        //Upload image
        $avatar = $this->uploadImageS3($input['customer_avatar'], '_customer.');
        //Update avatar customer
        $mCustomer->edit([
            'customer_avatar' => $avatar
        ], $input['customer_id']);
        return [
            'customer_avatar' => $avatar
        ];
    }

    /**
     * Upload hình ảnh nhận hàng của nv giao hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function uploadPickUp($input)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        //Upload image
        $image = $this->uploadImageS3($input['image_pick_up'], '_delivery-history.');
        //Update image pick up
        $mDeliveryHistory->edit([
            'image_pick_up' => $image,
            'time_pick_up' => Carbon::now()->format('Y-m-d H:i:s')
        ], $input['delivery_history_id']);

        return [
            'image_pick_up' => $image
        ];
    }

    /**
     * Upload hình ảnh giao hàng của nv giao hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function uploadDrop($input)
    {
        $mDeliveryHistory = new DeliveryHistoryTable();
        //Upload image
        $image = $this->uploadImageS3($input['image_drop'], '_delivery-history.');
        //Update image pick up
        $mDeliveryHistory->edit([
            'image_drop' => $image,
            'time_drop' => Carbon::now()->format('Y-m-d H:i:s')
        ], $input['delivery_history_id']);

        return [
            'image_drop' => $image
        ];
    }

    /**
     * Lưu ảnh vào folder temp
     *
     * @param $file
     * @param $link
     * @return string
     */
    private function uploadImageS3($file, $link)
    {
        $time = Carbon::now();
//        $idTenant = "ed5fdecf0930c60d4dc30c103d826071";
        $idTenant = session()->get('idTenant');

        $to = $idTenant . '/' . date_format($time, 'Y') . '/' . date_format($time, 'm') . '/' . date_format($time, 'd') . '/';

        $file_name =
            str_random(5) .
            rand(0, 9) .
            time() .
            date_format($time, 'd') .
            date_format($time, 'm') .
            date_format($time, 'Y') .
            $link .
            $file->getClientOriginalExtension();

        Storage::disk('public')->put( $to . $file_name, file_get_contents($file), 'public');

        //Lấy real path trên s3
        return $this->s3Disk->getRealPath($to. $file_name);
    }
}