<div class="col-12" id="m-dashbroad">
    <div class="row" id="m--star-dashbroad">
        <div class="col-lg-4 col-xs-4">
            <div id="car-delivered-tab" class="tab-report-my-work m-portlet m--bg-brand m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm dathanhtoan move-tab">
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <p class="m-widget25__price m--font-brand mb-0">{{$totalWork['totalMyWorkProcessor']}}</p>
                        <p class="m-widget25__desc mb-0 text-uppercase"><strong>{{__('Công việc được giao cho tôi')}}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
            <div id="car-still-tab" class="tab-report-my-work m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan move-tab">
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <p class="m-widget25__price m--font-brand mb-0">{{$totalWork['totalMyWorkUnfinished']}}</p>
                        <p class="m-widget25__desc mb-0 text-uppercase"><strong>{{__('Công việc chưa hoàn thành')}}</strong></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-xs-4">
            <div id="car-still-tab" class="tab-report-my-work m-portlet m--bg-danger m-portlet--bordered-semi m-portlet--full-height  m-portlet--head-sm chuathanhtoan chuathanhtoanoverdue move-tab">
                <div class="m-portlet__body">
                    <div class="m-widget25">
                        <p class="m-widget25__price m--font-brand mb-0" style="color:red !important;">{{$totalWork['totalMyWorkOverDue']}}</p>
                        <p class="m-widget25__desc mb-0 text-uppercase" style="color:red !important;"><strong>{{__('Công việc quá hạn')}}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>