@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
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
                        <i class="fa fa-plus-circle"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CẤU HÌNH GIÁ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                {{--<button href="javascript:void(0)"--}}
                {{--data-toggle="modal"--}}
                {{--data-target="#modalAdd"--}}
                {{--onclick="customerSource.clearAdd()"--}}
                {{--class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill">--}}
                {{--<span>--}}
                {{--<i class="fa fa-plus-circle"></i>--}}
                {{--<span> THÊM NGUỒN {{__('KHÁCH HÀNG')}}</span>--}}
                {{--</span>--}}
                {{--</button>--}}
            </div>
        </div>
        <div class="m-portlet__body" id="autotableconfig">
            <div class="row">
                <div class="col-xl-12">
                    {!! Form::open(['route' => 'admin.service-branch-price.submit-edit', 'id' => 'formEdit', 'class'=>'frmFilter'])!!}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group row">
                                <label class="col-lg-3 col-form-label">
                                    {{__('Chi nhánh cần cấu hình')}}:
                                </label>
                                <div class="col-lg-6">
                                    {!! Form::select('branch_id', ["0" => __('Chọn chi nhánh')] + $BRANCH_LIST, null , ['class' => 'form-control m-input m-input--solid', 'id' => 'branch_id', 'name' => 'branch_id']) !!}
                                    <span class="text-danger error-choose-branch"></span>
                                </div>

                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-lg-3 col-form-label">
                                    {{__('Bảng giá cần sao chép')}}:
                                </label>
                                <div class="col-lg-6">
                                    {!! Form::select('price', ["0" => __('Chọn chi nhánh')], null , ['class' => 'form-control m-input m-input--solid', 'id' => 'price', 'name' => 'price', 'disabled']) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="table-content m--margin-top-10">
                @include('admin::service-branch-prices.list-branch-price')
            </div>
            {{--<div align="right">--}}
            {{--<button type="button" class="btn btn-primary" id="btnSubmitChange"><i class="la la-save"></i>Lưu lại--}}
            {{--</button>--}}
            {{--<a href="{{ route('admin.service-branch-price') }}" class="btn btn-danger">Hủy</a>--}}
            {{--</div>--}}
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{ route('admin.service-branch-price') }}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" id="btnSubmitChange"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js?v='.time())}}" type="text/javascript"></script>

    <script type="text/template" id="service-tpl">
        <tr class="branch_tb">
            <td>{stt}</td>
            <td>{service_name}
                <input type="hidden" name="id_service[]" value="{service_id}">
            </td>
            <td class="ss--text-center">
                {price_standard}
                <input type="hidden" value="{price_standard}">
            </td>
            <td></td>
            <td></td>
            <td class="ss--text-center ss--width-150">
                <input class="new form-control m-input ss--btn-ct ss--text-center" name="new_price"
                       id="{service_id}" value="0">
            </td>
            <td></td>
            <td></td>
            <td>
                <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success m-checkbox--solid pull-right m--margin-top-5">
                    <input class="check" {checked} name="check_branch[]" type="checkbox">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>

    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{ asset('static/backend/js/admin/service-branch-prices/script.js?v='.time()) }}"></script>
    <script>
        $(document).ready(function () {
            $('#branch_id').select2();
            $('#price').select2();
        });
    </script>
@stop
