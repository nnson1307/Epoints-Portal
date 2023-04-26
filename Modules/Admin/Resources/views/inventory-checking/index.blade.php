@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <form class="frmFilter">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="ss--title m--font-bold m-portlet__head-text">
                            <i class="la la-th-list ss--icon-title m--margin-right-5"></i>
                            {{__('DANH SÁCH PHIẾU KIỂM KHO')}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="javascript:void(0)"
                       onclick="location.href='{{route('admin.inventory-checking.add')}}'"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIỂU KIỂM KHO')}}</span>
                        </span>
                    </a>
                    <a href="javascript:void(0)"
                       onclick="location.href='{{route('admin.inventory-checking.add')}}'"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                color_button btn_add_mobile" style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="ss--background">
                    <div class="row ss--bao-filter2">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="hidden" name="search_type" value="checking_code">
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập mã phiếu')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-4 input-group form-group">
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
                            </div>
                        </div>
                    </div>
                    <div class="row m--padding-left-15 m--padding-right-15">
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input onkeyup="InventoryChecking.notEnterInput(this)" class="form-control m-input daterange-picker"
                                       id="created_at1" name="created_at" autocomplete="off" placeholder="{{__('Ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-7"></div>
                        <div class="col-lg-2 form-group">
                            <button href="javascript:void(0)" onclick="InventoryChecking.search()"
                               class="btn ss--btn-search pull-right">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-content m--margin-top-30">
                    @include('admin::inventory-checking.list')
                </div><!-- end table-content -->
            </div>
        </form>
    </div>
    <input type="hidden" id="totalInput" value="">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/inventory-checking/list.js?v='.time())}}" type="text/javascript"></script>
@stop