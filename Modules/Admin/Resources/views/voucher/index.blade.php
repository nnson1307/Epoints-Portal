@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-promotion.png')}}" alt="" style="height: 20px;">
        {{__('KHUYẾN MÃI')}}
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
                        {{__('DANH SÁCH KHUYẾN MÃI')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.voucher.create',session('routeList')))
                    <a href="{{route("admin.voucher.create")}}"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm ">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span>{{__('THÊM MÃ GIẢM GIÁ')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.voucher.create')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            {{--@if (session('error'))--}}
                {{--<div class="alert alert-warning alert-dismissible m--margin-top-20">--}}
                    {{--<strong>{{__('Warning')}} : </strong> {!! session('error') !!}.--}}
                {{--</div>--}}
            {{--@endif--}}
{{--            @if (session('status'))--}}
{{--                <div class="alert alert-success alert-dismissible m--margin-top-20">--}}
{{--                    <strong>{{__('Success')}} : </strong> {!! session('status') !!}.--}}
{{--                </div>--}}
{{--            @endif--}}
            <form class="frmFilter ss--background">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-3 form-group">
                            <div class="m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="code">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập mã giảm giảm giá')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
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
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker','title'=>'Chọn trạng thái']) !!}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button onclick="Voucher.search()"
                                    class="btn ss--btn-search">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--margin-top-30">
                @include('admin::voucher.list')
            </div><!-- end table-content -->

        </div>
    </div>


@stop

@section("modal_section")
    <div id="detail" class="modal fade" role="dialog">
    </div>
@stop

@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/demo/css/admin/voucher/voucher.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/admin/voucher/voucher.js?v='.time())}}" type="text/javascript"></script>
    @if (Session::has("status"))
        <script>
            $.getJSON(laroute.route('translate'), function (json) {
                swal(
                    json['Lưu thông tin thành công'],
                    '',
                    'success'
                );
            });
        </script>
    @endif
@stop
