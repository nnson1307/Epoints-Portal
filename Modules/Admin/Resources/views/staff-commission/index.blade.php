@extends('layout')
@section('title_header')
        <span class="title_header">{{__('HOA HỒNG NHÂN VIÊN')}}</span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"></i> {{__('DANH SÁCH LOẠI HOA HỒNG NHÂN VIÊN')}}</span>
                    </h2>
                    <h3 class="m-portlet__head-text">

                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <button onclick="staffCommission.create()"
                   class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM HOA HỒNG NHÂN VIÊN')}}</span>
                        </span>
                </button>
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-12 form-group row">
                        <div class="col-lg-4">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{__('Nhập tên hoặc mã nhân viên')}}">
                                </div>
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
                                <div class="col-lg-4 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff"
                                               id="created_at"
                                               name="created_at"
                                               autocomplete="off" placeholder="@lang('NGÀY TẠO')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-2 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-15">
                @include('admin::staff-commission.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <div id="my-modal"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@stop
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/staff-commission/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        staffCommission._init();
    </script>
@stop
