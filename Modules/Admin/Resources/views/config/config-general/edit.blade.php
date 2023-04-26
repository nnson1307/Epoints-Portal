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
                        @lang('CẬP NHẬT CẤU HÌNH CHUNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form method="POST" id="form-update">
            {{ csrf_field() }}
            @if($detail['config_id'] == 3)
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">@lang('Từ khóa hot')</label>
                        <div class="col-10">
                            <a href="javascript:void(0)" onclick="config.addKey()" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mb-2">
                                <span>
                                    <i class="fa fa-plus-circle p-1"></i>
                                    <span>@lang('Thêm từ khóa')</span>
                                </span>
                            </a>
                            <div class="list-keyhot">
                                @foreach($arrHot as $key => $item)
                                    <div id="key{{$key}}">
                                        <input type="text" value="{{$item}}" name="key[{{$key}}]" class="form-control mb-2 w-50 d-inline">
                                        <button type="button" onclick="config.removeKey({{$key}})"
                                                class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                title="Xóa"><i class="la la-trash"></i></button>
                                    </div>
                                @endforeach
                            </div>
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
                                    <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <a href="javascript:void(0)" onclick="config.updateKey()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CẬP NHẬT')</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" id="config_id" value="{{$detail['config_id']}}">
            @elseif($detail['config_id'] == 4)
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">@lang('Tự động phân đơn hàng cho chi nhánh')</label>
                        <div class="col-10">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" name="auto_apply_branch"
                                           {{ ($detail['value'] == 1) ? 'checked' : '' }} class="manager-btn" value="1">
                                    <span></span>
                                </label>
                         </span>
                        </div>
                    </div>
                    @foreach($configDetail as $item)
                        <div class="form-group m-form__group row">
                            <label for="example-text-input" class="col-2 col-form-label">{{__($item['name'])}}</label>
                            <div class="col-4">
                                <input type="number" name="{{$item['key']}}" class="form-control input-number" {{$detail['value'] == 0 ? "disabled" :""}} value="{{$item['value']}}">
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
                                    <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <a href="javascript:void(0)"  onclick="config.updateBrand()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CẬP NHẬT')</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" value="{{$detail['config_id']}}">
            @elseif(in_array($detail['config_id'], [7,8]))
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$detail['name']}}</label>
                        <div class="col-2">
                            <div class="form-group m-form__group m-widget19">
                                <div class="m-widget19__pic">
                                    <img class="m--bg-metal  m-image  img-sd" id="blah" height="150px"
                                         src="{{$detail['value'] != null ? $detail['value'] : "https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947"}}"
                                         alt="Hình ảnh"/>
                                </div>
                                <input type="hidden" id="logo_old" name="logo_old" value="{{$detail['value']}}">
                                <input type="hidden" id="logo" name="logo">
                                <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg"
                                       data-msg-accept="Hình ảnh không đúng định dạng"
                                       id="getFile" type='file'
                                       onchange="uploadAvatar(this);"
                                       class="form-control"
                                       style="display:none"/>
                                <div class="m-widget19__action" style="max-width: 170px">
                                    <a href="javascript:void(0)"
                                       onclick="document.getElementById('getFile').click()"
                                       class="btn  btn-sm m-btn--icon color w-100">
                                            <span class="m--margin-left-20">
                                                <i class="fa fa-camera"></i>
                                                <span>
                                                    @lang('Tải ảnh lên')
                                                </span>
                                            </span>
                                    </a>
                                </div>
                            </div>
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
                                    <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <a href="javascript:void(0)"  onclick="config.updateBrand()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CẬP NHẬT')</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" value="{{$detail['config_id']}}">
            @elseif($detail['config_id'] == 10)
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">@lang('TimeZone')</label>
                        <div class="col-10 form-group">
                            <select class="form-control select-fix" name="value">
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
                                <span>@lang('HỦY')</span>
                            </span>
                            </a>
                            <a href="javascript:void(0)" onclick="config.updateKey()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('CẬP NHẬT')</span>
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" id="config_id" value="{{$detail['config_id']}}">
            @elseif($detail['config_id'] == 11)
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">@lang('Mã vùng')</label>
                        <div class="col-10 form-group">
                            <select class="form-control select-fix" name="value">
                                @foreach($countryIso as $key => $item)
                                    <option value="{{$item['country_iso']}}" {{$item['country_iso'] == $detail['value'] ? 'selected' : ''}}>{{$item['country_name']}}</option>
                                @endforeach
{{--                                <option value="+61">Australia</option>--}}
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
                                <span>@lang('HỦY')</span>
                            </span>
                            </a>
                            <a href="javascript:void(0)" onclick="config.updateKey()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('CẬP NHẬT')</span>
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" id="config_id" value="{{$detail['config_id']}}">
            @elseif($detail['config_id'] == 13)
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">@lang('Ẩn thanh toán online')</label>
                        <div class="col-10 form-group">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" name="value" id="is_payment_online" {{$detail['value'] == 1 ? 'checked' : ''}}>
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
                                <span>@lang('HỦY')</span>
                            </span>
                            </a>
                            <a href="javascript:void(0)" onclick="config.updateKey()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('CẬP NHẬT')</span>
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" id="config_id" value="{{$detail['config_id']}}">
            @elseif($detail['config_id'] == 22)
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">@lang('Tính năng bán âm')</label>
                        <div class="col-10 form-group">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" name="value" id="is_minus" {{$detail['value'] == 1 ? 'checked' : ''}}>
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
                                <span>@lang('HỦY')</span>
                            </span>
                            </a>
                            <a href="javascript:void(0)" onclick="config.updateKey()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('CẬP NHẬT')</span>
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" id="config_id" value="{{$detail['config_id']}}">
            @else
                <div class="m-portlet__body">
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$detail['name']}}</label>
                        <div class="col-10">
                            <input type="text" name="value" value="{{$detail['value']}}" class="form-control mb-2 w-50">
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
                                    <span>@lang('HỦY')</span>
                                </span>
                            </a>
                            <a href="javascript:void(0)"  onclick="config.updateBrand()"
                               class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CẬP NHẬT')</span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="config_id" value="{{$detail['config_id']}}">
            @endif
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <style>
        .select2-container{
            width: 100% !important;
        }
    </style>
@stop
@section('after_script')
    <script>
        var sum = "{{count($arrHot)}}";
        $('.select-fix').select2();

    </script>
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/config/script.js?v='.time()) }}"></script>
@stop


