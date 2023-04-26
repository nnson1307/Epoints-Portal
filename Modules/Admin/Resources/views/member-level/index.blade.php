@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ KHÁCH HÀNG')}}</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/
    </style>
    <div class="m-portlet m-portlet--head-sm" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH CẤP ĐỘ THÀNH VIÊN')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.member-level.submitadd',session('routeList')))

                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="name">

                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên cấp độ')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="member_level.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
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
                        <button class="btn btn-primary btn-search color_button">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>
            </form>
            <!-- end table-content -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success!</strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-30">
                @include('admin::member-level.list')
            </div><!-- end table-content -->
        </div>
    </div>
    @include('admin::member-level.add')
    @include('admin::member-level.edit')
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/jquery.mask.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/member_level/script.js?v='.time())}}" type="text/javascript"></script>
@stop
