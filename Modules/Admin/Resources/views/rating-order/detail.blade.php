@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ BÀI VIẾT')</span>
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
                        @lang('CHI TIẾT ĐÁNH GIÁ')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('admin.rating-order')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-3">
                    <!--begin::Portlet-->
                    <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                        <div class="m-demo__preview">
                            <div class="form-group m-form__group">
                                <h3 class="m-portlet__head-text">
                                    {{$item['order_code']}}
                                </h3>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Khách hàng'):
                                </div>
                                <div class="col-lg-7">
                                    @if($item['customer_avatar']!=null)
                                        <div class="m-card-user m-card-user--sm">
                                            <div class="m-card-user__pic">
                                                <img src="{{$item['customer_avatar']}}"
                                                     onerror="this.onerror=null;this.src='https://placehold.it/40x40/00a65a/ffffff/&text=' + '{{substr(str_slug($item['full_name']),0,1)}}';"
                                                     class="m--img-rounded m--marginless" alt="photo" width="40px"
                                                     height="40px">
                                            </div>
                                            <div class="m-card-user__details">
                                                {{$item['full_name']}}
                                            </div>
                                        </div>
                                    @else
                                        <span style="width: 150px;">
                                        <div class="m-card-user m-card-user--sm">
                                            <div class="m-card-user__pic">
                                                <div class="m-card-user__no-photo m--bg-fill-success">
                                                    <span>
                                                        {{substr(str_slug($item['full_name']),0,1)}}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="m-card-user__details">
                                               {{$item['full_name']}}
                                            </div>
                                        </div>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Email'):
                                </div>
                                <div class="col-lg-7">
                                    {{$item['email']}}
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Số điện thoại'):
                                </div>
                                <div class="col-lg-7">
                                    {{$item['phone']}}
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Đánh giá'):
                                </div>
                                <div class="col-lg-7">
                                    @for ($i = 1; $i <= $item['rating_value']; $i++)
                                        <img src="{{asset('static/backend/images/star.png')}}" alt="Hình ảnh"
                                             width="15px"
                                             height="15px">
                                    @endfor
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Tổng tiền'):
                                </div>
                                <div class="col-lg-7">
                                    {{number_format($item['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Chiết khấu thành viên'):
                                </div>
                                <div class="col-lg-7">
                                    {{number_format($item['discount_member'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Giảm giá'):
                                </div>
                                <div class="col-lg-7">
                                    {{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Phí vận chuyển'):
                                </div>
                                <div class="col-lg-7">
                                    {{number_format($item['tranport_charge'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <div class="col-lg-5 font-weight-bold">
                                    @lang('Thành tiền'):
                                </div>
                                <div class="col-lg-7">
                                    {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <div class="m-accordion m-accordion--default" id="m_accordion_1" role="tablist" aria-expanded="true">

                                    <!--begin::Item-->
                                    <div class="m-accordion__item">
                                        <div class="m-accordion__item-head" role="tab" id="m_accordion_1_item_1_head" data-toggle="collapse" href="#m_accordion_1_item_1_body" aria-expanded="true">
                                            <span class="m-accordion__item-title">@lang('Danh sách đơn hàng'): ({{count($getOrderDetail)}})</span>
                                            <span class="m-accordion__item-mode"></span>
                                        </div>
                                        <div class="m-accordion__item-body collapse show" id="m_accordion_1_item_1_body" role="tabpanel" aria-labelledby="m_accordion_1_item_1_head" data-parent="#m_accordion_1" style="">
                                            <div class="m-accordion__item-content">
                                                @if (count($getOrderDetail) > 0)
                                                    @foreach($getOrderDetail as $v)
                                                        <div class="form-group m-form__group row">
                                                            <div class="col-lg-9 col-md-6">
                                                                <span class="font-weight-bold">{{$v['object_name']}}</span> </br>
                                                                {{number_format($v['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} @lang('đ')
                                                            </div>
                                                            <div class="col-lg-3 col-md-6">
                                                                {{$v['quantity']}}
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!--end::Item-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Portlet-->
                </div>
                <div class="col-lg-9">
                    <div class="m-demo" data-code-preview="true" data-code-html="true" data-code-js="false">
                        <div class="m-demo__preview">
                            <div class="form-group m-form__group">
                                <h3 class="m-portlet__head-text">
                                    @lang('Nội dung đánh giá')
                                </h3>
                            </div>
                            <div class="form-group m-form__group">
                                @if (count($logSuggest) > 0)
                                    @foreach($logSuggest as $v)
                                        <span class="m-badge  m-badge--success m-badge--wide"
                                              style="font-size: 15px;">{{$v['content_suggest']}}</span>
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group m-form__group">
                                <textarea class="form-control" disabled>{{$item['comment']}}</textarea>
                            </div>
                            <div class="form-group m-form__group">
                                @if (count($logImage) > 0)
                                    @foreach($logImage as $k => $v)
                                        @if ($v['type'] == "image")
                                            <img class="m--bg-metal m-image img-sd myImg" src="{{$v['link']}}"
                                                 alt="Hình ảnh"
                                                 width="40px" height="40px" onclick="view.clickViewImage('{{$v['link']}}')"
                                                 style="margin-top: {{$k > 1 ? '5' : '0'}}px;">
                                        @elseif($v['type'] == "video")
                                            <img src="{{asset('static/backend/images/icon-video.png')}}" alt="Hình ảnh"
                                                 width="40px"
                                                 height="40px" onclick="view.clickViewVideo('{{$v['link']}}')">
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <div class="form-group m-form__group">
                                {{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i:s')}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="my-modal"></div>
@endsection
@section("after_style")
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/rating-order/list.js')}}" type="text/javascript"></script>
@stop


