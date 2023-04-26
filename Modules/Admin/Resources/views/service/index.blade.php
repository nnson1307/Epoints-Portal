@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ DỊCH VỤ')}}</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }


    </style>

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-open-box"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH DỊCH VỤ')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.service.add',session('routeList')))
                    <a href="{{route('admin.service.add')}}"
                       class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle icon-sz"></i>
							<span> {{__('THÊM DỊCH VỤ')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.service.add')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
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
                <div class="padding_row">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    {{--<button class="btn btn-primary btn-search" style="display: none">--}}
                                    {{--<i class="fa fa-search"></i>--}}
                                    {{--</button>--}}
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên hoặc mã dịch vụ')}}">
                                    {{--<div class="input-group-append">--}}
                                    {{--<a href="javascript:void(0)" onclick="service.refresh()"--}}
                                    {{--class="btn btn-primary m-btn--icon">--}}
                                    {{--<i class="la la-refresh"></i>--}}
                                    {{--</a>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                                    @if($name=='services$is_actived')
                                        {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker','title'=>'Chọn trạng thái']) !!}
                                    @endif
                                    @if($name=='services$service_category_id')
                                        {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker','title'=>'Chọn nhóm']) !!}
                                    @endif
                                </div>
                                @endforeach
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff"
                                               id="created_at"
                                               name="created_at"
                                               autocomplete="off" placeholder="{{__('Ngày tạo')}}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!--end: Search Form -->
            </form>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>{{__('Thông báo')}} : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-30">
                @include('admin::service.list')
            </div><!-- end table-content -->

        </div>
    </div>

@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/script.js?v='.time())}}" type="text/javascript"></script>
    {{--<script src="{{asset('static/backend/js/admin/staff/dropzone.js')}}" type="text/javascript"></script>--}}
    <script>
        $('#category_id_search').select2({
            placeholder: "Nhóm dịch vụ"
        });
        $('#branch_search').select2({
            placeholder: "{{__('Chi nhánh')}}"
        });
        $('#is_actived_search').select2({
            placeholder: "{{__('Trạng thái')}}"
        });
        $(".m_selectpicker").selectpicker();
    </script>

@stop

