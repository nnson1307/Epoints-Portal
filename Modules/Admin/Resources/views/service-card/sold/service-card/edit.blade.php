@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services-card.png')}}" alt="" style="height: 20px;">
        {{__('THẺ DỊCH VỤ')}}
    </span>
@endsection
@section('content')
    @if (session('errors'))
        @php $sessionErrors = session('errors'); @endphp
    @else
        @php $sessionErrors = []; @endphp
    @endif
    @php
        $check = old('number_using', $detailCardSold['number_using']);
    @endphp
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA THẺ DỊCH VỤ ĐÃ BÁN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>

        <form class="form" id="form-submit" method="POST" action="{{ route('admin.service-card.sold.update') }}">
            {{ csrf_field() }}
            <input type="hidden" id="card_code" name="card_code" value="{{$code}}">
            <div class="m-portlet__body" id="autotable">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Mã thẻ')}}:</label>
                                    <input class="form-control" value="{{$code}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Loại thẻ')}}: </label>
                                    <input class="form-control" value="{{__('Thẻ dịch vụ')}}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Hạn sử dụng')}}</label>
                                    <div class="input-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            @php
                                                $expired_date = $detailCardSold['expired_date']!=''?date_format(new DateTime($detailCardSold['expired_date']), 'd/m/Y'):'';
                                            @endphp
                                            <input type="text" readonly name="expired_date" class="form-control date-picker-expire"
                                                   id="expired_date" value="{{ old('expired_date', $expired_date) }}"
                                                   {{ ($check == 0) ? 'disabled' : '' }}>
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                    @if (count($sessionErrors) > 0 && isset($sessionErrors['expired_date']))
                                        <span class="form-control-feedback text-danger">{{ $sessionErrors['expired_date'][0] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Ghi chú')}}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="note" id="note"
                                               value="{{ old('note', $detailCardSold['note']) }}">
                                    </div>
                                    @if (count($sessionErrors) > 0 && isset($sessionErrors['note']))
                                        <span class="form-control-feedback text-danger">{{ $sessionErrors['note'][0] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group" id="number_using_div">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Số lần sử dụng')}}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" {{ ($check == 0) ? 'disabled' : '' }}
                                               id="number_using" name="number_using"
                                               min="1" value="{{ old('number_using', $detailCardSold['number_using']) }}">
                                    </div>
                                    @if (count($sessionErrors) > 0 && isset($sessionErrors['number_using']))
                                        <span class="form-control-feedback text-danger">{{ $sessionErrors['number_using'][0] }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group" id="limit_div">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Số lần đã sử dụng:</label>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="input-group" id="count_using_div">
                                                <input name="count_using" id="count_using" type="number" min="0"
                                                       class="form-control" {{ ($check == 0) ? 'disabled' : '' }}
                                                       value="{{ old('count_using', $detailCardSold['count_using']) }}">
                                            </div>
                                            @if (count($sessionErrors) > 0 && isset($sessionErrors['count_using']))
                                                <span class="form-control-feedback text-danger">{{ $sessionErrors['count_using'][0] }}</span>
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                            <label class="m-checkbox m-checkbox--air m--margin-top-10">
                                                <input id="number-using-not-limit" class="check-inventory-warning" name="not_limit"
                                                       type="checkbox" {{ ($check==0) ? 'checked' : '' }}>
                                                {{__('Không giới hạn')}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>Còn lại:</label>
                                    <input class="form-control" id="minus_using" value="{{ ($check == 0)
                                    ? '{{__('Không giới hạn')}}'
                                    : $detailCardSold['number_using'] - $detailCardSold['count_using'] }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Trạng thái')}}:</label>
                                    <label class="form-control" style="margin-bottom: 0;">
                                        @if($detailCardSold['is_actived'] == 1)
                                            <h6 class="m--font-success">{{__('Đã kích hoạt')}}</h6>
                                        @else
                                            <h6 class="m--font-danger">{{__('Chưa kích hoạt')}}</h6>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label>{{__('Hủy')}} thẻ dịch vụ</label>
                                    <div class="input-group">
                                        <div class="col-lg-1">
                                        <span class="m-switch m-switch--icon m-switch--danger m-switch--sm">
                                            <label>
                                                <input type="checkbox" name="is_deleted" {{ $detailCardSold['is_deleted'] == 1 ? 'checked' : '' }}>
                                                <span></span>
                                            </label>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-content list-history">
                    @include('admin::service-card.sold.service-card.list-detail')
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-lg-12">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <a href="{{route('admin.service-card.sold.service-card')}}"
                               class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                            </a>
                            <button type="button" onclick="serviceCard.save()"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md btn-save m--margin-left-10 m--margin-bottom-5 class-submit">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                            </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card/sold/service-card.js?v='.time())}}"
            type="text/javascript"></script>
    @if(session('fail'))
        <script>
            swal("{{__('Cập nhật')}} thất bại", "", "error");
        </script>
    @endif
@stop

