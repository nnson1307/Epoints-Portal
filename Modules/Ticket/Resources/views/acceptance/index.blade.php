@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> @lang('ticket::acceptance.manage_acceptance')</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
    <style>
        .modal .select2.select2-container,.select2-search__field{
            width: 100% !important;
        }
        span {cursor:pointer; }
        .number{
            -webkit-user-select: none;
            user-select: none;
        }
		.minus, .plus{
            height: 20px;
            width: 20px;
            text-align: center;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            display: inline-block;
            vertical-align: middle;
		}
		.number .number-input{
			height:20px;
            width: auto;
            text-align: center;
            font-size: 16px;
			border:1px solid #ddd;
			border-radius:4px;
            display: inline-block;
            vertical-align: middle;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance:textfield; /* Firefox */
        }
        .blockUI{
            z-index: 1051 !important;
        }
        .number .form-control-feedback{
            position: absolute;
            color: red;
        }
        .mw-100px{
            min-width: 100px;
        }
    </style>
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
                        @lang('ticket::acceptance.acceptance')
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)" onclick="Acceptance.configSearch()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> @lang('ticket::acceptance.config')</span>
                    </span>
                </a>
                <a href="{{route('ticket.acceptance.add')}}"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('ticket::acceptance.add')}}</span>
                        </span>
                    </a>
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
                                            <option value="0">{{ $config['placeholder'] }}</option>
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
                                {{__('ticket::acceptance.search')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('ticket::acceptance.list')
        </div>
    </div>
    @include('ticket::acceptance.popup.config_search')
    <div class="d-none">
        <div id="input-counter">
            <div class="number">
                <span class="minus">-</span>
                <input type="number" class="number-input" name="{product_id}" value="{value}" min="1" max="{max}" />
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
    <script src="{{asset('static/backend/js/ticket/acceptance/list.js?v='.time())}}" type="text/javascript"></script>
@stop
