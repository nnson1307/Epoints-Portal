<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>@yield('title', __('Hệ thống quản trị EPOINTS'))</title>

	<!--begin::Web font -->
	<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Quicksand:300,400,500,600,700","Roboto:300,400,500,600,700"]},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
	<!--end::Web font -->
	<!--begin:: Global Mandatory Vendors -->
	<link href="{{asset('vendors/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" type="text/css" />
	<!--end:: Global Mandatory Vendors -->
	<!--begin:: Global Optional Vendors -->
	<link href="{{asset('vendors/tether/dist/css/tether.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/owl.carousel/dist/assets/owl.theme.default.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/dropzone/dist/dropzone.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/animate.css/animate.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/sweetalert2/dist/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/socicon/css/socicon.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/vendors/line-awesome/css/line-awesome.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/vendors/flaticon/css/flaticon.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/vendors/metronic/css/styles.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('vendors/vendors/fontawesome5/css/all.min.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('static/backend/assets/vendors/custom/jquery-ui/jquery-ui.bundle.min.css')}}" rel="stylesheet" type="text/css" />
	<!--end:: Global Optional Vendors -->
	<!--begin::Global Theme Styles -->
	<link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
<!--RTL version:<link href="{{asset('static/backend/assets/demo/base/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />-->
	<!--Base Styles -->
	<link href="{{asset('static/backend/assets/demo/base/style.bundle.css')}}" rel="stylesheet" type="text/css" />
	<link href="{{asset('static/backend/css/login.css')}}" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" href="{{isset(config()->get('config.short_logo')->value) ? config()->get('config.short_logo')->value : ''}}" />
	@yield('after_style')
</head>
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >

<div class="m-grid m-grid--hor m-grid--root m-page">
	<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor m-login m-login--signin m-login--2 m-login-2--skin-2" id="m_login" style="background-image: url({{asset('static/backend/images/bg-login-while.jpg')}});">
		<div class="m-grid__item m-grid__item--fluid	m-login__wrapper">
			<div class="m-login__container">
				<div class="m-login__logo">
					<a href="#">
						<img src="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}">
					</a>
					<hr class="m-login-border">
				</div>

				<div class="m-login__signin">
					<div class="m-login__head">
						<div class="m-login__head">
							<h3 class="m-login__title">
								@lang('Mật khẩu mới')
							</h3>
							<div class="m-login__desc">
								@lang('')
							</div>
						</div>
						<form class="m-login__form m-form" id="form_forget_password" action="">
							@csrf
							<div class="">
								<input class="form-control m-input m-input--pill m-login__form-input--last m--form-login-new"
									   type="password"
									   placeholder="Nhập mật khẩu"
									   name="password"
									   id="password"
								>
							</div>
							<div class="">
								<input class="form-control m-input m-input--pill m-login__form-input--last m--form-login-new"
									   type="password"
									   placeholder="Nhập lại mật khẩu"
									   name="re_password"
									   id="re_password"
								>
							</div>
							<div class="m-login__form-action">
								<button onclick="forgetPass.submitNewPassword('{{$token}}')"
										id="m_login_forget_password_submit"
										type="button"
										class="btn m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn m-login__btn--primary ">
									@lang('Xác nhận')
								</button>
								&nbsp;&nbsp;
								<a id="m_login_forget_password_cancel" href="{{route('login')}}"
										class="btn btn-outline-focus m-btn m-btn--pill m-btn--custom m-login__btn">
									@lang('Hủy')
								</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!--begin:: Global Mandatory Vendors -->
<script src="{{asset('vendors/jquery/dist/jquery.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/popper.js/dist/umd/popper.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js-cookie/src/js.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/moment/min/moment.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/tooltip.js/dist/umd/tooltip.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/perfect-scrollbar/dist/perfect-scrollbar.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/wnumb/wNumb.js')}}" type="text/javascript"></script>
<script src="{{asset('js/laroute.js')}}" type="text/javascript"></script>

<!--end:: Global Mandatory Vendors -->

<!--begin:: Global Optional Vendors -->
<script src="{{asset('vendors/jquery.repeater/src/lib.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery.repeater/src/jquery.input.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery.repeater/src/repeater.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-form/dist/jquery.form.min.js')}}" type="text/javascript"></script>

<script src="{{asset('vendors/jquery-validation/dist/jquery.validate.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/jquery-validation/dist/additional-methods.js')}}" type="text/javascript"></script>


<script src="{{asset('static/backend/assets/demo/base/scripts.bundle.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/sweetalert2/dist/sweetalert2.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendors/js/framework/components/plugins/base/sweetalert2.init.js')}}"
		type="text/javascript"></script>forget-password.blade.php
<script src="{{asset('static/backend/js/user/login.js?v='.time())}}" type="text/javascript"></script>

</body>
</html>