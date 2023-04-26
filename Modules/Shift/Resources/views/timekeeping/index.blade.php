@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ NHÂN VIÊN')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .select2 {
            width: 100% !important;
        }

        .table-scroll {
            position: relative;
            max-width: 100%;
            margin: auto;
            overflow: hidden;
            border: 1px solid #f4f5f8;
        }

        .table-wrap {
            width: 100%;
            overflow: auto;
        }

        .table-scroll table {
            width: 100%;
            margin: auto;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-scroll th, .table-scroll td {
            padding: 5px 10px;
            border: 1px solid #f4f5f8;
            white-space: nowrap;
            vertical-align: top;
        }

        .table-scroll th {
            background: #dff7f8;
        }

        .table-scroll td {
            background: #fff;
        }

        .table-scroll thead, .table-scroll tfoot {
            background: #dff7f8;
            text-transform: uppercase;
        }

        .clone {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .clone th, .clone td {
            visibility: hidden
        }

        .clone td, .clone th {
            border-color: transparent
        }

        .clone tbody th {
            visibility: visible;
            color: black;
        }

        .clone .fixed-side {
            border: 1px solid #f4f5f8;
            visibility: visible;
        }

        .clone thead, .clone tfoot {
            background: transparent;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("CHẤM CÔNG")
                    </h2>
                </div>
                {{--@include('shift::timekeeping.navigation')--}}
            </div>

        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <input type="hidden" name="sort[0]" value="">
                        <input type="hidden" name="sort[1]" value="">
                        
                        <div class="form-group">
                            <div class="row">
                                @php $i = 0; @endphp
                                <div class="col-lg-3 form-group">
                                    <select class="form-control m_selectpicker" id="branch_object" name="branch_object">
                                        <option value="">
                                            {{ __('Chọn chi nhánh') }}
                                        </option>
                                        @foreach($arr_branch as $valueBranch => $itemBranch)
                                            <option value="{{ $itemBranch['branch_id'] }}">
                                                {{ $itemBranch['branch_name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                               
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach
                                <div class="form-group col-lg-3">
                                    <select class="form-control m_selectpicker" id="years" name="years">
                                     
                                        <option value="{{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}">
                                            {{\Carbon\Carbon::now()->addYears(-1)->format('Y')}}
                                        </option>
                                        <option value="{{\Carbon\Carbon::now()->format('Y')}}" selected>
                                            {{\Carbon\Carbon::now()->format('Y')}}
                                        </option>
                                        <option value="{{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}">
                                            {{\Carbon\Carbon::now()->addYears(+1)->format('Y')}}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control m_selectpicker" id="date_object" name="date_object">
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{$i}}" {{$i == \Carbon\Carbon::now()->format('m') ? 'selected': ''}}>
                                                {{__('Tháng ' . $i)}}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                
                                <div class="col-lg-3 form-group">
                                    
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('shift::timekeeping.list')
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        $(".m_selectpicker").select2({
            width: "100%"
        }).on('select2:select', function () {
            $('.btn-search').trigger('click');
        });

        $('#autotable').PioTable({
            baseUrl: laroute.route('timekeeping.list')
        });

        $(document).ready(function () {
            $(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');
        });
    </script>
@stop
