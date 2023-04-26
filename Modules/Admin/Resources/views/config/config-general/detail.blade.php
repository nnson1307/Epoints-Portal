@extends('layout')
@section('title_header')
    <span class="title_header">QUẢN LÝ CẤU HÌNH CHUNG</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        CHI TIẾT CẤU HÌNH CHUNG
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        {{ csrf_field() }}
        @if($detail['config_id'] == 3)
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">Từ khóa hot</label>
                    <div class="col-10">
                        @if($arrHot)
                            @foreach($arrHot as $item)
                                <input type="text" disabled value="{{$item}}" class="form-control mb-2 w-50">
                            @endforeach
                        @else
                            <input type="text" disabled class="form-control mb-2 w-50">
                        @endif
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>HỦY</span>
                            </span>
                        </a>
{{--                        @if(in_array('admin.config.edit-config-general',session('routeList')))--}}
                            <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                            </a>
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        @elseif($detail['config_id'] == 4)
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">Tự động phân đơn hàng cho chi nhánh</label>
                    <div class="col-10">
                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label style="margin: 0 0 0 10px; padding-top: 4px">
                                <input type="checkbox"
                                       disabled
                                       {{ ($detail['value'] == 1) ? 'checked' : '' }} class="manager-btn">
                                <span></span>
                            </label>
                     </span>
                    </div>
                </div>
                @foreach($configDetail as $item)
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$item['name']}}</label>
                        <div class="col-4">
                            <input type="text" disabled class="form-control" value="{{$item['value']}}">
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>HỦY</span>
                            </span>
                        </a>
{{--                        @if(in_array('admin.config.edit-config-general',session('routeList')))--}}
                            <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                            </a>
{{--                        @endif--}}
                    </div>
                </div>
            </div>
        @elseif($detail['config_id'] == 10)
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">@lang('TimeZone')</label>
                    <div class="col-10 form-group">
                        <select class="form-control select-fix" name="value" disabled>
                            @foreach($zone as $key => $item)
                                <option value="{{$item['zone_name']}}" {{$item['zone_name'] == $detail['value'] ? 'selected' : ''}}>{{$item['zone_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>HỦY</span>
                            </span>
                        </a>
                        {{--                        @if(in_array('admin.config.edit-config-general',session('routeList')))--}}
                        <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                        </a>
                        {{--                        @endif--}}
                    </div>
                </div>
            </div>
        @elseif($detail['config_id'] == 11)
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">@lang('Mã vùng')</label>
                    <div class="col-10 form-group">
                        <select class="form-control select-fix" name="value" disabled>
                            @foreach($countryIso as $key => $item)
                                <option value="{{$item['country_iso']}}" {{$item['country_iso'] == $detail['value'] ? 'selected' : ''}}>{{$item['country_name']}}</option>
                            @endforeach
{{--                            <option value="+61">Australia</option>--}}
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>HỦY</span>
                            </span>
                        </a>
                        {{--                        @if(in_array('admin.config.edit-config-general',session('routeList')))--}}
                        <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                        </a>
                        {{--                        @endif--}}
                    </div>
                </div>
            </div>
        @elseif($detail['config_id'] == 13)
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">@lang('Ẩn thanh toán online')</label>
                    <div class="col-10 form-group">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" disabled name="value" id="is_payment_online" {{$detail['value'] == 1 ? 'checked' : ''}}>
                                    <span></span>
                                </label>
                            </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>HỦY</span>
                            </span>
                        </a>
                        {{--                        @if(in_array('admin.config.edit-config-general',session('routeList')))--}}
                        <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                        </a>
                        {{--                        @endif--}}
                    </div>
                </div>
            </div>
        @else
            <div class="m-portlet__body">
                <div class="form-group m-form__group row">
                    <label for="example-text-input" class="col-2 col-form-label">{{$detail['key']}}</label>
                    <div class="col-10">
                        <input type="text" disabled value="{{$detail['value']}}" class="form-control mb-2 w-50">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>HỦY</span>
                            </span>
                        </a>
                        {{--                        @if(in_array('admin.config.edit-config-general',session('routeList')))--}}
                        <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>CHỈNH SỬA</span>
                                </span>
                        </a>
                        {{--                        @endif--}}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script>
    </script>
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/config/script.js?v='.time()) }}"></script>
@stop


