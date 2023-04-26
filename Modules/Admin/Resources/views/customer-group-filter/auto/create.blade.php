@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ CHI NHÁNH')}}</span>
@stop
@section('content')
    <style>
        .bdr {
            border-right: 1px dashed #e0e0e0 !important;
        }

        .padding-left-0 {
            padding-left: 0px;
        }

        .padding-right-0 {
            padding-right: 0px;
        }

        .button__addd--address button {
            background-color: #fff !important;
        }

        .button__addd--address button span {
            color: black;
        }

        .button__addd--address_b button {
            background-color: #fff !important;
        }

        .button__addd--address_b button span {
            color: black;
        }

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('THÊM NHÓM KHÁCH HÀNG ĐỘNG')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="table-content">
                <div class="form-group row m--margin-bottom-5">
                    <div class="col-lg-2 padding-left-0">
                        <div class="form-group">
                            <label class="col-xl-12 col-lg-12 col-form-label">
                                {{__('Tên nhóm khách hàng')}}
                                <span class="color_red"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-lg-6 padding-left-0">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="">
                            <span class="text-danger error-name"></span>
                        </div>
                    </div>
                </div>
                <div class="row A">
                    <div class="col-lg-12 padding-left-0">
                        <div class="m-portlet__body" id="autotable">
                            <div class="table-content">
                                <div class="form-group row">
                                    <label class="m--margin-right-10">
                                        {{__('Bao gồm những người đáp ứng')}}
                                    </label>
                                    <select name="A-or-and" id="A-or-and"
                                            class="form-control ss--select-2 ss-width-100pt"
                                            style="width: 20%">
                                        <option value="or">
                                            {{__('Bất kỳ')}}
                                        </option>
                                        <option value="and">
                                            {{__('Bao gồm')}}
                                        </option>
                                    </select>
                                    <label class="m--margin-left-10">
                                        {{__('điều kiện sau')}}
                                    </label>
                                </div>
                                <div class="form-group div-condition-A">

                                </div>

                                <div class="form-group row">
                                    <div class="col-lg-6 padding-left-0">
                                        <button onclick="userGroupAuto.addConditionA()"
                                                class="btn btn-primary m-btn m-btn--custom m-btn--icon color_button btn-add-condition-A">
															<span>
																<i class="fa fa-plus"></i>
																<span>{{__('Thêm điều kiện')}}</span>
															</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- end table-content -->

                        </div>
                    </div>
                </div>
                <div class="row B">
                    <div class="col-lg-12 padding-left-0">
                        <div class="m-portlet__body" id="autotable">
                            <div class="table-content">
                                <div class="form-group row">
                                    <label class="m--margin-right-10">
                                        {{__('Loại bỏ những người đáp ứng')}}
                                    </label>
                                    <select name="B-or-and" id="B-or-and"
                                            class="form-control ss--select-2 ss-width-100pt"
                                            style="width: 20%">
                                        <option value="or">
                                            {{__('Bất kỳ')}}
                                        </option>
                                        <option value="and">
                                            {{__('Bao gồm')}}
                                        </option>
                                    </select>
                                    <label class="m--margin-left-10">
                                        {{__('điều kiện sau')}}
                                    </label>
                                </div>
                                <div class="form-group div-condition-B">

                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-6 padding-left-0">
                                        <button onclick="userGroupAuto.addConditionB()"
                                                class="btn btn-primary m-btn m-btn--custom m-btn--icon color_button btn-add-condition-B">
															<span>
																<i class="fa fa-plus"></i>
																<span>{{__('Thêm điều kiện')}}</span>
															</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- end table-content -->

                        </div>
                    </div>
                </div>
            </div>
            <!-- end table-content -->
        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    @if (!empty($idSurvey))
                            <a href="{{ route('survey.edit-branch', ['id' => $idSurvey]) }}"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            @else
                                <a href="{{ route('admin.customer-group-filter') }}"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                        @endif
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                        </a>
                    <button type="button" onclick="userGroupAuto.save(0)"
                            class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                    </button>
                    <button type="button" onclick="userGroupAuto.save(1)"
                            class="btn  btn-success color_button son-mb
                                    m-btn m-btn--icon m-btn--wide m-btn--md btn-add m--margin-left-10">
							<span>
							<i class="fa fa-plus-circle"></i>
							<span>{{__('LƯU')}} &amp; {{__('TẠO MỚI')}}</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('after_script')
    <script>
        var ROUTE = '{{$route}}'
        var ID_SURVEY = '{{$idSurvey}}'
    </script>
    <script type="text/template" id="choose-condition-A">
        <div class="form-group row A-condition-1 div-A-1-condition">
            <div class="col-lg-4" style="padding-left: 0px">
                <select name="" id="" onchange="userGroupAuto.chooseConditionA(this)"
                        class="form-control ss--select-2 condition-A" style="width: 100%">
                    <option value="">
                        {{__('Chọn điều kiện')}}
                    </option>
                    {option}
                </select>
            </div>
            <div class="col-lg-6 div-content-condition">

            </div>
            <div class="col-lg-2">
                <button style="float: right;" type="button" onclick="userGroupAuto.removeConditionA(this)"
                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                    <i class="la la-close"></i>
                </button>
            </div>
            <div class="col-lg-12 div-content-condition-address">
                <div class="list__address">

                </div>
                <div class="button__addd--address mt-3 mb-3">
                    <button style="display:none" onclick="userGroupAuto.addItemAddress(this)"
                            class="btn btn-primary m-btn m-btn--custom m-btn--icon color_button btn-add-condition-B">
															<span>
																<i class="fa fa-plus"></i>
																<span>{{__('Thêm vị trí địa lý')}}</span>
															</span>
                    </button>
                </div>
                <div>

                </div>
    </script>
    <script type="text/template" id="choose-condition-B">
        <div class="form-group row B-condition-1 div-B-1-condition">
            <div class="col-lg-4" style="padding-left: 0px">
                <select name="" id="" onchange="userGroupAuto.chooseConditionB(this)"
                        class="form-control ss--select-2 condition-B" style="width: 100%">
                    <option value="">
                        {{__('Chọn điều kiện')}}
                    </option>
                    {option}
                </select>
            </div>
            <div class="col-lg-6 div-content-condition">

            </div>
            <div class="col-lg-2">
                <button style="float: right;" type="button" onclick="userGroupAuto.removeConditionB(this)"
                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                    <i class="la la-close"></i>
                </button>

            </div>
            <div class="col-lg-12 div-content-condition-address">
                <div class="list__address">

                </div>
                <div class="button__addd--address_b mt-3 mb-3">
                    <button style="display:none" onclick="userGroupAuto.addItemAddressB(this)"
                            class="btn btn-primary m-btn m-btn--custom m-btn--icon color_button btn-add-condition-B">
															<span>
																<i class="fa fa-plus"></i>
																<span>{{__('Thêm vị trí địa lý')}}</span>
															</span>
                    </button>
                </div>
                <div>
                </div>
    </script>
    <script type="text/template" id="tpl-customer-group-define">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" style="width: 100%">
            <option value="">
                {{__('Chọn nhóm')}}
            </option>
            @foreach($customerGroupDefine as $item)
                <option value="{{$item['id']}}">
                    {{$item['name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-day-appointment">
        <input type="text" class="form-control inputmask chooses-condition-A" value="30"
               placeholder="{{__('Nhập số ngày')}}" title="{{__('Số ngày')}}">
    </script>
    <script type="text/template" id="tpl-status_appointment">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" style="width: 100%">
            <option value="">
                {{__('Chọn trạng thái')}}
            </option>
            <option value="new">
                {{__('Mới')}}
            </option>
            <option value="confirm">
                {{__('Xác nhận')}}
            </option>
            <option value="cancel">
                {{__('Hủy')}}
            </option>
            <option value="finish">
                {{__('Hoàn thành')}}
            </option>
            <option value="wait">
                {{__('Chờ phục vụ')}}
            </option>

        </select>
    </script>
    <script type="text/template" id="tpl-time_appointment">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" style="width: 100%">
            <option value="">
                {{__('Chọn thời gian')}}
            </option>
            <option value="morning">
                {{__('Sáng (07h - 12h)')}}
            </option>
            <option value="noon">
                {{__('Trưa (12h - 14h)')}}
            </option>
            <option value="afternoon">
                {{__('Chiều (14h - 18h)')}}
            </option>
            <option value="evening">
                {{__('Tối (18h - 22h)')}}
            </option>
        </select>
    </script>
    <script type="text/template" id="tpl-not_appointment">
        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
            <label style="margin: 0 0 0 10px; padding-top: 4px">
                <input type="checkbox" checked class="manager-btn chooses-condition-A" disabled>
                <span></span>
            </label>
        </span>
    </script>
    <script type="text/template" id="tpl-use_service">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($listService as $item)
                <option value="{{$item['service_id']}}">
                    {{$item['service_name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-use_product">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($listProduct as $item)
                <option value="{{$item['product_child_id']}}">
                    {{$item['product_child_name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-range_point_a">
        <div class="row">
            <input type="hidden" id="range_point_value_a" class="form-control inputmask chooses-condition-A"
                   value="0,499">
            <div class="col-3">
                <input type="text" id="range_point_from_a" class="form-control inputmask"
                       onchange="userGroupAuto.setRangepointA();" value="0" placeholder="{{__('Từ')}}"
                       title="{{__('Từ')}}">
            </div>
            -
            <div class="col-3">
                <input type="text" id="range_point_to_a" class="form-control inputmask"
                       onchange="userGroupAuto.setRangepointA();" value="499" placeholder="{{__('Đến')}}"
                       title="{{__('Đến')}}">
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-range_point_b">
        <div class="row">
            <input type="hidden" id="range_point_value_b" class="form-control inputmask chooses-condition-A"
                   value="0,499">
            <div class="col-3">
                <input type="text" id="range_point_from_b" class="form-control inputmask"
                       onchange="userGroupAuto.setRangepointB();" value="0" placeholder="{{__('Từ')}}"
                       title="{{__('Từ')}}">
            </div>
            -
            <div class="col-3">
                <input type="text" id="range_point_to_b" class="form-control inputmask"
                       onchange="userGroupAuto.setRangepointB();" value="499" placeholder="{{__('Đến')}}"
                       title="{{__('Đến')}}">
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-is_rank">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($listRank as $item)
                <option value="{{$item['member_level_id']}}">
                    {{$item['name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-use_service_card">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($listServiceCard as $item)
                <option value="{{$item['service_card_id']}}">
                    {{$item['name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-type_customer">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            <option value="personal">
                {{__('Cá nhân')}}
            </option>
            <option value="bussiness">
                {{__('Doanh nghiệp')}}
            </option>

        </select>
    </script>
    <script type="text/template" id="tpl-group_customer">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($listGroupCustomer as $item)
                <option value="{{$item['customer_group_id']}}">
                    {{$item['group_name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-source_customer">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($listSourceCustomer as $item)
                <option value="{{$item['customer_source_id']}}">
                    {{$item['customer_source_name']}}
                </option>
            @endforeach
        </select>
    </script>
    <script type="text/template" id="tpl-address">
        <div class="row mt-3 mb-3">
            <div class=" col-10 item__address d-flex" style="gap:50px">
                <select type="text" name="province_main" onchange="getAddress.getDistrict(this)"
                        class="form-control ss--width-100 ss-select2 province_id" style="width: 100%">
                    <option value="">@lang('Tỉnh thành phố')</option>
                    @foreach ($listProvinces as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <select type="text" name="district" onchange="getAddress.getWard(this)"
                        class="form-control ss--width-100 ss-select2 district_id" style="width: 100%">
                    <option value="">@lang('Quận/Huyện')</option>
                </select>
                <select type="text" name="ward_main"
                        class="form-control ss--width-100 ss-select2 ward_main" style="width: 100%">
                    <option value="">@lang('Phường/xã')</option>
                </select>
                <button style="float: right;" type="button" onclick="userGroupAuto.removeItemAddress(this)"
                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                    <i class="la la-close"></i>
                </button>
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-address-b">
        <div class="row mt-3 mb-3">
            <div class=" col-10 item__address d-flex" style="gap:50px">
                <select type="text" name="province_main" onchange="getAddress.getDistrictB(this)"
                        class="form-control ss--width-100 ss-select2 province_id_b" style="width: 100%">
                    <option value="">@lang('Tỉnh thành phố')</option>
                    @foreach ($listProvinces as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                <select type="text" name="district" onchange="getAddress.getWardB(this)"
                        class="form-control ss--width-100 ss-select2 district_id_b" style="width: 100%">
                    <option value="">@lang('Quận/Huyện')</option>
                </select>
                <select type="text" name="ward_main"
                        class="form-control ss--width-100 ss-select2 ward_main_b" style="width: 100%">
                    <option value="">@lang('Phường/xã')</option>
                </select>
                <button style="float: right;" type="button" onclick="userGroupAuto.removeItemAddress(this)"
                        class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                    <i class="la la-close"></i>
                </button>
            </div>
        </div>
    </script>

    <script src="{{asset('static/backend/js/admin/user-group/add.js?v='.time())}}"
            type="text/javascript">
    </script>
    <script type="text/template" id="tpl-customer-group">
        <select name="" id=""
                class="form-control ss--select-2 chooses-condition-A" multiple="multiple" style="width: 100%">
            @foreach($customerGroupSystem as $item)
                <option value="{{$item['customer_group_id']}}">
                    {{$item['group_name']}}
                </option>
            @endforeach
        </select>
    </script>
@endsection
