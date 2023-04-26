<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Admin\Models\ActionTable;
use Modules\Admin\Models\ConfigTable;
use Modules\Admin\Models\MapRoleGroupStaffTable;
use Modules\Admin\Models\PageTable;
use Modules\ManagerWork\Http\Api\ManageFileApi;
use Modules\User\Http\Controllers\Api\StaffApi;
use Modules\User\Http\Requests\ForgetPassword\StoreRequest;
use Modules\User\Models\AdminMenuFunctionTable;
use Modules\User\Models\AdminServiceBrandFeatureChildTable;
use Modules\User\Models\UserTable;
use Modules\User\Repositories\AdminMenu\AdminMenuRepositoryInterface;
use Modules\User\Repositories\AdminMenuCategory\AdminMenuCategoryRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Str;
use Modules\User\Repositories\User\UserRepositoryInterface;

class LoginController extends Controller
{
    protected $admin_menu_category;
    protected $admin_menu;
    protected $rUser;

    public function __construct(
        AdminMenuCategoryRepositoryInterface $admin_menu_category,
        AdminMenuRepositoryInterface $admin_menu,
        UserRepositoryInterface $rUser
    )
    {
        $this->admin_menu_category = $admin_menu_category;
        $this->admin_menu = $admin_menu;
        $this->rUser = $rUser;
    }

    /**
     * Form login
     *
     * @return \Illuminate\Http\Response
     */
    public function indexAction()
    {
        //Đồng bộ quyền feature
//        $this->admin_menu->syncFeature();
        
        if (Auth::check()) {
            if (session()->has('brand_code') && session()->get('brand_code') == 'vsetcom') {
                return redirect()->route('ticket.dashboard');
            } else {
                return redirect()->route(LOGIN_HOME_PAGE);
            }
        }

        session()->forget('config_system');

        return view('user::login.index');
    }

