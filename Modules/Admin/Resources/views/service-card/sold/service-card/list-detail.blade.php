<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th">#</th>
            <th class="ss--text-center ss--font-size-th">{{__('NGÀY SỬ DỤNG')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('KHÁCH HÀNG')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('MÃ ĐƠN HÀNG')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('SỐ LƯỢNG')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('NHÂN VIÊN THỰC HIỆN')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('TRƯỚC KHI ĐIỀU TRỊ')}}</th>
            <th class="ss--text-center ss--font-size-th">{{__('SAU KHI ĐIỀU TRỊ')}}</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach($LIST as $key=>$value)
                <tr class="ss--nowrap">
                    <td class="ss--text-center ss--font-size-13">{{isset($page) ? $page*10 + $key + 1 : $key + 1}}</td>
                    <td class="ss--text-center ss--font-size-13">{{date_format(new DateTime($value['day_using']), 'd/m/Y H:i:s')}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['customer']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['order_code']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['quantity']}}</td>
                    <td class="ss--text-center ss--font-size-13">{{$value['staff_name']}}</td>
                    <td>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="image-show-before-{{$key}}" style="display: flex; justify-content: center;"
                                     onclick="serviceCardSoldImage.modal_carousel('{{$value['card_code']}}', '{{$value['order_code']}}', 'before')">
                                    @foreach($value['listImage'] as $image)
                                        @if($image['type'] == 'before')
                                            <div class="img-70 image-show-child list-image-new ">
                                                <img class='m--bg-metal img-sd '
                                                     src='{{$image['link']}}' alt='{{__('Hình ảnh')}}' width="50px" height="50px">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:void(0)"
                                   onclick="serviceCardSoldImage.image_dropzone('{{$value['order_code']}}', 'before', '{{$key}}')"
                                   class="btn btn-sm m-btn--icon color">

                                    <i class="la la-upload"><span></span></i>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="row">
                            <div class="col-md-9">
                                <div class="image-show-after-{{$key}}" style="display: flex; justify-content: center;"
                                     onclick="serviceCardSoldImage.modal_carousel('{{$value['card_code']}}', '{{$value['order_code']}}', 'after')">
                                    @foreach($value['listImage'] as $image)
                                        @if($image['type'] == 'after')
                                            <div class="img-70 image-show-child list-image-new">
                                                <img class='m--bg-metal img-sd '
                                                     src='{{$image['link']}}' alt='{{__('Hình ảnh')}}' width="50px" height="50px">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-3">
                                <a href="javascript:void(0)"
                                   onclick="serviceCardSoldImage.image_dropzone('{{$value['order_code']}}', 'after', '{{$key}}')"
                                   class="btn btn-sm m-btn--icon color">

                                    <i class="la la-upload"><span></span></i>
                                </a>

                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
@if(isset($LIST))
    {{ $LIST->links('admin::service-card.sold.helper-paging') }}
@endif