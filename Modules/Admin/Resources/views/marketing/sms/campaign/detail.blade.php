@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        {{__('SMS')}}
    </span>
@endsection
@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT CHIẾN DỊCH')}}
                    </h3>
                </div>
            </div>
            {{--<div class="m-portlet__head-tools">--}}
            {{--<a href="{{route('admin.sms.sms-campaign-add')}}"--}}
            {{--class="btn btn-primary m-btn m-btn--icon m-btn--pill">--}}
            {{--<span>--}}
            {{--<i class="fa flaticon-plus"></i>--}}
            {{--<span> Import file</span>--}}
            {{--</span>--}}
            {{--</a>--}}
            {{--<a href="{{route('admin.sms.sms-campaign-add')}}"--}}
            {{--class="btn btn-primary m-btn m-btn--icon m-btn--pill">--}}
            {{--<span>--}}
            {{--<i class="fa flaticon-plus"></i>--}}
            {{--<span> Thêm khách hàng</span>--}}
            {{--</span>--}}
            {{--</a>--}}
            {{--</div>--}}
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        {{__('Tên chiến dịch')}}: <b>{{$campaign->name}}</b>
                    </div>
                    {{--<div class="form-group m-form__group row">--}}
                    {{--<label for="" class="col-lg-3">--}}
                    {{--Nội dung:--}}
                    {{--</label>--}}
                    {{--<div class="col-lg-9">--}}
                    {{--<div class="form-group m-form__group">--}}
                    {{--<textarea disabled rows="5" cols="40" class="form-control m-input"--}}
                    {{--placeholder="Nhập tin nhắn mẫu">{{$campaign->content}}</textarea>--}}

                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group m-form__group">
                        {{__('Số lượng tin nhắn')}}: <b>{{$totalSms}}</b>
                    </div>
                    <div class="form-group m-form__group">
                        {{__('Tin nhắn thành công')}}: <b>{{$logSuccess}}</b>
                    </div>
                    <div class="form-group m-form__group">
                        {{__('Tin nhắn lỗi')}}: <b>{{$logError}}</b>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group" id="autotable">
                <div class="list-log-detail-campaign">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead class="bg">
                            <tr class="ss--font-size-13 ss--nowrap">
                                <th class="ss--font-size-th">#</th>
                                <th class="ss--font-size-th">{{__('TÊN KHÁCH HÀNG')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('SỐ ĐIỆN THOẠI')}}</th>
                                <th class="ss--font-size-th">{{__('NỘI DUNG TIN NHẮN')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('NGƯỜI TẠO')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('NGƯỜI GỬI')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('NGÀY TẠO')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('NGÀY GỬI')}}</th>
                                <th class="ss--font-size-th ss--text-center">{{__('TRẠNG THÁI')}}</th>
                            </tr>
                            </thead>
                            <tbody class="ss--font-size-13">
                            @if(isset($LIST))
                                @foreach($LIST as $key=>$value)
                                    <tr>
                                        <td class="ss--text-center">{{$key+1}}</td>
                                        <td class="ss--nowrap">{{$value['customer']}}</td>
                                        <td class="ss--text-center ss--nowrap">{{$value['phone']}}</td>
                                        <td>{{$value['message']}}</td>
                                        <td class="ss--text-center ss--nowrap">{{$value['created_by']}}</td>
                                        <td class="ss--text-center ss--nowrap">{{$value['sent_by']}}</td>
                                        <td class="ss--text-center ss--nowrap">{{(new DateTime($value['created_at']))->format('d/m/Y')}}</td>
                                        <td class="ss--text-center ss--nowrap">
                                            @if($value['time_sent_done']!=null)
                                                {{(new DateTime($value['time_sent_done']))->format('d/m/Y')}}
                                            @endif
                                        </td>
                                        <td class="ss--text-center ss--nowrap">
                                            @if($value['sms_status']=='sent')
                                                @if($value['error_code']==null)
                                                    <span class="">{{__('Thành công')}}</span>
                                                @else
                                                    <span class="">{{__('Lỗi')}}</span>
                                                @endif
                                            @elseif($value['sms_status']=='cancel')
                                                <span class="">{{__('Đã hủy')}}</span>
                                            @elseif($value['sms_status']=='new')
                                                <span class="">{{__('Chưa gửi')}}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="m-datatable m-datatable--default">
                        <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
                            @if(count($data)>10)
                            <ul class="m-datatable__pager-nav" style="float: right">
                                @if($page>1)
                                    <li><a onclick="SmsCampaign.pageClick(1)" title="First"
                                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                                    class="la la-angle-double-left">
                                            </i></a></li>
                                    <li><a onclick="SmsCampaign.pageClick({{$page-1}})" title="Previous"
                                           class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                                    class="la la-angle-left"></i></a></li>
                                @else
                                    <li><a title="First"
                                           class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                                           disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                                    <li><a title="Previous"
                                           class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                                           disabled="disabled"><i class="la la-angle-left"></i></a></li>
                                @endif
                                <?php
                                $totalPage = 0;
                                if (is_int(count($data) / 10) == true) {
                                    $totalPage = (count($data) / 10) + 1;
                                } else {
                                    $totalPage = (int)(count($data) / 10) + 2;
                                }
                                ?>
                                @for ($i=1;$i<$totalPage;$i++)
                                    @if($i==$page)
                                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                                               onclick="SmsCampaign.pageClick({{$i}})"
                                               title="1">{{ $i }}</a></li>
                                    @else
                                        <li><a class="m-datatable__pager-link" onclick="SmsCampaign.pageClick({{$i}})">{{ $i }}</a></li>
                                    @endif
                                @endfor
                                {{-- Next Page Link --}}
                            <!--                --><?php //dd($page,$totalPage)?>
                                @if($page<$totalPage-1)
                                    <li><a title="Next" class="m-datatable__pager-link"
                                           onclick="SmsCampaign.pageClick({{$page+1}})"
                                           data-page=""><i class="la la-angle-right"></i></a></li>
                                    <li><a title="Last" onclick="SmsCampaign.pageClick({{(int)(count($data)/10)+1}})"
                                           class="m-datatable__pager-link m-datatable__pager-link--last"
                                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                                @else
                                    <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--disabled"
                                           disabled="disabled"
                                           data-page=""><i class="la la-angle-right"></i></a></li>
                                    <li><a title="Last"
                                           class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                                           disabled="disabled"
                                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                                @endif
                            </ul>
                            @endif
                            <div class="m-datatable__pager-info" style="float: left">
                            <span class="m-datatable__pager-detail">
                                @if(count($data)>0)
                                    {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}
                                @else
                                    {{__('Hiển thị 0 - 0 của 0')}}
                                @endif
                            </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="m-portlet__foot">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit  ss--width--100">
                <div class="m-form__actions m-form__actions--solid m--align-right">
                    <a href="{{route('admin.sms.sms-campaign')}}"
                       class="ss--btn-mobiles btn btn-metal ss--btn m-btn m-btn--icon m-btn--wide m-btn--md">
						 <span class="ss--text-btn-mobi">
						<i class="la la-arrow-left"></i>
						<span>@lang('HỦY')</span>
						</span>
                    </a>

                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->
    <input type="hidden" id="id" name="id" value="{{$campaign->campaign_id}}">
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/index.js')}}"
            type="text/javascript"></script>
@stop
