@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ DANH SÁCH VẬT TƯ')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH VẬT TƯ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{-- @if(in_array('ticket.material.add',session('routeList'))) --}}
                <a href="javascript:void(0)" onclick="Material.configSearch()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> {{ __('CẤU HÌNH') }}</span>
                    </span>
                </a>
                <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="Material.clear()"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIẾU YÊU CẦU')}}</span>
                        </span>
                    </a>
                {{-- @endif --}}
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    @foreach ($searchConfig as $config)
                        @if ($config['active'])
                            @if ($config['type'] == 'select2')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <select name="{{ $config['name'] }}"
                                            class="form-control select2 select2-active">
                                            <option value="">{{ $config['placeholder'] }}</option>
                                            @foreach ($config['data'] as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif($config['type'] == 'daterange_picker')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control daterange-picker"
                                                style="background-color: #fff" name="{{ $config['name'] }}" autocomplete="off"
                                                placeholder="{{ $config['placeholder'] }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($config['type'] == 'text')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="{{ $config['name'] }}"
                                            placeholder="{{ $config['placeholder'] }}">
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <div class="col-lg-3">
                        <div class="d-flex">
                            <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </button>
                            <button class="btn btn-primary color_button btn-search" style="display: block">
                                @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('ticket::material.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::material.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::material.edit')
        </div>
    </div>
    <div class="modal fade" id="modalReplace" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::material.replace_material')
        </div>
    </div>
    <div class="modal fade" id="modalView" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('ticket::material.view')
        </div>
    </div>
    @include('ticket::material.popup.config_search')
    <div class="d-none">
        <div id="input-counter">
            <div class="number">
                <span class="minus">-</span>
                <input type="number" class="number-input" name="{product_id}" value="{value}" min="0" max="{max}" />
                <span class="plus">+</span>
            </div>
        </div>
        <div id="select-status">
            <select name="{product_id}" class="form-control mw-100px">
                @foreach ($statusMaterialItem as $key => $val)
                    <option value="{{$key}}">{{$val}}</option>
                @endforeach
            </select>
        </div>
    </div>
@stop
@section('after_script')
@include('ticket::language.lang')
<script>
        jQuery.extend(jQuery.validator.messages, {
        // required: "This field is required.",
        // remote: "Please fix this field.",
        // email: "Please enter a valid email address.",
        // url: "Please enter a valid URL.",
        // date: "Please enter a valid date.",
        // dateISO: "Please enter a valid date (ISO).",
        number: "{{ __('Vui lòng nhập một số hợp lệ.') }}",
        // digits: "Please enter only digits.",
        // creditcard: "Please enter a valid credit card number.",
        // equalTo: "Please enter the same value again.",
        // accept: "Please enter a value with a valid extension.",
        maxlength: jQuery.validator.format("{{ __('Vui lòng nhập không quá {0} ký tự.') }}"),
        minlength: jQuery.validator.format("{{ __('Vui lòng nhập ít nhất {0} ký tự.') }}"),
        // rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
        // range: jQuery.validator.format("Please enter a value between {0} and {1}."),
        max: jQuery.validator.format("{{ __('Vui lòng nhập giá trị nhỏ hơn hoặc bằng {0}.') }}"),
        min: jQuery.validator.format("{{ __('Vui lòng nhập giá trị lớn hơn hoặc bằng {0}.') }}")
    });
</script>
    <script src="{{asset('static/backend/js/ticket/material/list.js?v='.time())}}" type="text/javascript"></script>
    <script>
        @if($ticket_request_material_id != null)
            Material.view(`{{$ticket_request_material_id}}`);
        @endif
    </script>
    
@stop
