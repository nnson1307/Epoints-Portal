@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ CHI NHÁNH')}}</span>
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
                        {{__('DANH SÁCH CHI NHÁNH')}}
                    </h2>

                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.branch.add',session('routeList')))
                    <a class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill"
                       href="{{route('admin.branch.add')}}">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span>{{__('THÊM CHI NHÁNH')}}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                {{--<input type="hidden" name="search_type" value="supplier_name">--}}

                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập tên chi nhánh')}}">
                                {{--<div class="input-group-append">--}}
                                {{--<a href="javascript:void(0)" onclick="branch.refresh()"--}}
                                {{--class="btn btn-primary m-btn--icon">--}}
                                {{--<i class="la la-refresh"></i>--}}
                                {{--</a>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
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
                        </div>
                    </div>
                    <div class="col-lg-2 form-group">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </div>


            </form>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible">
                    <strong>Success : </strong> {!! session('status') !!}.
                </div>
            @endif
            <div class="table-content m--padding-top-30">
                @include('admin::branch.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/branch/script.js?v='.time())}}" type="text/javascript"></script>

@stop
