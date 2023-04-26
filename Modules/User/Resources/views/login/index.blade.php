@extends('layout-login')

@section('content')
<div class="m-login__container">
	<div class="m-login__logo">
		<a href="#">
			<img src="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}">
		</a>
		<hr class="m-login-border">
	</div>

	<div class="m-login__signin">
		<div class="m-login__head">
			<h3 class="m-login__title">
				@lang(isset(config()->get('config.text_login')->value) ? __("ĐĂNG NHẬP VÀO"). " " .config()->get('config.text_login')->value : '')
			</h3>

		</div>
		<form class="m-login__form m-form" method="post">
			{!! csrf_field() !!}
			<div class="m-input-icon m-input-icon--left">
				<input type="text" class="form-control m-input m-input--pill m--form-login-new"
					   placeholder="@lang('Tài Khoản')" name="user_name" autocomplete="off">
				<span class="m-input-icon__icon m-input-icon__icon--left">
					<span>
						<i class="la la-user"></i>
					</span>
				</span>
				@if ($errors->has('user_name'))
					<div id="email-error" class="form-control-feedback">{{ $errors->first('user_name') }}</div>
				@endif
			</div>
			<div class="m-input-icon m-input-icon--left">
				<input class="form-control m-input m-input--pill m-login__form-input--last m--form-login-new" type="password" placeholder="@lang('Mật Khẩu')" name="password" autocomplete="off">
				<span class="m-input-icon__icon m-input-icon__icon--left">
					<span>
						<i class="la la-lock"></i>
					</span>
				</span>
				@if ($errors->has('user_name'))
					<div id="email-error" class="form-control-feedback">{{ $errors->first('user_name') }}</div>
				@endif
			</div>
			<div class="row m-login__form-sub">
{{--				<div class="col m--align-left m-login__form-left">--}}
{{--					<label class="m-checkbox  m-checkbox--focus">--}}
{{--						<input type="checkbox" name="remember">--}}
{{--						@lang('Ghi nhớ')--}}
{{--						<span></span>--}}
{{--					</label>--}}
{{--				</div>--}}
{{--				<div class="col m--align-right m-login__form-right">--}}
{{--					<a href="{{route('login.forgetPassword')}}" id="" class="m-link">--}}
{{--						@lang('Quên mật khẩu') ?--}}
{{--					</a>--}}
{{--				</div>--}}
			</div>
			<div class="m-login__form-action">
				<button id="m_login_signin_submit" class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary btn-block">
					@lang('Đăng nhập')
				</button>
			</div>
		</form>
	</div>
	
	<div class="m-login__forget-password">
		<div class="m-login__head">
			<h3 class="m-login__title">
				@lang('Quên mật khẩu')
			</h3>
			<div class="m-login__desc">
				@lang('Vui lòng điền chính xác thông tin đăng nhập. Chúng tôi sẽ gửi email xác thực tới email đã đăng ký để kích hoạt tính năng lấy lại mật khẩu')
			</div>
		</div>
		<form class="m-login__form m-form" id="form_forget_password" action="">
			<div class="m-input-icon m-input-icon--left">
				<input class="form-control m-input m-input--pill m-login__form-input--last m--form-login-new" type="email" placeholder="Địa chỉ email" name="email">
				<span class="m-input-icon__icon m-input-icon__icon--left">
					<span>
						<i class="la la-envelope"></i>
					</span>
				</span>
			</div>
			<div class="m-login__form-action">
				<button id="m_login_forget_password_submit"
						type="submit"
						class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary ">
					Gửi				</button>
				&nbsp;&nbsp;
				<button id="m_login_forget_password_cancel" class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">
					Huỷ				</button>
			</div>
		</form>
	</div>
</div>
@endsection
@section('after_script')
@stop