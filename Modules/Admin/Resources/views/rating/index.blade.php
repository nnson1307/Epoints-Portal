@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ BÀI VIẾT')</span>
@stop
@section('content')
    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang('DANH SÁCH ĐÁNH GIÁ KHÁCH HÀNG')
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('admin.rating')}}"
                   class="float-right btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md">
                    <span>@lang('SẢN PHẨM/DỊCH VỤ/ THẺ DV')</span>
                </a>
                <a href="{{route('admin.rating-order')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md0 m--margin-left-10">
                    <span>@lang('ĐƠN HÀNG')</span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                {{--<input type="hidden" name="search_type" value="supplier_name">--}}

                                <input type="text" class="form-control" name="full_name"
                                       placeholder="Nhập tên khách hàng">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="branch.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    @php $i = 0; @endphp
                                    @foreach ($FILTER as $name => $item)
                                        @if ($i > 0 && ($i % 4 == 0))
                                </div>
                                <div class="form-group m-form__group row align-items-center">
                                    @endif
                                    @php $i++; @endphp
                                    <div class="col-lg-6 input-group">
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
                                    <div class="col-lg-6">
                                        <div class="input-group" style="background-color: white">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input readonly="" class="form-control m-input daterange-picker"
                                                       id="created_at" name="created_at" autocomplete="off"
                                                       placeholder="{{__('Ngày tạo')}}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                        <span><i class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>


            </form>

            <div class="table-content m--padding-top-30">
                @include('admin::rating.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/rating/list.js')}}" type="text/javascript"></script>
    <script>
        $("#created_at").daterangepicker({
            autoUpdateInput: false,
            autoApply: true,
            locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: [
                    "CN",
                    "T2",
                    "T3",
                    "T4",
                    "T5",
                    "T6",
                    "T7"
                ],
                "monthNames": [
                    "Tháng 1 năm",
                    "Tháng 2 năm",
                    "Tháng 3 năm",
                    "Tháng 4 năm",
                    "Tháng 5 năm",
                    "Tháng 6 năm",
                    "Tháng 7 năm",
                    "Tháng 8 năm",
                    "Tháng 9 năm",
                    "Tháng 10 năm",
                    "Tháng 11 năm",
                    "Tháng 12 năm"
                ],
                "firstDay": 1
            }
        });
    </script>
@stop
