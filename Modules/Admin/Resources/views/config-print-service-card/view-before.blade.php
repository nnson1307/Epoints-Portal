<style type="text/css">
    .card {
        width: 450px;
        height: 200px;
        width: 80px;
        height: 80px;
    }

    .img {
        width: 100%;
        border-radius: 50%;
        height: auto;
    }

    .name {
        color: #fff;
        font-size: 0.9rem;
        font-weight: bold;
    }

    .qr_code {
        width: 60px;
        height: 60px;
        position: absolute;
        bottom: 20px;
        right: 20px;
    }

    .left {
        background-image: url({{asset('uploads/admin/template-card/img-round.png')}});
        background-size: 100% 100%;
        background-color: #fff;
        width: 40%;
        height: 100%;
        float: left;
    }

    .right {
        background-color: #fff;
        width: 60%;
        height: 100%;
        float: right;
    }


    .name_card {
        font-size: 17px;
        font-weight: bold;

    }

    .code {
        font-size: 15px;
        font-style: italic;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    .div_img {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        display: flex;
    }

    .div_all {
        width: 450px;
        height: 180px;

    }

    .font_li {
        font-size: 13px;
    }

    .div_all_left {
        text-align: center;
        position: absolute;
        top: calc(50% - 4rem);
        left: calc(50% - 5.5rem);
    }

    @media (min-width: 1024px) and (max-width: 1100px) {
        .div_all {
            width: 300px;
            height: 130px;
        }

        .name_card {
            font-size: 12px;
        }

        .code {
            font-size: 11px;
        }

        .font_li {
            font-size: 11px;
        }

        .div_img {
            width: 60px;
            height: 60px;
        }

        .qr_code {
            bottom: 10px;
            right: 15px;
            height: 50px;
            width: 50px;
        }

        .div_all_left {
            top: calc(50% - 3rem);
            left: calc(50% - 3.5rem);
        }

        .div_img {
            height: 50px;
            width: 50px;
        }
    }

    @media (max-width: 450px) {
        .div_all {
            width: 220px;
            height: 100px;
        }

        .name_card {
            font-size: 9px;
        }

        .code {
            font-size: 8px;
        }

        .font_li {
            font-size: 8px;
        }

        .div_img {
            height: 40px;
            width: 40px;
        }

        .div_all_left {
            top: calc(50% - 2rem);
            left: calc(50% - 2.5rem);
        }

        .name {
            font-size: 9px;
        }

        .qr_code {
            height: 30px;
            width: 30px;
            bottom: 10px;
            right: 10px;
        }

    }
</style>
@if($type=='click')
    <div class="div_all" style="background-color: {{'#'.$background}};">
        <div style="width: 40%;height:100%;float: left; background: url({{asset('uploads/admin/template-card/img-round.png')}}) no-repeat right; background-size: cover;position: relative;">
            <div class="div_all_left">
                <div class="div_img">
                    <img class="img" src="{{asset($print['logo'])}}" alt="">
                </div>
                <span class="name">
                    @if($name_spa!=null)
                        {{$name_spa}}
                    @else
                        {{$spa_info[0]['name']}}
                    @endif
                </span>
            </div>

        </div>

        <div style="width: 60%;height:100%;float: right; position: relative;">
            <div style="margin-top: 10px">
                <ul style="color: {{'#'.$color}}">
                    <li class="name_card">{{__('Thẻ tắm trắng body White')}}</li>
                    <li class="code">Code: CCCEEEAAAWWWEEEF</li>
                    <li class="font_li">
                        <img src="{{asset('uploads/admin/template-card/address.png')}}" width="10px"
                             height="10px">
                        {{$branch['address'].', '.$branch['district_type'].' '
                            .$branch['district_name'].', '.$branch['province_type'].' '.$branch['province_name']}}
                    </li>
                    <li class="font_li">
                        <img src="{{asset('uploads/admin/template-card/phone-call.png')}}" width="10px"
                             height="10px">
                        {{$branch['phone']}}
                    </li>
                </ul>
            </div>
            <div class="qr_code">
                @if($print['qr_code']==1)
                    <img src="{{asset('uploads/admin/template-card/qr_code_samp.jpeg')}}" height="100%" width="100%">
                @endif
            </div>
        </div>
    </div>
@else
    <div class="div_all" style="background-color: {{'#'.$item['background']}};">
        <div style="width: 40%;height:100%;float: left; background: url({{asset('uploads/admin/template-card/img-round.png')}}) no-repeat right; background-size: cover;position: relative;">
            <div class="div_all_left">
                <div class="div_img">
                    <img class="img" src="{{asset($item['logo'])}}" alt="">
                </div>
                <span class="name">
                    @if($item['name_spa']!=null)
                        {{$item['name_spa']}}
                    @else
                        {{$spa_info[0]['name']}}
                    @endif
                </span>
            </div>

        </div>

        <div style="width: 60%;height:100%;float: right; position: relative;">
            <div style="margin-top: 10px">
                <ul style="color: {{'#'.$item['color']}}">
                    <li class="name_card">{{__('Thẻ tắm trắng body White')}}</li>
                    <li class="code">Code: CCCEEEAAAWWWEEEF</li>
                    <li class="font_li">
                        <img src="{{asset('uploads/admin/template-card/address.png')}}" width="10px"
                             height="10px">
                        {{$branch['address'].', '.$branch['district_type'].' '
                            .$branch['district_name'].', '.$branch['province_type'].' '.$branch['province_name']}}
                    </li>
                    <li class="font_li">
                        <img src="{{asset('uploads/admin/template-card/phone-call.png')}}" width="10px"
                             height="10px">
                        {{$branch['phone']}}
                    </li>
                </ul>
            </div>
            <div class="qr_code">
                @if($item['qr_code']==1)
                    <img src="{{asset('uploads/admin/template-card/qr_code_samp.jpeg')}}" height="100%" width="100%">
                @endif
            </div>
        </div>
    </div>
@endif
