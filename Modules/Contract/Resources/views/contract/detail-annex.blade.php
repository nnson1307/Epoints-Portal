<?php

use Modules\Contract\Models\ContractCategoryStatusTable;use Modules\Contract\Models\PaymentMethodTable;use Modules\Contract\Models\PaymentUnitTable;
?>
@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ HỢP ĐỒNG')</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHI TIẾT PHỤ LỤC HỢP ĐỒNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('contract.contract.show', [ "id" => $contract_id])}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('QUAY LẠI')</span>
                            </span>
                </a>
            </div>
        </div>

        <div class="m-portlet__body">

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head">
                    <div class="form-group">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#info" role="tab"
                                   aria-selected="true">
                                    @lang('Thông tin phụ lục')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#log_info_contract" role="tab"
                                   aria-selected="true">
                                    @lang('Thông tin chung')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#log_goods_contract" role="tab"
                                   aria-selected="false">
                                    @lang('Hàng hoá')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#log_annex" role="tab"
                                   aria-selected="false">
                                    @lang('Lịch sử hoạt động')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group tab-content">
                        <div class="tab-pane active show" id="info" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-lg-12 float-right">
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                        <label style="margin: 0 0 0 10px">
                                            <input type="checkbox" disabled {{$item['is_active'] == 1 ? 'checked' : ''}} class="manager-btn" name="is_active" id="is_active">
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black_title">
                                        @lang('Mã phụ lục'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" readonly class="form-control m-input" value="{{$item['contract_annex_code']}}" id="annex_contract_annex_code" name="annex_contract_annex_code">
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black_title">
                                        @lang('Ngày có hiệu lực'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" readonly class="form-control m-input" id="annex_effective_date" value="{{date('d/m/Y', strtotime($item['effective_date']))}}" name="annex_effective_date"
                                               placeholder="@lang('Chọn ngày có hiệu lực')">
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black_title">
                                        @lang('Ngày ký phụ lục'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" readonly class="form-control m-input" id="annex_sign_date" value="{{date('d/m/Y',strtotime($item['sign_date']))}}" name="annex_sign_date"
                                               placeholder="@lang('Chọn ngày ký phụ lục')">
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-6">
                                    <label class="black_title">
                                        @lang('Ngày hết hiệu lực'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" readonly class="form-control m-input" id="annex_expired_date" value="{{date('d/m/Y',strtotime($item['expired_date']))}}" name="annex_expired_date"
                                               placeholder="@lang('Chọn ngày hết hiệu lực')">
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-12">
                                    <label class="black_title">
                                        @lang('Loại điều chỉnh'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="m-form__group form-group">
                                        <div class="m-radio-inline">
                                            <label class="m-radio cus">
                                                <input type="radio" disabled name="annex_adjustment_type" onclick="contractAnnex.changeSubmitAnnex()"
                                                       {{$item['adjustment_type'] == 'update_contract' ? 'checked' : ''}}
                                                       value="update_contract"> {{__('Cập nhật hợp đồng')}}
                                                <span></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" disabled name="annex_adjustment_type" onclick="contractAnnex.changeSubmitAnnex()"
                                                       {{$item['adjustment_type'] == 'renew_contract' ? 'checked' : ''}}
                                                       value="renew_contract"> {{__('Gia hạn hợp đồng')}}
                                                <span></span>
                                            </label>
                                            <label class="m-radio cus">
                                                <input type="radio" disabled name="annex_adjustment_type" onclick="contractAnnex.changeSubmitAnnex()"
                                                       {{$item['adjustment_type'] == 'update_info' ? 'checked' : ''}}
                                                       value="update_info"> {{__('Bổ sung thông tin')}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-12">
                                    <label class="black_title">
                                        @lang('Nội dung'):<b class="text-danger">*</b>
                                    </label>
                                    <div class="form-group m-form__group">
                                        <div class="input-group m-input-group">
                                            <textarea readonly id="annex_content" name="annex_content" class="form-control autosizeme" rows="12"
                                                      placeholder="{{__('Nhập nội dung')}}"
                                                      data-autosize-on="true"
                                                      style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">{{$item['content']}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group col-lg-12">
                                    <label>
                                        {{__('Hồ sơ đính kèm')}}:
                                    </label>
                                    <div id="contract_annex_list_files" class="row">
                                        @if($item['list_link'] != '')
                                            @foreach(explode(',',$item['list_link']) as $key => $value)
                                                <div class="col-lg-12">
                                                    <a href="{{$value}}" value="{{explode(',', $item['list_name'])[$key]}}" name="contract_annex_list_files[]" class="ss--text-black" download="{{explode(',', $item['list_name'])[$key]}}">{{explode(',', $item['list_name'])[$key]}}</a>
                                                    <br>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="log_info_contract" role="tabpanel">
                            <div class="table-content m--padding-top-15 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table m-table--head-bg-default">
                                        <thead class="bg">
                                            <tr>
                                                <th style="width: 40%" class="tr_thead_list"></th>
                                                <th style="width: 30%" class="tr_thead_list">{{__('TRƯỚC CẬP NHẬT')}}</th>
                                                <th style="width: 30%" class="tr_thead_list">{{__('SAU CẬP NHẬT')}}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if(isset($infoGeneral) && count($infoGeneral) > 0)
                                            <tr>
                                                <td style="font-weight: bold">@lang('Thông tin hợp đồng')</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($infoGeneral as $key => $value)
                                                @switch($value['key'])
                                                    @case('number_day_renew')
                                                    <tr>
                                                        <td>@lang('Hợp đồng cần gia hạn trước')</td>
                                                        <td>{{$value['value_old'] . ' ' . __('Ngày hết hiệu lực')}}</td>
                                                        <td>{{$value['value_new'] . ' ' . __('Ngày hết hiệu lực')}}</td>
                                                    </tr>
                                                    @break
                                                    @case('status_code_created_ticket')
                                                        <?php

                                                        $mContractStatus = new ContractCategoryStatusTable();
                                                        $statusNameOld = $mContractStatus->getStatusNameByCode($value['value_old']);
                                                        $statusNameNew = $mContractStatus->getStatusNameByCode($value['value_new']);
                                                        ?>
                                                    <tr>
                                                        <td>@lang('Tạo ticket khi hợp đồng ở trạng thái')</td>
                                                        <td>{{$statusNameOld['status_name']}}</td>
                                                        <td>{{$statusNameNew['status_name']}}</td>
                                                    </tr>
                                                    @break
                                                    @case('is_renew')
                                                    <tr>
                                                        <td>@lang('Hợp đồng cần gia hạn trước?')</td>
                                                        <td>{{$value['value_old'] == 1 ? 'Có' : 'Không'}}</td>
                                                        <td>{{$value['value_new'] == 1 ? 'Có' : 'Không'}}</td>
                                                    </tr>
                                                    @break
                                                    @case('is_created_ticket')
                                                    <tr>
                                                        <td>@lang('Tạo ticket khi hợp đồng ở trạng thái?')</td>
                                                        <td>{{$value['value_old'] == 1 ? 'Có' : 'Không'}}</td>
                                                        <td>{{$value['value_new'] == 1 ? 'Có' : 'Không'}}</td>
                                                    </tr>
                                                    @break
                                                    @case('is_value_goods')
                                                    <tr>
                                                        <td>@lang('Lấy theo giá trị hàng hoá')</td>
                                                        <td>{{$value['value_old'] == 1 ? 'Có' : 'Không'}}</td>
                                                        <td>{{$value['value_new'] == 1 ? 'Có' : 'Không'}}</td>
                                                    </tr>
                                                    @break
                                                    @case('status_code')
                                                        <?php

                                                        $mContractStatus = new ContractCategoryStatusTable();
                                                        $statusNameOld = $mContractStatus->getStatusNameByCode($value['value_old']);
                                                        $statusNameNew = $mContractStatus->getStatusNameByCode($value['value_new']);
                                                        ?>
                                                        <tr>
                                                            <td>@lang('Trạng thái hợp đồng')</td>
                                                            <td>{{$statusNameOld['status_name']}}</td>
                                                            <td>{{$statusNameNew['status_name']}}</td>
                                                        </tr>
                                                    @break
                                                    @case('tag')
                                                    @case('sign_by')
                                                    @case('follow_by')
                                                    <?php
                                                        $tagOld = json_decode($value['value_old']);
                                                        $tagNew = json_decode($value['value_new']);
                                                        $lstTagNameOld = $lstTagNameNew = '';
                                                        foreach ($tagOld as $kt => $vt) {
                                                            $vt = (array)$vt;
                                                            $lstTagNameOld .= $vt[array_key_first($vt)] . '<br/>';
                                                        }
                                                        foreach ($tagNew as $kt => $vt) {
                                                            $vt = (array)$vt;
                                                            $lstTagNameNew .= $vt[array_key_first($vt)] . '<br/>';

                                                        }
                                                    ?>
                                                        <tr>
                                                            <td>{{$value['key_name']}}</td>
                                                            <td>{!! $lstTagNameOld !!}</td>
                                                            <td>{!! $lstTagNameNew !!}</td>
                                                        </tr>
                                                    @break
                                                    @default
                                                        @if($value['type'] == 'date')
                                                            <tr>
                                                                <td>{{$value['key_name']}}</td>
                                                                <td>{{$value['value_old'] != '' ? date('d/m/Y', strtotime($value['value_old'])) : ''}}</td>
                                                                <td>{{$value['value_new'] != '' ? date('d/m/Y', strtotime($value['value_new'])) : ''}}</td>
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>{{$value['key_name']}}</td>
                                                                <td>{{$value['value_old']}}</td>
                                                                <td>{{$value['value_new']}}</td>
                                                            </tr>
                                                        @endif
                                                    @break
                                                @endswitch
                                            @endforeach
                                        @endif
                                        @if(isset($infoPartner) && count($infoPartner) > 0)
                                            <tr>
                                                <td style="font-weight: bold">@lang('Thông tin đối tác')</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($infoPartner as $key => $value)
                                                @if($value['key'] == 'partner_object_form')
                                                    <tr>
                                                        <td>{{$value['key_name']}}</td>
                                                        <td>{{
                                                        $value['value_old'] == 'internal' ?
                                                        __('Nội bộ') : ($value['value_old'] == 'external' ? __('Bên ngoài') : __('Đại lý'))
                                                        }}</td>
                                                        <td>{{
                                                        $value['value_new'] == 'internal' ?
                                                        __('Nội bộ') : ($value['value_new'] == 'external' ? __('Bên ngoài') : __('Đại lý'))
                                                        }}</td>
                                                    </tr>
                                                @else
                                                <tr>
                                                    <td>{{$value['key_name']}}</td>
                                                    <td>{{$value['value_old']}}</td>
                                                    <td>{{$value['value_new']}}</td>
                                                </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(isset($infoPayment) && count($infoPayment) > 0)
                                            <tr>
                                                <td style="font-weight: bold">@lang('Thông tin thanh toán')</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @foreach($infoPayment as $key => $value)
                                                @switch($value['key'])
                                                    @case('payment_unit_id')
                                                    <?php
                                                    $mPaymentUnit = new PaymentUnitTable();
                                                    $statusNameOld = $mPaymentUnit->getInfo($value['value_old']);
                                                    $statusNameNew = $mPaymentUnit->getInfo($value['value_new']);
                                                    ?>
                                                    <tr>
                                                        <td>@lang('Đơn vị thanh toán')</td>
                                                        <td>{{$statusNameOld['name']}}</td>
                                                        <td>{{$statusNameNew['name']}}</td>
                                                    </tr>
                                                    @break
                                                    @case('payment_method_id')
                                                    <?php
                                                    $mPaymentUnit = new PaymentMethodTable();
                                                    $statusNameOld = $mPaymentUnit->getInfo($value['value_old']);
                                                    $statusNameNew = $mPaymentUnit->getInfo($value['value_new']);
                                                    ?>
                                                    <tr>
                                                        <td>@lang('Phương thức thanh toán')</td>
                                                        <td>{{$statusNameOld['payment_method_name']}}</td>
                                                        <td>{{$statusNameNew['payment_method_name']}}</td>
                                                    </tr>
                                                    @break
                                                    @default
                                                    <tr>
                                                        <td>{{$value['key_name']}}</td>
                                                        <td>{{$value['value_old']}}</td>
                                                        <td>{{$value['value_new']}}</td>
                                                    </tr>
                                                    @break
                                                @endswitch
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="log_goods_contract" role="tabpanel">
                            <div class="row">
                                <div class="table-content m--padding-top-15 col-lg-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table m-table--head-bg-default"> <thead class="bg">
                                            <tr>
                                                <th class="tr_thead_list text-center">{{__('TRƯỚC CẬP NHẬT')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($logGoodsOld as $key => $value)
                                                    <tr>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-lg-2">
                                                                    @switch($value['object_type'])
                                                                        @case('product')
                                                                        <span class="m-badge m-badge--success m-badge--wide">@lang('Sản phẩm')</span>
                                                                        @break;
                                                                        @case('service')
                                                                        <span class="m-badge m-badge--success m-badge--wide">@lang('Dịch vụ')</span>
                                                                        @break;
                                                                        @case('service_card')
                                                                        <span class="m-badge m-badge--success m-badge--wide">@lang('Thẻ dịch vụ')</span>
                                                                        @break;
                                                                    @endswitch
                                                                </div>
                                                                <div class="col-lg-8">
                                                                    <b>{{ $value['object_code'] . ' ' . $value['object_name']  }}</b>
                                                                    <br>
                                                                    {{ number_format($value['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)  . ' x ' . $value['quantity'] }}
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <b>{{number_format($value['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</b>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="table-content m--padding-top-15 col-lg-6">
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table m-table--head-bg-default"> <thead class="bg">
                                            <tr>
                                                <th class="tr_thead_list text-center">{{__('SAU CẬP NHẬT')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($logGoodsNew as $key => $value)
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-lg-2">
                                                                @switch($value['object_type'])
                                                                    @case('product')
                                                                    <span class="m-badge m-badge--success m-badge--wide">@lang('Sản phẩm')</span>
                                                                    @break;
                                                                    @case('service')
                                                                    <span class="m-badge m-badge--success m-badge--wide">@lang('Dịch vụ')</span>
                                                                    @break;
                                                                    @case('service_card')
                                                                    <span class="m-badge m-badge--success m-badge--wide">@lang('Thẻ dịch vụ')</span>
                                                                    @break;
                                                                @endswitch
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <b>{{ $value['object_code'] . ' ' . $value['object_name']  }}</b>
                                                                <br>
                                                                {{ number_format($value['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)  . ' x ' . $value['quantity'] }}
                                                            </div>
                                                            <div class="col-lg-2">
                                                                <b>{{number_format($value['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</b>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="log_annex" role="tabpanel">
                            <div class="table-content m--padding-top-15 col-lg-12">
                                <div class="table-responsive">
                                    <table class="table table-striped m-table m-table--head-bg-default">
                                        <thead class="bg">
                                        <tr>
                                            <th style="width: 40%" class="tr_thead_list"></th>
                                            <th style="width: 30%" class="tr_thead_list">{{__('TRƯỚC CẬP NHẬT')}}</th>
                                            <th style="width: 30%" class="tr_thead_list">{{__('SAU CẬP NHẬT')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($logInfo) && count($logInfo) > 0)
                                                @foreach($logInfo as $key => $value)
                                                    @switch($value['key'])
                                                        @case('contract_annex_code')
                                                        <tr>
                                                            <td>@lang('Mã phụ lục')</td>
                                                            <td>{{$value['value_old']}}</td>
                                                            <td>{{$value['value_new']}}</td>
                                                        </tr>
                                                        @break
                                                        @case('sign_date')
                                                        <tr>
                                                            <td>@lang('Ngày ký')</td>
                                                            <td>{{$value['value_old']}}</td>
                                                            <td>{{$value['value_new']}}</td>
{{--                                                            <td>{{date('d/m/Y', strtotime($value['value_old']))}}</td>--}}
{{--                                                            <td>{{date('d/m/Y', strtotime($value['value_new']))}}</td>--}}
                                                        </tr>
                                                        @break
                                                        @case('effective_date')
                                                        <tr>
                                                            <td>@lang('Ngày có hiệu lực')</td>
                                                            <td>{{$value['value_old']}}</td>
                                                            <td>{{$value['value_new']}}</td>
{{--                                                            <td>{{date('d/m/Y', strtotime($value['value_old']))}}</td>--}}
{{--                                                            <td>{{date('d/m/Y', strtotime($value['value_new']))}}</td>--}}
                                                        </tr>
                                                        @break
                                                        @case('expired_date')
                                                        <tr>
                                                            <td>@lang('Ngày hết hiệu lực')</td>
                                                            <td>{{$value['value_old']}}</td>
                                                            <td>{{$value['value_new']}}</td>
{{--                                                            <td>{{date('d/m/Y', strtotime($value['value_old']))}}</td>--}}
{{--                                                            <td>{{date('d/m/Y', strtotime($value['value_new']))}}</td>--}}
                                                        </tr>
                                                        @break
                                                        @case('adjustment_type')
                                                        <tr>
                                                            <td>@lang('Loại điều chỉnh')</td>
                                                            <td>{{$value['value_old'] == 'update_contract'
                                                            ? __('Cập nhật hợp đồng')
                                                            : ($value['value_old'] == 'renew_contract'
                                                                ? __('Gia hạn hợp đồng')
                                                                : __('Bổ sung thông tin'))}}</td>
                                                            <td>{{$value['value_new'] == 'update_contract'
                                                            ? __('Cập nhật hợp đồng')
                                                            : ($value['value_new'] == 'renew_contract'
                                                                ? __('Gia hạn hợp đồng')
                                                                : __('Bổ sung thông tin'))}}</td>
                                                        </tr>
                                                        @break
                                                        @case('content')
                                                        <tr>
                                                            <td>@lang('Nội dung')</td>
                                                            <td>{{$value['value_old']}}</td>
                                                            <td>{{$value['value_new']}}</td>
                                                        </tr>
                                                        @break
                                                        @case('is_active')
                                                        <tr>
                                                            <td>@lang('Trạng thái')</td>
                                                            <td>{{$value['value_old'] == 1 ? 'Có' : 'Không'}}</td>
                                                            <td>{{$value['value_new'] == 1 ? 'Có' : 'Không'}}</td>
                                                        </tr>
                                                        @break
                                                    @endswitch
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

@endsection
@section("after_style")
    <style>
        .color_title {
            color: #008990 !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
        }
    </style>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
@stop


