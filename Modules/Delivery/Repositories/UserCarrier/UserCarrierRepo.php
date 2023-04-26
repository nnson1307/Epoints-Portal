<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/8/2020
 * Time: 10:40 AM
 */

namespace Modules\Delivery\Repositories\UserCarrier;


use Illuminate\Support\Facades\Storage;
use Modules\Delivery\Models\UserCarrierTable;

class UserCarrierRepo implements UserCarrierRepoInterface
{
    protected $userCarrier;

    public function __construct(
        UserCarrierTable $userCarrier
    ) {
        $this->userCarrier = $userCarrier;
    }

    /**
     * Ds nv giao hàng
     *
     * @param array $filters
     * @return mixed
     */
    public function getList(array $filters = [])
    {
        return $this->userCarrier->getList($filters);
    }

    /**
     * Thêm nv giao hàng
     *
     * @param $input
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function store($input)
    {
        try {
            unset($input['password_confirm']);
            $input['password'] = bcrypt($input['password']);

//            if ($input['avatar'] != null) {
//                $input['avatar'] =  url('/') . '/' . $this->moveImage($input['avatar'], USER_CARRIER_PATH);
//            }

            //Thêm nv giao hàng
            $this->userCarrier->add($input);

            return [
                'error' => false,
                'message' => __('Thêm mới thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thêm mới thất bại')
            ];
        }
    }

    /**
     * Move ảnh từ folder temp sang folder chính
     *
     * @param $filename
     * @param $PATH
     * @return mixed|string
     */
    public function moveImage($filename, $PATH)
    {
        $old_path = TEMP_PATH . '/' . $filename;
        $new_path = $PATH . date('Ymd') . '/' . $filename;
        Storage::disk('public')->makeDirectory($PATH . date('Ymd'));
        Storage::disk('public')->move($old_path, $new_path);
        return $new_path;
    }

    /**
     * Lấy thông tin nv giao hàng
     *
     * @param $userCarrierId
     * @return array|mixed
     */
    public function getInfo($userCarrierId)
    {
        $info = $this->userCarrier->getInfo($userCarrierId);

        return [
            'item' => $info
        ];
    }

    /**
     * Chỉnh sửa nv giao hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function update($input)
    {
        try {
            if ($input['password_new'] != null) {
                $input['password'] = bcrypt($input['password_new']);
            }

            if ($input['avatar'] == null) {
                $input['avatar'] = $input['avatar_old'];
            }

            unset($input['password_confirm'], $input['password_new'], $input['avatar_old']);
            //Thêm nv giao hàng
            $this->userCarrier->edit($input, $input['user_carrier_id']);

            return [
                'error' => false,
                'message' => __('Chỉnh sửa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Chỉnh sửa thất bại')
            ];
        }
    }

    /**
     * Thay đổi trạng thái
     *
     * @param $input
     * @return array|mixed
     */
    public function changeStatus($input)
    {
        try {
            $this->userCarrier->edit($input, $input['user_carrier_id']);

            return [
                'error' => false,
                'message' => __('Thay đổi trạng thái thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Thay đổi trạng thái thất bại')
            ];
        }
    }

    /**
     * Xóa nv giao hàng
     *
     * @param $input
     * @return array|mixed
     */
    public function destroy($input)
    {
        try {
            $this->userCarrier->edit([
                'is_deleted' => 1
            ], $input['user_carrier_id']);

            return [
                'error' => false,
                'message' => __('Xóa thành công')
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => __('Xóa thất bại')
            ];
        }
    }
}