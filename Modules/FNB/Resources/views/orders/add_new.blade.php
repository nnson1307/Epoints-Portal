@extends('layout')
@section('title_header')
    <span class="title_header">
        {{ __('Thêm đơn hàng') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/todh.css')}}">
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('Thêm đơn hàng') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <form method="get" id="" action="" style="display: none">
            </form>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="menu-bar">
                            <div class="areas">
                                <button type="button" class="btn btn-info ">
                                    {{ __('Khu vực bàn') }}
                                </button>
                            </div>
                            <div class="menu">
                                <button type="button" class="btn btn-info menu-button" style="color:black">{{ __('Thực đơn') }}</button>
                            </div>
                        </div>
                        <div class="info-areas">
                                <button type="button" class="btn btn-info areas-all">{{ __('Tất cả') }}</button>
                                <button type="button" class="btn btn-info areas-floor-1" style="color:black">{{ __('Lầu 1') }}</button>
                                <button type="button" class="btn btn-info areas-floor-2" style="color:black">{{ __('Lầu 2') }}</button>
                                <button type="button" class="btn btn-info areas-vip" style="color:black">{{ __('Phòng VIP') }}</button>
                        </div>
                        <div class="table-orders row">
                            <div class="col-sm">
                                <div class="info-table info-style-1">
                                    <h2 class="action-table" style="">
                                        <span class="la la-print print"></span>
                                        <span class="la la-bell bell"></span>
                                    </h2>
                                    <div class="contact">
                                        <span class="fa	fa-user-friends friends"></span>
                                        <span class="friends"> 2 </span>
                                    </div>
                                    <span class="table-name">
                                                {{ __('Bàn 01') }}
                                            </span>
                                    <div class="amount">
                                        <span class="la la-tag tag-price"></span>
                                        <span class="price"> 250,000đ</span>
                                    </div>
                                    <div class="time">
                                        <span>15:28 12/12/2022</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="info-table info-style-2">
                                    <div class="contact" style="padding-bottom: 20px;">
                                        <span class="fa	fa-user-friends friends"></span>
                                        <span class="friends"> 2 </span>
                                    </div>
                                    <span class="table-name">
                                                {{ __('Bàn 02') }}
                                            </span>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="info-table info-style-3">
                                    <h2 class="action-table" style="">
                                        <span class="fa fa-check-circle check-table"></span>
                                        <span class="la la-print print"></span>
                                    </h2>
                                    <div class="contact">
                                        <span class="fa	fa-user-friends friends"></span>
                                        <span class="friends"> 2 </span>
                                    </div>
                                    <span class="table-name">
                                                {{ __('Bàn 03') }}
                                            </span>
                                    <div class="amount">
                                        <span class="la la-tag tag-price"></span>
                                        <span class="price" style="color:darkcyan"> 250,000đ</span>
                                    </div>
                                    <div class="time">
                                        <span>15:28 12/12/2022</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="info-table info-style-4">
                                    <h2 class="action-table" style="">
                                        <span class="la la-ellipsis-h ellipsis"></span>
                                    </h2>
                                    <div class="contact">
                                        <span class="fa	fa-user-friends friends"></span>
                                        <span class="friends"> 2 </span>
                                    </div>
                                    <span class="table-name">
                                                {{ __('Bàn 04') }}
                                            </span>
                                    <div class="amount">
                                        <span class="la la-tag tag-price"></span>
                                        <span class="price" style="color:darkcyan"> 0đ</span>
                                    </div>
                                    <div class="time">
                                        <span>15:28 12/12/2022</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="info-table info-style-3">
                                    <h2 class="action-table" style="">
                                        <span class="la la-print print"></span>
                                    </h2>
                                    <div class="contact">
                                        <span class="fa	fa-user-friends friends"></span>
                                        <span class="friends"> 2 </span>
                                    </div>
                                    <span class="table-name">
                                                {{ __('Bàn 03') }}
                                            </span>
                                    <div class="amount">
                                        <span class="la la-tag tag-price"></span>
                                        <span class="price" style="color:red"> 250,000đ</span>
                                    </div>
                                    <div class="time">
                                        <span>15:28 12/12/2022</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-orders row">
                            <div class="menu-orders">
                                <div class="col-sm">
                                    <div class="info-menu menu-style">
                                        <div class="col-4 col-4-menu">
                                            <div class="images">
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTl-oBhoS4F8VwO5dTVlZ4_yt2_WgllN0MCxg&usqp=CAU" alt="">
                                            </div>
                                        </div>
                                        <div class="col-8" style="    padding-left: 20px;">
                                            <h4 class="prod-name">TRÀ SỮA TRÂN CHÂU ĐƯỜNG ĐEN</h4>
                                            <div class="descriptions">
                                                <span>Trà sữa siêu béo</span><br>
                                                <span>Trà sữa siêu ngọt</span><br>
                                                <span>Trân châu siêu dai</span>
                                            </div>
                                            <div class="action-tab">
                                                <div class="move-table">
                                                    <button type="button" class="btn btn-info add-prod" style="color:green"
                                                            onclick="order.addProd()">
                                                        {{ __('Thêm') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="info-menu menu-style">
                                        <div class="col-4 col-4-menu">
                                            <div class="images">
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSFhTnCxHQQMBFySkw9XokzYrSRI5I4--Azdw&usqp=CAU" alt="">
                                            </div>
                                        </div>
                                        <div class="col-8" style="    padding-left: 20px;">
                                            <h4 class="prod-name">TRÀ SỮA TRÂN CHÂU ĐƯỜNG ĐEN</h4>
                                            <div class="descriptions">
                                                <span>Trà sữa siêu béo</span><br>
                                                <span>Trà sữa siêu ngọt</span><br>
                                                <span>Trân châu siêu dai</span>
                                            </div>
                                            <div class="action-tab">
                                                <div class="move-table">
                                                    <button type="button" class="btn btn-info add-prod" style="color:green"
                                                            onclick="order.addProd()">
                                                        {{ __('Thêm') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-sm inf">
                        <div class="cus">
                            <div class="search">
                                <i class="fa 	fa-user"></i>
                                <input type="text" placeholder="{{__('Tìm kiếm khách hàng')}}">
                            </div>
                            <div class="tag__header">
                                <button type="button" class="btn btn-info">
                                    {{ __('DH123456789') }}
                                    <span class="la la-close"></span>
                                </button>
                                <button type="button" class="btn btn-info">
                                    {{ __('DH123456789') }}
                                    <span class="la la-close"></span>
                                </button>
                                <button type="button" class="btn btn-info">
                                    <span class="fa 	fa-plus"></span>
                                </button>

                            </div>
                            <div class="info-cus">
                                <a href="" class="circle">
                                    <img src="data:image/jpeg;base64,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"
                                         alt="Cloud Chen">
                                </a>
                                <div class="infomation">
                                    <div class="name">
                                        <a href="">{{__('LÊ QUANG PHÚ')}}</a>
                                        <span>{{__(' - PHONE')}}</span>
                                    </div>
                                    <div class="rank">
                                        <span>{{__('Hạng: Thành viên')}}</span>
                                    </div>
                                    <div class="point">
                                        <span>{{__('Điểm thành viên: 1000')}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body" style="padding: 0px;padding-top:30px; width:950px">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table ss--header-table">
                                        <thead>
                                        <tr class="ss--nowrap">
                                            <th class="ss--font-size-th ss--text-center">#</th>
                                            <th class="ss--font-size-th ss--text-center">{{__('Tên')}}</th>
                                            <th class="ss--font-size-th  ss--text-center">{{__('Giá')}}</th>
                                            <th class="ss--font-size-th ss--text-center">{{__('Số lượng')}}</th>
                                            <th class="ss--font-size-th ss--text-center">{{__('Giảm giá')}}</th>
                                            <th class="ss--font-size-th ss--text-center">{{__('Thành tiền')}}</th>
                                            <th class="ss--font-size-th ss--text-center"></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="ss--font-size-13 ss--nowrap">
                                            <td class="ss--text-center">{{__('1')}}</td>
                                            <td class="ss--text-center" style="font-weight:bold">
                                                {{__('Trà đào')}}<br>
                                                <button onclick="order.note()">
                                                    <span class="fa fa-plus"></span>
                                                    <span>Món thêm</span>
                                                </button>
                                            </td>
                                            <td class="ss--text-center">{{__('30,000đ')}}</td>
                                            <td class="ss--text-center">
                                                <button onclick="order.plus()">
                                                    <span class="fa fa-minus "></span>
                                                </button>
                                                {{__('2')}}
                                                <button onclick="order.minus()">
                                                    <span class="fa fa-plus"></span>
                                                </button>
                                            </td>
                                            <td class="ss--text-center">{{__('10,000d')}}</td>
                                            <td class="ss--text-center">{{__('60,000d')}}</td>
                                            <td class="ss--text-center">
                                                <button class="del-prod">
                                                    <span class="la la-close" style="    font-weight: bold;padding: 2px;"></span>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="ss--font-size-13 ss--nowrap">
                                            <td class="ss--text-center">{{__('1')}}</td>
                                            <td class="ss--text-center" style="font-weight:bold">
                                                {{__('Trà đào')}}<br>
                                                <button onclick="order.note()">
                                                    <span class="fa fa-plus"></span>
                                                    <span>Món thêm</span>
                                                </button>
                                            </td>
                                            <td class="ss--text-center">{{__('30,000đ')}}</td>
                                            <td class="ss--text-center">{{__('2')}}</td>
                                            <td class="ss--text-center">{{__('10,000d')}}</td>
                                            <td class="ss--text-center">{{__('60,000d')}}</td>
                                            <td class="ss--text-center">
                                                <span class="la la-close"></span>
                                            </td>
                                        </tr>
                                        <tr class="ss--font-size-13 ss--nowrap">
                                            <td class="ss--text-center">{{__('1')}}</td>
                                            <td class="ss--text-center" style="font-weight:bold">
                                                {{__('Trà đào')}}<br>
                                                <button onclick="order.note()">
                                                    <span class="fa fa-plus"></span>
                                                    <span>Món thêm</span>
                                                </button>
                                            </td>
                                            <td class="ss--text-center">{{__('30,000đ')}}</td>
                                            <td class="ss--text-center">{{__('2')}}</td>
                                            <td class="ss--text-center">{{__('10,000d')}}</td>
                                            <td class="ss--text-center">{{__('60,000d')}}</td>
                                            <td class="ss--text-center">
                                                <span class="la la-close"></span>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="total">
                                <div class="staff">{{__('Nhân viên phục vụ: Nguyễn Ngọc Sơn')}}</div>
                                <div class="surcharge">{{__('Phụ thu:')}}</div>
                                <div class="discount-member">{{__('Chiết khấu thành viên:')}}</div>
                                <div class="discount">
                                    {{__('Giảm giá:')}}
                                    <button onclick="order.addCodeDiscount()">
                                        <span class="fa fa-plus"></span>
                                    </button>
                                </div>
                                <div class="total-money">{{__('Thành tiền:')}}</div>
                            </div>
                            <div class="action-tab">
                                <div class="move-table">
                                    <button type="button" class="btn btn-info" onclick="order.moveTable()">
                                        <i class="fa flaticon-refresh"></i>
                                        {{ __('Chuyển bàn') }}
                                    </button>
                                </div>
                                <div class="detached-table">
                                    <button type="button" class="btn btn-info">
                                        <i class="fa fa-angle-double-left"></i>
                                        <i class="fa fa-angle-double-right"></i>
                                        {{ __('Tách bàn') }}
                                    </button>
                                </div>
                                <div class="merge-table" onclick="order.mergeTable()">
                                    <button type="button" class="btn btn-info">
                                        <i class="fa fa-angle-double-right"></i>
                                        <i class="fa fa-angle-double-left"></i>
                                        {{ __('Gộp bàn') }}
                                    </button>
                                </div>
                                <div class="merge-bill" onclick="order.mergeBill()">
                                    <button type="button" class="btn btn-info">
                                        <i class="fa 	fa-money-bill-wave"></i>
                                        {{ __('Gộp Bill') }}
                                    </button>
                                </div>
                                <div class="waiter">
                                    <button type="button" class="btn btn-info" onclick="order.chooseWaiter()">
                                        <i class="fa fa-user-plus"></i>
                                        {{ __('Nhân viên phục vụ') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container act">
                <div class="action-orders">
                    <a href="#" class="btn btn-metal cancel">{{ __('HỦY') }}</a>
                    <a href="#" class="btn btn-success">{{ __('IN TẠM TÍNH') }}</a>
                    <a href="javascript:void(0)" class="btn btn-warning"
                       onclick="order.payment()">{{ __('THANH TOÁN') }}</a>
                    <a href="#" class="btn btn-primary">{{ __('TẠO ĐƠN HÀNG') }}</a>
                </div>
            </div>
            <div class="table-content table-content-font-a mt-3">
            </div>
            <!-- end table-content -->
        </div>
    </div>

    <div class="append-popup"></div>
{{--    @include('fnb::orders.popup.note')--}}
    @include('fnb::orders.popup.move-table')
    @include('fnb::orders.popup.merge-table')
    @include('fnb::orders.popup.merge-bill')
    @include('fnb::orders.popup.choose-waiter')
    @include('fnb::orders.popup.add-code-discount')
    @include('fnb::orders.popup.payment-order')

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/fnb/areas/script.js?v='.time())}}"></script>
    <script>
        areas._init();
    </script>
    <script>
        var order = {
            note: function () {
                $('#noteee').modal('show');
            },
            moveTable: function () {
                $('#move-table').modal('show');
            },
            mergeTable: function () {
                $('#merge-table').modal('show');
            },
            mergeBill: function () {
                $('#merge-bill').modal('show');
            },
            chooseWaiter: function () {
                $('#choose-waiter').modal('show');
            },
            addCodeDiscount: function () {
                $('#add-code-discount').modal('show');
            },
            payment: function () {
                $('#payment-order').modal('show');
            }
        }
    </script>

@stop

