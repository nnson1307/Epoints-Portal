@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CẤU HÌNH THÔNG BÁO TỰ ĐỘNG')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-edit"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA CẤU HÌNH THÔNG BÁO TỰ ĐỘNG')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            {!! csrf_field() !!}
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tên thông báo')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="name" class="form-control m-input" id="name"
                                   value="{{$item['name']}}" placeholder="{{__('Nhập tên thông báo')}}..." disabled>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Loại gửi')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="send_type" name="send_type"
                                        onchange="index.changeType(this)">
                                    <option></option>
                                    <option value="immediately" {{$item['send_type'] == "immediately" ? "selected" : ""}}>
                                        {{__('Gửi ngay')}}
                                    </option>
                                    <option value="before" {{$item['send_type'] == "before" ? "selected" : ""}}>{{__('Gửi trước')}}
                                    </option>
                                    <option value="after" {{$item['send_type'] == "after" ? "selected" : ""}}>{{__('Gửi sau')}}
                                    </option>
                                    <option value="in_time" {{$item['send_type'] == "in_time" ? "selected" : ""}}>{{__('Trong khoảng thời gian')}}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Đơn vị cộng thêm')}}:
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="schedule_unit" name="schedule_unit"
                                        {{in_array( $item['send_type'], ['immediately', 'in_time']) ? 'disabled' : ''}}>
                                    <option></option>
                                    <option value="day" {{$item['schedule_unit'] == "day" ? "selected" : ""}}>{{__('Ngày')}}
                                    </option>
                                    <option value="hour" {{$item['schedule_unit'] == "hour" ? "selected" : ""}}>{{__('Giờ')}}
                                    </option>
                                    <option value="minute" {{$item['schedule_unit'] == "minute" ? "selected" : ""}}>
                                        {{__('Phút')}}
                                    </option>
                                </select>
                                <span class="error_schedule_unit" style="color:#FF0000;"></span>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Giá trị')}}:
                            </label>
                            <div class="input-group" id="div-value">
                                <input type="text" name="value" class="form-control m-input" id="value"
                                       value="{{$item['value']}}" placeholder="{{__('Nhập giá trị')}}..."
                                        {{in_array( $item['send_type'], ['immediately']) ? 'disabled' : ''}}>
                            </div>
                            <span class="error_value" style="color:#FF0000;"></span>
                        </div>
                        @if (!in_array($item['key'], ['appointment_staff']))
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{__('Người nhận')}}:
                                </label>
                                <div class="input-group">
                                    <select class="form-control" style="width: 100%" id="role_group_id"
                                            name="role_group_id" multiple>
                                        <option></option>
                                        @foreach($optionRoleGroup as $v)
                                            <option value="{{$v['id']}}" {{in_array($v['id'], $arrayReceiver) ? 'selected': ''}}>{{{$v['name']}}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFile').click()"
                                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm avatar')}}
                                                </span>
                                            </span>
                                    </a>
                                </div>
                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <input type="hidden" id="avatar_old" name="avatar_old" value="{{$item['avatar']}}">
                                    <div class="wrap-img avatar float-left">
                                        @if($item['avatar']!=null)
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{$item['avatar']}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img" id="delete-avatar" style="display: block">
                                                    <a href="javascript:void(0)" onclick="index.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                                        @else
                                            <img class="m--bg-metal m-image img-sd" id="blah"
                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img" id="delete-avatar">
                                                    <a href="javascript:void(0)" onclick="index.remove_avatar()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                                        @endif
                                        <input type="hidden" id="avatar" name="avatar">
                                    </div>
                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                           id="getFile"
                                           type="file"
                                           onchange="uploadAvatar(this);" class="form-control"
                                           style="display:none">
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group ">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFileBackground').click()"
                                       class="btn  btn-sm m-btn--icon color">
                                            <span>
                                                <i class="la la-plus"></i>
                                                <span>
                                                    {{__('Thêm background')}}
                                                </span>
                                            </span>
                                    </a>
                                </div>
                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <input type="hidden" id="detail_background_old" name="detail_background_old"
                                           value="{{$item['detail_background']}}">
                                    <div class="wrap-img background float-left">
                                        @if($item['detail_background']!=null)
                                            <img class="m--bg-metal m-image img-sd" id="blahBg"
                                                 src="{{$item['detail_background']}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img" id="delete-bg" style="display: block">
                                                    <a href="javascript:void(0)" onclick="index.remove_background()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                 </span>
                                        @else
                                            <img class="m--bg-metal m-image img-sd" id="blahBg"
                                                 src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
                                                 alt="{{__('Hình ảnh')}}" width="100px" height="100px">
                                            <span class="delete-img" id="delete-bg">
                                                    <a href="javascript:void(0)" onclick="index.remove_background()">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                    </span>
                                        @endif
                                        <input type="hidden" id="detail_background" name="detail_background">
                                    </div>
                                    <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                           data-msg-accept="{{__('Hình ảnh không đúng định dạng')}}"
                                           id="getFileBackground"
                                           type="file"
                                           onchange="uploadBackground(this);" class="form-control"
                                           style="display:none">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tiêu đề')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="title" class="form-control m-input" id="title"
                                   value="{{$item['title']}}" placeholder="Nhập {{__('Tiêu đề')}}...">
                            <span class="error-name"></span>
                        </div>
                        <div class="form-group">
                            <label>
                                {{__('Nội dung tin nhắn')}}:<b class="text-danger">*</b>
                            </label>
                            <textarea placeholder="{{__('Nhập nội dung thông báo')}}" rows="5" cols="40"
                                      name="message" id="message" class="form-control">{{$item['message']}}</textarea>
                        </div>
                        <div class="form-group row">
                            @if (in_array($item['key'], ['order_status_A', 'order_status_B', 'order_status_C', 'order_status_D', 'order_status_I', 'order_status_S', 'order_status_W']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[order_code]')">{{__('Mã đơn hàng')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[customer_name]')">{{__('Tên khách hàng')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[total_product]')">{{__('Số lượng SP/DV')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[total_amount]')">{{__('Tổng tiền')}}</a>
                            @elseif(in_array($item['key'], ['appointment_A', 'appointment_C', 'appointment_R', 'appointment_W', 'appointment_U', 'appointment_staff']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[branch_name]')">{{__('Chi nhánh')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[time]')"> {{__('Thời gian đặt')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[date]')">{{__('Ngày đặt')}}</a>
                                @if($item['key'] == 'appointment_U')
                                    <a href="javascript:void(0)"
                                       class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                       style="color: black;"
                                       onclick="index.append_para_txa('[appointment_code]')">{{__('Mã lịch hẹn')}}</a>
                                @endif
                            @elseif(in_array($item['key'], ['service_card_expired', 'service_card_nearly_expired', 'service_card_over_number_used']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;" onclick="index.append_para_txa('[name]')">{{__('Tên thẻ')}}</a>
                                @if($item['key'] != 'service_card_over_number_used')
                                    <a href="javascript:void(0)"
                                       class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                       style="color: black;"
                                       onclick="index.append_para_txa('[expired_date]')">{{__('Ngày hết hạn')}}</a>
                                @endif
                            @elseif(in_array($item['key'], ['customer_ranking', 'customer_W']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[name]')">{{__('Tên khách hàng')}}</a>
                            @elseif(in_array($item['key'], ['delivery_W']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_txa('[delivery_history_code]')">{{__('Mã giao hàng')}}</a>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>
                                {{__('Nội dung chi tiết')}}:<b class="text-danger">*</b>
                            </label>
                            <textarea placeholder="{{__('Nhập nội dung thông báo')}}" rows="5" cols="40"
                                      name="detail_content" id="detail_content"
                                      class="form-control">{{$item['detail_content']}}</textarea>
                        </div>
                        <div class="form-group row">
                            @if (in_array($item['key'], ['order_status_A', 'order_status_B', 'order_status_C', 'order_status_D', 'order_status_I', 'order_status_S', 'order_status_W']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[order_code]')">{{__('Mã đơn hàng')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[customer_name]')">{{__('Tên khách hàng')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[total_product]')">{{__('Số lượng SP/DV')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[total_amount]')">{{__('Tổng tiền')}}</a>
                            @elseif(in_array($item['key'], ['appointment_A', 'appointment_C', 'appointment_R', 'appointment_W', 'appointment_U', 'appointment_staff']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[branch_name]')">{{__('Chi nhánh')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[time]')"> {{__('Thời gian đặt')}}</a>
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;" onclick="index.append_para_ck('[date]')">{{__('Ngày đặt')}}</a>
                                @if($item['key'] == 'appointment_U')
                                    <a href="javascript:void(0)"
                                       class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                       style="color: black;"
                                       onclick="index.append_para_ck('[appointment_code]')">{{__('Mã lịch hẹn')}}</a>
                                @endif
                            @elseif(in_array($item['key'], ['service_card_expired', 'service_card_nearly_expired', 'service_card_over_number_used']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;" onclick="index.append_para_ck('[name]')">{{__('Tên thẻ')}}</a>
                                @if($item['key'] != 'service_card_over_number_used')
                                    <a href="javascript:void(0)"
                                       class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                       style="color: black;"
                                       onclick="index.append_para_ck('[expired_date]')">{{__('Ngày hết hạn')}}</a>
                                @endif
                            @elseif(in_array($item['key'], ['customer_ranking', 'customer_W']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[name]')">{{__('Tên khách hàng')}}</a>
                            @elseif(in_array($item['key'], ['delivery_W']))
                                <a href="javascript:void(0)"
                                   class="btn m--margin-left-10 btn-sm ss--btn-parameter ss--font-weight-200"
                                   style="color: black;"
                                   onclick="index.append_para_ck('[delivery_history_code]')">{{__('Mã giao hàng')}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('config-staff')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>
                        <button type="button" onclick="index.save('{{$item['key']}}')"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="avatar-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img" id="delete-avatar"><a href="javascript:void(0)" onclick="index.remove_avatar()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="avatar" name="avatar" value="">
    </script>
    <script type="text/template" id="background-tpl">
        <img class="m--bg-metal m-image img-sd" id="blah"
             src="{{asset('static/backend/images/service-card/default/hinhanh-default3.png')}}"
             alt="{{__('Hình ảnh')}}" width="100px" height="100px">
        <span class="delete-img" id="delete-bg"><a href="javascript:void(0)" onclick="index.remove_background()">
            <i class="la la-close"></i></a>
        </span>
        <input type="hidden" id="detail_background" name="detail_background" value="">
    </script>
    <script type="text/template" id="input-val-tpl">
        <input type="text" name="value" class="form-control m-input" id="value" placeholder="{{__('Nhập giá trị')}}...">
    </script>
    <script type="text/template" id="input-time-tpl">
        <input type="text" id="value" name="value" class="form-control m-input" readonly
               placeholder="{{__('Nhập giá trị')}}">
    </script>
    <script src="{{asset('static/backend/js/notification/config-staff/script.js')}}"
            type="text/javascript"></script>
    <script>
        index._init();
    </script>
@stop
