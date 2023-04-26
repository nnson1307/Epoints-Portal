<?php
/**
 * Created by PhpStorm.
 * User: WAO
 * Date: 26/03/2018
 * Time: 6:27 CH
 */
namespace Modules\Admin\Requests;
use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class StaffRequestForm  extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $staff_id = $this->request->get('staff_id');

        switch ($this->route()->getActionMethod()){
            case 'add':
                return [
                    'fullname'              => 'required',
                    'staff_department_id'   => 'required',
                    'username'              => 'required',
                    'staff_title_id'        => 'required',
                    'password'              => 'required|confirmed',
                    'password_confirmation' => 'required|required_with:password',
                    'code'                  => 'required|unique:staffs',
                    'phone'                 => 'required|unique:staffs',
                    'avatar'                => 'required|mimes:jpeg,jpg,png',
                ];
                break ;
            default :
                return [
                    'fullname'              => 'required',
                    'staff_department_id'   => 'required',
                    'username'              => 'required',
                    'password'              => 'required',
                    'staff_title_id'        => 'required',
                    'password_confirmation' => 'required',
                    'avatar'                => 'mimes:jpeg,jpg,png',
                    'code'  => 'required|unique:staffs,staff_id'. (($staff_id )? ",'{$staff_id}' ,staff_id" : ''),
                    'phone' => 'required|unique:staffs,staff_id'. (($staff_id )? ",'$staff_id' ,staff_id" : ''),
                ];
             break ;
        }
    }
    /*
     * function custom messages
     */
    public function messages()
    {
        switch ($this->route()->getActionMethod()){
            case 'add':
                return [
                    'code.required'                  => 'Mã  nhân viên bắt buộc !',
                    'code.unique'                    => 'Mã nhân viên đã tồn tại !',
                    'fullname.required'              => 'Tên nhân viên bắt buộc !',
                    'staff_department_id.required'   => 'Tên phòng ban bắt buộc !',
                    'username.required'              => 'Tên  tài khoản bắt buộc !',
                    'password.required'              => 'Mật khẩu bắt buộc !',
                    'password.confirmed'             => 'Mật khẩu không khớp',
                    'password_confirmation.required' => 'Xác nhận lại mật khẩu',
                    'phone.required'                 => 'Số điện thoại bắt buộc !',
                    'phone.unique'                   => 'Số điện thoại này đã tồn tại !',
                    'staff_title_id.required'        => 'Chức danh bắt buộc !',
                    'avatar.required'                => 'Hình ảnh  bắt buộc',
                    'avatar.mimes'                   => 'Hình ảnh phải đúng định dạng jpeg,jpg,png',
                ];
                break ;
            default :
                return [
                    'code.required'                  => 'Mã  nhân viên bắt buộc !',
                    'code.unique'                    => 'Mã nhân viên đã tồn tại !',
                    'fullname.required'              => 'Tên nhân viên bắt buộc !',
                    'staff_department_id.required'   => 'Tên phòng ban bắt buộc !',
                    'username.required'              => 'Tên  tài khoản bắt buộc !',
                    'password.required'              => 'Mật khẩu bắt buộc !',
                    'password_confirmation.required' => 'Xác nhận lại mật khẩu',
                    'phone.required'                 => 'Số điện thoại bắt buộc !',
                    'phone.unique'                   => 'Số điện thoại này đã tồn tại !',
                    'staff_title_id.required'        => 'Chức danh bắt buộc !',
                    'avatar.mimes'                   => 'Hình ảnh phải đúng định dạng jpeg,jpg,png',
                ];
            break ;
        }
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}