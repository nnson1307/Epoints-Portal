<style>
    #service-card {
        width: 500px;
        max-height: 270px;
        margin: 0 auto;
        background: #fff
    }

    .body-card {
        background: #ccc
    }

    .img-line img {
        display: flex;
        max-width: 100%
    }

    .footer-card {
        position: relative;
        height: 85px;
    }

    .background-footer {
        background: #55ccb9;
        height: 70px;
    }


    .footer-info ul {
        list-style: none;
        padding: 0
    }

    .content-card {
        text-align: center;
        height: 170px;
    }

    .content-card .service-card-logo {
        padding: 15px 0;
    }

    .content-card .service-card-logo img {
        max-width: 100%;
        height: 68px;
    }

    .content-card .service-card-name-spa {
        font-weight: 600;
        font-size: 14px;
    }

    .content-card .service-card-name-spa {
        padding: 10px 0;
    }

    .content-left {
        float: left;
        width: 30%;
        padding: 0 5px;
        position: relative;
    }

    .content-mid {
        float: left;
        width: 65%;
        margin-top: 30px;
        text-align: left;
        padding-left: 10px;
    }

    .content-right {
        float: left;
        width: 30%;
        margin-top: 30px;
    }

    .content-mid h3 {
        font-weight: bold;
        font-size: 18px;
        text-transform: uppercase;
        /*color: #55ccb9;*/
    }

    .content-mid .money {
        font-weight: bold;
        font-size: 25px;
        text-transform: uppercase;
        /*color: red;*/
    }

    .content-mid li {
        font-size: 14px;
    }

    .wrap-discound {
        width: 100%;
    }

    .discount {
        width: 80px;
        height: 80px;
        border-radius: 40px;
        background: #ed1c24;
        margin: 0 auto;
        vertical-align: middle;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    .footer-info ul {
        padding-left: 15px;
        padding-top: 10px;
    }

    .footer-info li {
        font-size: 12px;
        color: #fff;
    }

    .text-discount {
        padding-top: 15px;
    }

    .text-word {
        font-size: 14px;
        color: #fff;
    }

    .text-number {
        font-size: 14px;
        color: #fff;
    }

    .footer-info {
        float: left;
        width: 70%;
    }

    .footer-barcode {
        float: left;
        width: 30%;
    }

    .code {
        position: absolute;
        bottom: 25px;
        right: 35px;
    }
</style>
<div class="form-group m-form__group sv-card-print">
    @foreach($data_card as $key=>$item)
        <div class="form-group m-form__group" id="service-card">
            <div class="toimg {{$item['card_code']}}">
                <div id="check-selector-{{$key+1}}">
                    <input type="hidden" name="code" value="{{$item['card_code']}}">
                    <input type="hidden" name="stt" value="{{$key+1}}">
                    <div class="img-line">
                        <img src="{{asset('static/backend/images/template-print-card/header-card.png')}}">
                    </div>
                    @if($item['card_type']=='service')

                        @if($config_service_card['background_image']!=null)
                            <style>
                                .background-{{$key+1}}  {
                                    background-image: url({{asset($config_service_card['background_image'])}});
                                    background-size: cover;
                                    color: {{'#'.$config_service_card['color']}};
                                }
                            </style>
                        @else
                            <style>
                                .background-{{$key+1}}  {
                                    background-color: {{'#'.$config_service_card['background']}};
                                    color: {{'#'.$config_service_card['color']}};
                                }
                            </style>
                        @endif
                    @else
                        @if($config_money_card['background_image']!=null)
                            <style>
                                .background-{{$key+1}}  {
                                    background-image: url({{asset($config_money_card['background_image'])}});
                                    background-size: cover;
                                    color: {{'#'.$config_money_card['color']}};
                                }
                            </style>
                        @else
                            <style>
                                .background-{{$key+1}}  {
                                    background-color: {{'#'.$config_money_card['background']}};
                                    color: {{'#'.$config_money_card['color']}};
                                }
                            </style>
                        @endif
                    @endif
                    <div class="content-card clearfix background-{{$key+1}}">
                        <div class="content-left">
                            <div class="content-center">
                                <div class="service-card-logo">
                                    @if($item['card_type']=='service')
                                        @if($config_service_card['name_spa']!=null)
                                            <img src="{{asset($config_service_card['logo'])}}">
                                        @else
                                            <img src="{{asset($spa_info['logo'])}}">
                                        @endif
                                    @else
                                        @if($config_money_card['name_spa']!=null)
                                            <img src="{{asset($config_money_card['logo'])}}">
                                        @else
                                            <img src="{{asset($spa_info['logo'])}}">
                                        @endif
                                    @endif

                                </div>
                                <div class="service-card-name-spa">
                                    @if($item['card_type']=='service')
                                        @if($config_service_card['name_spa']!=null)
                                            {{$config_service_card['name_spa']}}
                                        @else
                                            {{$spa_info['name']}}
                                        @endif
                                    @else
                                        @if($config_money_card['name_spa']!=null)
                                            {{$config_money_card['name_spa']}}
                                        @else
                                            {{$spa_info['name']}}
                                        @endif
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="content-mid">
                            <h3>{{$item['card_name']}}</h3>
                            @if($item['card_type']=='money')
                                <h3 class="money">{{number_format($item['money'])}}Đ</h3>
                            @endif
                            <ul>
                                <li>@lang("Mã thẻ"): <strong>{{$item['card_code']}}</strong></li>
                                @if($item['card_type']=='service')
                                    @if($item['is_actived']!=0)
                                        @if($item['date_using']==0)
                                            <li>@lang("Hạn sử dụng"): <strong>@lang("Không giới hạn")</strong></li>
                                        @else
                                            <li>@lang("Hạn sử dụng"):
                                                <strong>{{\Carbon\Carbon::parse($item['actived_at'])->addDays($item['date_using'])->format('d/m/Y')}}</strong>
                                            </li>
                                        @endif
                                    @else
                                        <li>@lang("Hạn sử dụng"): <strong>@lang("Chưa kích hoạt")</strong></li>
                                    @endif

                                @endif
                                @if($item['card_type']=='service')
                                    @if($item['number_using']==0)
                                        <li>@lang("Số lần sử dụng"): <strong>@lang("Không giới hạn")</strong></li>
                                    @else
                                        <li>@lang("Số lần sử dụng"): <strong>{{$item['number_using']}}</strong></li>
                                    @endif

                                @else
                                    <li>@lang("Số lần sử dụng"): <strong>01 @lang("lần")</strong></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="footer-card clearfix">
                        <div class="background-footer">
                            <div class="footer-info">
                                <ul>
                                    <li>
                                        <i class="la la-map-marker"></i>
                                        {{$branch['address'].', '.$branch['district_type'].' '.$branch['district_name'].', '.$branch['province_type'].' '.$branch['province_name']}}
                                    </li>
                                    <li>
                                        <i class="fa fa-phone"></i>
                                        <span style="margin-left: 3px;">{{$branch['phone']}}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="footer-barcode">
                                <div class="code">
                                    {!! QrCode::size(100)->generate($item['card_code']); !!}
                                </div>

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="form-group m-form__group m--margin-top-10" align="center">
            <a href="javascript:void(0)" onclick="order.print('{{$item['card_code']}}')"
               class="btn btn-outline-primary btn-sm m-btn m-btn--icon">
                <span> <i class="la la-print"></i> <span> @lang("In") </span> </span>
            </a>
            <a href="javascript:void(0)" onclick="ORDERGENERAL.sendEachSmsServiceCard('{{$item['card_code']}}')"
               class="btn btn-outline-primary btn-sm m-btn m-btn--icon btn-send-sms">
                <span><i class="la la-mobile-phone"></i><span>@lang("SMS")</span></span>
            </a>
        </div>
    @endforeach
</div>
<div class="canvas" style="display: none">

</div>



