<div id="editForm" class="modal fade" role="dialog">
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <style>
            .form-control-feedback {
                color: #ff0000;
            }
        </style>
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title title_index">
                    <i class="la la-edit"></i> {{__('CHỈNH SỬA ĐƠN VỊ VẬN CHUYỂN')}}
                </h4>
            </div>
            <form id="formEdit">
                <div class="modal-body">
                    <input type="hidden" id="hhidden">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>
                                    {{__('Đơn vị vận chuyển')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="transport_name" class="form-control m-input btn-sm"
                                       id="h_transport_name"
                                       placeholder="{{__('Hãy nhập đơn vị vận chuyển')}}">
                                <span class="error-name"></span>
                                @if ($errors->has('transport_name'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('transport_name') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Người đại diện')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="contact_name" class="form-control m-input btn-sm"
                                       id="h_contact_name"
                                       placeholder="{{__('Hãy nhập người đại diện')}}">

                                @if ($errors->has('contact_name'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('contact_name') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                                </label>
                                <input type="text" name="contact_phone" class="form-control m-input btn-sm"
                                       id="h_contact_phone" placeholder="{{__('Hãy nhập số điện thoại')}}">

                                @if ($errors->has('contact_phone'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('contact_phone') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                                </label>
                                <input name="address" class="form-control m-input btn-sm" id="h_address"
                                       placeholder="{{__('Hãy nhập địa chỉ')}}">
                                @if ($errors->has('address'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('address') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group block-token" style="display:none">
                                <label>
                                    {{__('Token')}}:<b class="text-danger">*</b>
                                </label>
                                <input name="token" class="form-control m-input btn-sm" id="token"
                                       placeholder="{{__('Hãy nhập token')}}">
                                @if ($errors->has('token'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('token') }}
                                </span>
                                    <br>
                                @endif
                                <p class="mt-3">{{__('Bạn chưa có tài khoản?')}} <a href="https://sso.ghn.vn/v2/ssoLogin?app=import&returnUrl=https://khachhang.ghn.vn/sso-login?token=" target="_blank">{{__('Đăng ký tại đây')}}</a></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
{{--                            <div class="form-group">--}}
{{--                                <label>--}}
{{--                                    {{__('Chi phí giao hàng')}}:<b class="text-danger">*</b>--}}
{{--                                </label>--}}
{{--                                <input type="text" name="charge" class="form-control m-input btn-sm charge-add" id="h_charge"--}}
{{--                                       placeholder="{{__('Hãy nhập chi phí giao hàng')}}">--}}
{{--                                @if ($errors->has('charge'))--}}
{{--                                    <span class="form-control-feedback">--}}
{{--                                     {{ $errors->first('charge') }}--}}
{{--                                </span>--}}
{{--                                    <br>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <label>
                                    {{__('Chức danh')}}:
                                </label>
                                <input type="text" name="contact_title" class="form-control m-input btn-sm"
                                       id="h_contact_title" placeholder="{{__('Hãy nhập chức danh')}}">

                                @if ($errors->has('contact_title'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('contact_title') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                            <div class="form-group">
                                <label>
                                    {{__('Mô tả')}}:
                                </label>
                                <textarea rows="5" name="description" class="form-control m-input"
                                          id="h_description"></textarea>
                                @if ($errors->has('description'))
                                    <span class="form-control-feedback">
                                     {{ $errors->first('description') }}
                                </span>
                                    <br>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="transport_code" name="transport_code" value="">
                <div class="modal-footer">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit w-100">
                        <div class="m-form__actions m--align-right">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>


                            <button type="submit" id="btnLuu"
                                    class="btn btn-primary color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-edit"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
                            </button>

                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
