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

        .form-control-feedback {
            color: red;
        }
    </style>
    @include('admin::customer.active-sv-card')
    <div class="m-portlet m-portlet--head-sm" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                         <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH KHÁCH HÀNG')}}
                    </h2>

                </div>
            </div>

            <div class="m-portlet__head-tools">
                <form action="{{route('admin.customer.export-all')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="search_export" name="search_export">
                    <input type="hidden" id="customer_group_id_export" name="customer_group_id_export">
                    <input type="hidden" id="created_at_export" name="created_at_export">

                    <button type="submit"
                            class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('EXPORT')}}
                                            </span>
                                        </span>
                    </button>
                </form>
                @if(in_array('admin.customer.import-excel', session()->get('routeList')))
                    <a href="javascript:void(0)" onclick="index.modal_file()"
                       class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Import file')}}
                                            </span>
                                        </span>
                    </a>
                @endif
                @if(in_array('admin.customer.add', session()->get('routeList')))
                    <a href="{{route('admin.customer.add')}}"
                       class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('THÊM KHÁCH HÀNG')}}</span>
                            </span>
                    </a>
                @endif
            </div>

        </div>
        <div class="m-portlet__body">
            <form class="frmFilter bg">
                <div class="padding_row">
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search"
                                               placeholder="{{__('Nhập thông tin tìm kiếm...')}}">
                                    </div>
                                </div>
                            </div>
                            @foreach ($FILTER as $name => $item)
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
                            <div class="form-group col-lg-3">
                                <div class="input-group m-input-group">
                                    <select class="form-control width-select" name="customer_refer_id"
                                            id="customer_refer_id" style="width: 100%">
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-lg-3">
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

                            <div class="form-group col-lg-3">
                                <select name="customers$province_id" id="province_id" class="form-control" onchange='addressCustomer.changeProvince()'
                                        style="width: 100%">
                                    <option></option>
                                    @foreach($optionProvince as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <select name="customers$district_id" id="district_id" onchange='addressCustomer.changeDistrict()'
                                        class="form-control district" style="width: 100%">
                                    <option></option>
                                </select>
                            </div>
                            <div class="form-group col-lg-3">
                                <select name="customers$ward_id" id="ward_id"
                                        class="form-control ward_id" style="width: 100%">
                                    <option></option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group m-form__group">
                                    <button class="btn btn-primary color_button btn-search"
                                            onclick="customer.searchList()">
                                        {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible">
                        <strong>{{__('Success')}} : </strong> {!! session('status') !!}.
                    </div>
                @endif
            </form>
            <div class="table-content m--padding-top-30">
                @include('admin::customer.list')

            </div><!-- end table-content -->

        </div>
    </div>
    @include('admin::customer.pop.modal-excel')
    <div id="show-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/customer/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/customer/import-excel.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $(".m_selectpicker").selectpicker();
    </script>
    <script type="text/template" id="tb-card-tpl">
        <tr class="tr-card">
            <td>
                {code}
                <input type="hidden" name="code" value="{code}">
            </td>
            <td>
                {name_code}
            </td>
            <td>
                {day_active}
                <input type="hidden" name="day_active" value="{day_active}">
            </td>
            <td>
                {day_expiration}
                <input type="hidden" name="day_active" value="{day_expiration}">
            </td>
            <td>
                {name_type}
                <input type="hidden" name="type" value="{type}">
            </td>
            <td>
                {price_td}
                <input type="hidden" name="price" value="{price}">
            </td>
            <td>
                <input type="hidden" name="number_using" value="{number_using}">
                <input type="hidden" name="service_card_id" value="{service_card_id}">
                <input type="hidden" name="service_card_list_id" value="{service_card_list_id}">
                <a style="margin-top: -5px;"
                   class='remove m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill'><i
                            class='la la-trash'></i></a>
            </td>
        </tr>
    </script>
@stop