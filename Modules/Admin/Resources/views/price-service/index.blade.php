@extends('layout')

@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }
    </style>
    {{--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" />--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h2 class="m-portlet__head-label m-portlet__head-label--primary">
                                <span>GIÁ DỊCH VỤ CHI NHÁNH</span>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="m-portlet m-portlet--mobile ">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">

                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body" id="autotable">
                                <form class="m-form m-form--fit m-form--label-align-right frmFilter">
                                    <div class="m-form m-form--label-align-right m--margin-bottom-30">
                                        <div class="row align-items-center m--margin-bottom-10">
                                            <div class="col-xl-6 order-2 order-xl-1">
                                                <div class="form-group m-form__group row align-items-center">
                                                    <div class="input-group col-xs-6">
                                                        {{--<div class="input-group-append">--}}
                                                        {{--<input type="hidden" value="service_name" name="search_type">--}}
                                                        {{--</div>--}}
                                                        <input type="text" class="form-control" name="search"
                                                               placeholder="Nhập nội dung tìm kiếm">
                                                        <div class="input-group-append">
                                                            <button class="btn btn-primary">
                                                                <i class="fa fa-search"></i>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group m-form__group row align-items-center" style="padding:5px 15px;">
                                                    @php $i = 0; @endphp
                                                    @foreach ($FILTER as $name => $item)
                                                        @if ($i > 0 && ($i % 4 == 0))
                                                </div>
                                                <div class="form-group m-form__group row align-items-center" style="padding:5px 15px;">
                                                    @endif
                                                    @php $i++; @endphp
                                                    <div class="col-lg-12 input-group">
                                                        @if(isset($item['text']))
                                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    {{ $item['text'] }}
                                                </span>
                                                            </div>
                                                        @endif
                                                        {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input']) !!}
                                                    </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group m-form__group row align-items-center" style="padding:5px 15px;">
                                                    <div class="col-lg-12 input-group">
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">{{__('Ngày tạo')}}</span>
                                                        </div>
                                                        <input readonly class="form-control m-input daterange-picker" id="created_at"
                                                               name="created_at" readonly
                                                               autocomplete="off" placeholder="{{__('Ngày tạo')}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                                @if (session('status'))
                                    <div class="alert alert-success alert-dismissible">
                                        <strong>Thông báo : </strong> {!! session('status') !!}.
                                    </div>
                                @endif
                                <div class="table-content">
                                    @include('admin::price-service.list')
                                </div><!-- end table-content -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/price-service/script.js')}}" type="text/javascript"></script>

@stop

