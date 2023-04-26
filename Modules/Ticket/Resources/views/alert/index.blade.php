@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('CẤU HÌNH HỆ THỐNG CẢNH BÁO') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <style>
        .bg-line>.col:not(:last-child):before{
            content: '';
            width: 5px;
            height: 100%;
            background: #fff;
            position: absolute;
            right: 0px;
        }
        .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('CẤU HÌNH HỆ THỐNG CẢNH BÁO') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
       
        <form class="m-portlet__body" action="{{route('ticket.alert.edit')}}" method="GET" id="form-alert">
            <div class="m-portlet m-portlet--head-sm container">
                <nav class="mb-0 pt-4 ss--background pl-3">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true">
                            <h5>@lang('Ticket quá hạn')</h5>
                        </a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
                            aria-controls="nav-profile" aria-selected="false">
                            <h5>@lang('Ticket chưa phân công')</h5>
                        </a>
                    </div>
                </nav>
                <div class="tab-content ss--background" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="m-portlet__body">
                            <div id="autotable">
                                <div class="table-content m--padding-top-30">
                                    <div class="d-flex justify-content-between bg-line">
                                        <div class="col ss--background">
                                            <h3 class="m-portlet__head-text text-danger">
                                                {{ __('Cảnh báo mức 1') }}
                                            </h3>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Thời gian cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[0]['ticket_alert_id']}}[time]" class="form-control select2 select2-active">
                                                        <option value="">@lang('Chọn thời gian cảnh báo')</option>
                                                        @foreach ($timeWarning as $key => $value)
                                                            <option value="{{ $key }}"{{$list[0]['time'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Người nhận cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[0]['ticket_alert_id']}}[ticket_role_queue_id]" class="form-control select2 select2-active">
                                                        <option value="">{{ __('Chọn người nhận cảnh báo') }}</option>
                                                        @foreach ($roleQueue as $key => $value)
                                                            <option value="{{ $key }}"{{$list[0]['ticket_role_queue_id'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Template cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <textarea class="form-control m-input" name="{{$list[0]['ticket_alert_id']}}[template]" rows="5"
                                                cols="5" placeholder="{{__('Nhập template cảnh báo')}}...">{{$list[0]['template']}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua thông báo')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[0]['ticket_alert_id']}}[is_noti]"{{$list[0]['is_noti'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua email')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[0]['ticket_alert_id']}}[is_email]"{{$list[0]['is_email'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col ss--background">
                                            <h3 class="m-portlet__head-text text-danger">
                                                {{ __('Cảnh báo mức 2') }}
                                            </h3>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Thời gian cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[1]['ticket_alert_id']}}[time]" class="form-control select2 select2-active">
                                                        <option value="">@lang('Chọn thời gian cảnh báo')</option>
                                                        @foreach ($timeWarning as $key => $value)
                                                            <option value="{{ $key }}"{{$list[1]['time'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Người nhận cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[1]['ticket_alert_id']}}[ticket_role_queue_id]" class="form-control select2 select2-active">
                                                        <option value="">{{ __('Chọn người nhận cảnh báo') }}</option>
                                                        @foreach ($roleQueue as $key => $value)
                                                            <option value="{{ $key }}"{{$list[1]['ticket_role_queue_id'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Template cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <textarea class="form-control m-input" name="{{$list[1]['ticket_alert_id']}}[template]" rows="5"
                                                cols="5" placeholder="{{__('Nhập template cảnh báo')}}...">{{$list[1]['template']}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua thông báo')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[1]['ticket_alert_id']}}[is_noti]"{{$list[1]['is_noti'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua email')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[1]['ticket_alert_id']}}[is_email]"{{$list[1]['is_noti'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col ss--background">
                                            <h3 class="m-portlet__head-text text-danger">
                                                {{ __('Cảnh báo mức 3') }}
                                            </h3>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Thời gian cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[2]['ticket_alert_id']}}[time]" class="form-control select2 select2-active">
                                                        <option value="">@lang('Chọn thời gian cảnh báo')</option>
                                                        @foreach ($timeWarning as $key => $value)
                                                            <option value="{{ $key }}"{{$list[2]['time'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Người nhận cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[2]['ticket_alert_id']}}[ticket_role_queue_id]" class="form-control select2 select2-active">
                                                        <option value="">{{ __('Chọn người nhận cảnh báo') }}</option>
                                                        @foreach ($roleQueue as $key => $value)
                                                            <option value="{{ $key }}"{{$list[2]['ticket_role_queue_id'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Template cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <textarea class="form-control m-input" name="{{$list[2]['ticket_alert_id']}}[template]" rows="5"
                                                cols="5" placeholder="{{__('Nhập template cảnh báo')}}...">{{$list[2]['template']}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua thông báo')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[2]['ticket_alert_id']}}[is_noti]"{{$list[2]['is_noti'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua email')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[2]['ticket_alert_id']}}[is_email]"{{$list[2]['is_email'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="m-portlet__body">
                            <div id="autotable">
                                <div class="table-content m--padding-top-30">
                                    <div class="d-flex justify-content-between bg-line">
                                        <div class="col ss--background">
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Thời gian cảnh báo mức 1')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[3]['ticket_alert_id']}}[time]" class="form-control select2 select2-active">
                                                        <option value="">@lang('Thời gian cảnh báo mức 1')</option>
                                                        @foreach ($timeWarning as $key => $value)
                                                            <option value="{{ $key }}"{{$list[3]['time'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Thời gian cảnh báo mức 2')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[3]['ticket_alert_id']}}[time_2]" class="form-control select2 select2-active">
                                                        <option value="">@lang('Thời gian cảnh báo mức 2')</option>
                                                        @foreach ($timeWarning as $key => $value)
                                                            <option value="{{ $key }}"{{$list[3]['time_2'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Thời gian cảnh báo mức 3')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[3]['ticket_alert_id']}}[time_3]" class="form-control select2 select2-active">
                                                        <option value="">@lang('Thời gian cảnh báo mức 3')</option>
                                                        @foreach ($timeWarning as $key => $value)
                                                            <option value="{{ $key }}"{{$list[3]['time_3'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua thông báo')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[3]['ticket_alert_id']}}[is_noti]"{{$list[3]['is_noti'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group row">
                                                    <div class="col-lg-9 m--margin-top-5">
                                                        <i>{{__('Cảnh báo qua email')}}</i><b class="text-danger">&nbsp;*</b>
                                                    </div>
                                                    <div class="col-lg-3">
                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                            <label>
                                                                <input id="is-actived-edit" type="checkbox" class="manager-btn" name="{{$list[3]['ticket_alert_id']}}[is_email]"{{$list[3]['is_email'] == 1 ? ' checked' : ''}}>
                                                                <span></span>
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col ss--background">
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Người nhận cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <div class="input-group">
                                                    <select name="{{$list[3]['ticket_alert_id']}}[ticket_role_queue_id]" class="form-control select2 select2-active">
                                                        <option value="">{{ __('Chọn người nhận cảnh báo') }}</option>
                                                        @foreach ($roleQueue as $key => $value)
                                                            <option value="{{ $key }}"{{$list[3]['ticket_role_queue_id'] == $key ? ' selected' : ''}}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    {{__('Template cảnh báo')}}:<b class="text-danger">*</b>
                                                </label>
                                                <textarea class="form-control m-input" name="{{$list[3]['ticket_alert_id']}}[template]" rows="5"
                                                cols="5" placeholder="{{__('Nhập template cảnh báo')}}...">{{$list[3]['template']}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <button data-dismiss="modal"
                                class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                                </span>
                        </button>
        
                        <button type="submit"
                                class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                                    <span class="ss--text-btn-mobi">
                                    <i class="la la-check"></i>
                                    <span>{{__('LƯU THÔNG TIN')}}</span>
                                    </span>
                        </button>
                    </div>
                </div>
            </div>
        @csrf
        </form>
    </div>
@stop
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{ asset('static/backend/js/ticket/alert/list.js?v=' . time()) }}" type="text/javascript"></script>
    @if(Session::has('message'))
    <script>
        $(document).ready(function () {
            swal(
            "{{ Session::get('message') }}",
            '',
            "{{ Session::get('alert-class', 'warning') }}"
        );
        });
    </script>
    @endif
@stop
