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
                        @lang('CHI TIẾT HỢP ĐỒNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('contract.contract')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                </a>

                @if($infoGeneral['is_browse'] == 0)
                    <a href="javascript:void(0)" onclick="detail.showModalStatus({{$infoGeneral['contract_id']}})"
                       class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-edit"></i>
                            <span>@lang('CẬP NHẬT TRẠNG THÁI')</span>
                        </span>
                    </a>
                    @if(in_array('contract.contract.edit', session()->get('routeList')))
                    <a href="{{route('contract.contract.edit', $infoGeneral['contract_id'])}}"
                       class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-edit"></i>
                            <span>@lang('CHỈNH SỬA')</span>
                        </span>
                    </a>
                    @endif
                @else
                    @if(isset($browse['can_browse']) && $browse['can_browse'] == 1)
                        @if(in_array('contract.contract-browse.confirm', session()->get('routeList')))
                        <a href="javascript:void(0)" onclick="listBrowse.confirm({{isset($browse['contract_browse_id']) ? $browse['contract_browse_id'] : ''}})"
                           class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-edit"></i>
                                <span>@lang('DUYỆT')</span>
                            </span>
                        </a>
                        @endif
                        @if(in_array('contract.contract-browse.refuse', session()->get('routeList')))
                        <a href="javascript:void(0)" onclick="listBrowse.showModalRefuse({{isset($browse['contract_browse_id']) ? $browse['contract_browse_id'] : ''}})"
                           class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-remove"></i>
                                <span>@lang('TỪ CHỐI')</span>
                            </span>
                        </a>
                        @endif
                    @endif
                @endif
                @if(isset($isCreateTicket) && $isCreateTicket != 0)
                    @if(in_array('ticket.add', session()->get('routeList')))
                    <a href="/ticket/add?contract={{$infoGeneral['contract_id']}}" target="_blank"
                       class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                        <span>
                            <i class="la la-check"></i>
                            <span>@lang('TẠO YÊU CẦU')</span>
                        </span>
                    </a>
                    @endif
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <?php $i = 0; ?>
            @foreach($optionStatus as $v)
                @if($v['status_code'] == $infoGeneral['status_code'])
                    @break;
                @else
                    <?php $i++; ?>
                @endif
            @endforeach
            <ol class="stepBar step{{count($optionStatus)}}">
                @foreach($optionStatus as $key => $value)
                    <li class="step {{$key <= $i ? 'current': ''}}">
                        {{$value['status_name']}}
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
    <div class="row">
        {{-- <div class="col-lg-3">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <h3 class="m-portlet__head-text">
                            <i class="flaticon-statistics"></i>

                            @if (in_array($infoPartner['partner_object_type'], ['personal', 'business']))
                                {{$infoPartner['customer_name']}}
                            @else
                                {{$infoPartner['supplier_name']}}
                            @endif
                        </h3>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Loại khách hàng'):
                        </div>
                        <div class="col-lg-7">
                            @if ($infoPartner['partner_object_type'] == 'personal')
                                @lang('Cá nhân')
                            @elseif ($infoPartner['partner_object_type'] == 'business')
                                @lang('Doanh nghiệp')
                            @else
                                @lang('Đối tác')
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Mã số thuế'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPartner['tax_code']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Số điện thoại'):
                        </div>
                        <div class="col-lg-7">
                            @if (in_array($infoPartner['partner_object_type'], ['personal', 'business']))
                                {{$infoPartner['customer_phone']}}
                            @else
                                {{$infoPartner['supplier_phone']}}
                            @endif
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Địa chỉ'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPartner['address']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Email'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPartner['email']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Người đại diện'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPartner['representative']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Chức vụ'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPartner['staff_title']}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <h3 class="m-portlet__head-text">
                            @lang('Thông tin hợp đồng')
                        </h3>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Mã hợp đồng'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoGeneral['contract_code']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Số hợp đồng'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoGeneral['contract_no']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Loại hợp đồng'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoGeneral['contract_category_name']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Trạng thái'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoGeneral['status_name']}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Ngày hiệu lực'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoGeneral['effective_date'] != null ? \Carbon\Carbon::parse($infoGeneral['effective_date'])->format('d/m/Y') : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Ngày hết hạn'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoGeneral['expired_date'] != null ? \Carbon\Carbon::parse($infoGeneral['expired_date'])->format('d/m/Y') : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Giá trị hợp đồng'):
                        </div>
                        <div class="col-lg-7">
                            {{number_format($infoPayment != null ? $infoPayment['last_total_amount'] : 0, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Tổng giá trị đã thanh toán'):
                        </div>
                        <div class="col-lg-7">
                            {{number_format($totalReceipt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Giá trị chưa thanh toán'):
                        </div>
                        <div class="col-lg-7">
                            {{number_format($totalNotReceipt, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Đơn hàng'):
                        </div>
                        <div class="col-lg-7">
                            {{$orderCode}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <h3 class="m-portlet__head-text">
                            @lang('Nhân viên phụ trách')
                        </h3>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Tên'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPerformer != null ? $infoPerformer['full_name'] : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Chức vụ'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPerformer != null ? $infoPerformer['staff_title_name'] : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Phòng ban'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPerformer != null ? $infoPerformer['department_name'] : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Số điện thoại'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPerformer != null ? $infoPerformer['phone'] : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Email'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPerformer != null ? $infoPerformer['email'] : ''}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5 font-weight-bold">
                            @lang('Địa chỉ'):
                        </div>
                        <div class="col-lg-7">
                            {{$infoPerformer != null ? $infoPerformer['address'] : ''}}
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

        </div> --}}
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet">
                <div class="m-portlet__head" style="border-bottom: none;">
                    <div class="form-group">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#info" role="tab"
                                   aria-selected="true">
                                    @lang('Thông tin chung')
                                </a>
                            </li>
                            @if ($infoCategory['type'] == 'sell')
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#expected-receipt"
                                       role="tab"
                                       aria-selected="false">
                                        @lang('Dự kiến thu')
                                    </a>
                                </li>
                                <li class="nav-item m-tabs__item">
                                    <a class="nav-link m-tabs__link" data-toggle="tab" href="#receipt" role="tab"
                                       aria-selected="false">
                                        @lang('Chi tiết thu')
                                    </a>
                                </li>
                            @endif
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#expected-spend" role="tab"
                                   aria-selected="false">
                                    @lang('Dự kiến chi')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#spend" role="tab"
                                   aria-selected="false">
                                    @lang('Chi tiết chi')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#contract-file" role="tab"
                                   aria-selected="false">
                                    @lang('Đính kèm')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#goods" role="tab"
                                   aria-selected="false">
                                    @lang('Hàng hoá')
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#annex" role="tab"
                                   aria-selected="false">
                                    @lang('Phụ lục')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group tab-content">
                        <div class="tab-pane active show" id="info" role="tabpanel">
                            <form id="form-info">
                                <div id="group-info">
                                    @include('contract::contract.inc.info.view-info-detail')
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane" id="expected-receipt" role="tabpanel">
                            
                            <div id="autotable-expected-receipt">
                               
                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">
                                            <input type="hidden" name="type" value="receipt">

                                            <button class="btn btn-primary btn-search btn-search-expected-receipt"
                                                    style="display: none;">
                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                            @if(in_array('contract.contract.edit', session()->get('routeList')))
                                            <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=expected-receipt"
                                            class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-edit"></i>
                                                    <span>@lang('CHỈNH SỬA')</span>
                                                </span>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                <div class="table-content m--padding-top-15">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="receipt" role="tabpanel">
                            <div id="autotable-receipt">
                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">

                                            <button class="btn btn-primary btn-search btn-search-receipt"
                                                    style="display: none;">
                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                            @if(in_array('contract.contract.edit', session()->get('routeList')))
                                            <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=receipt"
                                            class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-edit"></i>
                                                    <span>@lang('CHỈNH SỬA')</span>
                                                </span>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                <div class="table-content m--padding-top-15">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="expected-spend" role="tabpanel">
                            <div id="autotable-expected-spend">
                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">
                                            <input type="hidden" name="type" value="spend">

                                            <button class="btn btn-primary btn-search btn-search-expected-spend"
                                                    style="display: none;">
                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                            @if(in_array('contract.contract.edit', session()->get('routeList')))
                                            <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=expected-spend"
                                            class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-edit"></i>
                                                    <span>@lang('CHỈNH SỬA')</span>
                                                </span>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                <div class="table-content m--padding-top-15">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="spend" role="tabpanel">
                            <div id="autotable-spend">
                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">

                                            <button class="btn btn-primary btn-search btn-search-spend"
                                                    style="display: none;">
                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                            @if(in_array('contract.contract.edit', session()->get('routeList')))
                                            <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=spend"
                                            class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                <span>
                                                    <i class="la la-edit"></i>
                                                    <span>@lang('CHỈNH SỬA')</span>
                                                </span>
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>

                                <div class="table-content m--padding-top-15">

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="contract-file" role="tabpanel">
                            <div id="autotable-file">
                                
                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">
        
                                            <button class="btn btn-primary btn-search btn-search-file"
                                                    style="display: none;">
                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                            @if(in_array('contract.contract.edit', session()->get('routeList')))
                                                <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=contract-file"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                    <span>
                                                        <i class="la la-edit"></i>
                                                        <span>@lang('CHỈNH SỬA')</span>
                                                    </span>
                                                </a>
                                                @endif
                                        </div>
                                    </div>
                                    
                                </form>
        
                                <div class="table-content m--padding-top-15">
        
                                </div>
                            </div>
                          
                            
                            {{-- <div id="autotable-file">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">

                                        <button class="btn btn-primary btn-search btn-search-file"
                                                style="display: none;">
                                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                        @if(in_array('contract.contract.edit', session()->get('routeList')))
                                        <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=contract-file"
                                        class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                            <span>
                                                <i class="la la-edit"></i>
                                                <span>@lang('CHỈNH SỬA')</span>
                                            </span>
                                        </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="table-content m--padding-top-15">
                                    <div class="table-responsive"></div>
                                </div>
                            </div> --}}
                        </div>
                        <div class="tab-pane" id="goods" role="tabpanel">
                            <div class="row">
                                <div class="col-lg-12">
                                    @if(in_array('contract.contract.edit', session()->get('routeList')))
                                    <a href="/contract/contract/edit/{{$infoGeneral['contract_id']}}?tab=goods"
                                    class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                        <span>
                                            <i class="la la-edit"></i>
                                            <span>@lang('CHỈNH SỬA')</span>
                                        </span>
                                    </a>
                                    @endif
                                </div>
                            </div><br>
                            <div id="list-goods"></div>
                        </div>
                        <div class="tab-pane" id="annex" role="tabpanel">
                            <div class="row" id="autotable-annex">
                                <div class="form-group m-form__group col-lg-12">
                                    @if(in_array('contract.contract.get-popup-annex', session()->get('routeList')))
                                        <button type="button" onclick="contractAnnex.popupAddContractAnnex()"
                                                class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                                                    <span>
                                                        <i class="la la-check"></i>
                                                        <span>@lang('THÊM')</span>
                                                    </span>
                                        </button>
                                    @endif
                                </div>
                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <input type="text" hidden class="form-control m-input" name="contract_id"
                                           value="{{$infoGeneral['contract_id']}}">
                                            <button class="btn btn-primary btn-search btn-search-annex color_button" hidden>
                                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                           
                                        </div>
                                    </div>
                                </form>
                                <div class="table-content m--padding-top-15 col-lg-12">
                                    @include('contract::contract.list-annex')
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-annex-modal"></div>

    <input type="hidden" id="show_category" name="show_category" value="0">
    <input type="hidden" id="contract_category_id_hidden" name="contract_category_id_hidden"
           value="{{$infoGeneral['contract_category_id']}}">
    <input type="hidden" id="contract_id_hidden" name="contract_id_hidden" value="{{$infoGeneral['contract_id']}}">
@endsection
@section("after_style")
    <style>
        .color_title {
            color: #008990 !important;
            font-weight: bold !important;
            font-size: 1.1rem !important;
        }

        .stepBar {
            position: relative;
            list-style: none;
            margin: 0 0 1em;
            padding: 0;
            text-align: center;
            width: 100%;
            overflow: hidden;
            *zoom: 1
        }

        .stepBar .step {
            position: relative;
            float: left;
            display: inline-block;
            line-height: 40px;
            padding: 0 40px 0 20px;
            background-color: #eee;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        .stepBar .step:before, .stepBar .step:after {
            position: absolute;
            left: -15px;
            display: block;
            content: '';
            background-color: #eee;
            border-left: 4px solid #FFF;
            width: 20px;
            height: 20px
        }

        .stepBar .step:after {
            top: 0;
            -moz-transform: skew(30deg);
            -ms-transform: skew(30deg);
            -webkit-transform: skew(30deg);
            transform: skew(30deg)
        }

        .stepBar .step:before {
            bottom: 0;
            -moz-transform: skew(-30deg);
            -ms-transform: skew(-30deg);
            -webkit-transform: skew(-30deg);
            transform: skew(-30deg)
        }

        .stepBar .step:first-child {
            -moz-border-radius-topleft: 4px;
            -webkit-border-top-left-radius: 4px;
            border-top-left-radius: 4px;
            -moz-border-radius-bottomleft: 4px;
            -webkit-border-bottom-left-radius: 4px;
            border-bottom-left-radius: 4px
        }

        .stepBar .step:first-child:before, .stepBar .step:first-child:after {
            content: none
        }

        .stepBar .step:last-child {
            -moz-border-radius-topright: 4px;
            -webkit-border-top-right-radius: 4px;
            border-top-right-radius: 4px;
            -moz-border-radius-bottomright: 4px;
            -webkit-border-bottom-right-radius: 4px;
            border-bottom-right-radius: 4px
        }

        .stepBar .step.current {
            color: #FFF;
            background-color: #0067AC
        }

        .stepBar .step.current:before, .stepBar .step.current:after {
            background-color: #0067AC
        }

        .stepBar.step2 .step {
            width: 50%
        }

        .stepBar.step3 .step {
            width: 33.333%
        }

        .stepBar.step4 .step {
            width: 25%
        }

        .stepBar.step5 .step {
            width: 20%
        }

        .stepBar.step6 .step {
            width: 16%
        }

        .stepBar.step7 .step {
            width: 14%
        }

        .stepBar.step8 .step {
            width: 12.5%
        }
    </style>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/contract/contract/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/contract/contract-browse/list.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._initEdit();
    </script>
@stop


