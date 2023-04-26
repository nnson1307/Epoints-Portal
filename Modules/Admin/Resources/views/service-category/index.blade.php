@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ DỊCH VỤ')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

    </style>

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="la la-th-list"></i>
                        </span>
                        <h2 class="m-portlet__head-text">
                            {{__('DANH SÁCH NHÓM DỊCH VỤ')}}
                        </h2>

                    </div>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.service_category.submitAdd',session('routeList')))
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#add"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM NHÓM DỊCH VỤ')}}</span>
                        </span>
                    </a>
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#add"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>


        <div class="m-portlet__body" id="autotable">
            <!--begin: Search Form -->
            <form class="frmFilter bg">

                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="name">
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên nhóm dịch vụ')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-12 form-group input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item['text'] }}
                                                    </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker','title'=>'Chọn trạng thái']) !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>


                <!--end: Search Form -->
            </form>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-30">
                @include('admin::service-category.list')
            </div>
        </div>
    </div>
    <!--end::Portlet-->

    @include('admin::service-category.add')
    @include('admin::service-category.edit')
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-category/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $(".m_selectpicker").selectpicker();
    </script>
@stop
