@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CẤU HÌNH')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-cogs"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CẤU HÌNH TỪ CHỐI ĐƠN HÀNG')
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="div_province">
                @if (count($rejectOrder) > 0)
                    @foreach($rejectOrder as $item)
                        <div class="div_object row">
                            <div class="form-group m-form__group col-lg-5">
                                <label class="black_title">
                                    @lang('Tỉnh/thành'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control m-input province_id" style="width: 100%"
                                            onchange="view.changeProvince(this)">
                                        <option></option>
                                        @foreach($optionProvince as $v)
                                            <option value="{{$v['provinceid']}}"
                                                    {{$item['province_id'] == $v['provinceid'] ? 'selected': ''}}>{{$v['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="error_province_id color_red"></span>
                            </div>
                            <div class="form-group m-form__group col-lg-5">
                                <label class="black_title">
                                    @lang('Quận/huyện'):<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select class="form-control m-input district_id" style="width: 100%" multiple>
                                        <option></option>
                                        @foreach($item['district'] as $v1)
                                            <option value="{{$v1['districtid']}}"
                                                {{in_array($v1['districtid'], $item['detail']) ? 'selected': ''}}>{{$v1['type']. ' '. $v1['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="error_district_id color_red"></span>
                            </div>
                            <div class="form-group m-form__group col-lg-2">
                                <label class="black_title"></label>
                                <div class="input-group">
                                    <a href="javascript:void(0)" onclick="view.removeObject(this)"
                                       class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                       title="@lang('Xoá')">
                                        <i class="la la-trash"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="div_object row">
                        <div class="form-group m-form__group col-lg-5">
                            <label class="black_title">
                                @lang('Tỉnh/thành'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control m-input province_id" style="width: 100%"
                                        onchange="view.changeProvince(this)">
                                    <option></option>
                                    @foreach($optionProvince as $v)
                                        <option value="{{$v['provinceid']}}">{{$v['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="error_province_id color_red"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-5">
                            <label class="black_title">
                                @lang('Quận/huyện'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control m-input district_id" style="width: 100%" multiple>
                                    <option></option>
                                </select>
                            </div>
                            <span class="error_district_id color_red"></span>
                        </div>
                        <div class="form-group m-form__group col-lg-2">
                            <label class="black_title"></label>
                            <div class="input-group">
                                <a href="javascript:void(0)" onclick="view.removeObject(this)"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Xoá')">
                                    <i class="la la-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="form-group">
                <a href="javascript:void(0)" onclick="view.addObject()"
                   class="btn  btn-sm m-btn--icon color">
                        <span>
                            <i class="la la-plus"></i>
                            <span> @lang('Thêm tỉnh thành')</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <button type="button" onclick="view.save()"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="my-modal"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/config/config-reject-order/script.js?v='.time())}}"
            type="text/javascript"></script>

    <script>
        view._init();
    </script>

    <script type="text/template" id="object-tpl">
        <div class="div_object row">
            <div class="form-group m-form__group col-lg-5">
                <label class="black_title">
                    @lang('Tỉnh/thành'):<b class="text-danger">*</b>
                </label>
                <div class="input-group">
                    <select class="form-control m-input province_id" style="width: 100%"
                            onchange="view.changeProvince(this)">
                        <option></option>
                        @foreach($optionProvince as $v)
                            <option value="{{$v['provinceid']}}">{{$v['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error_province_id color_red"></span>
            </div>
            <div class="form-group m-form__group col-lg-5">
                <label class="black_title">
                    @lang('Quận/huyện'):<b class="text-danger">*</b>
                </label>
                <div class="input-group">
                    <select class="form-control m-input district_id" style="width: 100%" multiple>
                        <option></option>
                    </select>
                </div>
                <span class="error_district_id color_red"></span>
            </div>
            <div class="form-group m-form__group col-lg-2">
                <label class="black_title"></label>
                <div class="input-group">
                    <a href="javascript:void(0)" onclick="view.removeObject(this)"
                       class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                       title="@lang('Xoá')">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </script>
@stop
