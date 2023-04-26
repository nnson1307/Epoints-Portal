@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-calendar.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ LỊCH HẸN')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm" >
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH TRỄ HẸN')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                {{--<input type="hidden" name="search_type" value="name">--}}
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập thông tin tìm kiếm')}}">

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
                            <div class="col-lg-6 form-group input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item['text'] }}
                                                    </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input select2','title'=>__('Chọn trạng thái')]) !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="input-group" style="background-color: white">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly="" class="form-control m-input daterange-picker"
                                       id="created_at" name="created_at" autocomplete="off"
                                       placeholder="{{__('Ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="row">
                            <div class="col-lg-5 form-group">
                                <button class="btn btn-primary color_button btn-search">
                                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                            <div class="col-lg-5 form-group">
                                <a href="javascript:void(0)" onclick="list_late.refresh()"
                                   class="btn btn-info color_button  m-btn--icon height-40">
                                    {{__('LÀM MỚI')}} <i class="la la-refresh"></i>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>


            </form>
            <div class="table-content m--padding-top-30">
                @include('admin::customer-appointment.list-late.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/customer-appointment/list-late.js')}}" type="text/javascript"></script>
@stop
