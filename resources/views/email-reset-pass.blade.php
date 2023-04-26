@lang('Xin chào') <br/>
@lang('Vui lòng truy cập đường dẫn sau để thay đổi mật khẩu'): <br/>
<a href="{{route('login.resetPassword', ['token' => $token])}}">>
    @lang('Lấy lại mật khẩu')
</a>