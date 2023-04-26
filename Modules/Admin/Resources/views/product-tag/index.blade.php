@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
@stop

@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH TAG")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.product-tag.create',session('routeList')))
                    <a href="{{route('admin.product-tag.create')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM TAG')</span>
                                    </span>
                    </a>
                    <a href="{{route('admin.product-tag.create')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="@lang("Nhập tên tag")">
                            </div>
                        </div>
                        <div class="col-lg-8">
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
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach
{{--                                <div class="col-lg-4 form-group">--}}
{{--                                    <div class="m-input-icon m-input-icon--right">--}}
{{--                                        <input readonly class="form-control m-input daterange-picker"--}}
{{--                                               style="background-color: #fff"--}}
{{--                                               id="created_at"--}}
{{--                                               name="created_at"--}}
{{--                                               autocomplete="off" placeholder="@lang('NGÀY TẠO')">--}}
{{--                                        <span class="m-input-icon__icon m-input-icon__icon--right">--}}
{{--                                    <span><i class="la la-calendar"></i></span></span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('admin::product-tag.list')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-tag/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        list._init();
    </script>
@endsection