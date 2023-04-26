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
{{--                @if(in_array('customer-lead.customer-deal',session('routeList')))--}}
                    <a href="{{route('customer-lead.customer-deal')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                    <span>
                        <i class="la la-eye"></i>
                        <span> @lang('DANH SÁCH DEAL')</span>
                    </span>
                    </a>
{{--                @endif--}}
{{--                @if(in_array('customer-lead.create',session('routeList')))--}}
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
{{--                @endif--}}
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="form-group m-form__group row">
                <div class="col-lg-4 form-group">
                    <div class="form-group">
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="@lang("Nhập tên khách hàng")">
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <select class="form-control" id="kanban_pipeline_id" name="pipeline_id"
                                style="width:100%;" onchange="kanBanView.changePipeline()">
                            @foreach($optionPipeline as $v)
                                <option value="{{$v['pipeline_id']}}" {{$v['is_default'] == 1 ? 'selected' : ''}}>{{$v['pipeline_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <select class="form-control" id="kanban_type_customer" name="type_customer"
                                style="width:100%;">
                            <option value="">@lang("Chọn loại khách hàng")</option>
                            <option value="customer">@lang('Khách hàng')</option>
                            <option value="lead">@lang('Khách hàng tiềm năng')</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <select class="form-control" style="width:100%;"
                                id="kanban_order_source_id"
                                name="order_source_id">
                            <option value="">@lang("Chọn nguồn đơn hàng")</option>
                            @foreach($optionOrderSource as $key => $value)
                                <option value="{{$value['order_source_id']}}">{{$value['order_source_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input readonly class="form-control m-input daterange-picker"
                               style="background-color: #fff"
                               id="kanban_closing_date"
                               name="closing_date"
                               autocomplete="off" placeholder="@lang('NGÀY KẾT THÚC DỰ KIẾN')">
                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <div class="m-input-icon m-input-icon--right">
                        <select class="form-control" style="width:100%;"
                                id="kanban_branch_code"
                                name="branch_code">
                            <option value="">@lang("Chọn chi nhánh")</option>
                            @foreach($optionBranches as $key => $value)
                                <option value="{{$value['branch_code']}}">{{$value['branch_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
{{--                    <div class="m-input-icon m-input-icon--right">--}}
{{--                        <select class="form-control" id="kanban_care_type" name="care_type"--}}
{{--                                style="width:100%;">--}}
{{--                            <option value="">@lang("Chọn loại chăm sóc khách hàng")</option>--}}
{{--                            <option value="chat">@lang('Trò chuyện')</option>--}}
{{--                            <option value="call">@lang('Gọi điện')</option>--}}
{{--                            <option value="email">@lang('Email')</option>--}}
{{--                        </select>--}}
{{--                    </div>--}}
                    <select class="form-control" id="select_manage_type_work_id" name="select_manage_type_work_id"
                            style="width:100%;" onchange="kanBanView.changePipeline()">
                        <option value="">{{__('Chọn loại chăm sóc khách hàng')}}</option>
                        @foreach ($listWorkType as $itemType)
                            <option value="{{$itemType['manage_type_work_id']}}">{{$itemType['manage_type_work_name']}}</option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="dataField" id="dataField" value="">
                <input type="hidden" name="search_manage_type_work_id" id="search_manage_type_work_id" value="">

                <div class="col-lg-2 form-group">
                    <button class="btn btn-primary color_button btn-search" onclick="kanBanView.changePipeline()">
                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                    </button>
                </div>
            </div>
            <div class="parent_kanban" id="m_blockui_1_content">
                <div id="kanban"></div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="my-modal-create-customer"></div>
    <div id="popup-work-edit"></div>
    <div id="vund_popup"></div>
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
        kanBanView._init();
        kanBanView.loadKanban();
        $('#select_manage_type_work_id').select2();
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


