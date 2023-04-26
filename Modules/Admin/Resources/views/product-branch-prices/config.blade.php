@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-price.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ GIÁ')}}
    </span>
@endsection
@section('content')
    {{--<style>--}}
    {{--.form-control-feedback {--}}
    {{--color: #ff0000;--}}
    {{--}--}}

    {{--.dropzone img {--}}
    {{--border-radius: 10px;--}}
    {{--vertical-align: middle;--}}
    {{--width: 114px;--}}
    {{--height: 114px;--}}
    {{--}--}}
    {{--</style>--}}
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẤU HÌNH GIÁ SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools"></div>
        </div>
        <div class="m-portlet__body" id="autotableconfig">
            <div class="row">
                <div class="col-xl-12">
                    {!! Form::open(['route' => 'admin.product-branch-price.submit-edit', 'id' => 'formEdit']) !!}
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="col-form-label">
                                    {{__('Sản phẩm')}}:
                                </label>
                                {!! Form::select('product_id',["0" => __("Chọn sản phẩm")]+ $PRODUCT_LIST, null , ['class' => 'form-control m-input m-input--solid', 'id' => 'product_id']) !!}
                                <span class="text-danger error-product-config"></span>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="col-form-label">
                                    {{__('Chi nhánh cần cấu hình')}}:
                                </label>
                                {!! Form::select('branch_id', ["0" => __("Chọn chi nhánh")] + $BRANCH_LIST, null , ['class' => 'form-control m-input m-input--solid', 'id' => 'branch_id', 'disabled']) !!}
                                <span class="text-danger error-branch-config"></span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="col-form-label">
                                    {{__('Bảng giá cần sao chép')}}:
                                </label>
                                {!! Form::select('price', ["0" => __("Chọn chi nhánh")], null , ['class' => 'form-control m-input m-input--solid', 'id' => 'price', 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="table-content m--margin-top-10">
                @include('admin::product-branch-prices.list-branch-price')
            </div>
            {{--<div align="right">--}}
            {{--<button type="button" class="btn btn-primary" id="btnSubmitChange"><i class="la la-save"></i>Lưu lại--}}
            {{--</button>--}}
            {{--<a href="{{ route('admin.product-branch-price') }}" class="btn btn-danger">Hủy</a>--}}
            {{--</div>--}}
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{ route('admin.product-branch-price') }}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 ss--btn">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" id="btnSubmitChange"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/product/jquery.masknumber.js')}}" type="text/javascript"></script>

    <script src="{{ asset('static/backend/js/admin/product-branch-prices/script.js?v='.time())}}"></script>
    <script>
        $(document).ready(function () {
            $('#branch_id').select2();
            $('#price').select2();
            $('#product_id').select2({
                placeholder: '{{__('Chọn sản phẩm')}}',
            });
        });
    </script>
@stop
