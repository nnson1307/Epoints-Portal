@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('DANH SÁCH HÌNH THỨC THANH TOÁN')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('DANH SÁCH HÌNH THỨC THANH TOÁN')}}</span>
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('payment-method.create')}}"
                   class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM HÌNH THỨC THANH TOÁN')}}</span>
                        </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group row">
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên hoặc mã hình thức thanh toán')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 form-group">
                            <select class="form-control m-input select2" name="method_type">
                                <option value="" selected="selected">{{__('Chọn loại hình thức thanh toán')}}</option>
                                <option value="auto">{{__('Tự động')}}</option>
                                <option value="manual">{{__('Thủ công')}}</option>
                            </select>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary btn-search color_button">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
{{--                 @include('helpers.filter')--}}
            </form>
            <div class="table-content m--padding-top-15">
                @include('payment::payment-method.list')
            </div><!-- end table-content -->
        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/payment/payment-method/script.js?v='.time())}}" type="text/javascript"></script>
@stop
