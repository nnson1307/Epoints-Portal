<div class="row" id="m--star-dashbroad">
    <div class="col-lg-3 col-xs-6">
        <div id="car-paid-tab" class="m-portlet m--bg-success m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm tongtien move-tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text m--font-light" >
                            {{__('Đơn hàng')}} <small>{{__('Trong ngày')}}</small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-widget25">
                    <span class="m-widget25__price m--font-brand">{{$orders}}</span>
                    <span class="m-widget25__desc">{{__('Đơn hàng')}}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div id="car-delivered-tab" class="m-portlet m--bg-brand m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm dathanhtoan move-tab">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text m--font-light">
                            {{__('Lịch hẹn')}} <small>{{__('Trong ngày')}}</small>
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-widget25">
                    <span class="m-widget25__price m--font-brand">{{$appointment}}</span>
                    <span class="m-widget25__desc">{{__('Lịch hẹn')}}</span>
                </div>
            </div>
        </div>
    </div>
    @if (session()->get('brand_code') != 'giakhang')
        <div class="col-lg-3 col-xs-6">
            <div id="car-still-tab" class="m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan move-tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text m--font-light">
                                {{__('Khách hàng')}}<small>{{__('Trong ngày')}}</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <span class="m-widget25__price m--font-brand">{{$totalcustomerOnDay}}</span>
                        <span class="m-widget25__desc">{{__('Khách hàng')}}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div id="branch-revenue-report-tab" class="m-portlet m--bg-warning m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm sotienhuy move-tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text m--font-light">
                            {{__('Doanh thu')}} <small>{{__('Trong ngày')}}</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <span class="m-widget25__price m--font-brand">{{number_format($sumRevenueInDay, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                        <span class="m-widget25__desc">{{__(' đ')}}</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-lg-3 col-xs-6">
            <div id="car-still-tab" class="m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan move-tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text m--font-light">
                                {{__('Xe còn')}} <small>{{__('Trong ngày')}}</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <span class="m-widget25__price m--font-brand">{{$service['use']}}/{{$service['total']}}</span>
                        <span class="m-widget25__desc">{{__('Xe')}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-xs-6">
            <div id="branch-revenue-report-tab" class="m-portlet m--bg-warning m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm sotienhuy move-tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text m--font-light">
                                {{__('Doanh thu chi nhánh')}} <small>{{__('Trong ngày')}}</small>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <span class="m-widget25__price m--font-brand">{{number_format($sumRevenueInDay, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</span>
                        <span class="m-widget25__desc">{{__(' đ')}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>