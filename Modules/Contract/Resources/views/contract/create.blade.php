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
                        @lang('THÊM HỢP ĐỒNG')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

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
                                                <span class="m-nav__link-text">{{__("Khách hàng")}}</span>
                                            </a>
                                        </li>
                                        <li class="m-nav__item">
                                            <a data-toggle="modal"
                                               data-target="#add-supplier-quickly" href="" class="m-nav__link">
                                                <i class="m-nav__link-icon fa fa-user-plus"></i>
                                                <span class="m-nav__link-text">{{__("Thêm nhà cung cấp")}}</span>
                                            </a>
                                        </li>
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
                        <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#info" role="tab"
                           aria-selected="true">
                            @lang('Thông tin chung')
                        </a>
                    </li>
                </ul>
            </div>
            <div class="form-group tab-content">
                <div class="tab-pane active show" id="info" role="tabpanel">
                    <form id="form-info">
                        <input type="hidden" id="deal_code" name="deal_code" value="{{$dealCode}}">
                        <div id="group-info">

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
                                <button type="button" onclick="create.save()"
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
            </div>
        </div>
    </div>
    <div id="my-modal"></div>

    <input type="hidden" id="show_category" name="show_category" value="{{$showCategory}}">
    <input type="hidden" id="category_id_load" name="category_id_load" value="{{$categoryIdLoad}}">
    <input type="hidden" id="order_code_load" name="order_code_load" value="{{$infoOrder != null ? $infoOrder['order_code'] : ''}}">
    <input type="hidden" id="contract_source" name="contract_source" value="{{$infoOrder != null ? "order" : 'contract'}}">

    @include('contract::contract.pop.choose-category')
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
    <script src="{{asset('static/backend/js/contract/contract/script.js?v='.time())}}" type="text/javascript"></script>
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
        view._init();
    </script>
@stop


