<?php
namespace Modules\User\Repositories\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\UserTable;

/**
 * User repository
 * 
 * @author isc-daidp
 * @since Feb 23, 2018
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * @var UserTable
     */
    protected $user;
    protected $timestamps = true;
    
    
    public function __construct(UserTable $user)
    {
        $this->user = $user;    
    }
    
    
    /**
     * Lấy danh sách user
     */
    public function list(array $filters = [])
    {
        return $this->user->getList($filters);
    }
    
    
    /**
     * Xóa user
     */
    public function remove($id)
    {
        $this->user->remove($id);
    }
    
    
    /**
     * Thêm user
     */
    public function add(array $data)
    {
        $data['password'] = bcrypt($data['password']);
        
        return $this->user->add($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getItem($id)
    {
        return $this->user->getItem($id);
    }

    /**
     * Chuyển sang trang đổi mk mới
     * @param $token
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function resetPassword($token)
    {
        //Lấy tk có token, và token chỉ có hạn là 1 ngày
        $dayNow = Carbon::now()->format('Y-m-d 00:00:00');
        $param = [
            'password_reset' => $token,
            'date_password_reset' => $dayNow,
        ];
        $getUser = $this->user->getItemByCondition($param);
        if ($getUser == null) {
            return redirect()->route('login');
        }
        return view('user::login.reset-password', [
            'token' => $token,
        ]);
    }

    /***
     * Submit đổi mk mới
     * @param $params
     * @return array|mixed
     */
    public function submitNewPassword($params)
    {
        $token = strip_tags($params['token']);
        $password = strip_tags($params['password']);
        $filter = ['password_reset' => $token];
        //Lấy user có token.
        $getUser = $this->user->getItemByCondition($filter);
        if ($getUser == null) {
            return [
                'error' => true,
                'message' => __('Tài khoản không tồn tại!')
            ];
        }
        $data = [
            'password_reset' => null,
            'date_password_reset' => null,
            'password' => Hash::make($password),
        ];
        $this->user->edit($getUser['staff_id'], $data);
        return [
            'error' => false,
            'message' => __('Thay đổi mật khẩu thành công!')
        ];
    }
}