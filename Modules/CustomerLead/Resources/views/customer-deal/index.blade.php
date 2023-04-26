@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-order.png')}}" alt=""
                style="height: 20px;"> @lang('QUẢN LÝ DEAL')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .select2 {
            width: 100% !important;
        }

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH DEAL")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
{{--                @if(in_array('customer-lead.customer-deal.assign', session('routeList')))--}}
                    <a href="{{route('customer-lead.customer-deal.assign')}}" class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                    <span>
                                        <span> @lang('PHÂN BỔ')</span>
                                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="listDeal.revoke()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                            <span>
                                                <span> @lang('THU HỒI')</span>
                                            </span>
                    </a>
{{--                @endif--}}
                @if(in_array('customer-lead.customer-deal.kanban-view', session('routeList')))
                    <a href="{{route('customer-lead.customer-deal.kanban-view')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                    <span>
                                        <i class="la la-eye"></i>
                                        <span> @lang('KAN BAN VIEW')</span>
                                    </span>
                    </a>
                @endif
                @if(in_array('customer-lead.customer-deal.create',session('routeList')))
                    <a href="javascript:void(0)" onclick="create.popupCreate(false)"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM DEAL')</span>
                                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="create.popupCreate(false)"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="@lang("Nhập mã deal, tên deal, người sở hữu hoặc khách hàng")">
                            </div>
                        </div>
                        <div class="col-lg-8">
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
                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff"
                                               id="created_at"
                                               name="created_at"
                                               autocomplete="off" placeholder="@lang('NGÀY TẠO')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>

                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control" style="width:100%;"
                                                id="pipeline"
                                                name="pipeline_code"
                                        >
                                            <option value="">@lang("Chọn pipeline")</option>
                                            @foreach($optionPipeline as $key => $value)
                                                <option value="{{$value['pipeline_code']}}">{{$value['pipeline_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control" style="width:100%;" id="journey" name="journey_code">
                                            <option value="">@lang("Chọn hành trình")</option>
                                        </select>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="m-input-icon m-input-icon--right">
                                <select class="form-control" style="width:100%;"
                                        id="order_source_id"
                                        name="order_source_id"
                                >
                                    <option value="">@lang("Chọn nguồn đơn hàng")</option>
                                    @foreach($optionOrderSource as $key => $value)
                                        <option value="{{$value['order_source_id']}}">{{$value['order_source_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control" style="width:100%;"
                                                id="branch"
                                                name="branch_code"
                                        >
                                            <option value="">@lang("Chọn chi nhánh")</option>
                                            @foreach($optionBranches as $key => $value)
                                                <option value="{{$value['branch_code']}}">{{$value['branch_name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff"
                                               id="closing_date"
                                               name="closing_date"
                                               autocomplete="off" placeholder="@lang('NGÀY KẾT THÚC DỰ KIẾN')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff"
                                               id="closing_due_date"
                                               name="closing_due_date"
                                               autocomplete="off" placeholder="@lang('NGÀY KẾT THÚC THỰC TẾ')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <div class="m-input-icon m-input-icon--right">
                                        <select class="form-control" style="width:200px !important;"
                                                id="compare"
                                                name="compare">
                                            <option value=">">@lang('Lớn hơn')</option>
                                            <option value="<">@lang('Bé hơn')</option>
                                            <option value="=">@lang('Bằng')</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="value" id="value"
                                       placeholder="@lang("Nhập giá trị deal")">
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('customer-lead::customer-deal.list')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-modal-create-lead"></div>
    <div id="my-modal-create-customer"></div>
    <div id="popup-work-edit"></div>
    <div id="vund_popup"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    
    <script src="{{asset('static/backend/js/customer-lead/customer-deal/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/work.js?v='.time())}}"
            type="text/javascript"></script>
   
    <script>
        listDeal._init();
        @if(isset($param['id']))
            listDeal.detail({{$param['id']}})
        @elseif(isset($param['object_type']) && isset($param['object_id']))
            create.popupCreate(false, '{{$param['object_type']}}', '{{$param['object_id']}}');
        @endif
        $(".m_selectpicker").selectpicker();

        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#created_at").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",

                // maxDate: moment().endOf("day"),
                // startDate: moment().startOf("day"),
                // endDate: moment().add(1, 'days'),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    // "applyLabel": "Đồng ý",
                    // "cancelLabel": "Thoát",
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
            });
            $("#closing_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",

                // maxDate: moment().endOf("day"),
                // startDate: moment().startOf("day"),
                // endDate: moment().add(1, 'days'),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    // "applyLabel": "Đồng ý",
                    // "cancelLabel": "Thoát",
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
            });
            $("#closing_due_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                // buttonClasses: "m-btn btn",
                // applyClass: "btn-primary",
                // cancelClass: "btn-danger",

                // maxDate: moment().endOf("day"),
                // startDate: moment().startOf("day"),
                // endDate: moment().add(1, 'days'),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    // "applyLabel": "Đồng ý",
                    // "cancelLabel": "Thoát",
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
            });
        });
        function loadJourney(e){
            e.preventDefault();

        }
    </script>

    
@stop
