@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-sms.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ CHIẾN DỊCH ZNS') }}
    </span>
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
@endsection
@section('content')
    <!--begin::Portlet-->
    <form class="m-portlet" id="form-add">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('THÊM THỦ CÔNG') }}
                        @if(isset($item->zns_template_id))
                            <input type="hidden" name="zns_template_id" value="{{$item->zns_template_id}}">
                        @endif
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Loại mẫu') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <select name="type_template_follower" class="form-control">
                            @foreach($list_type_template_follower as $key => $value)
                                <option value="{{$key}}"{{ isset($params['type_template_follower']) && $params['type_template_follower'] == $key?' selected':'' }}>{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <label>
                        {{ __('Tên mẫu ZNS') }}: <b class="text-danger">*</b>
                    </label>
                </div>
                <div class="col-lg-9">
                    <div class="form-group m-form__group">
                        <input id="template_name" name="template_name" type="text" class="form-control"
                               placeholder="{{ __('Nhập tên template') }}" value="{{isset($item->template_name)?$item->template_name:''}}">
                    </div>
                </div>
            </div>
            <div id="template_type">
                @if(isset($params['type_template_follower']) && $params['type_template_follower'] == 0)
                    @include('zns::template.follower.include.type_1')
                @elseif(isset($params['type_template_follower']) && $params['type_template_follower'] == 1)
                    @include('zns::template.follower.include.type_2')
                @elseif(isset($params['type_template_follower']) && $params['type_template_follower'] == 2)
                    @include('zns::template.follower.include.type_3')
                @elseif(isset($params['type_template_follower']) && $params['type_template_follower'] == 3)
                    @include('zns::template.follower.include.type_4')
                @elseif(isset($params['type_template_follower']) && $params['type_template_follower'] == 4)
                    @include('zns::template.follower.include.type_5')
                @endif
            </div>
        </div>

        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                <div class="m-form__actions m-form__actions--solid m--align-right">
                    <a href="{{ route('zns.template-follower') }}"
                       class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                        <span class="ss--text-btn-mobi">
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </form>
    <!--end::Portlet-->
    @if(isset($params['type_template_follower']) && $params['type_template_follower'] == 3)
        @include('zns::template.follower.include.modal-file')
        <script type="text/template" id="tpl-file">
            <div class="form-group m-form__group div_file d-flex">
                <input type="hidden" name="file" value="{fileName}">
                <a target="_blank" href="{fileName}" class="file_ticket">
                    {fileName}
                </a>
                <a style="color:black;"
                   href="javascript:void(0)" onclick="follower.removeFile(this)">
                    <i class="la la-trash"></i>
                </a>
            </div>
        </script>
    @endif
    @if(isset($params['type_template_follower']) && $params['type_template_follower'] == 4)
        <script type="text/template" id="tpl-file">
            <div class="form-group m-form__group div_file d-flex">
                <input type="hidden" name="file" value="{fileName}">
                <a target="_blank" href="{fileName}" class="file_ticket">
                    {fileName}
                </a>
                <a style="color:black;"
                   href="javascript:void(0)" onclick="follower.removeFile(this)">
                    <i class="la la-trash"></i>
                </a>
            </div>
        </script>
    @endif
    <div class="modal fade" id="show-list-customer" role="dialog"></div>
    <div class="modal fade" id="confirm" role="dialog"></div>
    <div class="d-none" id="button-delete-customer">
        <button class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill remove-customer"
                title="Delete" data-value="1">
            <i class="la la-trash"></i>
        </button>
    </div>
    <div class="d-none" id="eye-link-preview">
        <a href="{link}" target="_blank" class="text-center text-primary">
            <i class="fa fa-eye"></i>
        </a>
    </div>
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
@endsection
@section('after_script')
    <script src="{{ asset('static/backend/js/zns/template/edit-follower.js?v=' . time()) }}"
            type="text/javascript">
    </script>
    <script>
        $('#form-add *').prop("disabled", true);
        follower.dropzoneFile();
    </script>
@stop
