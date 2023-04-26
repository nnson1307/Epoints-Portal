@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-price.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ GIÁ')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('QUẢN LÝ GIÁ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.service-branch-price.config',session('routeList')))
                    <a href="{{route('admin.service-branch-price.config')}}"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle m--margin-right-5"></i>
							<span> {{__('CẤU HÌNH GIÁ DỊCH VỤ')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.service-branch-price.config')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>

        <div class="m-portlet__body">
            <div class="ss--background">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-4 form-group">
                            <div class="m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="group_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập tên dịch vụ')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
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
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker ss--width-100-']) !!}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-5 form-group">
                            <button onclick="filter()"
                                    class="btn ss--btn-search">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            {{--@if (session('status'))--}}
            {{--<div class="alert alert-success alert-dismissible">--}}
            {{--<strong>Thông báo : </strong> {!! session('status') !!}.--}}
            {{--</div>--}}
            {{--@endif--}}
            <div id="list-data" class="m--padding-top-30">
                <div id="autotable">
                    <form class="frmFilter">
                        <div class="table-content">
                            @include('admin::service-branch-prices.list')
                        </div><!-- end table-content -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js?v='.time())}}" type="text/javascript"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/admin/service-branch-prices/script.js?v='.time())}}" type="text/javascript"></script>

@stop

