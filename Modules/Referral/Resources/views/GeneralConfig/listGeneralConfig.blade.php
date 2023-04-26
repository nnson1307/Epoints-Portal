@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ REFERRAL')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            background-color: #4fc4cb;
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }

        .status-inactive {
            border: 1px solid red;
            color: white;
            background-color: red;
            border-radius: 10px;
        }

        .status-active {
            border: 1px solid green;
            color: white;
            background-color: green;
            border-radius: 10px;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('QUẢN LÝ REFERRAL')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            @include('referral::layouts.tab-header')
            <div class="m-portlet__head-tools">
                <h5 style="font-weight:bold">Lịch sử cấu hình chung</h5>
            </div>
            <div class="m-portlet__body" style="padding: 0px;padding-top: 12px;">
                <form class="frmFilter ss--background" action= {{route('referral.historyGeneralConfig')}}>
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="created_by">
                                        <option value="">Người tạo</option>
                                        @foreach($data['filter'] as $k=>$v)
                                            <option value="{{$v['staff_id']}}" {{isset($param['created_by']) && $param['created_by'] == $v['staff_id']? 'selected':''}}>{{$v['full_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control m-input daterange-picker"
                                                   style="background-color: #fff"
                                                   id="start"
                                                   name="start"
                                                   autocomplete="off" placeholder="@lang('Ngày tạo')">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control m-input daterange-picker"
                                                   style="background-color: #fff"
                                                   id="end"
                                                   name="end"
                                                   autocomplete="off" placeholder="@lang('Ngày áp dụng')">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3" style="flex: 0 0 24%;max-width: 25%;">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <select class="form-control select2" name="status">
                                        <option value="">Trạng thái</option>
                                        <option value="active" {{isset($param['status']) && $param['status'] == 'active'? 'selected':''}}>
                                            Đang hoạt động
                                        </option>
                                        <option value="inactive" {{isset($param['status']) && $param['status'] == 'inactive'? 'selected':''}}>
                                            Không hoạt động
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                        <div class="text-right">
                            <a href="{{route('referral.historyGeneralConfig')}}"
                               class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </a>
                            <button type="submit"
                                    class="btn color_button">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="table-content">
                <div class="m-portlet__body" style="padding: 0px;padding-top:12px">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--text-center">#</th>
                                <th>{{__('Nội dung cấu hình')}}</th>
                                <th class="ss--text-center">{{__('Ngày áp dụng từ')}}</th>
                                <th class="ss--text-center">{{__('Ngày áp dụng đến')}}</th>
                                <th class="ss--text-center">{{__('Người tạo')}}</th>
                                <th class="ss--text-center">{{__('Ngày tạo')}}</th>
                                <th class="ss--text-center">{{__('Trạng thái')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($data['table']) != 0)
                                @foreach($data['table'] as $k => $v)
                                    <tr class="ss--font-size-13 ss--nowrap">
                                        <td class="ss--text-center">{{isset($param['page']) ? ($param['page']-1)*10 + $k+1 : $k+1}}</td>
                                        <td>
                                            <a href="{{route('referral.generalConfig',['id'=> $v['referral_config_id']])}}"> {{$v['config_description']}} </a>
                                        </td>
                                        <td class="ss--text-center">
                                            <p>{{\Carbon\Carbon::createFromFormat('d/m/Y H:i',$v['start'])->format('d/m/Y')}}</p>
                                            <p>{{\Carbon\Carbon::createFromFormat('d/m/Y H:i',$v['start'])->format('H:i')}}</p>
                                        </td>
                                        <td class="ss--text-center">
                                            @if($v['end'] != null)
                                                <p>{{\Carbon\Carbon::createFromFormat('d/m/Y H:i',$v['end'])->format('d/m/Y')}}</p>
                                                <p>{{\Carbon\Carbon::createFromFormat('d/m/Y H:i',$v['end'])->format('H:i')}}</p>
                                            @else
                                                <p></p>
                                            @endif
                                        </td>
                                        <td class="ss--text-center">{{$v['staff_update']}}</td>

                                        <td class="ss--text-center">
                                            <p>{{\Carbon\Carbon::createFromFormat('d/m/Y H:i',$v['created_at'])->format('d/m/Y')}}</p>
                                            <p>{{\Carbon\Carbon::createFromFormat('d/m/Y H:i',$v['created_at'])->format('H:i')}}</p>
                                        </td>
                                        <td class="ss--text-center">
                                            @if($v['payment_cycle_status'] == 'active')
                                                <div class="status-active">
                                                    <span>Đang hoạt động</span>
                                                </div>
                                            @else
                                                <div class="status-inactive">
                                                    <span>Không hoạt động</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="ss--font-size-13 ss--nowrap">
                                    <td colspan="7">
                                        <div class="not_find"
                                             style="text-align: center;padding-top: 40px;padding-bottom: 40px;font-weight: bold;">
                                            <i class="la la-search-plus"> </i>
                                            <span>@lang('Chưa có dữ liệu')</span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                {{ $data['table']->appends($param)->links('helpers.paging-load') }}
            </div>
        </div>
    </div>
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        $('.select2').select2();
    </script>
    <script>
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];
            $("#start,#end").daterangepicker({
                autoUpdateInput: true,
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

            $("#start").val("{{isset($param['start']) ? $param['start']:''}}");
            $("#end").val("{{isset($param['end'])? $param['end']:''}}");
        });
    </script>

@stop
