@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ LOẠI PHIẾU CHI')</span>
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
                        @lang('CHỈNH SỬA LOẠI PHIẾU CHI')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-create">
                <div class="form-group m-form__group">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên loại phiếu chi (Tiếng Việt)'):<b class="text-danger"> *</b>
                        </label>
                        <input type="text" class="form-control m-input format-money"
                               value="{{$item['payment_type_name_vi'] != null ? $item['payment_type_name_vi'] : ''}}"
                               id="name_vi" name="name_vi" placeholder="@lang('Nhập tên loại phiếu chi')">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên loại phiếu chi (Tiếng Anh)'):<b class="text-danger"> *</b>
                        </label>
                        <input type="text" class="form-control m-input format-money"
                               value="{{$item['payment_type_name_en'] != null ? $item['payment_type_name_en'] : ''}}"
                               id="name_en" name="name_en" placeholder="@lang('Nhập tên loại phiếu chi')">
                    </div>
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        @lang('Kích hoạt'):<b class="text-danger">*</b>
                    </label>
                    <div>
                         <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                            <label>
                                <input type="checkbox" id="is_active" name="is_active"
                                       class="manager-btn" {{$item['is_active']==1?'checked':''}}>
                                <span></span>
                            </label>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('payment-type')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="edit.save('{{$item['payment_type_id'] != null ? $item['payment_type_id'] : ''}}')"
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
    <script src="{{asset('static/backend/js/payment/payment-type/script.js?v='.time())}}" type="text/javascript"></script>
@endsection