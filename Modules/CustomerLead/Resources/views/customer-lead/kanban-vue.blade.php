@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ KHÁCH HÀNG TIỀM NĂNG')</span>
@stop
@section('content')
    <style>
        .jqx-kanban-column-header {
            height: auto !important;
        }
        .select2 {
            width: 100% !important;
        }
        .icon-header-kanban {
            padding-top: 0 !important;
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
        .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-aside-right, .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-wrapper {
            margin-bottom: 0px !important;
        }
        .m-body .m-content {
            padding-bottom: 0px;
        }
        .m-portlet .m-portlet__body {
            padding-bottom: 0px !important;
        }
        .td_vtc{
            vertical-align: middle !important;
        }
        .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-aside-right, .m-footer--push.m-aside-left--enabled:not(.m-footer--fixed) .m-wrapper {
            margin-bottom: 0px !important;
        }
        .m-body .m-content {
            padding-bottom: 0px;
        }
        .m-portlet .m-portlet__body {
            padding-bottom: 0px !important;
        }
    </style>
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" href="{{asset('vue/kanban-customerlead/css/app.css')}}">
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
                @if(in_array('customer-lead',session('routeList')))
                    <a href="{{route('customer-lead')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                    <span>
                        <i class="la la-eye"></i>
                        <span> @lang('DANH SÁCH KHÁCH HÀNG TIỀM NĂNG')</span>
                    </span>
                    </a>
                @endif
                @if(in_array('customer-lead.create',session('routeList')))
                    <a href="javascript:void(0)" onclick="create.popupCreate(true)"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM KHÁCH HÀNG TIỀM NĂNG')</span>
                                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="create.popupCreate(true)"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="app"></div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="popup-work-edit"></div>
    <div id="vund_popup"></div>
    <div id="zone-popup-show"></div>
    <script src="{{asset('vue/kanban-customerlead/js/app.js?v=' . time())}}"></script>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet"
          href="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/styles/jqx.base.css')}}"
          type="text/css"/>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
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
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/customer-comment.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/work.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            let loadingCreate = false;
            listLead.jsonLang = JSON.parse(localStorage.getItem('tranlate'));

            $(document).on('click', '.act-customer-detail', function(){
                let customerLeadId = $(this).attr('customer-lead-id');
                listLead.detail(customerLeadId);
            });

            $(document).on('click', '.act-customer-care', function(){
                let customerLeadId = $(this).attr('customer-lead-id');
                listLead.popupCustomerCare(customerLeadId);
            });

            $(document).on('click', '.act-customer-edit', function(){
                let customerLeadId = $(this).attr('customer-lead-id');
                edit.popupEdit(customerLeadId, true);
            });
        });
        kanBanView._init();
        // kanBanView.loadKanban();
        $('#select_manage_type_work_id').select2();
    </script>
    <script type="text-template" id="tpl-phone">
        <div class="form-group m-form__group div_phone_attach">
            <div class="input-group">
                <input type="hidden" class="number_phone" value="{number}">
                <input type="text" class="form-control phone phone_attach" placeholder="@lang('Số điện thoại')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removePhone(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_phone_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-email">
        <div class="form-group m-form__group div_email_attach">
            <div class="input-group">
                <input type="hidden" class="number_email" value="{number}">
                <input type="text" class="form-control email_attach" placeholder="@lang('Email')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeEmail(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_email_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-fanpage">
        <div class="form-group m-form__group div_fanpage_attach">
            <div class="input-group">
                <input type="hidden" class="number_fanpage" value="{number}">
                <input type="text" class="form-control fanpage_attach" placeholder="@lang('Fan page')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeFanpage(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_fanpage_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text/template" id="tpl-type">
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Mã số thuế'):<b class="text-danger">*</b>
            </label>
            <input type="text" class="form-control m-input" id="tax_code" name="tax_code"
                   placeholder="@lang('Mã số thuế')">
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Người đại diện'):<b class="text-danger">*</b>
            </label>
            <input type="text" class="form-control m-input" id="representative" name="representative"
                   placeholder="@lang('Người đại diện')">
        </div>
        <div class="form-group m-form__group">
            <label class="black_title">
                @lang('Hot line'):<b class="text-danger">*</b>
            </label>
            <input type="text" class="form-control m-input" id="hotline" name="hotline"
                   placeholder="@lang('Hot line')">
        </div>
    </script>
    <script type="text/template" id="tpl-contact">
        <tr class="tr_contact">
            <td>
                <input type="hidden" class="number_contact" value="{number}">
                <input type="text" class="form-control m-input full_name_contact" placeholder="@lang('Họ và tên')">
                <span class="error_full_name_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input phone phone_contact" placeholder="@lang('Số điện thoại')">
                <span class="error_phone_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control email_contact" placeholder="@lang('Email')">
                <span class="error_email_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input address_contact" placeholder="@lang('Địa chỉ')">
                <span class="error_address_contact_{number} color_red"></span>
            </td>
            <td>
                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:void(0)" onclick="view.removeContact(this)">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="detail.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25%;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="detail.changeObject(this)">
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
                <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount"  style="background-color: white;"
                       value="" readonly>
            </td>
            <td>
                <a href="javascript:void(0)" onclick="detail.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
@stop


