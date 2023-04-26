@extends('layout')
@section('title_header')
    <span class="title_header">{{ __('managerwork::managerwork.manage_lead') }}</span>
@stop
@section('content')
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
                        <span> {{ __('managerwork::managerwork.list_lead') }}</span>
                    </span>
                    </a>
                @endif
                @if(in_array('customer-lead.create',session('routeList')))
                    <a href="javascript:void(0)" onclick="create.popupCreate(true)"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> {{ __('managerwork::managerwork.add_lead') }}</span>
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
            <div class="form-group m-form__group row">
                <div class="col-lg-3">
                    <div class="input-group">
                        <input type="input" class="form-control" id="search" name="search"
                               onchange="kanBanView.changePipeline()" placeholder="{{ __('managerwork::managerwork.input_search') }}">
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <select class="form-control" id="pipeline_id" name="pipeline_id"
                                style="width:100%;" onchange="kanBanView.changePipeline()">
                            {{-- @foreach($optionPipeline as $v)
                                <option value="{{$v['pipeline_id']}}" {{$v['is_default'] == 1 ? 'selected' : ''}}>{{$v['pipeline_name']}}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="input-group">
                        <select class="form-control" id="customer_type_filter" name="customer_type_filter"
                                style="width:100%;" onchange="kanBanView.changePipeline()">
                            <option></option>
                            <option value="personal">{{ __('managerwork::managerwork.personal') }}</option>
                            <option value="business">{{ __('managerwork::managerwork.business') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3">
                    <button class="btn btn-primary color_button btn-search" onclick="kanBanView.changePipeline()">
                        {{ __('managerwork::managerwork.search') }} <i class="fa fa-search ic-search m--margin-left-5"></i>
                    </button>
                </div>
            </div>

            <div class="parent_kanban" id="m_blockui_1_content">
                <div id="kanban"></div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="kanban2"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet"
          href="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/styles/jqx.base.css')}}"
          type="text/css"/>
    <script src="{{ asset('static/backend/js/manager-project/managerWork/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxcore.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxsortable.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxkanban.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxsplitter.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/jqxdata.js')}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/kanban/jqwidgets/demos.js')}}"
            type="text/javascript"></script>
    {{-- <script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js')}}"
            type="text/javascript"></script> --}}
    <script>
        $(document).ready(function () {

            var fields = [
                     { name: "id", type: "string" },
                     { name: "status", map: "state", type: "string" },
                     { name: "text", map: "label", type: "string" },
                     { name: "tags", type: "string" },
                     { name: "color", map: "hex", type: "string" },
                     { name: "resourceId", type: "number" }
            ];

            var source =
             {
                 localData: [
                          { id: "1161", state: "new", label: "Combine Orders", tags: "orders, combine", hex: "#5dc3f0", resourceId: 3 },
                          { id: "1645", state: "work", label: "Change Billing Address", tags: "billing", hex: "#f19b60", resourceId: 1 },
                          { id: "9213", state: "new", label: "One item added to the cart", tags: "cart", hex: "#5dc3f0", resourceId: 3 },
                          { id: "6546", state: "done", label: "Edit Item Price", tags: "price, edit", hex: "#5dc3f0", resourceId: 4 },
                          { id: "9034", state: "new", label: "Login 404 issue", tags: "issue, login", hex: "#6bbd49" }
                 ],
                 dataType: "array",
                 dataFields: fields
             };

            var dataAdapter = new $.jqx.dataAdapter(source);

            var resourcesAdapterFunc = function () {
                var resourcesSource =
                {
                    localData: [
                          { id: 0, name: "No name", image: "../../../jqwidgets/styles/images/common.png", common: true },
                          { id: 1, name: "Andrew Fuller", image: "../../../images/andrew.png" },
                          { id: 2, name: "Janet Leverling", image: "../../../images/janet.png" },
                          { id: 3, name: "Steven Buchanan", image: "../../../images/steven.png" },
                          { id: 4, name: "Nancy Davolio", image: "../../../images/nancy.png" },
                          { id: 5, name: "Michael Buchanan", image: "../../../images/Michael.png" },
                          { id: 6, name: "Margaret Buchanan", image: "../../../images/margaret.png" },
                          { id: 7, name: "Robert Buchanan", image: "../../../images/robert.png" },
                          { id: 8, name: "Laura Buchanan", image: "../../../images/Laura.png" },
                          { id: 9, name: "Laura Buchanan", image: "../../../images/Anne.png" }
                    ],
                    dataType: "array",
                    dataFields: [
                         { name: "id", type: "number" },
                         { name: "name", type: "string" },
                         { name: "image", type: "string" },
                         { name: "common", type: "boolean" }
                    ]
                };

                var resourcesDataAdapter = new $.jqx.dataAdapter(resourcesSource);
                return resourcesDataAdapter;
            }

            $('#kanban2').jqxKanban({
                resources: resourcesAdapterFunc(),
                source: dataAdapter,
                width: '100%',
                height: '100%',
                columns: [
                    { text: "Backlog", dataField: "new" },
                    { text: "In Progress", dataField: "work" },
                    { text: "Done", dataField: "done" }
                ]
            });
        });
    </script>
@stop


