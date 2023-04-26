@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ GÓI BẢO HÀNH')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHỈNH SỬA GÓI BẢO HÀNH')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Tên gói bảo hành'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input"
                                   id="package_name" name="package_name" value="{{$data['packed_name']}}"
                                   placeholder="@lang('Nhập tên gói bảo hành')">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Giá trị bảo hành') (%):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input" value="{{$data['percent']}}"
                                   id="percent" name="percent" placeholder="@lang('Nhập giá trị bảo hành')">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số tiền tối đa được bảo hành'):<b class="text-danger"> *</b>
                            </label>
                            <input type="text" class="form-control m-input" value="{{$data['required_price']}}"
                                   id="money_maximum" name="money_maximum"
                                   placeholder="@lang('Nhập số tiền tối đa được bảo hành')">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời hạn bảo hành'):<b class="text-danger"> *</b>
                            </label>
                            <div class="input-group">
                                <div class="m-demo" data-code-preview="true" data-code-html="true"
                                     data-code-js="false">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-default rdo {{$data['time_type'] == 'day' ? 'active': ''}}">
                                            <input type="radio" name="date-use" value="day" id="option1"
                                                   autocomplete="off">
                                            <span class="m--margin-left-5 m--margin-right-5">{{__('Ngày')}}</span>
                                        </label>
                                        <label class="btn btn-default rdo {{$data['time_type'] == 'week' ? 'active': ''}}">
                                            <input type="radio" name="date-use" value="week" id="option2"
                                                   autocomplete="off">
                                            <span class="m--margin-left-5 m--margin-right-5">{{__('Tuần')}}</span>
                                        </label>
                                        <label class="btn btn-default rdo {{$data['time_type'] == 'month' ? 'active': ''}}">
                                            <input type="radio" name="date-use" value="month" id="option3"
                                                   autocomplete="off">
                                            <span class="m--margin-left-5 m--margin-right-5">{{__('Tháng')}}</span>
                                        </label>
                                        <label class="btn btn-default rdo {{$data['time_type'] == 'year' ? 'active': ''}}">
                                            <input type="radio" name="date-use" value="year" id="option4"
                                                   autocomplete="off">
                                            <span class="m--margin-left-5 m--margin-right-5">{{__('Năm')}}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text" class="form-control m-input"
                                           id="time_warranty" name="time_warranty"
                                           value="@switch($data['time_type'])
                                    @case('day'){{$data['time']}}@break
                                    @case('week'){{$data['time'] / 7}}@break
                                    @case('month'){{$data['time'] / 30}}@break
                                    @case('year'){{$data['time'] / 365}} @break
                                    @endswitch">
                                    <span class="err error-time-warranty"></span>
                                </div>
                                <div class="col-lg-4">
                                    <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                        <input id="time_warranty_unlimited" class="check-inventory-warning"
                                               type="checkbox" {{$data['time_type'] == 'infinitive' ? 'checked': ''}}>
                                        {{__('Không giới hạn')}}
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Số lần được bảo hành'):<b class="text-danger"> *</b>
                            </label>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-group m-input-group">
                                        <input name="number_warranty" id="number_warranty" style="text-align: right" type="text"
                                               class="form-control" value="{{$data['quota']}}">
                                        <div class="input-group-append">
                                            <button class="btn ss--button-cms-piospa"><b>{{__('LẦN')}}</b>
                                            </button>
                                        </div>
                                    </div>
                                    <span class="err error-number-warranty"></span>
                                </div>
                                <div class="col-lg-4">
                                    <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                        <input id="number_warranty_unlimited" class="check-inventory-warning"
                                               type="checkbox"  {{$data['quota'] == 0 ? 'checked': ''}}>
                                        {{__('Không giới hạn')}}
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">@lang('Mô tả ngắn'):</label>
                            <input type="text" class="form-control m-input" value="{{$data['description']}}"
                                   id="short_description" name="short_description"
                                   placeholder="@lang('Nhập mô tả ngắn')">
                        </div>
                        <div class="form-group m-form__group">
                            <label> {{__('Mô tả chi tiết')}}:</label>
                            <div class="summernote">{!! $data['detail_description'] !!}</div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="form-group m-form__group mt-3">
                <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                        onclick="view.showModal('product')">
                    <i class="la la-plus"></i> @lang('THÊM SẢN PHẨM')
                </button>
                <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                        onclick="view.showModal('service')">
                    <i class="la la-plus"></i> @lang('THÊM DỊCH VỤ')
                </button>
                <button type="button" class="btn btn-outline-info btn-sm m-btn m-btn--custom"
                        onclick="view.showModal('service_card')">
                    <i class="la la-plus"></i> @lang('THÊM THẺ DỊCH VỤ')
                </button>
            </div>

            <div class="form-group m-form__group" id="autotable-discount">
                <form class="frmFilter">
                    <div class="form-group m-form__group" style="display: none;">
                        <button class="btn btn-primary color_button btn-search">
                            {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                        </button>
                    </div>
                </form>
                <div class="table-content div_table_discount">
                    <div class="table-responsive">
                        <table class="table table-striped m-table m-table--head-bg-default" id="table-discount">
                            <thead class="bg">
                            <tr>
                                <th class="tr_thead_list">{{__('TÊN')}}</th>
                                <th class="tr_thead_list">{{__('GIÁ GỐC')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($listResult) > 0)
                                @foreach($listResult as $v)
                                    <tr>
                                        <td>
                                            {{$v['object_name']}}
                                            <input type="hidden" class="object_type" value="{{$v['object_type']}}">
                                            <input type="hidden" class="object_code" value="{{$v['object_code']}}">
                                        </td>
                                        <td>{{number_format($v['base_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                        <td>
                                            <a href="javascript:void(0)"
                                               onclick="view.removeTr(this, '{{$v['object_code']}}', 'discount', '{{$v['object_type']}}')"
                                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                               title="Delete">
                                                <i class="la la-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        {{ $listResult->links('helpers.paging') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('warranty-package')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="edit.save('{{$data['packed_code']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/warranty/warranty-package/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        edit._init();
    </script>

@endsection