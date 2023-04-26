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
                        <i class="la la-edit"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA GIÁ SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-xl-12">
                    {!! Form::open(['route' => 'admin.product-branch-price.submit-edit', 'id' => 'formEdit']) !!}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label>{{__('Phiên bản')}}: {{$item['product_child_name']}}</label>
                                <div class="input-group m-input-group ss--display-none">
                                    {!! Form::text('product_child_name', $item['product_child_name'], ['class' => 'form-control m-input', 'disabled', 'readonly']) !!}
                                    <input type="hidden" name="product_child_id"
                                           value="{{ $item['product_child_id'] }}" id="product_child_id">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-6">
                                    <label>
                                        {{__('Giá gốc')}}: {{number_format($item['price'],0,"",",")}} {{__('VNĐ')}}
                                    </label>
                                    <div class="input-group m-input-group ss--display-none">
                                        {!! Form::text('old_price', $item['price'], ['class' => 'form-control m-input', 'disabled', 'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                            {{--<div class="form-group m-form__group row">--}}
                            {{--<div class="col-lg-12">--}}
                            {{--<label>--}}
                            {{--Giá bán:--}}
                            {{--</label>--}}
                            {{--<div class="input-group m-input-group">--}}
                            {{--{!! Form::text('new_price', 0, ['class' => 'form-control m-input col-lg-4', 'id' => 'price_standard']) !!}--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            {{--</div>--}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive col-lg-12">
                            <table class="table table-striped m-table ss--header-table" id="table_branch">
                                <thead>
                                <tr class="ss--font-size-th ss--nowrap">
                                    <th>#</th>
                                    <th>{{__('CHI NHÁNH')}}</th>
                                    <th class="ss--text-center">{{__('GIÁ GỐC')}}</th>
                                    <th class="ss--text-center">{{__('GIÁ BÁN')}}</th>
                                    <th>
                                        <label class="m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success pull-right m--margin-bottom-20">
                                            <input id="check_all_branch" name="check_all_branch" checked
                                                   type="checkbox">
                                            <span></span>
                                        </label>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $stt=0; @endphp
                                @foreach($LIST as $key=>$value)
                                    @php $stt++; @endphp
                                    <tr class="branch_tb ss--font-size-13">
                                        <td>{{$stt}}<input type="hidden" id="product_branch_price_id"
                                                             name="brach_id"
                                                             value="{{$value['product_branch_price_id']}}"></td>
                                        <td>{{$value['branch_name']}}<input type="hidden" name="id_branch[]"
                                                                            value="{{$value['branch_id']}}">
                                        </td>
                                        <td class="ss--text-center ss--nowrap">{{number_format($value['old_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                            <input
                                                    type="hidden"
                                                    value="{{$value['old_price']}}">
                                        </td>
                                        <td class="ss--text-center ss--width-120">
                                            <input class="new form-control m-input ss--btn-ct ss--text-center ss--width-120"
                                                   name="new_price"
                                                   id="{{ $value['product_branch_price_id'] }}"
                                                   value="{{number_format($value['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}"></td>
                                        <td>
                                            <label class="m--margin-top-5 m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success m-checkbox--solid pull-right">
                                                <input class="check"
                                                       {{ ($value['is_actived'] == 1) ? 'checked' : '' }} id="check_branch"
                                                       name="check_branch[]"
                                                       type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                @foreach($branchWhereIn as $key=>$value)
                                    @php $stt++; @endphp
                                    <tr class="branch_tb ss--font-size-13">
                                        <td>{{$stt}}
                                            <input type="hidden" id="product_branch_price_id"
                                                             name="brach_id"
                                                             value="0"></td>
                                        <td>{{$value['branch_name']}}<input type="hidden" name="id_branch[]"
                                                                            value="{{$value['branch_id']}}">
                                        </td>
                                        <td class="ss--text-center ss--nowrap">{{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                            <input
                                                    type="hidden"
                                                    value="{{$item['price']}}">
                                        </td>
                                        <td class="ss--text-center ss--width-120">
                                            <input class="new form-control m-input ss--btn-ct ss--text-center ss--width-120"
                                                   name="new_price"
                                                   id="0"
                                                   value="0"></td>
                                        <td>
                                            <label class="m--margin-top-5 m-checkbox m-checkbox--air m-checkbox--solid ss--m-checkbox--state-success m-checkbox--solid pull-right">
                                                <input class="check"
                                                       id="check_branch"
                                                       name="check_branch[]"
                                                       type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{--<div align="right">--}}
                    {{--<button type="button" class="btn btn-primary" id="btn"><i class="la la-save"></i>Lưu lại--}}
                    {{--</button>--}}
                    {{--<a href="{{ route('admin.product-branch-price') }}" class="btn btn-danger">Hủy</a>--}}
                    {{--</div>--}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{ route('admin.product-branch-price') }}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5 ss--btn">
						<span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" id="btn"
                                class="ss--btn-mobiles btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
							<span class="ss--text-btn-mobi">
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js?v='.time())}}" type="text/javascript"></script>


    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{ asset('static/backend/js/admin/product-branch-prices/script.js?v='.time())}}"></script>
    <script type="text/template" id="branch-tpl">
        <tr class="branch_tb">
            <td>{stt}</td>
            <td class="branch">{branch_name}<input type="hidden" class="branch_hidden" id="branch_hidden"
                                                   name="branch_hidden"
                                                   value="{branch_id}"></td>
            <td>
                <div>{old_price}<input type="hidden" id="old_tb" name="old_tb" value="{old_price}"></div>
            </td>
            <td class="new_price"><input class="new form-control m-input" id="new_tb" name="new_tb" value="{new_price}"
                                         maxlength="11"></td>
            <td class="checkBox"><label class="m-checkbox m-checkbox--air m-checkbox--solid col-lg-6">
                    <input class="check" style="text-align: center" id="check_branch" name="check_branch[]"
                           type="checkbox">
                    <span></span>
                </label></td>
        </tr>

    </script>
@endsection
