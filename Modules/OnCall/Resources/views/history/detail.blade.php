@extends('layout')
@section('title_header')
    <span class="title_header">@lang('CUỘC GỌI')</span>
@stop

@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                         <span class="m-portlet__head-icon">
                             <i class="fa fa-plus-circle"></i>
                         </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHI TIẾT CUỘC GỌI')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Số người gọi'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" id="pipeline_name" name="pipeline_name"
                               value="{{$item['extension_number']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Sđt người nhận'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['object_phone']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên người gọi'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['staff_name']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tên người nhận'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['object_name']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Nguồn khách hàng'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['source_name']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Trạng thái'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{$item['status'] == 0 ? __('Thất bại'): __('Thành công')}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Loại cuộc gọi'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{$item['history_type'] == "out" ? __('Cuộc gọi đi'): __('Cuộc gọi đến')}}" disabled>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Thời gian bắt đầu'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{$item['start_time'] != null ? \Carbon\Carbon::parse($item['start_time'])->format('d/m/Y H:i:s') : ''}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Thời gian đổ chuông'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{$item['ring_time'] != null ? \Carbon\Carbon::parse($item['ring_time'])->format('d/m/Y H:i:s') : ''}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Thời gian trả lời'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{$item['reply_time'] != null ? \Carbon\Carbon::parse($item['reply_time'])->format('d/m/Y H:i:s') : ''}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Thời gian kết thúc'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{$item['end_time'] != null ? \Carbon\Carbon::parse($item['end_time'])->format('d/m/Y H:i:s') : ''}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tổng thời gian đổ chuông'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['total_ring_time']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Tổng thời gian đàm thoại'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input" value="{{$item['total_reply_time']}}" disabled>
                    </div>
                    <div class="form-group m-form__group">
                        <label class="black_title">
                            @lang('Cước cuộc gọi'):<b class="text-danger">*</b>
                        </label>
                        <input type="text" class="form-control m-input"
                               value="{{number_format($item['postage'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('oncall.history')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection