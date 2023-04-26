<div class="">
    <label><strong>{{__('Thông tin đối tác')}}</strong></label>
    <nav>
        <div class="nav nav-tabs nav-tabs-delivery" id="nav-tab" role="tablist">
            @if(isset($listServiceMain[3]))
                <a class="nav-item nav-link {{isset($listServiceMain[3]) && i ($service_type_id == 3 || $service_type_id == 0) ? 'active show' : ''}}" id="normal-delivery-tab" data-toggle="tab" href="#normal-delivery" role="tab" aria-controls="normal-delivery" aria-selected="true">{{__('Thường')}} <img class="img-fluid" src="{{asset('static/backend/images/car.png')}}"></a>
            @endif
            @if(isset($listServiceMain[1]))
                <a class="nav-item nav-link {{(!isset($listServiceMain[3]) && ($service_type_id == 1 || $service_type_id == 0)) || $service_type_id == 1 ? 'active show' : ''}}" id="fast-delivery-tab" data-toggle="tab" href="#fast-delivery" role="tab" aria-controls="fast-delivery" aria-selected="false">{{__('Nhanh')}} <img class="img-fluid" src="{{asset('static/backend/images/plane.png')}}"></a>
            @endif
            @if(isset($listServiceMain[2]))
                <a class="nav-item nav-link {{((!isset($listServiceMain[3]) && !isset($listServiceMain[1])) && ($service_type_id == 2 || $service_type_id == 0 )) || $service_type_id == 2 ? 'active show' : ''}}" id="day-delivery-tab" data-toggle="tab" href="#day-delivery" role="tab" aria-controls="day-delivery" aria-selected="false">{{__('Trong ngày')}} <img class="img-fluid" src="{{asset('static/backend/images/clock.png')}}"></a>
            @endif
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade {{isset($listServiceMain[3]) && ($service_type_id == 3 || $service_type_id == 0) ? 'show active' : '' }}" id="normal-delivery" role="tabpanel" aria-labelledby="normal-delivery-tab">
            <div class="table-responsive">
                <table class="table table-striped m-table m-table--head-bg-default">
                    <thead class="bg">
                    <tr>
                        <th class="tr_thead_list">{{__('Đối tác vận chuyển')}}</th>
                        <th class="tr_thead_list">{{__('Dịch vụ')}}</th>
                        <th class="tr_thead_list">{{__('Phí dự kiến')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($listServiceMain[3]))
                        @foreach($listServiceMain[3] as $itemService)
                            <tr class="block_partner_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}">
                                <td>
                                    <label class="m-radio-inline">
                                        <input type="radio" class="d-inline-block" {{isset($service_id) && $service_id == $itemService['service_id'] && $service_type_id == $itemService['service_type_id'] ? 'checked' : '' }} name="is_partner" value="ghn" onchange="delivery.previewOrder()" data-service-id="{{$itemService['service_id']}}" data-service-type-id="{{$itemService['service_type_id']}}">
                                        <span></span>
                                        <div class="pt-1 pl-3 d-inline-block"><img style="width:100px" class="img-fluid" src="{{asset('static/backend/images/ghn_icon.png')}}"></div>
                                    </label>
                                </td>
                                <td>
                                    {{$itemService['service_name']}}
                                    <input type="hidden" class="input_name_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}" value="{{$itemService['service_name']}}">
                                </td>
                                <td>
                                    <span class="fee_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}">{{number_format($itemService['service_fee'])}}</span><span>đ</span>
                                    <input type="hidden" class="input_fee_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}" value="{{$itemService['service_fee']}}">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade  {{((!isset($listServiceMain[3]) && isset($listServiceMain[1])) && ($service_type_id == 1 || $service_type_id == 0)) || $service_type_id == 1 ? 'show active' : '' }}" id="fast-delivery" role="tabpanel" aria-labelledby="fast-delivery-tab">
            <div class="table-responsive">
                <table class="table table-striped m-table m-table--head-bg-default">
                    <thead class="bg">
                    <tr>
                        <th class="tr_thead_list">{{__('Đối tác vận chuyển')}}</th>
                        <th class="tr_thead_list">{{__('Dịch vụ')}}</th>
                        <th class="tr_thead_list">{{__('Phí dự kiến')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($listServiceMain[1]))
                        @foreach($listServiceMain[1] as $itemService)
                            <tr class="block_partner_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}">
                                <td>
                                    <label class="m-radio-inline">
                                        <input type="radio" class="d-inline-block" {{isset($service_id) && $service_id == $itemService['service_id'] && $service_type_id == $itemService['service_type_id'] ? 'checked' : '' }} name="is_partner" value="ghn" onchange="delivery.previewOrder()" data-service-id="{{$itemService['service_id']}}" data-service-type-id="{{$itemService['service_type_id']}}">
                                        <span></span>
                                        <div class="pt-1 pl-3 d-inline-block"><img style="width:100px" class="img-fluid" src="{{asset('static/backend/images/ghn_icon.png')}}"></div>
                                    </label>
                                </td>
                                <td>
                                    {{$itemService['service_name']}}
                                    <input type="hidden" class="input_name_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}" value="{{$itemService['service_name']}}">
                                </td>
                                <td>
                                    <span class="fee_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}">{{number_format($itemService['service_fee'])}}</span><span>đ</span>
                                    <input type="hidden" class="input_fee_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}" value="{{$itemService['service_fee']}}">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade {{((!isset($listServiceMain[3]) && !isset($listServiceMain[1]) && isset($listServiceMain[2])) && ($service_type_id == 2 || $service_type_id == 0 )) || $service_type_id == 2 ? 'show active' : '' }}" id="day-delivery" role="tabpanel" aria-labelledby="day-delivery-tab">
            <div class="table-responsive">
                <table class="table table-striped m-table m-table--head-bg-default">
                    <thead class="bg">
                    <tr>
                        <th class="tr_thead_list">{{__('Đối tác vận chuyển')}}</th>
                        <th class="tr_thead_list">{{__('Dịch vụ')}}</th>
                        <th class="tr_thead_list">{{__('Phí dự kiến')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($listServiceMain[2]))
                        @foreach($listServiceMain[2] as $itemService)
                            <tr class="block_partner_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}">
                                <td>
                                    <label class="m-radio-inline">
                                        <input type="radio" class="d-inline-block" {{isset($service_id) && $service_id == $itemService['service_id'] && $service_type_id == $itemService['service_type_id'] ? 'checked' : '' }} name="is_partner" value="ghn" onchange="delivery.previewOrder()" data-service-id="{{$itemService['service_id']}}" data-service-type-id="{{$itemService['service_type_id']}}">
                                        <span></span>
                                        <div class="pt-1 pl-3 d-inline-block"><img style="width:100px" class="img-fluid" src="{{asset('static/backend/images/ghn_icon.png')}}"></div>
                                    </label>
                                </td>
                                <td>
                                    {{$itemService['service_name']}}
                                    <input type="hidden" class="input_name_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}" value="{{$itemService['service_name']}}">
                                </td>
                                <td>
                                    <span class="fee_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}">{{number_format($itemService['service_fee'])}}</span><span>đ</span>
                                    <input type="hidden" class="input_fee_{{$itemService['service_id']}}_{{$itemService['service_type_id']}}" value="{{$itemService['service_fee']}}">
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
