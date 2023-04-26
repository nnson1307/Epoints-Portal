<label class="kt-font-bold">Xác nhận thông tin</label>
<div class="review-pc">
    @if(isset($data))
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Họ & tên khách hàng')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{$data['customer_name']}}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Số điện thoại')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{$data['phone']}}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Email')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{$data['email']}}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Chi nhánh thực hiện')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{$data['branch_name']}}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Kỹ thuật viên phục vụ')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{$data['staff_name']}}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Thời gian thực hiện')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{date('d/m/Y',strtotime($data['date'])).' '.$data['time']}}</span>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-3">
                <span class="weight-400">{{__('Ghi chú')}}</span>
            </div>
            <div class="col-md-9">
                <span class="weight-400">{{$data['description']}}</span>
            </div>
        </div>
    @endif
</div>
<div class="review-mobile">
    @if(isset($data))
        <table class="table">
            <tbody>
            <tr>
                <td class="weight-400 width-50">{{__('Họ & tên khách hàng')}}</td>
                <td class="weight-400">{{$data['customer_name']}}</td>
            </tr>
            <tr>
                <td class="weight-400 width-50">{{__('Số điện thoại')}}</td>
                <td class="weight-400">{{$data['phone']}}</td>
            </tr>
            <tr>
                <td class="weight-400 width-50">{{__('Email')}}</td>
                <td class="weight-400">{{$data['email']}}</td>
            </tr>
            <tr>
                <td class="weight-400 width-50">{{__('Chi nhánh thực hiện')}}</td>
                <td class="weight-400">{{$data['branch_name']}}</td>
            </tr>
            <tr>
                <td class="weight-400 width-50">{{__('Kỹ thuật viên phục vụ')}}</td>
                <td class="weight-400">{{$data['staff_name']}}</td>
            </tr>
            <tr>
                <td class="weight-400 width-50">{{__('Thời gian thực hiện')}}</td>
                <td class="weight-400">{{date('d/m/Y',strtotime($data['date'])).' '.$data['time']}}</td>
            </tr>
            <tr>
                <td class="weight-400 width-50">{{__('Ghi chú')}}</td>
                <td class="weight-400">{{$data['description']}}.</td>
            </tr>
            </tbody>
        </table>
    @endif
</div>
<div id="table-review">
    <div class="kt-section__content">
        @if(isset($service))
            <table class="table">
                <thead class="bg">
                <tr>
                    <td></td>
                    <td rowspan="2">{{__('Dịch vụ')}}</td>
                    <td>{{__('Thời gian')}}</td>
                    <td>{{__('Giá tiền')}}n</td>
                </tr>
                </thead>
                <tbody>
                @foreach($service as $item)
                    <tr>
                        <td class="td-img">
                            @if(isset($item['service_avatar']))
                                <img src="{{asset($item['service_avatar'])}}">
                            @else
                                <img src="{{asset('static/booking-template/image/default-placeholder.png')}}">
                            @endif
                        </td>
                        <td>
                            <span class="kt-font-bold">{{$item['service_name']}}</span>
                        </td>
                        <td>
                            {{$item['time']}} {{__('phút')}}
                        </td>
                        <td>
                            <span class="kt-font-bold">{{number_format($item['new_price'])}}đ</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>