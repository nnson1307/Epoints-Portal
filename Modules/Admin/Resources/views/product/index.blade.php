@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.product.import-template', session()->get('routeList')))
                    <a href="javascript:void(0)" onclick="product.modalImportProduct()"
                       class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="
la la-cloud-upload"></i>
                                            <span>
                                                {{__('Import file')}}
                                            </span>
                                        </span>
                    </a>
                @endif
                @if(in_array('admin.product.add',session('routeList')))
                    <a href="{{route('admin.product.add')}}"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle m--margin-right-5"></i>
							<span> {{__('THÊM SẢN PHẨM')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.product.add')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter bg">
                <div class="padding_row">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3 form-group input-group">
                                <input type="hidden" name="search_type" value="product_name">
                                        <button class="btn btn-primary btn-search" style="display: none">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" class="form-control" name="search_keyword"
                                               placeholder="{{__('Nhập tên sản phẩm')}}">
                            </div>
                            @foreach ($FILTER as $name => $item)
                            <div class="col-lg-3 form-group input-group">
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker ']) !!}
                            </div>
                            
                            @endforeach
                            <div class="col-lg-3 form-group input-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input onkeyup="product.notEnterInput(this)" type="text"
                                           class="form-control m-input daterange-picker" id="created_at"
                                           name="created_at"
                                           autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <a href="{{route('admin.product')}}" class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                    {{ __('XÓA BỘ LỌC') }}
                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                </a>
        
                                <button href="javascript:void(0)" onclick="product.search()"
                                        class="btn ss--btn-search">
                                    {{__('TÌM KIẾM')}}
                                    <i class="fa fa-search ss--icon-search"></i>
                                </button>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('admin::product.list')
            </div><!-- end table-content -->
        </div>
    </div>
    @include('admin::product.modal.excel-image')

    @include('admin::product.modal.modal-excel')

@endsection
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $(".m_selectpicker").selectpicker();
        $(document).ready(function() {
            $('.m_selectpicker').change(function () {
                product.search();
            })
        })
        // $.getJSON(laroute.route('translate'), function (json) {
        //     @if(session()->get('error-remove'))
        //     setTimeout(function () {
        //         toastr.error(json['Sản phẩm không tồn tại'])
        //     }, 60);
        //     @endif
        //     @if(session()->get('error-branch'))
        //     setTimeout(function () {
        //         toastr.error(json['Sản phẩm không thuộc chi nhánh này'])
        //     }, 60);
        //     @endif
        // });
    </script>
@stop
