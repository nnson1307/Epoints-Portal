<?php

namespace Modules\Api\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Matrix\Exception;
use Modules\Api\Repositories\UserBrand\UserBrandRepositoryInterface;

class UserBrandController extends Controller
{
    protected $userBrand;
    public function __construct(UserBrandRepositoryInterface $userBrand)
    {
        $this->userBrand = $userBrand;
    }

    public function getDetail(Request $request)
    {
        try {
            $data = $request->all();
            $id = '1';
            $result = $this->userBrand->getItem($id);

            return $this->responseJson(CODE_SUCCESS, null, $result);
        } catch (Exception $exception) {
            return $this->responseJson(CODE_FAILED,null, 'Lấy thông tin chi tiết thất bại');
        }
    }


    public function  updatePass(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['user_id'];
            $result = $this->userBrand->updatePass($data, $id);

            return $this->responseJson(CODE_SUCCESS, null, $result);
        } catch (Exception $exception) {
            return $this->responseJson(CODE_FAILED,null, 'Reset mật khẩu thất bại');
        }
    }

    public function ChangeStatus(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['user_id'];
            $result = $this->userBrand->changeStatus($data, $id);

            return $this->responseJson(CODE_SUCCESS, null, $result);
        } catch (Exception $e) {
            return $this->responseJson(CODE_FAILED,null, 'Thay đổi trạng thái thất bại');
        }
    }
}