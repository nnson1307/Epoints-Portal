<div class="modal fade show" id="config-account" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-cog"></i>@lang('CẤU HÌNH TÀI KHOẢN')
                </h5>
            </div>
            <form id="form-config">
                <div class="modal-body">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên tài khoản'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control m-input" id="user_name" name="user_name"
                                   placeholder="@lang('Nhập tên tài khoản')" value="{{$item['user_name']}}">
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Mật khẩu'):<b class="text-danger">*</b>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control m-input" id="password" name="password"
                                   placeholder="@lang('Nhập mật khẩu')" value="{{$item['password']}}">
                        </div>
                    </div>

                    {{--<div class="form-group m-form__group">--}}
                        {{--<label class="black_title">{{__('Trạng thái')}}:</label>--}}
                        {{--<div class="input-group">--}}
                            {{--<span class="m-switch m-switch--icon m-switch--success m-switch--sm">--}}
                                        {{--<label>--}}
                                            {{--<input type="checkbox" id="is_actived"--}}
                                                   {{--{{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn">--}}
                                            {{--<span></span>--}}
                                        {{--</label>--}}
                            {{--</span>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
                <div class="modal-footer">
                    <div class="m-form__actions m--align-right w-100">
                        <a href="javascript:void(0)" data-dismiss="modal"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                           <span>
                            <i class="la la-arrow-left"></i>
                               <span>{{__('HỦY')}}</span>
                           </span>
                        </a>
                        <button type="button" onclick="list.submitSetting({{$item['id']}})"
                                class="btn btn-info color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CHỈNH SỬA')}}</span>
							</span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>