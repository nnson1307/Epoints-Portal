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
                        {{__('CHỈNH SỬA GIÁ DỊCH VỤ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-xl-12">
                    {!! Form::open(['route' => 'admin.service-branch-price.submit-edit', 'id' => 'formEdit']) !!}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group m-form__group">
                                <label>{{__('Dịch vụ')}}: {{  $item['service_name']}}</label>
                                <div class="input-group m-input-group ss--display-none">
                                    {!! Form::text('service_name',$item['service_name'], ['class' => 'form-control m-input', 'disabled', 'readonly']) !!}
                                    <input type="hidden" name="service_id" value="{{ $item['service_id'] }}"
                                           id="service_id">
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-6">
                                    <label>
                                        {{__('Giá chuẩn')}}: {{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('VNĐ')
                                    </label>
                                    <div class="input-group m-input-group ss--display-none">
                                        {!! Form::text('old_price', $item['price_standard'], ['class' => 'form-control m-input', 'disabled', 'readonly']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                {{--<div class="col-lg-12">--}}
                                {{--<label>--}}
                                {{--Giá chi nhánh:--}}
                                {{--</label>--}}
                                {{--{!! Form::text('new_price', 0, ['class' => 'form-control m-input col-lg-4', 'id' => 'price_standard']) !!}--}}
                                {{--</div>--}}
                                {{--<div class="col-lg-3">--}}
                                {{--<div class="input-group m-input-group">--}}
                                {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                    </div>

                    <div class="row m--margin-top-10">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <div class="m-scrollable m-scroller ps ps--active-y ss--table-scroll-vertical"
                                     data-scrollable="true" style="height: 250px">
                                    <table class="table table-striped m-table ss--header-table ss--nowrap"
                                           id="table_branch">
                                        <thead>
                                        <tr class="ss--font-size-th ss--nowrap">
                                            <th class="ss--width-50">#</th>
                                            <th>{{__('CHI NHÁNH')}}</th>
                                            <th class="ss--text-center">{{__('GIÁ CHUẨN')}}</th>
                                            <th></th>
                                            <th></th>
                                            <th class="ss--text-center">{{__('GIÁ CHI NHÁNH')}}</th>
                                            <th></th>
                                            <th></th>

                                            @if(session()->get('brand_code') == 'giakhang')
                                                <th>{{__('GIÁ TUẦN')}}</th>
                                                <th>{{__('GIÁ THÁNG')}}</th>
                                                <th>{{__('GIÁ NĂM')}}</th>
                                            @else
                                                <th hidden>{{__('GIÁ TUẦN')}}</th>
                                                <th hidden>{{__('GIÁ THÁNG')}}</th>
                                                <th hidden>{{__('GIÁ NĂM')}}</th>
                                            @endif
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
                                            <tr class="branch_tb">
                                                <td>{{$stt}}<input type="hidden" id="service_branch_price_id"
                                                                     name="brach_id"
                                                                     value="{{$value['service_branch_price_id']}}"></td>
                                                <td>{{$value['branch_name']}}
                                                    <input type="hidden" name="id_branch[]" value="{{$value['branch_id']}}">
                                                </td>
                                                <td class="ss--text-center">
                                                    {{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                                    <input type="hidden" value="{{$item['price_standard']}}">
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td class="ss--text-center ss--width-150">
                                                    <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                           name="new_price"
                                                           id="{{ $value['service_branch_price_id'] }}"
                                                           value="{{number_format($value['new_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                </td>
                                                <td></td>
                                                <td></td>

                                                @if(session()->get('brand_code') == 'giakhang')
                                                    <td class="ss--text-center ss--width-150">
                                                        <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                               id="price_week_{{$value['service_branch_price_id']}}" value="{{number_format($value['price_week'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    </td>
                                                    <td class="ss--text-center ss--width-150">
                                                        <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                               id="price_month_{{$value['service_branch_price_id']}}" value="{{number_format($value['price_month'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    </td>
                                                    <td class="ss--text-center ss--width-150">
                                                        <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                               id="price_year_{{$value['service_branch_price_id']}}" value="{{number_format($value['price_year'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">
                                                    </td>
                                                @else
                                                    {{--<td hidden class="ss--text-center ss--width-150">--}}
                                                        {{--<input class="new form-control m-input ss--btn-ct ss--text-center"--}}
                                                               {{--id="price_week_{{$value['service_branch_price_id']}}" value="{{number_format($value['price_week'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                                                    {{--</td>--}}
                                                    {{--<td hidden class="ss--text-center ss--width-150">--}}
                                                        {{--<input class="new form-control m-input ss--btn-ct ss--text-center"--}}
                                                               {{--id="price_month_{{$value['service_branch_price_id']}}" value="{{number_format($value['price_month'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                                                    {{--</td>--}}
                                                    {{--<td hidden class="ss--text-center ss--width-150">--}}
                                                        {{--<input class="new form-control m-input ss--btn-ct ss--text-center"--}}
                                                               {{--id="price_year_{{$value['service_branch_price_id']}}" value="{{number_format($value['price_year'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}">--}}
                                                    {{--</td>--}}
                                                @endif
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
                                        @if(count($branchWhereIn)>0)
                                            @foreach($branchWhereIn as $value)
                                                @php $stt++; @endphp
                                                <tr class="branch_tb">
                                                    <td>{{$stt}}<input type="hidden" id="service_branch_price_id"
                                                                         name="brach_id"
                                                                         value="0">
                                                    </td>
                                                    <td>{{$value['branch_name']}}<input type="hidden" name="id_branch[]"
                                                                                        value="{{$value['branch_id']}}">
                                                    </td>
                                                    <td class="ss--text-center">
                                                        {{number_format($item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}<input
                                                                type="hidden"
                                                                value="{{$item['price_standard'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}}">
                                                    </td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="ss--text-center ss--width-150">
                                                        <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                               name="new_price"
                                                               id="01"
                                                               value="0">
                                                    </td>
                                                    <td></td>
                                                    <td></td>

                                                    @if(session()->get('brand_code') == 'giakhang')
                                                        <td class="ss--text-center ss--width-150">
                                                            <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                                    value="0">
                                                        </td>
                                                        <td class="ss--text-center ss--width-150">
                                                            <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                                    value="0">
                                                        </td>
                                                        <td class="ss--text-center ss--width-150">
                                                            <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                                   value="0">
                                                        </td>
                                                    @else
                                                        <td hidden class="ss--text-center ss--width-150">
                                                            <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                                   value="0">
                                                        </td>
                                                        <td hidden class="ss--text-center ss--width-150">
                                                            <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                                   value="0">
                                                        </td>
                                                        <td hidden class="ss--text-center ss--width-150">
                                                            <input class="new form-control m-input ss--btn-ct ss--text-center"
                                                                   value="0">
                                                        </td>
                                                    @endif
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
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="modal-footer">
            {{--<div align="right">--}}
            {{--<button type="button" class="btn btn-primary" id="btn"><i class="la la-save"></i>Lưu lại--}}
            {{--</button>--}}
            {{--<a href="{{ route('admin.service-branch-price') }}" class="btn btn-danger">Hủy</a>--}}
            {{--</div>--}}

            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
                <div class="col-lg-12">
                    <div class="m-form__actions m--align-right">
                        <a href="{{ route('admin.service-branch-price') }}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
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
    <script src="{{asset('static/backend/js/admin/general/tableHeadFixer.js?v='.time())}}" type="text/javascript"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>

    <script src="{{ asset('static/backend/js/admin/service-branch-prices/script.js?v='.time()) }}"></script>
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
