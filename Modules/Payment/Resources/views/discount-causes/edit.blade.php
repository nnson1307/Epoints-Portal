@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ LÝ DO GIẢM GIÁ')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA LÝ DO GIẢM GIÁ')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            {!! csrf_field() !!}
            <form id="formEdit">
                    <input type="text" class="form-control m-input"
                           name="discount_causes_id" value="{{$item["discount_causes_id"]}}" hidden>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên lý do giảm giá (Tiếng Việt)'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input"
                                       name="discount_causes_name_vi" value="{{$item["discount_causes_name_vi"]}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên lý do giảm giá (Tiếng Anh)'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control m-input"
                                       name="discount_causes_name_en" value="{{$item["discount_causes_name_en"]}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái'): <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label>
                                        @if($item["is_active"] == 1)
                                            <input type="checkbox" checked="" class="manager-btn" name="is_active" id="is_active">
                                        @else
                                            <input type="checkbox" class="manager-btn" name="is_active" id="is_active">
                                        @endif
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('discount-causes')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="discountCauses.save()"
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
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/payment/discount-causes/script.js?v='.time())}}" type="text/javascript"></script>
@endsection
