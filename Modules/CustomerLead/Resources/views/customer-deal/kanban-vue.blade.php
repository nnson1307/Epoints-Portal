@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ DEAL')</span>
@stop
@section('content')
    <style>
        .jqx-kanban-column-header {
            height: auto !important;
        }
        .select2 {
            width: 100% !important;
        }
        .jqx-kanban-column-header {
            position: relative;
        }
        .block-image-icon {
            position: absolute;
            top: 0;
            right: 0;
        }
        .parent_kanban {
            /*overflow-x: scroll;*/
            width: 100%;
        }
        #kanban {
            width: 101% !important;
            height: auto !important;
            min-height: 500px !important;
            overflow-x: scroll;
            display: -webkit-inline-box;
        }
        .jqx-kanban-column-show {
            display: inline-grid;
            width: 250px !important;
        }

        .jqx-kanban-column-hidden {
            height: auto !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" href="{{asset('vue/kanban-customerdeal/css/app-deal.css')}}">
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="la la-th-large"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('KAN BAN VIEW')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                    <a href="{{route('customer-lead.customer-deal')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                    <span>
                        <i class="la la-eye"></i>
                        <span> @lang('DANH SÁCH DEAL')</span>
                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="create.popupCreate(true)"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM DEAL')</span>
                                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="create.popupCreate(true)"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="app"></div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-modal-create-customer"></div>
    <div id="popup-work-edit"></div>
    <div id="vund_popup"></div>
    <div id="zone-popup-show"></div>
    <script src="{{asset('vue/kanban-customerdeal/js/app-deal.js?v=' . time())}}"></script>
@endsection
@section("after_style")
    <link rel="stylesheet"
          href="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/styles/jqx.base.css')}}"
          type="text/css"/>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/work.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxcore.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxsortable.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxkanban.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxsplitter.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxdata.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/demos.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-deal/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        // kanBanView._init();
        // kanBanView.loadKanban();
        $('#select_manage_type_work_id').select2();

        $(document).ready(function () {
            let loadingCreate = false;
            listDeal.jsonLang = JSON.parse(localStorage.getItem('tranlate'));

            $(document).on('click', '.act-customer-detail', function(){
                let dealId = $(this).attr('deal-id');
                listDeal.detail(dealId);
            });

            $(document).on('click', '.act-customer-care', function(){
                let dealId = $(this).attr('deal-id');
                listDeal.popupDealCare(dealId);
            });

            $(document).on('click', '.act-customer-edit', function(){
                let dealId = $(this).attr('deal-id');
                edit.popupEdit(dealId, true);
            });
        });
    </script>

    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="view.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25%;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="view.changeObject(this)">
                    <option></option>
                </select>
                <span class="error_object color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input object_price" name="object_price"
                       value="" readonly>
                <input type="hidden" class="object_id" name="object_id">
            </td>
            <td>
                <input type="text" class="form-control m-input object_quantity" name="object_quantity"
                       value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount"
                       value="" readonly>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="view.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop


