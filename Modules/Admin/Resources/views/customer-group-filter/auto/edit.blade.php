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
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA NHÓM KHÁCH HÀNG ĐỘNG')}}
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
                            <input type="text" class="form-control" id="name"
                                   value="{{$customerGroup['name']}}" name="name" placeholder="">
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
                                            class="form-control ss--select-2 ss-width-100pt select-2"
                                            style="width: 20%">
                                        <option value="or" {{$customerGroup['filter_condition_rule_A'] == 'or' ? 'selected' : ''}}>
                                            {{__('Bất kỳ')}}
                                        </option>
                                        <option value="and" {{$customerGroup['filter_condition_rule_A'] == 'and' ? 'selected' : ''}}>
                                            {{__('Bao gồm')}}
                                        </option>
                                    </select>
                                    <label class="m--margin-left-10">
                                        {{__('điều kiện sau')}}
                                    </label>
                                </div>
                                <div class="form-group div-condition-A">
                                    @if(count($customerGroupDetail) > 0)
                                        @foreach($customerGroupDetail as $detail)
                                            @if($detail['group_type'] == 'A')
                                                <div class="form-group row A-condition-1 div-A-1-condition">
                                                    <div class="col-lg-4" style="padding-left: 0px">
                                                        <select name="" id="" disabled
                                                                class="form-control ss--select-2 condition-A select-2"
                                                                style="width: 100%">
                                                            @foreach($condition as $item)
                                                                @if($item['id'] == $detail['condition_id'])
                                                                    <option value="{{$item['id']}}">
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 div-content-condition">
                                                        @if($detail['condition_id'] == 1)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    style="width: 100%">
                                                                @foreach($customerGroupDefine as $item)
                                                                    <option value="{{$item['id']}}" {{$arrayConditionA[1] == $item['id'] ? 'selected' : ''}}>
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 2)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionA[2]}}"
                                                                   placeholder="{{__('Nhập số ngày')}}"
                                                                   title="{{__('Số ngày')}}">
                                                        @elseif($detail['condition_id'] == 3)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    style="width: 100%">
                                                                <option value="new" {{$arrayConditionA[3] == 'new' ? 'selected' : ''}}>
                                                                    Mới
                                                                </option>
                                                                <option value="confirm" {{$arrayConditionA[3] == 'confirm' ? 'selected' : ''}}>
                                                                    Xác nhận
                                                                </option>
                                                                <option value="cancel" {{$arrayConditionA[3] == 'cancel' ? 'selected' : ''}}>
                                                                    Hủy
                                                                </option>
                                                                <option value="finish" {{$arrayConditionA[3] == 'finish' ? 'selected' : ''}}>
                                                                    Hoàn thành
                                                                </option>
                                                                <option value="wait" {{$arrayConditionA[3] == 'wait' ? 'selected' : ''}}>
                                                                    Chờ phục vụ
                                                                </option>

                                                            </select>
                                                        @elseif($detail['condition_id'] == 4)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    style="width: 100%">
                                                                <option value="morning" {{$arrayConditionA[4] == 'morning' ? 'selected' : ''}}>
                                                                    Sáng (07h - 12h)
                                                                </option>
                                                                <option value="noon" {{$arrayConditionA[4] == 'noon' ? 'selected' : ''}}>
                                                                    Trưa (12h - 14h)
                                                                </option>
                                                                <option value="afternoon" {{$arrayConditionA[4] == 'afternoon' ? 'selected' : ''}}>
                                                                    Chiều (14h - 18h)
                                                                </option>
                                                                <option value="evening" {{$arrayConditionA[4] == 'evening' ? 'selected' : ''}}>
                                                                    Tối (18h - 22h)
                                                                </option>
                                                            </select>
                                                        @elseif($detail['condition_id'] == 5)
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox"
                                                                           {{$arrayConditionA[5] == 1 ? 'checked' : ''}}
                                                                           class="manager-btn chooses-condition-A"
                                                                           disabled>
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @elseif($detail['condition_id'] == 6)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listService as $item)
                                                                    <option value="{{$item['service_id']}}"
                                                                            {{in_array($item['service_id'], $arrayConditionA[6]) ? 'selected' : ''}}>
                                                                        {{$item['service_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 7)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listService as $item)
                                                                    <option value="{{$item['service_id']}}"
                                                                            {{in_array($item['service_id'], $arrayConditionA[7]) ? 'selected' : ''}}>
                                                                        {{$item['service_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 8)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listProduct as $item)
                                                                    <option value="{{$item['product_child_id']}}"
                                                                            {{in_array($item['product_child_id'], $arrayConditionA[8]) ? 'selected' : ''}}>
                                                                        {{$item['product_child_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 9)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listProduct as $item)
                                                                    <option value="{{$item['product_child_id']}}"
                                                                            {{in_array($item['product_child_id'], $arrayConditionA[9]) ? 'selected' : ''}}>
                                                                        {{$item['product_child_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 10)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionA[10]}}"
                                                                   placeholder="{{__('Nhập số ngày')}}"
                                                                   title="{{__('Số ngày')}}">
                                                        @elseif($detail['condition_id'] == 11)
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox"
                                                                           {{$arrayConditionA[11] == 1 ? 'checked' : ''}}
                                                                           class="manager-btn chooses-condition-A"
                                                                           disabled>
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @elseif($detail['condition_id'] == 12)
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox"
                                                                           {{$arrayConditionA[12] == 1 ? 'checked' : ''}}
                                                                           class="manager-btn chooses-condition-A"
                                                                           disabled>
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @elseif($detail['condition_id'] == 13)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listRank as $item)
                                                                    <option value="{{$item['member_level_id']}}"
                                                                            {{in_array($item['member_level_id'], $arrayConditionA[13]) ? 'selected' : ''}}>
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 14)
                                                            <div class="row">
                                                                <input type="hidden" id="range_point_value_a"
                                                                       class="form-control inputmask chooses-condition-A"
                                                                       value="{{$arrayConditionA[14][0].','.$arrayConditionA[14][1]}}">
                                                                <div class="col-3">
                                                                    <input type="text" id="range_point_from_a"
                                                                           class="form-control inputmask"
                                                                           onchange="userGroupAuto.setRangepointA();"
                                                                           value="{{$arrayConditionA[14][0]}}"
                                                                           placeholder="{{__('Từ')}}"
                                                                           title="{{__('Từ')}}">
                                                                </div>
                                                                -
                                                                <div class="col-3">
                                                                    <input type="text" id="range_point_to_a"
                                                                           class="form-control inputmask"
                                                                           onchange="userGroupAuto.setRangepointA();"
                                                                           value="{{$arrayConditionA[14][1]}}"
                                                                           placeholder="{{__('Đến')}}"
                                                                           title="{{__('Đến')}}">
                                                                </div>
                                                            </div>
                                                        @elseif($detail['condition_id'] == 15)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionA[15]}}"
                                                                   placeholder="{{__('Nhập số khách hàng')}}"
                                                                   title="{{__('Nhập số khách hàng')}}">
                                                        @elseif($detail['condition_id'] == 16)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionA[16]}}"
                                                                   placeholder="{{__('Nhập số khách hàng')}}"
                                                                   title="{{__('Nhập số khách hàng')}}">
                                                        @elseif($detail['condition_id'] == 17)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listServiceCard as $item)
                                                                    <option value="{{$item['service_card_id']}}"
                                                                            {{in_array($item['service_card_id'], $arrayConditionA[17]) ? 'selected' : ''}}>
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 19)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                <option value="personal" {{in_array('personal', $arrayConditionA[19]) ? 'selected' : ''}}>
                                                                    {{__('Cá nhân')}}
                                                                </option>
                                                                <option value="bussiness" {{in_array('bussiness', $arrayConditionA[19]) ? 'selected' : ''}}>
                                                                    {{__('Doanh nghiệp')}}
                                                                </option>
                                                            </select>
                                                        @elseif ($detail['condition_id'] == 21)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listSourceCustomer as $item)
                                                                    <option value="{{$item['customer_source_id']}}" {{in_array($item['customer_source_id'], $arrayConditionA[21]) ? 'selected' : ''}}>
                                                                        {{$item['customer_source_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif ($detail['condition_id'] == 20)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listGroupCustomer as $item)
                                                                    <option value="{{$item['customer_group_id']}}" {{in_array($item['customer_group_id'], $arrayConditionA[20]) ? 'selected' : ''}}>
                                                                        {{$item['group_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif ($detail['condition_id'] == 18)
                                                            @php
                                                                $province = isset($arrayConditionA[18]->province) ? $arrayConditionA[18]->province : '';
                                                                $district = isset($arrayConditionA[18]->district) ? $arrayConditionA[18]->district : '';
                                                            @endphp
                                                            <div class="" style="display:flex; gap:25px">
                                                                <select type="text" name="province_main"
                                                                        onchange="getAddress.getDistrict(this)"
                                                                        id="province_id"
                                                                        class="form-control ss--width-100 ss-select2"
                                                                        style="width: 100%">
                                                                    <option>@lang('Tỉnh thành phố')</option>
                                                                    @foreach ($listProvinces as $key => $value)
                                                                        <option value="{{ $key }}" {{ $province == $key ? ' selected="selected"' : '' }}>{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <select type="text" name="district" id="district_id"
                                                                        class="form-control ss--width-100 ss-select2 district"
                                                                        style="width: 100%">
                                                                    @if ($listDistrict != ''  &&  $listDistrict->count() > 0)
                                                                        @foreach($listDistrict as $item)
                                                                            <option value="{{ $item->districtid }}" {{$item->districtid == $district ? ' selected="selected"' : ''}}>{{  $item->type. ' ' .$item->name }}</option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="">@lang('Quận/Huyện')</option>
                                                                    @endif
                                                                </select>
                                                                <select type="text" name="ward_main" id="ward_id"
                                                                        class="form-control ss--width-100 ss-select2"
                                                                        style="width: 100%">
                                                                    <option value="">@lang('Phường/xã')</option>
                                                                </select>
                                                            </div>
                                                        @elseif($detail['condition_id'] == 22)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($customerGroupSystem as $item)
                                                                    <option value="{{$item['customer_group_id']}}" {{in_array($item['customer_group_id'], $arrayConditionA[22]) ? 'selected' : ''}}>
                                                                        {{$item['group_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button style="float: right;" type="button"
                                                                onclick="userGroupAuto.removeConditionA(this)"
                                                                class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                                                            <i class="la la-close"></i>
                                                        </button>

                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                                            class="form-control ss--select-2 ss-width-100pt select-2"
                                            style="width: 20%">
                                        <option value="or" {{$customerGroup['filter_condition_rule_B'] == 'or' ? 'selected' : ''}}>
                                            {{__('Bất kỳ')}}
                                        </option>
                                        <option value="and" {{$customerGroup['filter_condition_rule_B'] == 'and' ? 'selected' : ''}}>
                                            {{__('Bao gồm')}}
                                        </option>
                                    </select>
                                    <label class="m--margin-left-10">
                                        {{__('điều kiện sau')}}
                                    </label>
                                </div>
                                <div class="form-group div-condition-B">
                                    @if(count($customerGroupDetail) > 0)
                                        @foreach($customerGroupDetail as $detail)
                                            @if($detail['group_type'] == 'B')
                                                <div class="form-group row B-condition-1 div-B-1-condition">
                                                    <div class="col-lg-4" style="padding-left: 0px">
                                                        <select name="" id="" disabled
                                                                class="form-control ss--select-2 condition-B select-2"
                                                                style="width: 100%">
                                                            @foreach($condition as $item)
                                                                @if($item['id'] == $detail['condition_id'])
                                                                    <option value="{{$item['id']}}">
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6 div-content-condition">
                                                        @if($detail['condition_id'] == 1)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    style="width: 100%">
                                                                @foreach($customerGroupDefine as $item)
                                                                    <option value="{{$item['id']}}" {{$arrayConditionB[1] == $item['id'] ? 'selected' : ''}}>
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 2)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionB[2]}}"
                                                                   placeholder="{{__('Nhập số ngày')}}"
                                                                   title="{{__('Số ngày')}}">
                                                        @elseif($detail['condition_id'] == 3)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    style="width: 100%">
                                                                <option value="new" {{$arrayConditionB[3] == 'new' ? 'selected' : ''}}>
                                                                    {{__('Mới')}}
                                                                </option>
                                                                <option value="confirm" {{$arrayConditionB[3] == 'confirm' ? 'selected' : ''}}>
                                                                    {{__('Xác nhận')}}
                                                                </option>
                                                                <option value="cancel" {{$arrayConditionB[3] == 'cancel' ? 'selected' : ''}}>
                                                                    {{__('Hủy')}}
                                                                </option>
                                                                <option value="finish" {{$arrayConditionB[3] == 'finish' ? 'selected' : ''}}>
                                                                    {{__('Hoàn thành')}}
                                                                </option>
                                                                <option value="wait" {{$arrayConditionB[3] == 'wait' ? 'selected' : ''}}>
                                                                    {{__('Chờ phục vụ')}}
                                                                </option>

                                                            </select>
                                                        @elseif($detail['condition_id'] == 4)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    style="width: 100%">
                                                                <option value="morning" {{$arrayConditionB[4] == 'morning' ? 'selected' : ''}}>
                                                                    {{__('Sáng (07h - 12h)')}}
                                                                </option>
                                                                <option value="noon" {{$arrayConditionB[4] == 'noon' ? 'selected' : ''}}>
                                                                    {{__('Trưa (12h - 14h)')}}
                                                                </option>
                                                                <option value="afternoon" {{$arrayConditionB[4] == 'afternoon' ? 'selected' : ''}}>
                                                                    {{__('Chiều (14h - 18h)')}}
                                                                </option>
                                                                <option value="evening" {{$arrayConditionB[4] == 'evening' ? 'selected' : ''}}>
                                                                    {{__('Tối (18h - 22h)')}}
                                                                </option>
                                                            </select>
                                                        @elseif($detail['condition_id'] == 5)
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox"
                                                                           {{$arrayConditionB[5] == 1 ? 'checked' : ''}}
                                                                           class="manager-btn chooses-condition-A"
                                                                           disabled>
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @elseif($detail['condition_id'] == 6)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listService as $item)
                                                                    <option value="{{$item['service_id']}}"
                                                                            {{in_array($item['service_id'], $arrayConditionB[6]) ? 'selected' : ''}}>
                                                                        {{$item['service_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 7)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listService as $item)
                                                                    <option value="{{$item['service_id']}}"
                                                                            {{in_array($item['service_id'], $arrayConditionB[7]) ? 'selected' : ''}}>
                                                                        {{$item['service_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 8)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listProduct as $item)
                                                                    <option value="{{$item['product_child_id']}}"
                                                                            {{in_array($item['product_child_id'], $arrayConditionB[8]) ? 'selected' : ''}}>
                                                                        {{$item['product_child_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 9)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listProduct as $item)
                                                                    <option value="{{$item['product_child_id']}}"
                                                                            {{in_array($item['product_child_id'], $arrayConditionB[9]) ? 'selected' : ''}}>
                                                                        {{$item['product_child_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 10)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionB[10]}}"
                                                                   placeholder="{{__('Nhập số ngày')}}"
                                                                   title="{{__('Số ngày')}}">
                                                        @elseif($detail['condition_id'] == 11)
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox"
                                                                           {{$arrayConditionB[11] == 1 ? 'checked' : ''}}
                                                                           class="manager-btn chooses-condition-A"
                                                                           disabled>
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @elseif($detail['condition_id'] == 12)
                                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                                    <input type="checkbox"
                                                                           {{$arrayConditionB[12] == 1 ? 'checked' : ''}}
                                                                           class="manager-btn chooses-condition-A"
                                                                           disabled>
                                                                    <span></span>
                                                                </label>
                                                            </span>
                                                        @elseif($detail['condition_id'] == 13)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listRank as $item)
                                                                    <option value="{{$item['member_level_id']}}"
                                                                            {{in_array($item['member_level_id'], $arrayConditionB[13]) ? 'selected' : ''}}>
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 14)
                                                            <div class="row">
                                                                <input type="hidden" id="range_point_value_b"
                                                                       class="form-control inputmask chooses-condition-A"
                                                                       value="{{$arrayConditionB[14][0].','.$arrayConditionB[14][1]}}">
                                                                <div class="col-3">
                                                                    <input type="text" id="range_point_from_b"
                                                                           class="form-control inputmask"
                                                                           onchange="userGroupAuto.setRangepointB();"
                                                                           value="{{$arrayConditionB[14][0]}}"
                                                                           placeholder="{{__('Từ')}}"
                                                                           title="{{__('Từ')}}">
                                                                </div>
                                                                -
                                                                <div class="col-3">
                                                                    <input type="text" id="range_point_to_b"
                                                                           class="form-control inputmask"
                                                                           onchange="userGroupAuto.setRangepointB();"
                                                                           value="{{$arrayConditionB[14][1]}}"
                                                                           placeholder="{{__('Đến')}}"
                                                                           title="{{__('Đến')}}">
                                                                </div>
                                                            </div>
                                                        @elseif($detail['condition_id'] == 15)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionB[15]}}"
                                                                   placeholder="{{__('Nhập số khách hàng')}}"
                                                                   title="{{__('Nhập số khách hàng')}}">
                                                        @elseif($detail['condition_id'] == 16)
                                                            <input type="text"
                                                                   class="form-control inputmask chooses-condition-A"
                                                                   value="{{$arrayConditionB[16]}}"
                                                                   placeholder="{{__('Nhập số khách hàng')}}"
                                                                   title="{{__('Nhập số khách hàng')}}">
                                                        @elseif($detail['condition_id'] == 17)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listServiceCard as $item)
                                                                    <option value="{{$item['service_card_id']}}"
                                                                            {{in_array($item['service_card_id'], $arrayConditionB[17]) ? 'selected' : ''}}>
                                                                        {{$item['name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif($detail['condition_id'] == 19)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                <option value="personal" {{in_array('personal', $arrayConditionB[19]) ? 'selected' : ''}}>
                                                                    {{__('Cá nhân')}}
                                                                </option>
                                                                <option value="bussiness" {{in_array('bussiness', $arrayConditionB[19]) ? 'selected' : ''}}>
                                                                    {{__('Doanh nghiệp')}}
                                                                </option>
                                                            </select>
                                                        @elseif ($detail['condition_id'] == 21)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listSourceCustomer as $item)
                                                                    <option value="{{$item['customer_source_id']}}" {{in_array($item['customer_source_id'], $arrayConditionB[21]) ? 'selected' : ''}}>
                                                                        {{$item['customer_source_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif ($detail['condition_id'] == 20)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($listGroupCustomer as $item)
                                                                    <option value="{{$item['customer_group_id']}}" {{in_array($item['customer_group_id'], $arrayConditionB[20]) ? 'selected' : ''}}>
                                                                        {{$item['group_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @elseif ($detail['condition_id'] == 18)
                                                            @php
                                                                $province = isset($arrayConditionB[18]->province) ? $arrayConditionB[18]->province : '';
                                                                $district = isset($arrayConditionB[18]->district) ? $arrayConditionB[18]->district : '';
                                                            @endphp
                                                            <div class="" style="display:flex; gap:25px">
                                                                <select type="text" name="province_main"
                                                                        onchange="getAddress.getDistrictB(this)"
                                                                        id="province_id_b"
                                                                        class="form-control ss--width-100 ss-select2"
                                                                        style="width: 100%">
                                                                    <option>@lang('Tỉnh thành phố')</option>
                                                                    @foreach ($listProvinces as $key => $value)
                                                                        <option value="{{ $key }}" {{ $province == $key ? ' selected="selected"' : '' }}>{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                                <select type="text" name="district" id="district_id_b"
                                                                        class="form-control ss--width-100 ss-select2 district"
                                                                        style="width: 100%">
                                                                    @if ($listDistrictB != ''  &&  $listDistrictB->count() > 0)
                                                                        @foreach($listDistrictB as $item)
                                                                            <option value="{{ $item->districtid }}" {{$item->districtid == $district ? ' selected="selected"' : ''}}>  {{  $item->type. ' ' .$item->name }}</option>
                                                                        @endforeach
                                                                    @else
                                                                        <option value="">@lang('Quận/Huyện')</option>
                                                                    @endif
                                                                </select>
                                                                <select type="text" name="ward_main" id="ward_id_b"
                                                                        class="form-control ss--width-100 ss-select2"
                                                                        style="width: 100%">
                                                                    <option value="">@lang('Phường/xã')</option>
                                                                </select>
                                                            </div>
                                                        @elseif($detail['condition_id'] == 22)
                                                            <select name="" id=""
                                                                    class="form-control ss--select-2 chooses-condition-A select-2"
                                                                    multiple="multiple" style="width: 100%">
                                                                @foreach($customerGroupSystem as $item)
                                                                    <option value="{{$item['customer_group_id']}}" {{in_array($item['customer_group_id'], $arrayConditionB[22]) ? 'selected' : ''}}>
                                                                        {{$item['group_name']}}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @endif
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <button style="float: right;" type="button"
                                                                onclick="userGroupAuto.removeConditionB(this)"
                                                                class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">
                                                            <i class="la la-close"></i>
                                                        </button>

                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
                    <a href="{{route('admin.customer-group-filter')}}"
                       class="btn  btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </a>
                    <button type="button" onclick="userGroupAuto.save(0)"
                            class="btn  btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn-add-close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT')}}</span>
							</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="customer_group_id_" value="{{$customerGroup['id']}}">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('after_script')
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
               placeholder="{{__('Nhập số ngày')}}"
               title="{{__('Số ngày')}}">
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
        <div class="" style="display:flex; gap:25px">
            <select type="text" name="province_main" onchange="getAddress.getDistrict(this)" id="province_id"
                    class="form-control ss--width-100 ss-select2" style="width: 100%">
                <option>@lang('Tỉnh thành phố')</option>
                @foreach ($listProvinces as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <select type="text" name="district" id="district_id"
                    class="form-control ss--width-100 ss-select2 district" style="width: 100%">
                <option value="">@lang('Quận/Huyện')</option>
            </select>
            <select type="text" name="ward_main" id="ward_id"
                    class="form-control ss--width-100 ss-select2" style="width: 100%">
                <option value="">@lang('Phường/xã')</option>
            </select>
        </div>
    </script>
    <script type="text/template" id="tpl-address-b">
        <div class="" style="display:flex; gap:25px">
            <select type="text" name="province_main" onchange="getAddress.getDistrictB(this)" id="province_id_b"
                    class="form-control ss--width-100 ss-select2" style="width: 100%">
                <option>@lang('Tỉnh thành phố')</option>
                @foreach ($listProvinces as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            <select type="text" name="district" id="district_id_b"
                    class="form-control ss--width-100 ss-select2 district" style="width: 100%">
                <option value="">@lang('Quận/Huyện')</option>
            </select>
            <select type="text" name="ward_main" id="ward_id"
                    class="form-control ss--width-100 ss-select2" style="width: 100%">
                <option value="">@lang('Phường/xã')</option>
            </select>
        </div>
    </script>
    <script src="{{asset('static/backend/js/admin/user-group/edit.js')}}"
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