    /**
     * Xử lý login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postLogin(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 1,
                'message' => $validator->errors()->all()
            ]);
        }

        $certifications = [
            'user_name' => $request->user_name,
            'password' => $request->password,
            'is_actived' => 1,
            'is_deleted' => 0
        ];

        if (Auth::attempt($certifications)) {
            $idTenant = session()->get('idTenant');
            Cache::forget('data_menu_'.$idTenant);

            //Phân quyền
            $pages = new PageTable();
            $actions = new ActionTable();
            $mapRoleGroupStaff = new MapRoleGroupStaffTable();
            $mFeatureChild = new AdminServiceBrandFeatureChildTable();

            $arrService = [];

            //Lấy bảng quyền dịch vụ được cấp cho brand
            $allService = $mFeatureChild->getAllService();

            if (count($allService) > 0) {
                foreach ($allService as $v) {
                    $arrService [] = $v['feature_code'];
                }
            }

            $isAdmin = Auth::user()->is_admin;
            $staffId = Auth::user()->staff_id;
            if (!$request->session()->has('routeList')) {
                if ($isAdmin != 1) {
                    //Lấy quyền page
                    $getRolePage = $mapRoleGroupStaff->getRolePageByStaff($staffId, $arrService);
                    //Lấy quyền action
                    $getRoleAction = $mapRoleGroupStaff->getRoleActionByStaff($staffId, $arrService);
                    //Merge 2 mãng quyền lại
                    $arrayRole = array_merge($getRolePage, $getRoleAction);
                    $arrayRole [] = 'admin.menu-all';
                    //Push session quyền khi login thành công
                    $request->session()->put('routeList', $arrayRole);
                } else {
                    //Lấy quyền page
                    $getRolePage = $pages->getAllRoute($arrService);
                    //Lấy quyền action
                    $getRoleAction = $actions->getAllRoute($arrService);
                    //Merge 2 mãng quyền lại
                    $arrayRole = array_merge($getRolePage, $getRoleAction);
                    $arrayRole [] = 'admin.menu-all';
                    //Push session quyền khi login thành công
                    $request->session()->put('routeList', $arrayRole);
                }
            }
            // Vertical menu
            $mMenuFunction = new AdminMenuFunctionTable();
            $getVerticalMenu = $mMenuFunction->getListMenuVertical()->toArray();
            session(['menuVertical' => $getVerticalMenu]);

            // Horizontal menu
            $getGroupMenu = $this->admin_menu_category->getListGroupMenu();
            $getHorizontalMenu = [];
            foreach ($getGroupMenu as $item) {
                $getHorizontalMenu[] = [
                    'menu_category_name' => $item['menu_category_name'],
                    'menu_category_icon' => $item['menu_category_icon'],
                    'menu_category_id' => $item['menu_category_id'],
                    'menu' => $mMenuFunction->getListMenuHorizontalByMenuCat($item['menu_category_id'])
                ];
            }
            // Check horizontal menu in route list
            foreach ($getHorizontalMenu as $item) {
                foreach ($item['menu'] as $k => $menu) {
                    if (!in_array($menu['admin_menu_route'], session('routeList'))) {
                        unset($item['menu'][$k]);
                    }
                }
            }
            session(['menuHorizontal' => $getHorizontalMenu]);

             $mStaffApi = new StaffApi();
             //Đăng ký device token
             $mStaffApi->registerDeviceToken([
                 'platform' => 'android',
                 'device_token' => 'portal123',
                 'staff_id' => Auth()->id(),
                 'imei' => 'portal123'
             ]);

             $staffLogin = $mStaffApi->loginStaff([
                 'user_name' => $request->user_name,
                 'password' => $request->password,
                 'platform' => 'android',
                 'device_token' => 'portal123',
                 'imei' => 'portal123',
                 'brand_code' => session()->get('brand_code')
             ]);

            if ($request->session()->has('staff_login')){
                $request->session()->forget('staff_login');
            }


            if (isset($staffLogin['Data']) && $staffLogin['Data'] != null){
                $request->session()->put('access_token',$staffLogin['Data']['access_token']);
            } else {
                $request->session()->put('access_token','');
            }

            // if (session()->has('brand_code') && in_array(session()->get('brand_code'), ['vsetcom'])) {
            //     $linkLogin = route('ticket.dashboard');
            // } else if (session()->has('brand_code') && in_array(session()->get('brand_code'), ['matthewsliquor'])) {
            //     $linkLogin = route('manager-work.report.my-work');
            // } else {
            //     $linkLogin = route(LOGIN_HOME_PAGE);
            // }
            $linkLogin = route(LOGIN_HOME_PAGE);
            $routeHomePage = session()->get('config_system')['home_page_portal'] ?? null;
            if (isset($routeHomePage) && in_array($routeHomePage, session('routeList'))) {
                $linkLogin = route($routeHomePage);
            }
            // Authentication passed...
            return response()->json([
                'error' => 0,
                'message' => __('Đăng nhập thành công.'),
//                'url' => route(LOGIN_HOME_PAGE)
                'url' => $linkLogin
            ]);
        }

        return response()->json([
            'error' => 1,
            'message' => __('Username hoặc password không đúng.')
        ]);
    }

    public function logoutAction(Request $request)
    {
        if ($request->session()->has('routeList')) {
            $request->session()->forget('routeList');
        }
        if ($request->session()->has('menuVertical')) {
            $request->session()->forget('menuVertical');
        }
        if ($request->session()->has('menuHorizontal')) {
            $request->session()->forget('menuHorizontal');
        }

        Auth::logout();

        return redirect()->route('login');
    }

    /**
     * Chuyển sang trang quên mật khẩu
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View|mixed
     */
    public function forgetPassword()
    {
        if (Auth::check()) {
            if (session()->has('brand_code') && session()->get('brand_code') == 'vsetcom') {
                return redirect()->route('ticket.dashboard');
            } else {
                return redirect()->route(LOGIN_HOME_PAGE);
            }
        }
        return view('user::login.forget-password');
    }

    /**
     * Gửi mail cho tk quên mk
     * @param Request $request
     * @return array
     */
    public function submitForgetPassword(Request $request)
    {
        try {
            $email = strip_tags($request->email);
            $user = new UserTable();
            //check email
            $param = ['email' => $email];
            $checkUser = $user->getItemByCondition($param);
            if ($checkUser == null) {
                return [
                    'error' => true,
                    'message' => __('Email không tồn tại!')
                ];
            }
            //Render token 32 ký tự
            $ranString = Str::random(32);
            $data = [
                'password_reset' => $ranString,
                'date_password_reset' => Date('Y-m-d')
            ];
            $user->edit($checkUser['staff_id'], $data);
            //viết hàm gửi email
            Mail::to($checkUser['email'])->send(new ResetPassword($ranString));
            return [
                'error' => true,
                'message' => __('Vui lòng kiểm tra lại email!')
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Chuyển sang trang đổi mk mới
     * @param $token
     * @return mixed
     */
    public function resetPassword($token)
    {
        return $this->rUser->resetPassword($token);
    }

    /**
     * Submit đổi mk mới
     * @param StoreRequest $request
     * @return mixed
     */
    public function submitNewPassword(StoreRequest $request)
    {
        $params = $request->all();
        return $this->rUser->submitNewPassword($params);
    }
}

