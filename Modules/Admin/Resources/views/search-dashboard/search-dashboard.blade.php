@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
       {{__('TÌM KIẾM')}}
    </span>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text ss--title">
                                <i class="la la-th-list ss--icon-title m--margin-right-5"></i>
                                {{__('KẾT QUẢ TÌM KIẾM')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--right m-tabs-line-danger"
                            role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#info" role="tab"
                                   aria-selected="false">
                                    {{__('KHÁCH HÀNG')}}
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#banner" role="tab"
                                   aria-selected="false">
                                    {{__('LỊCH HẸN')}}
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#time-work" role="tab"
                                   aria-selected="true">
                                    {{__('ĐƠN HÀNG')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="info" role="tabpanel">
                            <div class="ss--font-size-18 ss--text-black m--margin-bottom-20">
                                {{__('Đã tìm thấy')}} {{count($dataCustomer)}} {{__('khách hàng cho từ khóa')}} <b>{{$keyword}}</b>
                            </div>
                            <div class="table-content-customer">
                                @include('admin::search-dashboard.customer.list')
                            </div>
                        </div>
                        <div class="tab-pane " id="banner" role="tabpanel">
                            <div class="ss--font-size-18 ss--text-black m--margin-bottom-20">
                                {{__('Đã tìm thấy')}} {{count($dataCustomerAppointment)}} {{__('lịch hẹn cho từ khóa')}} <b>{{$keyword}}</b>
                            </div>
                            <div class="table-content-customer-appointment">
                                @include('admin::search-dashboard.customer-appointment.list')
                            </div>
                        </div>
                        <div class="tab-pane " id="time-work" role="tabpanel">
                            <div class="ss--font-size-18 ss--text-black m--margin-bottom-20">
                                {{__('Đã tìm thấy')}} {{count($dataOrder)}} {{__('đơn hàng cho từ khóa')}} <b>{{$keyword}}</b>
                            </div>
                            <div class="table-content-order">
                                @include('admin::search-dashboard.order.list')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <input type="hidden" id="keyword" value="{{$keyword}}">
@endsection

@section('after_script')
@endsection