@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CUỘC GỌI')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
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
                        @lang("DANH SÁCH EXTENSION")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('extension.modal-account', session('routeList')))
                    <a href="javascript:void(0)" onclick="list.showPopAccount()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                    <span>
                                        <span> @lang('CẤU HÌNH TÀI KHOẢN')</span>
                                    </span>
                    </a>
                @endif
                <a href="javascript:void(0)" onclick="list.syncExtension()"
                   class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                    <span>
                                        <span> @lang('ĐỒNG BỘ EXTENSION')</span>
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
                                <div class="col-lg-3 form-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="@lang("Nhập thông tin tìm kiếm")">
                                </div>

                                @php $i = 0; @endphp
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
                    @include('on-call::extension.list')
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/on-call/extension/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        list._init();

        $(".m_selectpicker").selectpicker();
    </script>
@stop