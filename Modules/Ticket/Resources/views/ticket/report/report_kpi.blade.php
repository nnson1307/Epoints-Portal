@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-order.png') }}" alt="" style="height: 20px;">
        @lang('BÁO CÁO KPI')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/phieu-custom.css') }}">
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        @lang("BÁO CÁO KPI")
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet m-portlet--head-sm">
                    <div class="m-portlet__head">
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-form m-form--label-align-right">
                            <div class="row">
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <div class="m-input-icon m-input-icon--right" id="m_daterangepicker_6">
                                        <input readonly="" class="form-control m-input daterange-picker" id="time"
                                            name="time" autocomplete="off" placeholder="{{ __('Chọn thời gian') }}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <select name="queue_process_id" style="width: 100%" class="form-control">
                                        <option value="">{{ __('Chọn queue') }}</option>
                                        @foreach ($queue as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <select name="staff_id" style="width: 100%" class="form-control">
                                        <option value="">{{ __('Chọn nhân viên') }}</option>
                                        @foreach ($staff as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 ss--col-xl-4 ss--col-lg-12 form-group">
                                    <select name="ticket_issue_group_id" style="width: 100%" class="form-control">
                                        <option value="">{{ __('Chọn loại yêu cầu') }}</option>
                                        @foreach ($requestGroup as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="m--margin-top-5" id="" style="width: 70%;margin:auto">
                                <div id="" style="width: 100%;margin:auto">
                                    <canvas id="canvas"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="table-report">
                            <div id="autotable"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">
@stop

@section('after_script')
    @include('ticket::language.lang')
    <script src="{{ asset('static/backend/js/admin/service/autoNumeric.min.js?v=' . time()) }}"></script>
    <script src="//www.amcharts.com/lib/3/amcharts.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/serial.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/radar.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/pie.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/tools/polarScatter/polarScatter.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/animate/animate.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/plugins/export/export.min.js" type="text/javascript"></script>
    <script src="//www.amcharts.com/lib/3/themes/light.js" type="text/javascript"></script>
    <style>
        .amcharts-chart-div>a {
            display: none !important;
        }

        .move-tab:hover {
            cursor: pointer;
        }

    </style>
    <script>
        $('#m_appointment').on('change', function(e) {
            // alert('dd')
        });
        $("#branch-revenue-report-tab").click(function() {
            $('body,html').animate({
                scrollTop: $("#branch-revenue-report").offset().top - 80
            }, 800);
        });
        $("#car-delivered-tab").click(function() {
            Appointments.tab_appointment();
            $('#car-delivered').trigger('click');
        });
        $("#car-paid-tab").click(function() {
            $('#car-paid').trigger('click');
        });
        $("#car-still-tab").click(function() {
            Services.tab_services();
            $('#car-still').trigger('click');
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist-plugin-legend/0.6.2/chartist-plugin-legend.min.js"
        integrity="sha512-J82gmCXFu+eMIvhK2cCa5dIiKYfjFY4AySzCCjG4EcnglcPQTST/nEtaf5X6egYs9vbbXpttR7W+wY3Uiy37UQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('static/backend/js/report/highcharts.js?v=' . time()) }}"></script>
    {{-- <script src="{{ asset('static/backend/js/ticket/ticket/report.js?v='.time()) }}" type="text/javascript"></script> --}}
    <script>
        $(document).ready(function() {
            ticket_kpi._init();
//            ticket_kpi.loadChart();
        });
        var ticket_kpi = {
            _init: function() {
                $.getJSON(laroute.route('translate'), function(json) {
                    var arrRange = {};
                    arrRange[json["Hôm nay"]] = [moment(), moment()];
                    arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1,
                        "days")];
                    arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
                    arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
                    arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
                    arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"),
                        moment().subtract(1, "month").endOf("month")
                    ];
                    $("#time").daterangepicker({
                        // autoUpdateInput: false,
                        autoApply: true,
                        // buttonClasses: "m-btn btn",
                        // applyClass: "btn-primary",
                        // cancelClass: "btn-danger",
                        maxDate: moment().endOf("day"),
                        startDate: moment().subtract(6, "days"),
                        endDate: moment(),
                        locale: {
                            cancelLabel: 'Clear',
                            format: 'DD/MM/YYYY',
                            "applyLabel": json["Đồng ý"],
                            "cancelLabel": json["Thoát"],
                            "customRangeLabel": json['Tùy chọn ngày'],
                            daysOfWeek: [
                                json["CN"],
                                json["T2"],
                                json["T3"],
                                json["T4"],
                                json["T5"],
                                json["T6"],
                                json["T7"]
                            ],
                            "monthNames": [
                                json["Tháng 1 năm"],
                                json["Tháng 2 năm"],
                                json["Tháng 3 năm"],
                                json["Tháng 4 năm"],
                                json["Tháng 5 năm"],
                                json["Tháng 6 năm"],
                                json["Tháng 7 năm"],
                                json["Tháng 8 năm"],
                                json["Tháng 9 năm"],
                                json["Tháng 10 năm"],
                                json["Tháng 11 năm"],
                                json["Tháng 12 năm"]
                            ],
                            "firstDay": 1
                        },
                        ranges: arrRange
                    }).on('apply.daterangepicker', function(event) {
                        window.myBar.destroy();
                        ticket_kpi.loadChart();
                    });
                    $('[name="ticket_issue_group_id"').select2().on('select2:select', function(event) {
                        window.myBar.destroy();
                        ticket_kpi.loadChart();
                    });
                    $('[name="staff_id"]').select2().on('select2:select', function(event) {
                        window.myBar.destroy();
                        ticket_kpi.loadChart();
                    });
                    $('[name="queue_process_id"]').select2().on('select2:select', function(event) {
                        window.myBar.destroy();
                        ticket_kpi.loadChart();
                    });
                    ticket_kpi.loadChart();
                });
            },
            loadChart: function() {
                $.ajax({
                    url: laroute.route('ticket.get-chart-kpi'),
                    method: 'POST',
                    dataType: 'JSON',
                    data: {
                        time: $('#time').val(),
                        staff_id: $('[name="staff_id"]').val(),
                        ticket_issue_group_id: $('[name="ticket_issue_group_id"').val(),
                        queue_process_id: $('[name="queue_process_id"]').val(),
                    },
                    success: function(res) {
                        parseChart(res.data);
                        $('.table-report').html(res.table);
                    }
                });
            },
        }

        function parseChart(data) {
            var barChartData = {
                labels: data.arrayCategories,
                datasets: [
                    {
                        label: "{{__('Ticket triển khai')}}",
                        yAxisID: 'bar-stack',
                        backgroundColor: "#5b9bd5",
                        borderColor: "#5b9bd5",
                        borderWidth: 1,
                        stack: 'bef',
                        data: data.enforce
                    },
                    {
                        label: "{{__('Ticket xử lý sự cố')}}",
                        yAxisID: "bar-stack",
                        backgroundColor: "#ed7d31",
                        borderColor: "#ed7d31",
                        borderWidth: 1,
                        stack: 'now',
                        data: data.issue
                    },
                    {
                        label: "{{__('Điểm đánh giá')}}",
                        yAxisID: "line",
                        backgroundColor: "#939393",
                        borderColor: "#939393",
                        type: 'line',
                        fill: false,
                        data: data.arrayLine,
                    },
                ]
            };

            var chartOptions = {
                responsive: true,
                scales: {
                    yAxes: [{
                            id: "bar-stack",
                            position: "left",
                            stacked: true,
                            ticks: {
                                beginAtZero: true
                            }
                        },

                        {
                            id: "line",
                            position: "right",
                            stacked: true,
                            ticks: {
                                beginAtZero: true
                            },
                            gridLines: {
                                drawOnChartArea: false,
                            },
                        }
                    ]
                }
            }
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: "bar",
                data: barChartData,
                options: chartOptions
            });
            console.log(window.myBar);
            window.myBar.update();
        }
        $(document).on('click', '.m-datatable__pager-link', function() {
            var data_page = parseInt($(this).attr('data-page'));
            if (!data_page) {
                return;
            }
            $.ajax({
                url: laroute.route('ticket.get-chart-kpi'),
                method: 'POST',
                dataType: 'JSON',
                data: {
                    page: data_page,
                    time: $('#time').val(),
                    staff_id: $('[name="staff_id"]').val(),
                    ticket_issue_group_id: $('[name="ticket_issue_group_id"').val(),
                    queue_process_id: $('[name="queue_process_id"]').val(),
                },
                success: function(res) {
                    $('.table-report').html(res.table)
                }
            });
        });
    </script>

@stop
