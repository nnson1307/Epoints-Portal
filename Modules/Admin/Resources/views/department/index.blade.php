@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <style>
        .form-control-feedback {
            color: red;
        }
    </style>
    <div class="row" id="autotable">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                             <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('DANH SÁCH PHÒNG BAN')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        @if(in_array('admin.department.add',session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="Department.showPopupAdd()"
                               class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('THÊM PHÒNG BAN')}}</span>
                            </span>
                            </a>
                            <a href="javascript:void(0)"
                               onclick="Department.showPopupAdd()"
                               class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                               style="display: none">
                                <i class="fa fa-plus-circle" style="color: #fff"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <form class="frmFilter ss--background m--margin-bottom-30">
                        <div class="ss--bao-filter">
                            <div class="row">
                                <div class="col-lg-4 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="hidden" name="search_type" value="department_name">
                                            <button class="btn btn-primary btn-search" style="display: none">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <input type="text" class="form-control" name="search_keyword"
                                                   placeholder="{{__('Nhập tên phòng ban')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="row">
                                        @php $i = 0; @endphp
                                        @foreach ($FILTER as $name => $item)
                                            @if ($i > 0 && ($i % 4 == 0))
                                    </div>
                                    <div class="form-group m-form__group row align-items-center">
                                        @endif
                                        @php $i++; @endphp
                                        <div class="col-lg-12 input-group">
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
                                <div class="col-lg-4 form-group">
                                    <button onclick="Department.search()"
                                            class="btn ss--btn-search">
                                        {{__('TÌM KIẾM')}}
                                        <i class="fa fa-search ss--icon-search"></i>
                                    </button>
                                    <a href="{{route('admin.department')}}"
                                       class="btn btn-metal  btn-search padding9x">
                                        <span><i class="flaticon-refresh"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{--<div class="m-form m-form--label-align-right m--margin-bottom-20">--}}
                        {{--@include('helpers.filter')--}}
                        {{--</div>--}}
                    </form>
                    <!--end: Search Form -->

                    <div class="table-content">
                        @include('admin::department.list')
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

    <div id="my-modal"></div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/department/list.js?v='.time())}}" type="text/javascript"></script>
@stop