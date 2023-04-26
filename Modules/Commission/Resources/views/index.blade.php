@extends('layout')

@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}" />
@endsection

@section('title_header')
    <span class="title_header">
        <img src="{{ asset('uploads/admin/icon/icon-product.png') }}" alt="" style="height: 20px;" />
        {{ __('QUẢN LÝ YÊU CẦU MUA GÓI ĐẦU TƯ - TIẾT KIỆM') }}
    </span>
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
                        {{ __('DANH SÁCH HOA HỒNG') }}
                    </h3>
                </div>
            </div>

            <div class="m-portlet__head-tools nt-class">
                <a href="{{ route('admin.commission.allocation') }}" style="margin-right: 10px;"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="flaticon-refresh"></i>
                        <span> PHÂN BỔ HOA HỒNG</span>
                    </span>
                </a>

                <a href="{{ route('admin.commission.add') }}" class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle m--margin-right-5"></i>
                        <span> THÊM HOA HỒNG</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="card-header tab-card-header ">
            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active show" href="{{ route('admin.commission') }}">Theo hoa hồng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.commission.received') }}">Theo nhân viên</a>
                </li>
            </ul>
        </div>

        <div class="m-portlet__body">
            <form class="frmFilter bg">

                <!-- LIST FILTER -->
                <div class="row padding_row">

                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="commission_name"
                                    placeholder="{{ __('Tên hoa hồng') }}" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="status" class="form-control m-input ss--select-2">
                            <option value="">Chọn trạng thái</option>
                            <option value="1">Hoạt động</option>
                            <option value="0">Không hoạt động</option>
                        </select>
                    </div>

                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="tags_id" class="form-control m-input ss--select-2 js-tags">
                            <option value="">{{__('Chọn tags')}}</option>
                            @if (isset($TAG_LIST))
                                @foreach ($TAG_LIST as $tag_item)
                                    <option value="{{ $tag_item['tags_id'] }}">{{ $tag_item['tags_name'] }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-3 form-group">
                        <select style="width: 100%;" name="commission_type" class="form-control m-input ss--select-2">
                            <option value="">Chọn loại hoa hồng</option>
                            <option value="order">Theo đơn hàng</option>
                            <option value="kpi">Theo KPI</option>
                            <option value="contract">Theo hợp đồng</option>
                        </select>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <button class="btn btn-primary color_button btn-search">{{ __('TÌM KIẾM') }} <i
                                    class="fa fa-search ic-search m--margin-left-5"></i></button>

                            <a href="{{ route('admin.commission') }}" class="btn btn-primary color_button btn-search padding9x">
                                <span>
                                    <i class="flaticon-refresh"></i>
                                </span>
                            </a>
                        </div>
                    </div>

                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible"><strong>{{ __('Success') }} : </strong>
                        {!! session('status') !!}.</div>
                @endif
            </form>

            <div class="table-content m--padding-top-30">
                @include('commission::list-commission')
            </div>
        </div>
    </div>
@endsection

@section('after_script')
    <script src="{{ asset('static/backend/js/admin/commission/script.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script>
        $(".m_selectpicker").selectpicker();
    </script>
@stop
