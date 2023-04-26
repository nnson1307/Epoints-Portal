@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('CẤU HÌNH HỢP ĐỒNG')}}
    </span>
@endsection
<?php
    $sttGeneral = 1;
    $sttPartner = 1;
    $sttPayment = 1;
    $sttRemind = 1;
?>
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="fas fa-plus"></i> {{__('CHỈNH SỬA LOẠI HỢP ĐỒNG')}}</span>
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('contract.contract-category')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('QUAY LẠI')</span>
                            </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
                <div class="row" style="margin-right: -80px;">
                    <form id="form-create-cc" class="col-lg-3 form-group">
                            <div class="col-lg-12 row">
                                <div class="form-group col-lg-9">
                                    <label>
                                        {{__('Trạng thái')}}:
                                    </label>
                                </div>
                                <div class="form-group col-lg-3 float-right">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px">
                                        <input type="checkbox" {{$item['is_actived'] == 1 ? 'checked' : ''}} class="manager-btn" name="is_actived" id="is_actived">
                                        <span></span>
                                    </label>
                                    </span>
                                </div>
                            </div>
{{--                            <div class="col-lg-12">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label>--}}
{{--                                        {{__('Mã loại hợp đồng')}}:<b class="text-danger">*</b>--}}
{{--                                    </label>--}}
{{--                                    <div class="form-group">--}}
{{--                                        <input aria-describedby="basic-addon1"--}}
{{--                                               name="contract_category_code"--}}
{{--                                               id="contract_category_code"--}}
{{--                                               class="format-money form-control m-input btn-sm"--}}
{{--                                               value="{{$item['contract_category_code']}}"--}}
{{--                                               placeholder="{{__('Nhập mã loại hợp đồng')}}">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Tên loại hợp đồng')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <input aria-describedby="basic-addon1"
                                               name="contract_category_name"
                                               id="contract_category_name"
                                               value="{{$item['contract_category_name']}}"
                                               class="format-money form-control m-input btn-sm"
                                               placeholder="{{__('Nhập tên loại hợp đồng')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Cấu hình mã hợp đồng')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <input aria-describedby="basic-addon1"
                                               name="contract_code_format"
                                               id="contract_code_format"
                                               value="{{$item['contract_code_format']}}"
                                               class="format-money form-control m-input btn-sm"
                                               placeholder="{{__('HDA00000')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Loại hợp đồng')}}:<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group">
                                        <select class="form-control select" name="type_contract" id="type_contract" style="width: 100%">
                                            <option value="sell" {{$item['type'] == 'sell' ? 'selected' :''}}>@lang('Bán')</option>
                                            <option value="buy" {{$item['type'] == 'buy' ? 'selected' :''}}>@lang('Mua')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>
                                        {{__('Template hợp đồng')}}:
                                    </label>
                                    <div id="contract_category_list_files" class="row">
                                        @if(isset($item['contract_category_link_files']) != '')
                                            @foreach(explode(',', $item['contract_category_link_files']) as $key => $value)
                                                <div class="col-lg-12">
                                                    <a href="{{$value}}" value="{{explode(',', $item['contract_category_name_files'])[$key]}}" name="contract_category_list_files[]" class="ss--text-black" download="{{explode(',', $item['contract_category_name_files'])[$key]}}">{{explode(',', $item['contract_category_name_files'])[$key]}}</a>
                                                    <a href="javascript:void(0)" onclick="contractCategories.removeFileCc(this)"><i class="la la-trash"></i></a>
                                                    <br>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="m-widget19__action">
                                        <input accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .docx, .doc"
                                               id="upload_file_cc" type="file"
                                               data-msg-accept="{{__('Tệp không đúng định dạng')}}"
                                               class="btn btn-primary btn-sm color_button m-btn text-center"
                                               style="display: none"
                                               onchange="contractCategories.uploadFileCc(this)"
                                        >
                                        <button type="button" onclick="document.getElementById('upload_file_cc').click()"
                                                class="btn btn-primary btn-sm color_button m-btn text-center">
                                        {{ __('Tải hồ sơ') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button type="button" onclick="contractCategories.submitEdit(this)"
                                        class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                    <span>
                                        <i class="la la-check"></i>
                                        <span>@lang('LƯU THÔNG TIN')</span>
                                    </span>
                                </button>
                            </div>
                    </form>
                    <div class="col-lg-9 form-group row">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;" id="id_ne">
                                    <li class="nav-item">
                                        <a class="nav-link active son" data-toggle="tab" show style="width: 100%; text-align: center"
                                           onclick="contractCategories.changeTab(this,'general')" value="@lang("SMS")">@lang("Thông tin hợp đồng")</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link son" data-toggle="tab" style="width: 100%; text-align: center"
                                           onclick="contractCategories.changeTab(this,'partner')" >@lang("Thông tin đối tác")</a>
                                    </li>
                                    <li class="nav-item" >
                                        <a class="nav-link son" data-toggle="tab"  style="width: 100%; text-align: center"
                                           onclick="contractCategories.changeTab(this,'status')">@lang("Trạng thái hợp đồng")</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link son" data-toggle="tab" style="width: 100%; text-align: center"
                                           onclick="contractCategories.changeTab(this,'payment')" >@lang("Thông tin thanh toán")</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link son" style="width: 100%; text-align: center"
                                           onclick="contractCategories.changeTab(this,'remind')">@lang("Nhắc nhở")</a>
                                    </li>
{{--                                    <li class="nav-item">--}}
{{--                                        <a class="nav-link son" style="width: 100%; text-align: center"--}}
{{--                                           onclick="contractCategories.changeTab(this,'notify', 'edit')">@lang("Thông báo")</a>--}}
{{--                                    </li>--}}
                                </ul>
                            </div>

                            <div class="bd-ct row" style="margin: 0 0;">
                                <div class="tab_contract_category col-lg-12 row" id="div-general">
                                    <div class="col-lg-12 row">
                                        <div class="col-6">
                                            <h2 class="m-portlet__head-text title_index">
                                                <span>{{__('Thông tin hợp đồng')}}</span>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <button class="float-right btn btn-primary btn-sm color_button m-btn btn_add_pc"
                                                    onclick="contractCategories.tabGeneralPrepend()">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span> {{__('Thêm trường dữ liệu')}}</span>
                                            </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-widget4 append m-section__content" id="m_blockui_1_content" style="width: 100%">
                                        <div class="table-responsive m--margin-top-10">
                                            <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true"
                                                 style="height: 450px; overflow: hidden;">
                                                <table class="table table-striped m-table m-table--head-bg-default table_list">
                                                    <thead class="bg">
                                                    <tr>
                                                        <th class="tr_thead_list">{{__('Tên trường')}}</th>
                                                        <th class="tr_thead_list">{{__('Loại dữ liệu')}}</th>
                                                        <th class="tr_thead_list">{{__('Hiển thị')}}</th>
                                                        <th class="tr_thead_list">{{__('Bắt buộc')}}</th>
                                                        <th class="tr_thead_list">{{__('Hành động')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table_tab_general" style="font-size: 13px">
                                                    @if(count($tabGeneral)>0)
                                                        @foreach($tabGeneral as $key=>$value)
                                                            @if($value['is_default'] == '1')
                                                            <tr class="tab-general-default">
                                                                <td class="key-general-default" hidden>{{$value['key']}}</td>
                                                                <td class="key-name-general-default" style="width: 250px;">{{$value['key_name']}}</td>
                                                                <td class="type-general-default">{{$value['type']}}</td>
                                                                <td class="is-show-general-default">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_show'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                </td>
                                                                <td class="is-validate-general-default">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_validate'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                </td>
                                                                <td>
                                                                </td>
                                                            </tr>
                                                            @else
                                                                <tr class="tab-general-custom tab-general-{{$value['key']}}">
                                                                    <td class="key-general" hidden>{{$value['key']}}</td>
                                                                    <td class="key-name-general" style="width: 250px;">
                                                                        <input aria-describedby="basic-addon1" value="{{$value['key_name']}}" disabled
                                                                               class="format-money form-control m-input btn-sm">
                                                                    </td>
                                                                    <td class="type-general">
                                                                        <select class="form-control select" style="width: 100%" disabled>
                                                                            <option value="int" {{$value['type'] == 'int' ? 'selected' : ''}}>int</option>
                                                                            <option value="float" {{$value['type'] == 'float' ? 'selected' : ''}}>float</option>
                                                                            <option value="text" {{$value['type'] == 'text' ? 'selected' : ''}}>text</option>
                                                                            <option value="date" {{$value['type'] == 'date' ? 'selected' : ''}}>date</option>
                                                                            <option value="text area" {{$value['type'] == 'text area' ? 'selected' : ''}}>text area</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="is-show-general">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_show'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is-validate-general">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_validate'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="general_custom_{{$sttGeneral}}">
                                                                        <a href="javascript:void(0)" onclick="contractCategories.enabledTab({{$sttGeneral}}, 'general', 1);"
                                                                        title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                            <i class="la la-edit"></i>
                                                                        </a>
{{--                                                                        <a href="javascript:void(0)" onclick="$(this).parent('td').parent('tr').remove();"--}}
{{--                                                                           title="{{__('Hủy')}}" style="color: #a1a1a1"><i class="la la-trash"></i>--}}
{{--                                                                        </a>--}}
                                                                    </td>
                                                                    <td hidden>
                                                                        <input type="text" hidden class="general_save_custom general_save_custom_{{$sttGeneral++}}" value="1">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="tb_log" style="color: #ff0000"></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 pt-3">
                                        <button type="button" onclick="contractCategories.submitAddGeneralTab('edit');"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-check"></i>
                                                    <span>@lang('LƯU THÔNG TIN HỢP ĐỒNG')</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="tab_contract_category col-lg-12 row" id="div-partner" hidden>
                                    <div class="col-lg-12 row">
                                        <div class="col-6">
                                            <h2 class="m-portlet__head-text title_index">
                                                <span>{{__('Thông tin đối tác')}}</span>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <button class="float-right btn btn-primary btn-sm color_button m-btn btn_add_pc"
                                                    onclick="contractCategories.tabPartnerPrepend()">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span> {{__('Thêm trường dữ liệu')}}</span>
                                            </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-widget4 append m-section__content" id="m_blockui_1_content" style="width: 100%">
                                        <div class="table-responsive m--margin-top-10">
                                            <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true"
                                                 style="height: 450px; overflow: hidden;">
                                                <table class="table table-striped m-table m-table--head-bg-default table_list">
                                                    <thead class="bg">
                                                    <tr>
                                                        <th class="tr_thead_list">{{__('Tên trường')}}</th>
                                                        <th class="tr_thead_list">{{__('Loại dữ liệu')}}</th>
                                                        <th class="tr_thead_list">{{__('Hiển thị')}}</th>
                                                        <th class="tr_thead_list">{{__('Bắt buộc')}}</th>
                                                        <th class="tr_thead_list">{{__('Hành động')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table_tab_partner" style="font-size: 13px">
                                                    @if(count($tabPartner)>0)
                                                        @foreach($tabPartner as $key=>$value)
                                                            @if($value['is_default'] == '1')
                                                                <tr class="tab-partner-default">
                                                                    <td class="key-partner-default" hidden>{{$value['key']}}</td>
                                                                    <td class="key-name-partner-default"  style="width: 250px;">{{$value['key_name']}}</td>
                                                                    <td class="type-partner-default">{{$value['type']}}</td>
                                                                    <td class="is-show-partner-default">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_show'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is-validate-partner-default">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_validate'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr class="tab-partner-custom tab-partner-{{$value['key']}}">
                                                                    <td class="key-partner" hidden>{{$value['key']}}</td>
                                                                    <td class="key-name-partner" style="width: 250px;">
                                                                        <input aria-describedby="basic-addon1" value="{{$value['key_name']}}" disabled
                                                                               class="format-money form-control m-input btn-sm">
                                                                    </td>
                                                                    <td class="type-partner">
                                                                        <select class="form-control select" style="width: 100%" disabled>
                                                                            <option value="int" {{$value['type'] == 'int' ? 'selected' : ''}}>int</option>
                                                                            <option value="float" {{$value['type'] == 'float' ? 'selected' : ''}}>float</option>
                                                                            <option value="text" {{$value['type'] == 'text' ? 'selected' : ''}}>text</option>
                                                                            <option value="date" {{$value['type'] == 'date' ? 'selected' : ''}}>date</option>
                                                                            <option value="text area" {{$value['type'] == 'text area' ? 'selected' : ''}}>text area</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="is-show-partner">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_show'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is-validate-partner">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_validate'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="partner_custom_{{$sttPartner}}">
                                                                        <a href="javascript:void(0)" onclick="contractCategories.enabledTab({{$sttPartner}}, 'partner');"
                                                                           title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                            <i class="la la-edit"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)" onclick="$(this).parent('td').parent('tr').remove();"
                                                                           title="{{__('Hủy')}}" style="color: #a1a1a1"><i class="la la-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td hidden>
                                                                        <input type="text" hidden class="partner_save_custom partner_save_custom_{{$sttPartner++}}" value="1">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="tb_log" style="color: #ff0000"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pt-3">
                                        <button type="button"  onclick="contractCategories.submitAddPartnerTab('edit');"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-check"></i>
                                                    <span>@lang('LƯU THÔNG TIN ĐỐI TÁC')</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="tab_contract_category col-lg-12 row" id="div-status" hidden>
                                    <div class="m-widget4 append m-section__content" id="m_blockui_1_content" style="width: 100%">
                                        <div class="table-responsive m--margin-top-10">
                                            <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true" data-scroll-x="true"
                                                 style="height: 450px; overflow: hidden;">
                                                <table class="table m-table m-table--head-bg-default table_list">
                                                    <thead class="">
                                                    <tr>
                                                        <th class="tr_thead_list" style="width: 200px !important;">{{__('Trạng thái bắt đầu từ')}}</th>
                                                        <th class="tr_thead_list"></th>
                                                        <th class="tr_thead_list" style="width: 200px !important;">{{__('Trạng thái đến')}}</th>
                                                        <th class="tr_thead_list">{{__('Phê duyệt')}}</th>
                                                        <th class="tr_thead_list">{{__('Người duyệt')}}</th>
                                                        <th class="tr_thead_list">{{__('Sửa')}}</th>
                                                        <th class="tr_thead_list">{{__('Xoá')}}</th>
                                                        <th class="tr_thead_list">{{__('Nhập lý do')}}</th>
                                                        <th class="tr_thead_list">{{__('Hiển thị')}}</th>
                                                        <th class="tr_thead_list">{{__('Hành động')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table_tab_status" style="font-size: 13px">
                                                    @if(count($tabStatus)>0)
                                                        @foreach($tabStatus as $key=>$value)
                                                            @if($checkHaveStatus == 1)
                                                                <tr class="tab-status">
                                                                    <td class="unique_status" hidden>{{$value['status_code']}}</td>
                                                                    <td class="status_id" hidden>{{$value['contract_category_status_id']}}</td>
                                                                    <td class="default_system" hidden>{{$value['default_system']}}</td>
                                                                    <td class="status_code" hidden>{{$value['status_code']}}</td>
                                                                    <td class="status_name" style="width: 200px !important;">
                                                                        <input aria-describedby="basic-addon1" value="{{$value['status_name']}}" disabled
                                                                               class="format-money form-control m-input btn-sm">
                                                                    </td>
                                                                    <td>
                                                                        <i class="la la-arrow-right mt-3"></i>
                                                                    </td>
                                                                    <td class="status_name_update" style="width: 200px !important;">
                                                                        <select class="form-control select" style="width: 100%" multiple disabled>
                                                                            @if(count($arrStatusUpdate[$value['status_code']])>0)
                                                                                @foreach($arrStatusUpdate[$value['status_code']] as $key1 => $value1)
                                                                                    <option value="{{$value1['status_name']}}" selected>{{$value1['status_name']}}</option>
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                    </td>
                                                                    <td class="is_approve">
                                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                        <label style="margin: 0 0 0 10px">
                                                                            <input type="checkbox" {{$value['is_approve'] == 1 ? 'checked' : ''}} disabled class="manager-btn" onchange="contractCategories.enabledApproveBy(this);">
                                                                            <span></span>
                                                                        </label>
                                                                        </span>
                                                                    </td>
                                                                    <td class="approve_by">
                                                                        <select class="form-control select" style="width: 150px !important;" disabled multiple>
                                                                            @foreach($lstRoleGroup as $item)
                                                                                @if(in_array($item['id'], $arrStatusApprove[$value['status_code']]))
                                                                                    <option value="{{$item['id']}}" selected>{{$item['name']}}</option>
                                                                                @else
                                                                                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="is_edit_contract">
                                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                        <label style="margin: 0 0 0 10px">
                                                                            <input type="checkbox"  {{$value['is_edit_contract'] == 1 ? 'checked' : ''}}  disabled class="manager-btn">
                                                                            <span></span>
                                                                        </label>
                                                                        </span>
                                                                    </td>
                                                                    <td class="is_deleted_contract">
                                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                        <label style="margin: 0 0 0 10px">
                                                                            <input type="checkbox"  {{$value['is_deleted_contract'] == 1 ? 'checked' : ''}}  disabled class="manager-btn">
                                                                            <span></span>
                                                                        </label>
                                                                        </span>
                                                                    </td>
                                                                    <td class="is_reason">
                                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                        <label style="margin: 0 0 0 10px">
                                                                            <input type="checkbox"  {{$value['is_reason'] == 1 ? 'checked' : ''}}  disabled class="manager-btn">
                                                                            <span></span>
                                                                        </label>
                                                                        </span>
                                                                    </td>
                                                                    <td class="is_show">
                                                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm" {{$value['default_system'] != '' ?  'hidden' : ''}}>
                                                                            <label style="margin: 0 0 0 10px">
                                                                                <input type="checkbox" {{$value['is_show'] == 1 ? 'checked' : ''}}  class="manager-btn">
                                                                                <span></span>
                                                                            </label>
                                                                        </span>
                                                                    </td>
                                                                    <td class="change_action">
                                                                        <a href="javascript:void(0)" data-default="1" onclick="contractCategories.editStatus(this);"
                                                                           title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                            <i class="la la-edit"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td hidden>
                                                                        <input type="text" class="save_change_action" value="1">
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr class="tab-status">
                                                                    <td class="status_id" hidden>{{$value['contract_category_config_status_default_id']}}</td>
                                                                    <td class="default_system" hidden>{{$value['default_system']}}</td>
                                                                    <td class="status_name" style="width: 200px !important;">
                                                                        <input aria-describedby="basic-addon1" value="{{$value['status_name']}}" disabled
                                                                               class="format-money form-control m-input btn-sm">
                                                                    </td>
                                                                    <td>
                                                                        <i class="la la-arrow-right mt-3"></i>
                                                                    </td>
                                                                    <td class="status_name_update" style="width: 200px !important;">
                                                                        <select class="form-control select" style="width: 100%" multiple disabled>
                                                                        </select>
                                                                    </td>
                                                                    <td class="is_approve">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" class="manager-btn" onchange="contractCategories.enabledApproveBy(this);">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="approve_by">
                                                                        <select class="form-control select" style="width: 150px !important;" disabled multiple>
                                                                            @foreach($lstRoleGroup as $item)
                                                                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </td>
                                                                    <td class="is_edit_contract">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" checked disabled class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is_deleted_contract">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" checked disabled class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is_reason">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" checked disabled class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is_show">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm" hidden>
                                                                        <label style="margin: 0 0 0 10px">
                                                                            <input type="checkbox" checked class="manager-btn">
                                                                            <span></span>
                                                                        </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="change_action">
                                                                        <a href="javascript:void(0)" data-default="1" onclick="contractCategories.editStatus(this);"
                                                                           title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                            <i class="la la-edit"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td hidden>
                                                                        <input type="text" class="save_change_action" value="1">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="tb_log" style="color: #ff0000"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pt-3">
                                        <button type="button"  onclick="contractCategories.addStatus();"
                                                class="float-left btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-check"></i>
                                                    <span>@lang('THÊM')</span>
                                                </span>
                                        </button>
                                        <button type="button"  onclick="contractCategories.submitEditStatusTab();"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-check"></i>
                                                    <span>@lang('LƯU TRẠNG THÁI HỢP ĐỒNG')</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="tab_contract_category col-lg-12 row" id="div-payment" hidden>
                                    <div class="col-lg-12 row">
                                        <div class="col-6">
                                            <h2 class="m-portlet__head-text title_index">
                                                <span>{{__('Thông tin thanh toán')}}</span>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <button class="float-right btn btn-primary btn-sm color_button m-btn btn_add_pc"
                                                    onclick="contractCategories.tabPaymentPrepend()">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span> {{__('Thêm trường dữ liệu')}}</span>
                                            </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-widget4 append m-section__content" id="m_blockui_1_content" style="width: 100%">
                                        <div class="table-responsive m--margin-top-10">
                                            <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true"
                                                 style="height: 450px; overflow: hidden;">
                                                <table class="table table-striped m-table m-table--head-bg-default table_list">
                                                    <thead class="bg">
                                                    <tr>
                                                        <th class="tr_thead_list">{{__('Tên trường')}}</th>
                                                        <th class="tr_thead_list">{{__('Loại dữ liệu')}}</th>
                                                        <th class="tr_thead_list">{{__('Hiển thị')}}</th>
                                                        <th class="tr_thead_list">{{__('Bắt buộc')}}</th>
                                                        <th class="tr_thead_list">{{__('Hành động')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table_tab_payment" style="font-size: 13px">
                                                    @if(count($tabPayment)>0)
                                                        @foreach($tabPayment as $key=>$value)
                                                            @if($value['is_default'] == '1')
                                                                <tr class="tab-payment-default">
                                                                    <td class="key-payment-default" hidden>{{$value['key']}}</td>
                                                                    <td class="key-name-payment-default"  style="width: 250px;">{{$value['key_name']}}</td>
                                                                    <td class="type-payment-default">{{$value['type']}}</td>
                                                                    <td class="is-show-payment-default">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_show'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is-validate-payment-default">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_validate'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr class="tab-payment-custom tab-payment-{{$value['key']}}">
                                                                    <td class="key-payment" hidden>{{$value['key']}}</td>
                                                                    <td class="key-name-payment" style="width: 250px;">
                                                                        <input aria-describedby="basic-addon1" value="{{$value['key_name']}}" disabled
                                                                               class="format-money form-control m-input btn-sm">
                                                                    </td>
                                                                    <td class="type-payment">
                                                                        <select class="form-control select" style="width: 100%" disabled>
                                                                            <option value="int" {{$value['type'] == 'int' ? 'selected' : ''}}>int</option>
                                                                            <option value="float" {{$value['type'] == 'float' ? 'selected' : ''}}>float</option>
                                                                            <option value="text" {{$value['type'] == 'text' ? 'selected' : ''}}>text</option>
                                                                            <option value="date" {{$value['type'] == 'date' ? 'selected' : ''}}>date</option>
                                                                            <option value="text area" {{$value['type'] == 'text area' ? 'selected' : ''}}>text area</option>
                                                                        </select>
                                                                    </td>
                                                                    <td class="is-show-payment">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_show'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="is-validate-payment">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" disabled {{$value['is_validate'] == 1 ? 'checked' : ''}} class="manager-btn">
                                                                        <span></span>
                                                                    </label>
                                                                    </span>
                                                                    </td>
                                                                    <td class="payment_custom_{{$sttPayment}}">
                                                                        <a href="javascript:void(0)" onclick="contractCategories.enabledTab({{$sttPayment}}, 'payment');"
                                                                           title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                            <i class="la la-edit"></i>
                                                                        </a>
                                                                        <a href="javascript:void(0)" onclick="$(this).parent('td').parent('tr').remove();"
                                                                           title="{{__('Hủy')}}" style="color: #a1a1a1"><i class="la la-trash"></i>
                                                                        </a>
                                                                    </td>
                                                                    <td hidden>
                                                                        <input type="text" hidden class="payment_save_custom payment_save_custom_{{$sttPayment++}}" value="1">
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="tb_log" style="color: #ff0000"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pt-3">
                                        <button type="button" onclick="contractCategories.submitAddPaymentTab('edit');"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-check"></i>
                                                    <span>@lang('LƯU THÔNG TIN THANH TOÁN')</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                                <div class="tab_contract_category col-lg-12 row" id="div-remind" hidden>
                                    <div class="col-lg-12 row">
                                        <div class="col-6">
                                            <h2 class="m-portlet__head-text title_index">
                                                <span>{{__('Nhắc nhở')}}</span>
                                            </h2>
                                        </div>
                                        <div class="col-6">
                                            <button class="float-right btn btn-primary btn-sm color_button m-btn btn_add_pc"
                                                    onclick="contractCategories.addRemind()">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span> {{__('Thêm')}}</span>
                                            </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="m-widget4 append m-section__content" id="m_blockui_1_content" style="width: 100%;">
                                        <div class="table-responsive m--margin-top-10">
                                            <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true"
                                                 style="height: 450px; overflow: hidden;">
                                                <table class="table table-responsive table-striped m-table m-table--head-bg-default table_list" style="width: auto">
                                                    <thead class="bg">
                                                    <tr>
                                                        <th class="tr_thead_list" width="5%">#</th>
                                                        <th class="tr_thead_list" width="10%">{{__('Loại nhắc nhở')}}</th>
                                                        <th class="tr_thead_list" width="15%">{{__('Tiêu đề')}}</th>
                                                        <th class="tr_thead_list" width="15%">{{__('Nội dung')}}</th>
                                                        <th class="tr_thead_list" width="10%">{{__('Thời gian gửi')}}</th>
                                                        <th class="tr_thead_list" width="15%">{{__('Người nhận')}}</th>
                                                        <th class="tr_thead_list" width="15%">{{__('Hình thức nhắc nhở')}}</th>
                                                        <th class="tr_thead_list" width="10%">{{__('Trạng thái')}}</th>
                                                        <th class="tr_thead_list" width="5%">{{__('Hành động')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table_tab_remind" style="font-size: 13px">
                                                        @foreach($arrTabRemind as $key => $value)
                                                            <tr class="tab-remind remind_{number_remind}">
                                                                <td class="remind_id" hidden>{{$value['remind_id']}}}</td>
                                                                <td class="number_remind">{{$value['number_remind']}}</td>
                                                                <td class="remind_type">{{$value['remind_type']}}</td>
                                                                <td class="title">{{$value['title']}}</td>
                                                                <td class="content">{{$value['content']}}</td>
                                                                <td class="time_send">{{$value['time_send']}}</td>
                                                                <td class="receiver_by">{{$value['receiver_by']}}</td>
                                                                <td class="remind_method">{{$value['remind_method']}}</td>
                                                                <td class="is_actived">
                                                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                        <label style="margin: 0 0 0 10px">
                                                                            <input type="checkbox" class="manager-btn" {{$value['is_actived'] == 1 ? 'checked' : ''}} disabled>
                                                                            <span></span>
                                                                        </label>
                                                                    </span>
                                                                </td>
                                                                <td class="change_action">
                                                                    <a href="javascript:void(0)" onclick="contractCategories.editRemind(this, {{$value['remind_id']}})"
                                                                       title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                        <i class="la la-edit"></i>
                                                                    </a>
                                                                    <a href="javascript:void(0)" onclick="contractCategories.removeRemind(this, {{$value['remind_id']}})"
                                                                       title="{{__('Hủy')}}" style="color: #a1a1a1"><i class="la la-trash"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="tb_log" style="color: #ff0000"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab_contract_category col-lg-12 row" id="div-notify" hidden>
                                    <div class="m-widget4 append m-section__content" id="m_blockui_1_content" style="width: 100%">
                                        <div class="table-responsive m--margin-top-10">
                                            <div class="m-scrollable m-scroller ps ps--active-y w-100" data-scrollable="true"
                                                 style="height: 450px; overflow: hidden;">
                                                <table class="table m-table m-table--head-bg-default table_list">
                                                    <thead class="">
                                                    <tr>
                                                        <th class="tr_thead_list" style="width: 200px !important;">{{__('Tự động thông báo khi chuyển trạng thái xử lý')}}</th>
                                                        <th class="tr_thead_list" style="width: 200px !important;">{{__('Nội dung thông báo')}}</th>
                                                        <th class="tr_thead_list"></th>
                                                        <th class="tr_thead_list">{{__('Người tạo')}}</th>
                                                        <th class="tr_thead_list">{{__('Người thực hiện')}}</th>
                                                        <th class="tr_thead_list">{{__('Người ký')}}</th>
                                                        <th class="tr_thead_list">{{__('Người theo dõi')}}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="table_tab_notify" style="font-size: 13px">
                                                    @if(count($lstNotify)>0)
                                                        @foreach($lstNotify as $key=>$value)
                                                            <tr class="tab-notify">
                                                                <td class="status_code" hidden>{{$value['status_code']}}</td>
                                                                <td class="status_name" style="width: 200px !important;">
                                                                    <input aria-describedby="basic-addon1" value="{{$value['status_name']}}" disabled
                                                                           class="format-money form-control m-input btn-sm">
                                                                </td>
                                                                <td class="content" style="width: 450px !important;">
                                                                    <input aria-describedby="basic-addon1" value="{{$value['content']}}" disabled
                                                                           class="format-money form-control m-input btn-sm {{$value['status_code']}}">
                                                                </td>
                                                                <td>
                                                                    <a href="javascript:void(0)" onclick="contractCategories.changeContentNotify(this, '{{$value['status_code']}}')"
                                                                       title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                                                                        <i class="la la-edit"></i>
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px">
                                                                    <input type="checkbox" {{$value['is_created_by'] == 1 ? 'checked' : ''}} class="manager-btn is_created_by">
                                                                    <span></span>
                                                                </label>
                                                                </span>
                                                                </td>
                                                                <td>
                                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px">
                                                                    <input type="checkbox" {{$value['is_performer_by'] == 1 ? 'checked' : ''}}  class="manager-btn is_performer_by">
                                                                    <span></span>
                                                                </label>
                                                                </span>
                                                                </td>
                                                                <td>
                                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                <label style="margin: 0 0 0 10px">
                                                                    <input type="checkbox" {{$value['is_signer_by'] == 1 ? 'checked' : ''}}  class="manager-btn is_signer_by">
                                                                    <span></span>
                                                                </label>
                                                                </span>
                                                                </td>
                                                                <td>
                                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                                    <label style="margin: 0 0 0 10px">
                                                                        <input type="checkbox" {{$value['is_follow_by'] == 1 ? 'checked' : ''}}  class="manager-btn is_follow_by">
                                                                        <span></span>
                                                                    </label>
                                                                </span>
                                                                </td>
                                                                <td hidden>
                                                                    <input type="text" class="save_content" value="1">
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <span class="tb_log" style="color: #ff0000"></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 pt-3">
                                        <button type="button"  onclick="contractCategories.submitAddNotifyTab();"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-check"></i>
                                                    <span>@lang('LƯU THÔNG BÁO')</span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <input type="text" id="contract_category_id" name="contract_category_id" value="{{$contract_category_id}}" hidden>
            <input type="text" id="check_save_general_tab" name="check_save_general_tab" value="{{$checkHaveGeneral}}" hidden>
            <input type="text" id="check_save_status_tab" name="check_save_status_tab" value="{{$checkHaveStatus}}" hidden>
            <input type="text" id="sttGeneral" name="sttGeneral" hidden value="{{$countSttGeneral}}">
            <input type="text" id="sttPartner" name="sttPartner" hidden value="{{$countSttPartner}}">
            <input type="text" id="sttPayment" name="sttPayment" hidden value="{{$countSttPayment}}">
            <input type="text" id="sttRemind" name="sttRemind" hidden value="{{$countRemind}}">
    </div>
    <form id="frm_create_remind">

    </form>
    <form id="frm_change_content_notify">

    </form>

@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        $('.select2').select2();
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/contract/contract-category/script.js')}}" type="text/javascript">
    </script>

    <script type="text/template" id="append-status">
        <tr class="tab-status">
            <td class="unique_status" hidden>{number_status}</td>
            <td class="status_id" hidden></td>
            <td class="default_system" hidden></td>
            <td class="status_name" style="width: 200px !important;">
                <input aria-describedby="basic-addon1"
                       class="format-money form-control m-input btn-sm">
            </td>
            <td>
                <i class="la la-arrow-right mt-3"></i>
            </td>
            <td class="status_name_update" style="width: 200px !important;">
                <select class="form-control select" style="width: 100%" multiple>
                </select>
            </td>
            <td class="is_approve">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" class="manager-btn" onchange="contractCategories.enabledApproveBy(this);">
                    <span></span>
                </label>
                </span>
            </td>
            <td class="approve_by">
                <select class="form-control select" disabled style="width: 100%" multiple>
                    @foreach($lstRoleGroup as $item)
                        <option value="{{$item['id']}}">{{$item['name']}}</option>
                    @endforeach
                </select>
            </td>
            <td class="is_edit_contract">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn">
                    <span></span>
                </label>
                </span>
            </td>
            <td class="is_deleted_contract">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn">
                    <span></span>
                </label>
                </span>
            </td>
            <td class="is_reason">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn">
                    <span></span>
                </label>
                </span>
            </td>
            <td class="is_show">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn">
                    <span></span>
                </label>
                </span>
            </td>
            <td class="change_action">
                <a href="javascript:void(0)" data-default="0" onclick="contractCategories.saveStatus(this)" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill save_journey">
                    <i class="la la-check"></i>
                </a>
            </td>
            <td hidden>
                <input type="text" class="save_change_action" value="0">
            </td>
        </tr>
    </script>
    <script type="text/template" id="append-remind">
        <tr class="tab-remind remind_{number_remind}">
            <td class="remind_id" hidden>{remind_id}</td>
            <td class="number_remind">{number_remind}</td>
            <td class="remind_type">{remind_type}</td>
            <td class="title">{title}</td>
            <td class="content">{content}</td>
            <td class="time_send">{time_send}</td>
            <td class="receiver_by">{receiver_by}</td>
            <td class="remind_method">{remind_method}</td>
            <td class="is_actived">
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label style="margin: 0 0 0 10px">
                        <input type="checkbox" class="manager-btn" {is_actived} disabled>
                        <span></span>
                    </label>
                </span>
            </td>
            <td class="change_action">
                <a href="javascript:void(0)" onclick="contractCategories.editRemind(this, {remind_id})"
                   title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                    <i class="la la-edit"></i>
                </a>
                <a href="javascript:void(0)" onclick="contractCategories.removeRemind(this, {remind_id})"
                   title="{{__('Hủy')}}" style="color: #a1a1a1"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text/template" id="append-notify">
        <tr class="tab-notify">
            <td class="status_code" hidden>{status_code}</td>
            <td class="status_name" style="width: 200px !important;">
                <input aria-describedby="basic-addon1" value="{status_name}" disabled
                       class="format-money form-control m-input btn-sm">
            </td>
            <td class="content" style="width: 450px !important;">
                <input aria-describedby="basic-addon1" value="{content}" disabled
                       class="format-money form-control m-input btn-sm {status_code}">
            </td>
            <td>
                <a href="javascript:void(0)" onclick="contractCategories.changeContentNotify(this, '{status_code}')"
                   title="{{__('Cập nhật')}}" style="color: #a1a1a1">
                    <i class="la la-edit"></i>
                </a>
            </td>
            <td>
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn is_created_by">
                    <span></span>
                </label>
                </span>
            </td>
            <td>
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn is_performer_by">
                    <span></span>
                </label>
                </span>
            </td>
            <td>
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                <label style="margin: 0 0 0 10px">
                    <input type="checkbox" checked class="manager-btn is_signer_by">
                    <span></span>
                </label>
                </span>
            </td>
            <td>
                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                    <label style="margin: 0 0 0 10px">
                        <input type="checkbox" checked class="manager-btn is_follow_by">
                        <span></span>
                    </label>
                </span>
            </td>
            <td hidden>
                <input type="text" class="save_content" value="1">
            </td>
        </tr>
    </script>
@stop
