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
                        @lang('CHỈNH SỬA HỢP ĐỒNG')
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
                <div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()"
                     class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new"
                     m-dropdown-toggle="hover" aria-expanded="true">
                    <a href="#"
                       class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--icon m-btn--icon-only m-dropdown__toggle">
                        <i class="la la-plus m--hide"></i>
                        <i class="la la-ellipsis-h"></i>
                    </a>
                    <div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none">
                            <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"
                                  style="left: auto; right: 21.5px;"></span>
                        <div class="m-dropdown__inner">
                            <div class="m-dropdown__body">
                                <div class="m-dropdown__content">
                                    <ul class="m-nav">
                                        <li class="m-nav__item">
                                            <a onclick="addQuickly.showPopupAddQuicklyCustomer()" href="javascript:void(0);" class="m-nav__link">
                                                <i class="m-nav__link-icon fa fa-user-plus"></i>
                                                <span class="m-nav__link-text">{{__("Thêm Khách hàng")}}</span>
                                            </a>
                                        </li>
                                        @if($infoCategory['type'] == 'buy')
                                        <li class="m-nav__item">
                                            <a data-toggle="modal"
                                               data-target="#add-supplier-quickly" href="" class="m-nav__link">
                                                <i class="m-nav__link-icon fa fa-user-plus"></i>
                                                <span class="m-nav__link-text">{{__("Thêm nhà cung cấp")}}</span>
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="m-portlet__body">
            <div class="form-group">
                <ul class="nav nav-tabs m-tabs-line m-tabs-line--primary m-tabs-line--2x" role="tablist">
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link {{ $tab == '' ? 'active show' : '' }}" data-toggle="tab" href="#info" role="tab"
                           aria-selected="true">
                            @lang('Thông tin chung')
                        </a>
                    </li>
                    @if ($infoCategory['type'] == 'sell')
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link {{ $tab == 'expected-receipt' ? 'active show' : '' }}" data-toggle="tab" href="#expected-receipt" role="tab"
                               aria-selected="false">
                                @lang('Dự kiến thu')
                            </a>
                        </li>
                        <li class="nav-item m-tabs__item">
                            <a class="nav-link m-tabs__link {{ $tab == 'receipt' ? 'active show' : '' }}" data-toggle="tab" href="#receipt" role="tab"
                               aria-selected="false">
                                @lang('Chi tiết thu')
                            </a>
                        </li>
                    @endif
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link {{  $tab == 'expected-spend' ? 'active show' : ''  }}" data-toggle="tab" href="#expected-spend" role="tab"
                           aria-selected="false">
                            @lang('Dự kiến chi')
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link {{  $tab == 'spend' ? 'active show' : ''  }}" data-toggle="tab" href="#spend" role="tab"
                           aria-selected="false">
                            @lang('Chi tiết chi')
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link {{  $tab == 'contract-file' ? 'active show' : ''  }}" data-toggle="tab" href="#contract-file" role="tab"
                           aria-selected="false">
                            @lang('Đính kèm')
                        </a>
                    </li>
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link {{  $tab == 'goods' ? 'active show' : ''  }}" data-toggle="tab" href="#goods" role="tab"
                           aria-selected="false">
                            @lang('Hàng hoá')
                        </a>
                    </li>
                </ul>
            </div>
            <div class="form-group tab-content">
                <div class="tab-pane {{ $tab == '' ? 'active show' : '' }}" id="info" role="tabpanel">
                    <form id="form-info">
                        <div id="group-info">
                            @include('contract::contract.inc.info.view-info-edit')
                        </div>

                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                <a href="{{route('contract.contract')}}"
                                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                                </a>
                                <button type="button" onclick="edit.saveInfo('{{$infoGeneral['contract_id']}}')"
                                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane {{ $tab == 'expected-receipt' ? 'active show' : '' }}" id="expected-receipt" role="tabpanel">
                    <a href="javascript:void(0)" onclick="expectedRevenue.showModalCreate('receipt')"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM')</span>
                                    </span>
                    </a>

                    <div id="autotable-expected-receipt">
                        <form class="frmFilter bg">
                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">
                            <input type="hidden" name="type" value="receipt">

                            <button class="btn btn-primary btn-search btn-search-expected-receipt"
                                    style="display: none;">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>

                        </form>

                        <div class="table-content m--padding-top-15">

                        </div>
                    </div>
                </div>
                <div class="tab-pane {{ $tab == 'receipt' ? 'active show' : '' }}" id="receipt" role="tabpanel">
                    <a href="javascript:void(0)" onclick="contractReceipt.showModalCreate()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM')</span>
                                    </span>
                    </a>
                    <div id="autotable-receipt">
                        <form class="frmFilter bg">
                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">

                            <button class="btn btn-primary btn-search btn-search-receipt"
                                    style="display: none;">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>

                        </form>

                        <div class="table-content m--padding-top-15">

                        </div>
                    </div>
                </div>
                <div class="tab-pane {{ $tab == 'expected-spend' ? 'active show' : '' }}" id="expected-spend" role="tabpanel">
                    <a href="javascript:void(0)" onclick="expectedRevenue.showModalCreate('spend')"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM')</span>
                                    </span>
                    </a>

                    <div id="autotable-expected-spend">
                        <form class="frmFilter bg">
                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">
                            <input type="hidden" name="type" value="spend">

                            <button class="btn btn-primary btn-search btn-search-expected-spend"
                                    style="display: none;">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>

                        </form>

                        <div class="table-content m--padding-top-15">

                        </div>
                    </div>
                </div>
                <div class="tab-pane {{ $tab == 'spend' ? 'active show' : '' }}" id="spend" role="tabpanel">
                    <a href="javascript:void(0)" onclick="contractSpend.showModalCreate()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM')</span>
                                    </span>
                    </a>
                    <div id="autotable-spend">
                        <form class="frmFilter bg">
                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">

                            <button class="btn btn-primary btn-search btn-search-spend"
                                    style="display: none;">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>

                        </form>

                        <div class="table-content m--padding-top-15">

                        </div>
                    </div>
                </div>
                <div class="tab-pane {{  $tab == 'contract-file' ? 'active show' : ''  }}" id="contract-file" role="tabpanel">
                    <a href="javascript:void(0)" onclick="contractFile.showModalCreate()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM')</span>
                                    </span>
                    </a>
                    <div id="autotable-file">
                        <form class="frmFilter bg">
                            <input type="hidden" name="contract_id" value="{{$infoGeneral['contract_id']}}">

                            <button class="btn btn-primary btn-search btn-search-file"
                                    style="display: none;">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>

                        </form>

                        <div class="table-content m--padding-top-15">

                        </div>
                    </div>
                </div>
                <div class="tab-pane {{  $tab == 'goods' ? 'active show' : ''  }}" id="goods" role="tabpanel">
                    @if ($infoCategory['type'] == 'sell')
                        <div class="row div_search_order">
                            <div class="form-group m-form__group col-lg-4">
                                <div class="m-input-icon m-input-icon--right">
                                    <input type="text" class="form-control m-input" id="search_order"
                                           placeholder="@lang('Nhập mã đơn hàng')">
                                    <a class="m-input-icon__icon m-input-icon__icon--right" href="javascript:void(0)"
                                       onclick="contractGoods.searchOrder()">
                                        <span><i class="la la-search"></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div id="list-goods"></div>
                    <div id="order-goods"></div>

                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                        <div class="m-form__actions m--align-right">
                            <div id="btn-save-good">
                                <button type="button" onclick="contractGoods.save()"
                                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>

    <input type="hidden" id="show_category" name="show_category" value="0">
    <input type="hidden" id="contract_category_id_hidden" name="contract_category_id_hidden"
           value="{{$infoGeneral['contract_category_id']}}">
    <input type="hidden" id="contract_id_hidden" name="contract_id_hidden" value="{{$infoGeneral['contract_id']}}">
    <input type="hidden" id="contract_category_type" name="contract_category_type" value="{{$infoCategory['type']}}">
    <input type="hidden" id="contract_source" name="contract_source">
    @include('contract::contract.pop.add-supplier')
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
    <script src="{{asset('static/backend/js/contract/contract/script.js')}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/contract/vat/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        function changeCustomerType(e){
            if($(e).val() == 'personal'){
                $('.open-business-input').attr('hidden', true);
            }else{
                $('.open-business-input').removeAttr('hidden');
            }
        }
        function onmouseoverAddNew() {
            $('.dropdow-add-new').show();
        }

        function onmouseoutAddNew() {
            $('.dropdow-add-new').hide();
        }
        view._initEdit();
    </script>

{{--    list script type="text/template"--}}
    @include('contract::contract.template-script.contract-template-view-edit')
@stop


