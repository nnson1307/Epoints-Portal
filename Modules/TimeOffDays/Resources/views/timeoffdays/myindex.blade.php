@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ ĐƠN PHÉP')</span>
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

    </style>

    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH ĐƠN PHÉP")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{ route('timeoffdays.add') }}"
                    class="btn btn-primary color_button m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('THÊM ĐƠN PHÉP') }}</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
                        <div class="form-group">
                            <div class="row">
                                

                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                @if ($i == 2)
                                    <div class="col-lg-3 form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control m-input daterange-picker"
                                                style="background-color: #fff" id="created_at" name="created_at"
                                                autocomplete="off" placeholder="{{ __('Chọn thời gian nghỉ phép') }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                @endif
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

                                


                                <div class="col-lg-3 form-group">
                                    <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                        {{ __('XÓA BỘ LỌC') }}
                                    </button>
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('timeoffdays::timeoffdays.mylist')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('after_script')
@include('ticket::language.lang')
    <script src="{{asset('static/backend/js/timeoffdays/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        timeoffdays._init();

        $(".m_selectpicker").select2({
            width: "100%"
        });
    </script>    
@stop