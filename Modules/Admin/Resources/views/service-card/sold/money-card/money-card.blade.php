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
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('THẺ TIỀN ĐÃ BÁN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="frmFilter ss--background">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập mã thẻ')}}">
                        </div>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select name="status" id="status" class="form-control" style="width: 100%">
                            <option value="">{{__('Chọn trạng thái')}}</option>
                            <option value="1">{{__('Đã kích hoạt')}}</option>
                            <option value="0">{{__('Chưa kích hoạt')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select name="branch" id="branch" class="form-control" style="width: 100%">
                            <option value="">{{__('Chọn chi nhánh')}}</option>
                            @foreach($branch as $key=>$value)
                                <option value="{{$value['branch_id']}}">{{$value['branch_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <select name="staff" id="staff" class="form-control" style="width: 100%">
                            <option value="">{{__('Chọn nhân viên')}}</option>
                            @foreach($staff as $key=>$value)
                                <option value="{{$value['staff_id']}}">{{$value['full_name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row m--padding-left-15 m--padding-right-15">
                    <div class="col-lg-3 form-group">
                        <div class="m-input-icon m-input-icon--right">
                            <input class="form-control m-input daterange-picker" id="time"
                                   name="time"
                                   autocomplete="off" placeholder="{{__('Chọn ngày kích hoạt')}}">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <div class="col-lg-7"></div>
                    <div class="col-lg-2 form-group">
                        <button href="javascript:void(0)" onclick="filter()"
                           class="btn ss--btn-search m-btn--icon pull-right">
                            {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-content list-card m--margin-top-30">
                <div class="table-responsive">
                    <table class="table table-striped m-table ss--header-table">
                        <thead>
                        <tr class="ss--nowrap">
                            <th class="ss--font-size-th">#</th>
                            <th class="ss--font-size-th">{{__('MÃ THẺ')}}</th>
                            <th class="ss--font-size-th">{{__('Tên Thẻ Dịch vụ')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('KH MUA')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('KH KÍCH HOẠT')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('NV BÁN')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('NV KÍCH HOẠT')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('CHI NHÁNH')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('TRẠNG THÁI')}}</th>
                            <th class="ss--text-center ss--font-size-th">{{__('NGÀY KÍCH HOẠT')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($LIST))
                            @foreach($LIST as $key=>$value)
                                <tr class="ss--nowrap">
                                    <td class="ss--font-size-13">{{$key+1}}</td>
                                    <td class="ss--font-size-13">
{{--                                        @if($value['customer_actived']!='')--}}
                                            <a href="{{route('admin.service-card.sold.detail',['money',$value['card_code']])}}" class="ss--text-black">
                                                {{$value['card_code']}}
                                            </a>
{{--                                        @else--}}
{{--                                            ****************--}}
{{--                                        @endif--}}
                                    </td>
                                    <td class="ss--text-center ss--font-size-13">{{$value['service_card_name']}}</td>
                                    <td class="ss--text-center ss--font-size-13">{{$value['customer_pay']}}</td>
                                    <td class="ss--text-center ss--font-size-13">{{$value['customer_actived']}}</td>
                                    <td class="ss--text-center ss--font-size-13">{{$value['staff_sold']}}</td>
                                    <td class="ss--text-center ss--font-size-13">{{$value['staff_actived']}}</td>
                                    <td class="ss--text-center ss--font-size-13">{{$value['branch']}}</td>
                                    @if($value['is_actived']==1)
                                        <td class="ss--text-center ss--font-size-13"><h6 class="m--font-success">{{__('Đã kích hoạt')}}</h6></td>
                                    @else
                                        <td class="ss--text-center ss--font-size-13"><h6 class="m--font-danger">{{__('Chưa kích hoạt')}}</h6></td>
                                    @endif
                                    <td class="ss--text-center ss--font-size-13">
                                        {{$value['actived_date']!=''?date_format(new DateTime($value['actived_date']), 'd/m/Y'):''}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                @include('admin::service-card.sold.paging')
            </div>
        </div>
    </div>
@endsection
@section("after_style")

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card/sold/money-card.js')}}"
            type="text/javascript"></script>
@stop
