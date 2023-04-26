@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ KHÁCH HÀNG TIỀM NĂNG')</span>
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
                        @lang('CHỈNH SỬA DANH MỤC PIPELINE')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <form id="form-edit">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('TÊN DANH MỤC PIPELINE'):<b class="text-danger">*</b>
                    </label>
                    <input type="text" class="form-control m-input" id="pipeline_category_name"
                           name="pipeline_category_name" value="{{$item['pipeline_category_name']}}">
                </div>
                <div class="form-group m-form__group">
                    <label>
                        @lang('Trạng thái'):
                    </label>
                    <div>
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input id="is_actived" name="is_actived" type="checkbox"
                                        {{$item['is_actived'] ==1 ? 'checked':''}}>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('customer-lead.pipeline-category')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                        </a>
                        <button type="button" onclick="edit.save({{$item['pipeline_category_id']}})"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
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
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/pipeline-category/script.js?v='.time())}}"
            type="text/javascript"></script>

{{--    <script>--}}
{{--        create._init();--}}
{{--    </script>--}}
@stop


