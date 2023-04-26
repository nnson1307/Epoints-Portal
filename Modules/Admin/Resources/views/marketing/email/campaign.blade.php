@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-email.png')}}" alt="" style="height: 20px;"> {{__('EMAIL')}}</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .modal-lg {
            max-width: 65% !important;
        }
    </style>


    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-large"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH CHIẾN DỊCH')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.email.add',session('routeList')))
                    <a href="{{route('admin.email.add')}}"
                       class="btn btn-primary m-btn m-btn--icon m-btn--pill btn-sm color_button btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle icon-sz"></i>
							<span> {{__('THÊM CHIẾN DỊCH')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.email.add')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="padding_row bg">
                <div class="row ">
                    <div class="col-lg-5">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                {{--<input type="hidden" name="search_type" value="supplier_name">--}}
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập tên chiến dịch')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="email.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="m-input-icon m-input-icon--right" style="background-color: white">
                            <input onchange="email.filter()" readonly
                                   class="form-control m-input daterange-picker" id="day-sent"
                                   name="day-sent"
                                   autocomplete="off" placeholder="{{__('Chọn ngày gửi')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <div class="m-input-icon m-input-icon--right" style="background-color: white">
                            <input onchange="email.filter()" readonly class="form-control m-input daterange-picker"
                                   id="created_at"
                                   name="created_at"
                                   autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                </div>
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
                            <div class="col-lg-3 form-group input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item['text'] }}
                                                    </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker width-select']) !!}
                            </div>
                            @endforeach
                            <div class="col-lg-3 form-group">
                                <button class="btn btn-primary btn-search  color_button" onclick="email.filter()">
                                    {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content list-campaign m--padding-top-30">
                @include('admin::marketing.email.list')
            </div><!-- end table-content -->
        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/email/script.js?v='.time())}}" type="text/javascript"></script>

@stop
