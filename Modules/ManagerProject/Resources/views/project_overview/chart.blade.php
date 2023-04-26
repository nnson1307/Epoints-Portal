<div class="pie-chart" style="display: flex">
    <div class="col-6" style="padding: 0;padding-right:2px">
        <div class="m-portlet" id="" style="{{$chartStatus != [] || $chartRisk != [] ? 'height:539px' :'height:140px'}}">
            <div class="m-portlet__body">
                <span class="chart-name">{{__('Tổng quan dự án theo Trạng thái')}}</span>
            </div>
            <div class="m-portlet__body">
                @if($chartStatus != [])
                    <figure class="highcharts-figure">
                        <div id="piechart-status"></div>
                    </figure>
                @else
                    <span>{{__('Chưa có thông tin')}}</span>
                @endif

            </div>
        </div>
    </div>
    <div class="col-6" style="padding: 0;padding-left:2px">
        <div class="m-portlet" id="" style="{{$chartStatus != [] || $chartRisk!= [] ? 'height:539px' :'height:140px;'}}">
            <div class="m-portlet__body">
                <span class="chart-name">{{__('Tổng quan dự án theo Mức độ rủi ro')}}</span>
            </div>
            <div class="m-portlet__body">
                @if($chartRisk!= [])
                    <figure class="highcharts-figure">
                        <div id="piechart-risk"></div>
                    </figure>
                @else
                    <span>{{__('Chưa có thông tin')}}</span>
                @endif

            </div>
        </div>
    </div>
</div>
<div class="bar-chart-1" style="display: flex">
    <div class="col-6" style="padding: 0;padding-right:2px">
        <div class="m-portlet" id="" style="{{$chartManager != [] || $chartDepartment != [] ? 'height:539px' :'height:140px;'}}">
            <div class="m-portlet__body">
                <span class="chart-name">{{__('Tổng quan dự án theo Người quản trị')}}</span>
            </div>
            <div class="m-portlet__body">
                @if($chartManager != [])
                    <figure class="highcharts-figure">
                        <div id="barchart-manager"></div>
                    </figure>
                @else
                    <span>{{__('Chưa có thông tin')}}</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-6" style="padding: 0;padding-left:2px">
        <div class="m-portlet" id="" style="{{$chartManager != [] || $chartDepartment != [] ? 'height:539px' :'height:140px;'}}">
            <div class="m-portlet__body">
                <span class="chart-name">{{__('Tổng quan dự án theo Phòng ban')}}</span>
            </div>
            <div class="m-portlet__body">
                @if($chartDepartment != [])
                    <figure class="highcharts-figure">
                        <div id="barchart-department"></div>
                    </figure>
                @else
                    <span>{{__('Chưa có thông tin')}}</span>
                @endif

            </div>
        </div>
    </div>
</div>
<div class="bar-chart-2" style="display: flex">
    <div class="col-6" style="padding: 0;padding-right:2px">
        <div class="m-portlet" id="">
            <div class="m-portlet__body">
                <span class="chart-name">{{__('Tổng quan dự án theo Ngân sách')}}</span>
            </div>
            <div class="m-portlet__body" style="{{$chartBudget != [] || $chartResource != [] ? 'height:470px' :'height:140px'}}">
                @if($chartBudget != [])
                    <figure class="highcharts-figure">
                        <div id="barchart-budget"></div>
                    </figure>
                @else
                    <span>{{__('Chưa có thông tin')}}</span>
                @endif

            </div>
        </div>
    </div>
    <div class="col-6" style="padding: 0;padding-left:2px">
        <div class="m-portlet" id="">
            <div class="m-portlet__body">
                <span class="chart-name">{{__('Tổng quan dự án theo Nguồn lực')}}</span>
            </div>
            <div class="m-portlet__body" style="{{$chartBudget != [] || $chartResource != [] ? 'height:470px' :'height:140px'}}">
                @if($chartResource != [])
                    <figure class="highcharts-figure">
                        <div id="barchart-resource"></div>
                    </figure>
                @else
                    <span>{{__('Chưa có thông tin')}}</span>
                @endif
            </div>
        </div>
    </div>
</div>