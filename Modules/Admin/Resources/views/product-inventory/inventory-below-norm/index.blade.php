@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ KHO')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH CẢNH BÁO TỒN KHO')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools"></div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập thông tin tìm kiếm')}}">
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary color_button btn-search" style="display: block">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                    <div class="padding_row row">
                        <div class="col-lg-12">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                        <span class="input-group-text">
                            {{ $item['text'] }}
                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>


                <div class="table-content m--padding-top-30">
                    @include('admin::product-inventory.inventory-below-norm.list')
                </div><!-- end table-content -->

            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link href="{{asset('static/booking-template')}}//assets/vendors/general/toastr/build/toastr.css" rel="stylesheet"
          type="text/css"/>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-inventory/list-bellow.js?v='.time())}}" type="text/javascript"></script>
@stop