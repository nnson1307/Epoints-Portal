@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ CẤU HÌNH CHUNG')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-eye"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHI TIẾT CẤU HÌNH CHUNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form method="POST" id="form-update">
            <div class="m-portlet__body">
                {{ csrf_field() }}
                @switch($detail['type'])
                    @case('text')
                    @case('date')
                    @case('time')
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$detail['name']}}</label>
                        <div class="col-10">
                            <input type="text" id="value" name="value" value="{{$detail['value']}}" disabled
                                   class="form-control mb-2 w-50">
                        </div>
                    </div>
                    @break
                    @case('image')
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
                    @break
                    @case('boolean')
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$detail['name']}}</label>
                        <div class="col-10">
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox" name="value" id="value" disabled
                                               {{ ($detail['value'] == 1) ? 'checked' : '' }} class="manager-btn"
                                               value="1">
                                        <span></span>
                                    </label>
                             </span>
                        </div>
                    </div>
                    @break
                    @case('ckeditor')
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$detail['name']}}</label>
                        <div class="col-10">
                            <div class="summernote">{!! $detail['value'] !!}</div>
                        </div>
                    </div>
                    @break
                    @case('option')
                    <div class="form-group m-form__group row">
                        <label for="example-text-input" class="col-2 col-form-label">{{$detail['name']}}</label>
                        <div class="col-10 form-group">
                            <select class="form-control select-fix" name="value" id="value" disabled>
                                @foreach($option as $key => $item)
                                    <option value="{{$key}}" {{$key == $detail['value'] ? 'selected' : ''}}>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @break
                @endswitch
                <div class="list-config-detail">
                    @foreach($configDetail as $item)
                        <div class="form-group m-form__group row">
                            <label for="example-text-input" class="col-2 col-form-label">{{__($item['name'])}}</label>
                            <div class="col-4 config-detail">
                                <input type="number" name="{{$item['key']}}" class="form-control input-number input-config-detail"
                                       {{$detail['value'] == 0 ? "disabled" :""}} value="{{$item['value']}}" disabled>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.config.config-general')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-arrow-left"></i>
                                    <span>@lang('TRỞ VỀ')</span>
                                </span>
                        </a>
                        <a href="{{route('admin.config.edit-config-general',['id' => $detail['config_id']])}}"
                           class="btn ss--button-cms-piospa bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                                <span>
                                    <i class="la la-edit"></i>
                                    <span>@lang('CHỈNH SỬA')</span>
                                </span>
                        </a>
                    </div>
                </div>
            </div>
            <input type="hidden" name="config_id" value="{{$detail['config_id']}}">
            <input type="hidden" id="config_type" name="config_type" value="{{$detail['type']}}">
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/javascript" src="{{ asset('static/backend/js/admin/config/script.js?v='.time()) }}"></script>
    <script>
        detail._init();
        $('.select-fix').select2();
    </script>
@stop


