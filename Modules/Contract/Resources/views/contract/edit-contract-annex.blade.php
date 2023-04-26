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
                    <li class="nav-item m-tabs__item">
                        <a class="nav-link m-tabs__link" data-toggle="tab" href="#goods" role="tab"
                           aria-selected="false">
                            @lang('Hàng hoá')
                        </a>
                    </li>
                </ul>
            </div>
            <div class="form-group tab-content">
                <div class="tab-pane active show" id="info" role="tabpanel">
                    <form id="form-info">
                        <div id="group-info">
                            @include('contract::contract.inc.annex.view-info-edit')
                        </div>
                        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                            <div class="m-form__actions m--align-right">
                                <a href="{{route('contract.contract.show', [ "id" => $infoGeneral['contract_id']])}}"
                                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('QUAY LẠI')</span>
                            </span>
                                </a>
                                <button type="button" onclick="contractAnnex.saveInfoContractAnnex(this,'{{$infoGeneral['contract_id']}}')"
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
                <div class="tab-pane" id="goods" role="tabpanel">
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
                            <a href="{{route('contract.contract.show', [ "id" => $infoGeneral['contract_id']])}}"
                               class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('QUAY LẠI')</span>
                            </span>
                            </a>
                            <button type="button" onclick="contractGoods.saveAnnexGood(this)"
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
    <div id="my-modal"></div>

    <input type="hidden" id="dataAnnexLocal" name="dataAnnexLocal" value="{{json_encode($dataAnnexLocal)}}">
    <input type="hidden" id="show_category" name="show_category" value="0">
    <input type="hidden" id="contract_category_id_hidden" name="contract_category_id_hidden"
           value="{{$infoGeneral['contract_category_id']}}">
    <input type="hidden" id="contract_id_hidden" name="contract_id_hidden" value="{{$infoGeneral['contract_id']}}">
    <input type="hidden" id="contract_category_type" name="contract_category_type" value="{{$infoCategory['type']}}">
    <input type="hidden" id="check_save_all" name="check_save_all" value="0">
    <input type="hidden" id="check_save_good" name="check_save_good" value="0">
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
    <script>
        contractAnnex._initEditContractAnnex();
        // window.history.pushState("", "", '/contract/contract/view-edit-contract-annex');
    </script>

    {{--    list script type="text/template"--}}
    @include('contract::contract.template-script.contract-template-view-edit')
@stop


