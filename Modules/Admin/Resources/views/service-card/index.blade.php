@extends('layout')
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/service-card.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services-card.png')}}" alt="" style="height: 20px;">
        {{__('THẺ DỊCH VỤ')}}
    </span>
@endsection
@section('content')
    <style>
    .err-choose-card {
        color: red
    }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH THẺ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.service-card.create',session('routeList')))
                    <a class="btn m-btn--pill ss--button-cms-piospa pull-right btn_add_pc btn-sm"
                       href="{{route("admin.service-card.create")}}">
                        <i class="fa fa-plus-circle m--margin-right-5"></i>
                        {{__('THÊM THẺ DỊCH VỤ')}}
                    </a>
                    <a href="{{route('admin.service-card.create')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                    color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="ss--background m--margin-bottom-30">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="group_name">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên thẻ')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-4 form-group input-group">
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
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <button href="javascript:void(0)" onclick="filter()"
                                    class="btn ss--btn-search">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>{{__('Success')}} : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content list-service-card m--margin-top-30">
                @include('admin::service-card.list')
            </div>
        </div>
    </div>


@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service-card/service-card.js')}}"
            type="text/javascript"></script>
@stop

