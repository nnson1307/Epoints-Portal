<?php

namespace Modules\Api\Repositories\UserBrand;
use DaiDP\StsSDK\TenantUserManagement\TenantUserManagementInterface;
use Illuminate\Support\Facades\DB;
use Matrix\Exception;
use Modules\Api\Models\AdminTable;


class UserBrandRepository implements UserBrandRepositoryInterface
{
    protected $admin;

    public function __construct(AdminTable $admin)
    {
        $this->admin = $admin;
    }

    public function getItem($id)
    {
        try {
            return $this->admin->getItem($id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function updatePass( array $data, $id)
    {
        $tk = $this->admin->getItem($id);
        $umSDK = app(TenantUserManagementInterface::class);
        $umSDK->setTenantId(session('idTenant'));
        $pass = isset($data['password']) ? $data['password'] : '23873463';
        $result = $umSDK->resetPassword(
            $tk['email'] ,
            strip_tags($pass)
        );
        if (!$result->error) {
            $params['is_change_pass'] = !$data['is_change_pass'];
            $this->admin->edit($params, $id);

            $item = [
                'email' => $tk['email'],
                'full_name' => $tk['full_name'],
                'password' => strip_tags($pass)
            ];

            return response()->json([
                'error' => false,
                'message' => 'Reset mật khẩu thành công'
            ]);
        } else {
            $message =  'Reset mật khẩu thất bại';
            if (isset($result->data['errors']) &&
                sizeof($result->data['errors']) > 0 &&
                isset($result->data['errors'][0]['description'])) {
                $message = $result->data['errors'][0]['description'];
            }
            return response()->json([
                'error' => true,
                'message' => $message
            ]);
        }
    }

    public function changeStatus(array $data, $id)
    {
        try {
            $datAdmin = [
                'is_actived' => $data['is_actived']
            ];

            if ($datAdmin['is_actived']==1) {
                DB::table('admin_lock_out')->where('admin_id', $id)->delete();
            }

            // TODO: Implement changeStatus() method.
            $result = $this->admin->changeStatus($datAdmin, $id);

            return [
                'error' => false,
                'message' => 'Thay đổi trạng thái thành công'
            ];
        } catch (Exception $e) {
            return [
                'error' => true,
                'message' => 'Thay đổi trạng thái thất bại'
            ];
        }

    }
}